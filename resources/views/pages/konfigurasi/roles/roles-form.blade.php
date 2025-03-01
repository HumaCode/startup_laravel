<x-form.modal title="Form Menu" action="{{ $action }}">

    @if ($data->id)
        @method('put')
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-form.input name="name" value="{{ $data->name }}" label="Nama Menu" required="true"
                placeholder="Nama Menu" />
        </div>

        <div class="col-md-6 mb-3">
            <x-form.input name="url" value="{{ $data->url }}" label="Url Menu" required="true"
                placeholder="URL Menu" />
        </div>

        <div class="col-md-6 mb-3">
            <x-form.input name="category" value="{{ $data->category }}" label="Kategori Menu" required="true"
                placeholder="Kategori Menu" />
        </div>

        <div class="col-md-6 mb-3">
            <x-form.input name="icon" value="{{ $data->icon }}" label="Icon Menu" placeholder="Icon Menu" />
        </div>

        <div class="col-md-6 mb-3">
            <x-form.input name="orders" value="{{ $data->orders }}" label="Urutan Menu" type="number" min="0"
                placeholder="Urutan Menu" />
        </div>


        <div class="col-md-6">
            <x-form.radio name="level_menu" :options="['Main Menu' => 'main_menu', 'Sub Menu' => 'sub_menu']" inline="true" value="{{ $data->orders }}"
                value="{{ $data->main_menu_id ? 'sub_menu' : 'main_menu' }}" label="Level Menu" />
        </div>


        <div class="col-md-6 {{ !$data->main_menu_id ? 'd-none' : '' }} mb-3" id="main_menu_wrapper">
            <x-form.select name="main_menu" id="main_menu" value="{{ $data->main_menu_id }}" label="Main Menu"
                :options="$mainMenus" />
        </div>

        @if (!$data->id)
            <div class="col-md-6 mb-3">
                <div class="">
                    <label for="" class="mb-2  d-block">Permissions</label>
                    @foreach (['create', 'read', 'update', 'delete'] as $item)
                        <x-form.checkbox name="permissions[]" id="{{ $item }}_permissions"
                            value="{{ $item }}" label="{{ $item }}" />
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</x-form.modal>
