@props(['id', 'icon', 'title'])
<table id="{{ $id }}" class="table table-sm table-striped table-bordered table-head-fixed " width="100%">
    {{ $slot }}
</table>
