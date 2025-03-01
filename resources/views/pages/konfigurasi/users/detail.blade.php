<x-form.modal title="Detail User" action="{{ $action ?? null }}">

    <div class="text-center">
        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
            {!! dynamicImage(
                user('foto'),
                null,
                [
                    'alt' => 'User Avatar',
                    'id' => 'showImage',
                    'class' => 'rounded-circle avatar-xl img-thumbnail user-profile-image',
                ],
                true,
                '100px',
                '85px',
            ) !!}

        </div>
        <h5 class="fs-16 mb-1">{{ $data->name }} dengan role {{ ucwords($data->user_role) }}</h5>
        <p class="text-muted mb-0">Mendaftar Sejak {{ tgl_indo($data->created_at) }} - {{ $data->pendaftar }}</p>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-4">
            <x-form.input name="name" readonly label="Nama User" placeholder="Nama User" value="{{ $data->name }}" />
        </div>
        <div class="col-md-4">
            <x-form.input name="username" readonly label="Username" placeholder="Username"
                value="{{ $data->username }}" />
        </div>

        <div class="col-md-4">
            <x-form.input name="email" readonly label="Email" placeholder="Email" value="{{ $data->email }}" />
        </div>

    </div>

</x-form.modal>
