@props(['class', 'label', 'type', 'name', 'placeholder'])
<div class="{{ $class }}">
    <div class="form-group">
        <label for="{{ $name }}">{{ $label }}</label>
        <input type="{{ $type }}" name="{{ $name }}" class="form-control" id="{{ $name }}"
            placeholder="{{ $placeholder }}" autocomplete="off" value="{{ old($name) }}" required>
    </div>
</div>
