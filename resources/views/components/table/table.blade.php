@props(['id', 'icon', 'title'])
<table id="{{ $id }}" class="table table-sm table-striped table-bordered" width="100%" {{ $attributes }}>
    {{ $slot }}
</table>
