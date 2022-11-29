@props(['class', 'buttonName', 'buttonIcon'])
<div class="btn-group">
    <button type="button" class="btn {{ $class }}" {{ $attributes }}><i class="fa {{ $buttonIcon }}"></i>
        {{ $buttonName }}</button>
</div>
