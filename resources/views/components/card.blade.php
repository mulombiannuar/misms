@props(['class', 'icon', 'title'])
<div class="card {{ $class }}">
    <div class="card-header">
        <h3 class="card-title"><i class="fa {{ $icon }}"></i> {{ $title }}</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        {{ $slot }}
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->
