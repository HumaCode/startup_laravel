@props([
    'name',
    'label',
    'value' => '',
    'placeholder' => '',
    'id' => $name,
    'type' => 'text',
    'required' => false,
    'min' => '',
    'readonly' => false,
])

<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ ucwords($label) }}
        @if ($required)
            <strong class="text-danger">*</strong>
        @endif
    </label>
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
        {{ $attributes->merge(['class' => 'form-control form-control-solid']) }} min="{{ $min }}"
        placeholder="{{ $placeholder }}" value="{{ $value }}" @if ($required) required @endif
        @if ($readonly) readonly @endif />
</div>
