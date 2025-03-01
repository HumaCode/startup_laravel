<x-form.modal title="Form Role" action="{{ $action ?? null }}">

    @if ($data->id)
        @method('put')
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-form.input name="name" value="{{ $data->name }}" label="Nama Role" required="true"
                placeholder="Nama Role" />
        </div>

        <div class="col-md-6 mb-3">
            <x-form.input name="guard_name" value="{{ $data->guard_name }}" label="Guard Name" required="true"
                placeholder="Guard Name" readonly />
        </div>

    </div>

</x-form.modal>
