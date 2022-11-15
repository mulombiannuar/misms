@props(['class', 'label', 'name', 'value'])
<div class="{{ $class }}">
    <div class="form-group">
        <label for="{{ $name }}">{{ $label }}</label>
        <select name="{{ $name }}" id="{{ $name }}" class="form-control select2" {{ $attributes }}
            required>
            <option class="mb-1" selected value="{{ $value }}">
                {{ empty($value) ? 'Select ' . ucwords(str_replace('_', ' ', $name)) : $value }} </option>
            {{ $slot }}
        </select>
    </div>
</div>
