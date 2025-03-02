<x-master-layout>

    @push('js')
        <script>
            $(document).ready(function() {
                $('#fotouser').change(function(e) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#showImages').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(e.target.files['0']);
                });
            })

            $(document).on('submit', '#edit-profile-form', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                var submitButton = $('#btn-edit-profil'); // Sesuaikan dengan ID tombol yang benar

                // Tambahkan loading state di tombol
                submitButton.attr('disabled', true).text('Menyimpan...');

                // Tambahkan jeda selama 500ms sebelum menjalankan AJAX
                setTimeout(function() {
                    $.ajax({
                        url: '{{ route('setting.update-data.user', $profil->id) }}',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message,
                                    icon: 'error',
                                });
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText); // Debugging
                            Swal.fire({
                                title: 'Error!',
                                text: 'Terjadi kesalahan: ' + xhr.responseText,
                                icon: 'error',
                            });
                        },
                        complete: function() {
                            // Aktifkan kembali tombol setelah selesai
                            submitButton.attr('disabled', false).text('Simpan');
                        }
                    });
                }, 500); // Jeda 500ms sebelum AJAX dijalankan
            });


            $(document).on('submit', '#ubah-password-form', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                var submitButton = $('#btn-ubah-password');

                // Nonaktifkan tombol saat proses berjalan
                submitButton.attr('disabled', true).html(
                    '<i class="spinner-border spinner-border-sm"></i> Menyimpan...'
                );

                // Tambahkan jeda 500ms sebelum AJAX dijalankan
                setTimeout(function() {
                    $.ajax({
                        url: '{{ route('setting.update-password.user', $profil->id) }}',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    $('#ubah-password-form')[0]
                                .reset(); // Reset form jika berhasil
                                });
                            } else {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: response.message,
                                    icon: 'error',
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) { // Laravel validation error
                                let errors = xhr.responseJSON.errors;
                                let errorMessage = '';
                                for (let field in errors) {
                                    errorMessage += `${errors[field][0]}<br>`;
                                }
                                Swal.fire({
                                    title: 'Validasi Gagal!',
                                    html: errorMessage,
                                    icon: 'error',
                                });
                            } else if (xhr.status === 400) { // Password lama salah
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: xhr.responseJSON.message ||
                                        'Password lama tidak sesuai.',
                                    icon: 'error',
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan pada server.',
                                    icon: 'error',
                                });
                            }
                        },
                        complete: function() {
                            // Aktifkan kembali tombol setelah selesai
                            submitButton.attr('disabled', false).html(
                                '<i class="ki-outline ki-send fs-2"></i>&nbsp; Simpan'
                            );
                        }
                    });
                }, 500); // Jeda 500ms sebelum AJAX dijalankan
            });
        </script>
    @endpush

    <x-slot name="toolbar">

        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap gap-2">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column align-items-start me-3 py-2 py-lg-0 gap-2">
                <!--begin::Title-->
                <h1 class="d-flex text-gray-900 fw-bold m-0 fs-3">Profil Saya</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                {{-- <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">
                        <a href="index.html" class="text-gray-600 text-hover-primary">Home</a>
                    </li>
                    <!--end::Item-->
                    <li class="breadcrumb-item text-gray-600">Utilities</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">Modals</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">General</li>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-500">View Users</li>
                    <!--end::Item-->
                </ul> --}}
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->
            {{-- <div class="d-flex align-items-center">
                <!--begin::Button-->
                <a href="#" class="btn btn-icon btn-color-primary bg-body w-35px h-35px w-lg-40px h-lg-40px me-3"
                    data-bs-toggle="modal" data-bs-target="#kt_modal_upgrade_plan">
                    <i class="ki-duotone ki-file-added fs-2 fs-md-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </a>
                <!--end::Button-->

            </div> --}}
            <!--end::Actions-->
        </div>

    </x-slot>
    <div class="card mb-5 mb-xl-8">

        <div class="card-body py-3">
            <div class="card mb-5 mb-xxl-8">
                <div class="card-body pt-9 pb-0">
                    <!--begin::Details-->
                    <div class="d-flex flex-wrap flex-sm-nowrap">
                        <!--begin: Pic-->
                        <div class="me-7 mb-4">
                            <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                                {!! dynamicImage($profil->foto, null, ['alt' => 'image'], true) !!}
                                <div
                                    class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                                </div>
                            </div>
                        </div>
                        <!--end::Pic-->

                        <!--begin::Info-->
                        <div class="flex-grow-1">
                            <!--begin::Title-->
                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                <!--begin::User-->
                                <div class="d-flex flex-column">
                                    <!--begin::Name-->
                                    <div class="d-flex align-items-center mb-2">
                                        <a href="#"
                                            class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ filterKata(ucwords($profil->name)) }}</a>
                                        <a href="#"><i class="ki-duotone ki-verify fs-1 text-primary"><span
                                                    class="path1"></span><span class="path2"></span></i></a>
                                    </div>
                                    <!--end::Name-->

                                    <!--begin::Info-->
                                    <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                        <a href="#"
                                            class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                            <i class="ki-duotone ki-profile-circle fs-4 me-1"><span
                                                    class="path1"></span><span class="path2"></span><span
                                                    class="path3"></span></i>
                                            {{ $profil->getRoleNames()->implode(', ') }}
                                        </a>
                                        <a href="#"
                                            class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                            <i class="ki-duotone ki-geolocation fs-4 me-1"><span
                                                    class="path1"></span><span class="path2"></span></i> SF,
                                            Bay Area
                                        </a>
                                        <a href="#"
                                            class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                                            <i class="ki-duotone ki-sms fs-4 me-1"><span class="path1"></span><span
                                                    class="path2"></span></i>
                                            {{ filterKata($profil->email) }}
                                        </a>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::User-->

                                <!--begin::Actions-->
                                <div class="d-flex my-4">
                                    @php
                                        // Membersihkan nomor telepon dan memastikan format diawali dengan 62
                                        $telp = preg_replace('/\D/', '', $profil->telp); // Menghapus karakter non-digit
                                        if (substr($telp, 0, 1) === '0') {
                                            $telp = '62' . substr($telp, 1); // Mengganti '0' di awal dengan '62'
                                        } elseif (substr($telp, 0, 2) === '62') {
                                            // Nomor sudah dalam format internasional
                                        } elseif (substr($telp, 0, 3) === '+62') {
                                            $telp = substr($telp, 1); // Menghapus '+' di awal
                                        }
                                    @endphp

                                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $telp) }}" target="_blank"
                                        class="btn btn-sm btn-primary me-2 hover-scale">
                                        <i class="ki-outline ki-whatsapp fs-2"></i>
                                        <!--begin::Indicator label-->
                                        <span class="indicator-label">Hubungi</span>
                                        <!--end::Indicator label-->
                                    </a>

                                </div>
                                <!--end::Actions-->
                            </div>
                            <!--end::Title-->

                            <!--begin::Stats-->
                            <div class="d-flex flex-wrap flex-stack">
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-column flex-grow-1 pe-8">
                                    <!--begin::Stats-->
                                    <div class="d-flex flex-wrap">
                                        <!--begin::Stat-->
                                        <div
                                            class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <!--begin::Number-->
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-arrow-up fs-3 text-success me-2"><span
                                                        class="path1"></span><span class="path2"></span></i>
                                                <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                    data-kt-countup-value="4500" data-kt-countup-prefix="$"
                                                    data-kt-initialized="1">$4,500</div>
                                            </div>
                                            <!--end::Number-->

                                            <!--begin::Label-->
                                            <div class="fw-semibold fs-6 text-gray-500">Earnings</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Stat-->

                                        <!--begin::Stat-->
                                        <div
                                            class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <!--begin::Number-->
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-arrow-down fs-3 text-danger me-2"><span
                                                        class="path1"></span><span class="path2"></span></i>
                                                <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                    data-kt-countup-value="80" data-kt-initialized="1">80
                                                </div>
                                            </div>
                                            <!--end::Number-->

                                            <!--begin::Label-->
                                            <div class="fw-semibold fs-6 text-gray-500">Projects</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Stat-->

                                        <!--begin::Stat-->
                                        <div
                                            class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                            <!--begin::Number-->
                                            <div class="d-flex align-items-center">
                                                <i class="ki-duotone ki-arrow-up fs-3 text-success me-2"><span
                                                        class="path1"></span><span class="path2"></span></i>
                                                <div class="fs-2 fw-bold counted" data-kt-countup="true"
                                                    data-kt-countup-value="60" data-kt-countup-prefix="%"
                                                    data-kt-initialized="1">%60</div>
                                            </div>
                                            <!--end::Number-->

                                            <!--begin::Label-->
                                            <div class="fw-semibold fs-6 text-gray-500">Success Rate</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Stat-->
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Wrapper-->

                            </div>
                            <!--end::Stats-->
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Details-->


                </div>
            </div>
        </div>

    </div>



    <div class="card">
        <div class="card-body py-3">
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_4">Edit Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5">Ubah Password</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_tab_pane_4" role="tabpanel">

                    <div class="card-body">

                        <form id="edit-profile-form" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="old_foto" value="{{ $profil->foto }}">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <x-form.input name="name" label="Nama" placeholder="Nama"
                                        value="{{ $profil->name }}" required="true" />
                                </div>

                                <div class="col-md-6 mb-3">
                                    <x-form.input name="username" label="Username" placeholder="Username"
                                        value="{{ $profil->username }}" required="true" />
                                </div>

                                <div class="col-md-4 mb-3">
                                    <x-form.input name="email" label="Email" placeholder="Email"
                                        value="{{ $profil->email }}" required="true" />
                                </div>

                                <div class="col-md-4 mb-3">
                                    <x-form.input name="telp" label="No Hp" placeholder="No Hp"
                                        value="{{ $profil->telp }}" required="true" />
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="jk" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" name="jk" aria-label="Jenis Kelamin">
                                        <option selected disabled>-- Pilih --</option>
                                        <option value="L" {{ $profil->jk == 'L' ? 'selected' : '' }}>Laki
                                            - Laki</option>
                                        <option value="P" {{ $profil->jk == 'P' ? 'selected' : '' }}>
                                            Perempuan</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="foto" class="form-label">Upload Gambar</label>
                                    <input type="file" name="foto" id="fotouser" class="form-control" />
                                </div>

                                <div class="col-lg-6 mb-3 text-center">
                                    <label for="" class="form-label d-block">Preview</label>

                                    {!! dynamicImage(
                                        user('foto'),
                                        null,
                                        [
                                            'alt' => 'User Avatar',
                                            'class' => 'symbol symbol-100px symbol-lg-160px symbol-fixed position-relative',
                                            'id' => 'showImages',
                                        ],
                                        true,
                                        '200px',
                                        '200px',
                                    ) !!}
                                </div>

                                <div class="col-md-12 text-end mt-4">
                                    <button type="submit" id="btn-edit-profil"
                                        class="btn btn-primary hover-scale"><i
                                            class="ki-outline ki-send fs-2"></i>&nbsp; Simpan</button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>


                <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
                    <div class="card-body">

                        <form id="ubah-password-form" method="POST">
                            @csrf

                            <div class="mb-3">
                                <x-form.input name="passLama" label="Password Lama" placeholder="Password Lama"
                                    required="true" type="password" />
                            </div>

                            <!--begin::Main wrapper-->
                            <div class="fv-row mb-4" data-kt-password-meter="true">
                                <!--begin::Wrapper-->
                                <div class="mb-1">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold fs-6 mb-2">
                                        Password Baru
                                    </label>
                                    <!--end::Label-->

                                    <!--begin::Input wrapper-->
                                    <div class="position-relative mb-3">
                                        <input class="form-control form-control-lg form-control-solid" type="password"
                                            placeholder="" name="passBaru" autocomplete="off" />

                                        <!--begin::Visibility toggle-->
                                        <span
                                            class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                            data-kt-password-meter-control="visibility">
                                            <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span
                                                    class="path2"></span><span class="path3"></span><span
                                                    class="path4"></span></i>
                                            <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span
                                                    class="path2"></span><span class="path3"></span></i>
                                        </span>
                                        <!--end::Visibility toggle-->
                                    </div>
                                    <!--end::Input wrapper-->

                                    <!--begin::Highlight meter-->
                                    <div class="d-flex align-items-center mb-3"
                                        data-kt-password-meter-control="highlight">
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                        </div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                        </div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2">
                                        </div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px">
                                        </div>
                                    </div>
                                    <!--end::Highlight meter-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Hint-->
                                <div class="text-danger mb-3">
                                    * Password harus lebih dari 8 karakter dan gunakan kombinasi huruf besar,
                                    kecil, angka dan simbol.
                                </div>
                                <!--end::Hint-->
                            </div>
                            <!--end::Main wrapper-->

                            <div class="mb-3">
                                <x-form.input name="passBaru_confirmation" label="Ulangi Password Baru"
                                    placeholder="Ulangi Password Baru" required="true" type="password" />
                            </div>

                            <br>

                            <div class="text-end mt-4">
                                <button type="submit" id="btn-ubah-password" class="btn btn-primary hover-scale"><i
                                        class="ki-outline ki-send fs-2"></i>&nbsp; Simpan</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</x-master-layout>
