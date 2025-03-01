function initDataTable(datatableId, url, columns, responsive = true, searching = true, lengthChange = true) {
    // Cek apakah DataTable sudah diinisialisasi, jika iya maka destroy terlebih dahulu
    if ($.fn.DataTable.isDataTable('#' + datatableId)) {
        // Destroy dan hapus isi tabel
        $('#' + datatableId).DataTable().clear().destroy();
        $('#' + datatableId).find('tbody').empty(); // Kosongkan isi tbody agar tabel benar-benar kosong
    }

    // Set delay untuk memastikan destroy selesai
    setTimeout(function () {
        // Inisialisasi ulang DataTable dengan opsi server-side yang lebih dioptimalkan
        $('#' + datatableId).DataTable({
            dom: '<"d-flex justify-content-between"<"dt-left"l><"dt-center"f>>t<"d-flex justify-content-between"<"dt-left"i><"dt-right"p>>',
            processing: true, // Menampilkan loading indicator saat data diproses
            serverSide: true, // Mengaktifkan server-side processing
            lengthChange: lengthChange, // Nonaktifkan dropdown "Tampilkan 10 data"
            searching: searching, // Nonaktifkan pencarian
            responsive: responsive, // Memastikan tampilan responsif
            searchDelay: 1000, // Menunda pencarian untuk mengurangi beban server
            deferRender: true, // Hanya render data yang perlu ditampilkan
            stateSave: true, // Menyimpan status halaman, filter, dsb
            pageLength: 10, // Batasi jumlah data per halaman
            ajax: {
                url: url, // URL yang dikirimkan dari view
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: function (d) {
                    d.search = $('#searchInput').val(); // Tambahkan parameter pencarian
                    return d;
                },
                dataSrc: function (json) {
                    return json.data;
                },
                error: function (xhr, error, code) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Tidak dapat menampilkan data',
                        icon: 'error'
                    });
                }
            },
            language: {
                url: "../backend/assets/dtindo.json" // Menggunakan bahasa Indonesia untuk DataTables
            },
            columns: columns, // Columns yang dikirim dari view
            order: [
                [1, 'asc']
            ], // Mengatur urutan default
            lengthMenu: lengthChange == true ? [10, 25, 50, 100] : [10], // Pilihan jumlah data per halaman
            drawCallback: function (settings) {
                var api = this.api();
                var data = api.rows({
                    page: 'current'
                }).data();
                var tbody = $('#' + datatableId + ' tbody');

                // Jika data kosong, tambahkan gambar
                if (data.length === 0) {
                    tbody.html(`
                        <tr>
                            <td colspan="${settings.aoColumns.length}" class="text-center">
                                <img src="../../../img/kosong.gif" alt="Data kosong" style="display:block; margin: 0 auto; max-width: 100px;">
                                <p style="text-align: center;">Data tidak tersedia</p>
                            </td>
                        </tr>
                    `);
                } else {
                    tbody.find('.data-kosong').remove(); // Hapus pesan kosong jika data ada
                }

                // Menambahkan animasi pada baris tabel
                $('#' + datatableId + ' tbody tr').addClass('hover-elevate-up');
                $('#' + datatableId + ' tbody td').addClass('hover-scale');


            },
            pagingType: 'simple_numbers', // Mengurangi kompleksitas navigasi paging
            scrollX: true, // Mengaktifkan scroll horizontal jika tabel terlalu lebar
            scrollCollapse: true, // Menghemat ruang dengan menghilangkan scroll jika tidak diperlukan
        });
    }, 1000);
}