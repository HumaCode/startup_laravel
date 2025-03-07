<x-master-layout>

    @push('css')
    @endpush

    @push('js')
        <script src="{{ asset('') }}backend/assets/js/datatable_show.js"></script>


        <script>
            $(document).ready(function() {

                document.getElementById('search-form').addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); // Mencegah form submit saat Enter ditekan
                    }
                });

                var table;

                // Kirimkan data ke fungsi JavaScript
                var datatableId = '{{ $datatableId }}'; // Ganti dengan id DataTable
                var url = '{{ $urlData }}'; // URL dari route
                var columns = [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        sortable: false,
                        render: function(data) {
                            return '<div class="text-center hover-scale">' + data + '.</div>';
                        }
                    },
                    {
                        data: 'user',
                        name: 'user',
                    },
                    {
                        data: 'role',
                        name: 'role',
                    },
                    {
                        data: 'telp',
                        name: 'telp',
                    },
                    {
                        data: 'jk',
                        name: 'jk',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ];

                // Panggil fungsi dari file show_tb.js
                initDataTable(datatableId, url, columns, true, false, false);

                $('#searchInput').on('keyup', function() {
                    $('#' + datatableId).DataTable().ajax
                        .reload(); // Reload DataTable dengan parameter pencarian baru
                });

                handleAction(datatableId, function() {
                    select2Init()
                })
                handleDelete(datatableId)
            });
        </script>
    @endpush


    <x-slot name="toolbar">
        <div id="kt_toolbar_container" class="container-xxl d-flex flex-stack flex-wrap gap-2">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column align-items-start me-3 py-2 py-lg-0 gap-2">
                <!--begin::Title-->
                <h1 class="d-flex text-gray-900 fw-bold m-0 fs-3">{{ $title }}</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-dot fw-semibold text-gray-600 fs-7">
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-600">
                        <a href="{{ $link }}" class="text-gray-600 text-hover-primary">{{ $subtitle }}</a>
                    </li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->

        </div>

    </x-slot>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-5 mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">{{ $subtitle }}</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Data {{ $subtitle }}</span>
                    </h3>
                    <div class="card-toolbar">
                        @can('create konfigurasi/users')
                            <div class="d-flex align-items-center">
                                <a href="{{ route('konfigurasi.users.create') }}"
                                    class="btn btn-sm btn-light-primary hover-scale action">
                                    <i class="ki-duotone ki-plus fs-2"></i>Tambah</a>
                            </div>
                        @endcan
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body py-3">

                    <form id="search-form">
                        <div class="form-floating my-7">
                            <input type="text" class="form-control" name="search" id="searchInput" />
                            <label for="searchInput">Pencarian</label>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table align-middle gs-0 gy-4" id="{{ $datatableId }}">
                            <thead>
                                <tr class="fw-bold text-muted bg-light">
                                    <th class="hover-scale ps-4 min-w-10px text-center">No</th>
                                    <th class="hover-scale ps-4 min-w-125px text-center">User</th>
                                    <th class="hover-scale min-w-100px text-center">Role</th>
                                    <th class="hover-scale min-w-100px text-center">No. Hp</th>
                                    <th class="hover-scale min-w-100px text-center">Jenis Kelamin</th>
                                    <th class="hover-scale min-w-100px text-center">Status Akun</th>
                                    <th class="min-w-100px text-center ">
                                        <i class="ki-outline ki-abstract-3"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-master-layout>
