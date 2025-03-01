@foreach ($menus as $mm)
    <tr class="">
        <th scope="row" class=""><strong>{{ strtoupper(filterkata($mm->name)) }}</strong></th>
        <td class="hover-scale">
            @foreach ($mm->permissions as $permission)
                <div class="form-check form-switch form-check-inline">
                    <input class="form-check-input h-20px w-30px" type="checkbox" name="permissions[]"
                        @checked($data->hasPermissionTo($permission->name)) value="{{ $permission->name }}" role="switch"
                        id="permission-{{ $mm->id . '-' . $permission->id }}">
                    <label class="form-check-label"
                        for="permission-{{ $mm->id . '-' . $permission->id }}">{{ ucwords(explode(' ', filterKata($permission->name))[0]) }}</label>
                </div>
            @endforeach
        </td>
    </tr>
    @foreach ($mm->subMenus as $sm)
        <tr>
            <td width="200">&nbsp; &nbsp; &nbsp; &nbsp;
                <x-form.checkbox id="parent{{ $mm->id . $sm->id }}" label="{{ filterkata($sm->name) }}"
                    class="parent" />
            </td>
            <td class="hover-scale">
                @foreach ($sm->permissions as $permission)
                    <div class="form-check form-switch form-check-inline ">
                        <input class="form-check-input h-20px w-30px child" name="permissions[]"
                            @checked($data->hasPermissionTo($permission->name)) value="{{ $permission->name }}" type="checkbox" role="switch"
                            id="permission-{{ $sm->id . '-' . $permission->id }}">
                        <label class="form-check-label"
                            for="permission-{{ $sm->id . '-' . $permission->id }}">{{ ucwords(explode(' ', filterKata($permission->name))[0]) }}</label>
                    </div>
                @endforeach
            </td>
        </tr>
    @endforeach
@endforeach
