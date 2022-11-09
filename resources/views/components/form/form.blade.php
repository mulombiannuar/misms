@props(['action', 'method', 'buttonName', 'buttonIcon', 'buttonClass'])
<form role="form" action="{{ $action }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    @csrf
    @method($method)
    <div class="card-body">
        <div class="row">
            {{ $slot }}
        </div>
    </div>
    <!-- /.card-body -->
    <div class="modal-footer justify-content-between">
        <button type="submit" class="btn {{ $buttonClass }}"> <i class="fa {{ $buttonIcon }}"></i>
            {{ $buttonName }}</button>
    </div>
</form>
