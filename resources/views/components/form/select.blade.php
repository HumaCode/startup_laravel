@props([
    'label' => null,
    'id' => 'select' . rand(),
    'value' => '',
    'placeholder' => '-- Pilih ' . $label . ' --',
    'class' => '',
    'options' => [],
])

<div class="mb-3">
    @if ($label)
        <label for="main_menu" class="form-label mb-3 d-block">{{ $label }} </label>
    @endif

    <select {{ $attributes->merge(['class' => 'form-select mb-3 ' . $class]) }}>
        <option selected value="" disabled>{{ $placeholder }}</option>
        @foreach ($options as $key => $item)
            <option value="{{ filterKata($item) }}" @selected($value == filterKata($item))>
                {{ ucwords(filterKata($key)) }}</option>
        @endforeach
        {{ $slot }}
    </select>
</div>
