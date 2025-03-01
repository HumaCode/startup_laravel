<x-form.modal title="Form Modal" action="{{ $action ?? null }}">
    @if ($data->id)
        @method('put')
    @endif
    <div class="row">
        <div class="col-md-4">
            <x-form.input name="name" label="Nama" placeholder="Nama" value="{{ $data->name }}" />
        </div>
        <div class="col-md-4">
            <x-form.input name="username" label="Username" placeholder="Username" value="{{ $data->username }}" />
        </div>
        <div class="col-md-4">
            <x-form.input name="email" label="Email" placeholder="Email" value="{{ $data->email }}" />
        </div>

        @if (request()->routeIs('konfigurasi.users.create'))
            <div class="col-md-6">
                <x-form.input type="password" name="password" label="Password" placeholder="Password" />
            </div>
            <div class="col-md-6">
                <x-form.input type="password" name="password_confirmation" label="Ulangi Password"
                    placeholder="Ulangi Password" />
            </div>
        @endif

        <div class="col-md-12 mb-3">
            <span class="">Roles</span>
            <select class="select3 form-select form-select-sm" name="roles" id="roles">
                <option selected disabled>-- Pilih Roles --</option>
                @foreach ($roles as $key => $item)
                    <option value="{{ $item }}" @selected(in_array($item, $data->roles->pluck('name')->toArray()))>{{ $item }}</option>
                @endforeach
            </select>
        </div>
    </div>
</x-form.modal>
