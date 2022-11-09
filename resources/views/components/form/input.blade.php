@props(['class', 'label', 'type', 'name', 'placeholder', 'value'])
<div class="{{ $class }}">
    <div class="form-group">
        <label for="{{ $name }}">{{ $label }}</label>
        <input type="{{ $type }}" name="{{ $name }}" class="form-control" id="{{ $name }}"
            placeholder="{{ $placeholder }}" autocomplete="off" value="{{ $value }} {{ old($name) }}"
            {{ $attributes }} required>
    </div>
</div>
