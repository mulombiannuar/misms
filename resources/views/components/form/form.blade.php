@props(['action', 'method', 'buttonName', 'buttonIcon'])
<form role="form" action="{{ $action }}" method="{{ $method }}" accept-charset="utf-8">
    @csrf
    @method($method)
    <div class="card-body">
        <div class="row">
            {{ $slot }}
        </div>
    </div>
    <!-- /.card-body -->
    <div class="modal-footer justify-content-between">
        <button type="submit" class="btn btn-secondary"> <i class="fa {{ $buttonIcon }}"></i>
            {{ $buttonName }}</button>
    </div>
</form>
