<x-form.modal title="Form Akses Role" action="{{ $action ?? null }}">

    @if ($data->id)
        @method('put')
    @endif

    <div class="row">
        <div class="col-md-12">
            <h5>Role : {{ strtoupper(filterkata($data->name)) }}</h5>

            <div class="my-3">
                <x-form.select class="copy-role" label="Copy dari role" placeholder="Copy Role" :options="$roles" />
                <br>
                <x-form.input name="search" class="search" label="Cari menu" placeholder="Cari..." />
            </div>

            <hr>
            <div class="table-responsive">
                <table class="table table-row-bordered table-striped">
                    <thead class="fw-bold fs-6 bg-secondary text-center hover-scale">
                        <tr>
                            <th scope="col" class="p-4" width="200">Menu</th>
                            <th scope="col" class="p-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="" id="menu_permissions">

                        @include('pages.konfigurasi.aksesrole.akses-role-items', $menus)

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-form.modal>
