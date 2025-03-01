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
                var url = '{{ route('konfigurasi.menu.data') }}'; // URL dari route
                var columns = [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        sortable: false,
                        render: function(data) {
                            return '<div class="text-center hover-scale">' + data + '.</div>';
                        }
                    }, {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'url',
                        name: 'url',
                    },
                    {
                        data: 'category',
                        name: 'category',
                    },
                    {
                        data: 'orders',
                        name: 'orders',
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


                function handleMenuChange() {
                    $('[name=level_menu]').on('change', function() {
                        if (this.value == 'sub_menu') {
                            $('#main_menu_wrapper').removeClass('d-none');
                        } else {
                            $('#main_menu_wrapper').addClass('d-none');
                        }
                    })
                }

                $('.sort').on('click', function(e) {
                    e.preventDefault();

                    handleAjax(this.href, 'put')
                        .onSuccess(function(res) {
                            window.location.reload()
                        }, false)
                        .execute()
                })

                $('#searchInput').on('keyup', function() {
                    $('#' + datatableId).DataTable().ajax
                        .reload(); // Reload DataTable dengan parameter pencarian baru
                });

                handleAction(datatableId, function() {
                    handleMenuChange()
                })
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
                        <a href="index.html" class="text-gray-600 text-hover-primary">{{ $title }}</a>
                    </li>
                    <!--begin::Item-->
                    <li class="breadcrumb-item text-gray-500">{{ $subtitle }}</li>
                    <!--end::Item-->
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
            <!--begin::Actions-->

            @can('create konfigurasi/menu')
                <div class="d-flex align-items-center">
                    <a href="{{ route('konfigurasi.menu.create') }}" class="btn btn-sm btn-light-primary action">
                        <i class="ki-duotone ki-plus fs-2"></i>Tambah Menu</a>
                </div>
            @endcan
            <!--end::Actions-->
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
                        {{-- <a href="#" class="btn btn-sm btn-light-primary">
                            <i class="ki-duotone ki-plus fs-2"></i>New Member</a> --}}
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

                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table align-middle gs-0 gy-4" id="{{ $datatableId }}">
                            <!--begin::Table head-->
                            <thead>
                                <tr class="fw-bold text-muted bg-light">
                                    <th class="ps-4 min-w-50px text-center">No</th>
                                    <th class="ps-4 min-w-255px text-center">Nama Menu</th>
                                    <th class="min-w-200px">Url</th>
                                    <th class="min-w-125px">Kategori</th>
                                    <th class="min-w-100px">Urutan</th>
                                    <th class="min-w-100px text-center ">
                                        <i class="ki-outline ki-abstract-3"></i>
                                    </th>
                                </tr>
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody>
                                {{-- <tr>
                                    <td class="px-4">
                                        <a href="#"
                                            class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$2,790</a>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7">Paid</span>
                                    </td>
                                    <td class="px-4">
                                        <a href="#"
                                            class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">$520</a>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7">Rejected</span>
                                    </td>
                                    <td class="px-4">
                                        <a href="#"
                                            class="text-gray-900 fw-bold text-hover-primary d-block mb-1 fs-6">Bradly
                                            Beal</a>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7">Insurance</span>
                                    </td>
                                    <td class="px-4">
                                        <span class="badge badge-light-primary fs-7 fw-bold">Approved</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#"
                                            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                            <i class="ki-duotone ki-switch fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        <a href="#"
                                            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                            <i class="ki-duotone ki-pencil fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                            </i>
                                        </a>
                                        <a href="#"
                                            class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                            <i class="ki-duotone ki-trash fs-2">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                                <span class="path4"></span>
                                                <span class="path5"></span>
                                            </i>
                                        </a>
                                    </td>
                                </tr> --}}
                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Table container-->
                </div>
                <!--begin::Body-->
            </div>
        </div>
    </div>

</x-master-layout>
