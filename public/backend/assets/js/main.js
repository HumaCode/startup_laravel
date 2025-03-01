$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name=csrf_token]').attr('content')
    }
})

function select2Init() {
    $('.select3').each(function () {
        $(this).select2({
            dropdownParent: $(this).closest('.modal'),
            allowClear: true
        });
    });
}


function handleFormSubmit(selector) {

    function init() {
        const _this = this

        $(selector).on('submit', function (e) {
            e.preventDefault();

            const _form = this
            $.ajax({
                url: this.action,
                method: this.method,
                data: new FormData(_form),
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $(_form).find('.is-invalid').removeClass(
                        'is-invalid')
                    $(_form).find('.invalid-feedback').remove()


                    submitLoader().show()
                },
                complete: function () {
                    submitLoader().hide()
                },
                success: (res) => {
                    if (_this.runDefaultSuccessCallback) {
                        $('#modal_action').modal('hide')
                        showToast(res.status, res.message)
                    }



                    _this.onSuccessCallback && _this.onSuccessCallback(res)

                    if (_this.dataTableId) {
                        $("#" + _this.dataTableId)
                            .DataTable()
                            .ajax.reload(null, false); // Reload tanpa mengubah pagination
                    }
                },
                error: function (err) {
                    const errors = err.responseJSON?.errors

                    if (errors) {
                        for (let [key, message] of Object.entries(
                            errors)) {

                            $(`[name=${key}]`).addClass('is-invalid')
                                .parent()
                                .append(
                                    `<div class="invalid-feedback">${message}</div>`
                                )
                        }
                    }

                    showToast('error', err.responseJSON?.message)
                }
            })
        })
    }

    function onSuccess(cb, runDefault = true) {
        this.onSuccessCallback = cb
        this.runDefaultSuccessCallback = runDefault
        return this
    }

    function setDataTable(id) {
        this.dataTableId = id
        return this
    }

    return {
        init,
        onSuccess,
        setDataTable,
        runDefaultSuccessCallback: true
    }
}

function handleAjax(url, method = 'get') {

    function onSuccess(cb, runDefault = true) {
        this.onSuccessCallback = cb
        this.runDefaultSuccessCallback = runDefault
        return this
    }

    function execute() {
        $.ajax({
            url,
            method,
            beforeSend: function () {
                showLoading()
            },
            complete: function () {
                showLoading(false)
            },
            success: (res) => {

                if (this.runDefaultSuccessCallback) {
                    const modal = $('#modal_action')
                    modal.html(res)
                    modal.modal('show')
                }



                this.onSuccessCallback && this.onSuccessCallback(res)


            },
            error: function (res) {
                console.log(res)
            }
        })
    }

    function onError(cb) {
        this.onErrorCallback = cb
        return this
    }

    return {
        execute,
        onSuccess,
        runDefaultSuccessCallback: true
    }
}


showLoading()
$(document).ready(function () {
    showLoading(false)
})

function showToast(status = 'success', message) {
    iziToast[status]({
        title: status == 'success' ? 'Berhasil' : 'Gagal',
        message: message,
        position: 'topRight'
    });
}

function handleAction(datatable, onShowAction, onSuccessAction) {
    $('.uiaaaauiaaaauii').on('click', '.action', function (e) {
        e.preventDefault();

        handleAjax(this.href)
            .onSuccess(function (res) {
                onShowAction && onShowAction(res)
                handleFormSubmit('#form_action')
                    .setDataTable(datatable)
                    .onSuccess(function (res) {
                        onSuccessAction && onSuccessAction(res)
                    })
                    .init();
            })
            .execute();
    })
}

function handleDelete(datatable, onSuccessAction) {
    // delete
    $('#' + datatable).on('click', '.delete', function (e) {
        e.preventDefault();

        Swal.fire({
            html: `Kamu yakin akan menghapus data ini..? <br> <strong class="text-danger">Data yang sudah terhapus tidak dapat dikembalikan lagi.</strong>`,
            icon: "info",
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: '<i class="ki-outline ki-trash fs-1"></i> Ya, Hapus Saja!',
            cancelButtonText: '<i class="ki-outline ki-cross-circle fs-1"></i> Tidak, Batalkan',
            customClass: {
                confirmButton: "btn btn-danger btn-sm hover-scale",
                cancelButton: 'btn btn-primary btn-sm hover-scale'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                handleAjax(this.href, 'delete')
                    .onSuccess(function (res) {

                        onSuccessAction && onSuccessAction(res)

                        Swal.fire({
                            text: res.message,
                            icon: res.status,
                            buttonsStyling: false,
                            confirmButtonText: '<i class="ki-outline ki-check-square fs-1"></i> Ok, Kembali',
                            customClass: {
                                confirmButton: "btn btn-success hover-scale"
                            }
                        }).then((result) => {
                            if (result) {
                                $('#' + datatable).DataTable().ajax.reload(null, false);
                            }
                            // Reload tabel setelah SweetAlert kedua ditutup
                            // window.LaravelDataTables[datatable].ajax.reload(null, false);
                            // $('#' + datatableId).DataTable().ajax.reload(null, false);
                        });

                    }, false)
                    .execute();
            } else if (result.isDismissed) {
                // Tampilkan alert info jika tombol cancel ditekan
                Swal.fire({
                    text: "Data tidak jadi dihapus",
                    icon: "info",
                    buttonsStyling: false,
                    confirmButtonText: '<i class="ki-outline ki-check-square fs-1"></i> Ok',
                    customClass: {
                        confirmButton: "btn btn-info hover-scale"
                    }
                });
            }
        });
    })
}

function showLoading(show = true) {
    const preloader = $(".preloader");

    if (show) {
        preloader.css({
            opacity: 1,
            visibility: "visible",
        });
    } else {
        preloader.css({
            opacity: 0,
            visibility: "hidden",
        });
    }
}

function submitLoader(formId = '#form_action') {
    const button = $(formId).find('button[type="submit"]');

    function show() {
        button.addClass("btn-load")
            .attr("disabled", true)
            .html(
                `<span class="d-flex align-items-center">
                    <span class="spinner-border flex-shrink-0"></span>
                    <span class="flex-grow-1 ms-2">Loading..</span>
                </span>`
            );
    }

    function hide(text = `<i class="ki-outline ki-check-square fs-2"></i>&nbsp;SIMPAN`) {
        button.removeClass("btn-load").removeAttr("disabled").html(text);
    }

    return {
        show,
        hide,
    }
}