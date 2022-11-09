@props(['action', 'btnSize'])
<div class="btn-group">
    <form action="{{ $action }}" method="post"
        onclick="return confirm('Do you really want to activate this record?')">
        @csrf
        @method('PUT')
        <button type="submit" class="btn btn-warning {{ $btnSize }}"><i class="fa fa-times-circle"></i>
            Activate</button>
    </form>
</div>
