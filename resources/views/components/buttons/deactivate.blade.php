@props(['action', 'btnSize'])
<div class="btn-group">
    <form action="{{ $action }}" method="post"
        onclick="return confirm('Do you really want to deactivate this record?')">
        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-success {{ $btnSize }}" {{ $attributes }}><i
                class="fa fa-check-circle"></i>
            Deactivate</button>
    </form>
</div>
