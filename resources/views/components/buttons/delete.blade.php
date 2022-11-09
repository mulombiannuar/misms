@props(['action', 'btnSize'])
<div class="btn-group">
    <form action="{{ $action }}" method="post"
        onclick="return confirm('Do you really want to delete this record?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger {{ $btnSize }}"><i class="fa fa-trash"></i>
            Delete</button>
    </form>
</div>
