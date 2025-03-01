<x-form.modal title="Form Permission" action="{{ $action ?? null }}">

    @if ($data->id)
        @method('put')
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <x-form.input name="name" value="{{ $data->name }}" label="Nama Permission" required="true"
                placeholder="Nama Permission" />
        </div>

        <div class="col-md-6 mb-3">
            <x-form.input name="guard_name" value="{{ $data->guard_name }}" label="Guard Name" required="true"
                placeholder="Guard Name" readonly="true" />
        </div>

    </div>

</x-form.modal>
