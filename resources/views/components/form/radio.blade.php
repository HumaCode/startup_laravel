@props(['name', 'label', 'value' => '', 'placeholder', 'id', 'options', 'inline' => false])

<div class="mb-3 ">
    <label for="level_menu" class="form-label d-block mt-2">{{ $label }}</label>

    @foreach ($options as $key => $optionValue)
        <div class="form-check {{ $inline ? 'form-check-inline' : '' }}">
            <input class="form-check-input" {{ $value == $optionValue ? 'checked' : '' }} type="radio"
                name="{{ $name }}" id="{{ $optionValue . $key }}" value="{{ $optionValue }}">
            <label class="" for="{{ $optionValue . $key }}">{{ $key }}</label>
        </div>
    @endforeach

</div>
