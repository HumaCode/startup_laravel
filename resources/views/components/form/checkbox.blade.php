@props([
    'value' => '',
    'justify' => 'form-check-inline',
    'label' => null,
    'id' => 'id_' . rand(),
    'checked' => false,
])

<div class="form-check {{ $justify }}">
    <input {{ $attributes->merge(['class' => 'form-check-input']) }} {{ $checked }} type="checkbox"
        id="{{ $id }}" value="{{ $value }}"> &nbsp;

    @if ($label)
        <label for="{{ $id }}" class="mb-2">{{ $label }}</label>
    @endif
</div>
