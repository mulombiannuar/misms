<a href="{{ route('admin.sessions.edit', $session->session_id) }}">
    <x-buttons.button class="btn-primary btn-xs" buttonName="Edit" buttonIcon="fa-edit" />
</a>
<a href="{{ route('admin.sessions.show', $session->session_id) }}">
    <x-buttons.button class="btn-info btn-xs" buttonName="Show" buttonIcon="fa-bars" />
</a>
<x-buttons.delete action="{{ route('admin.sessions.destroy', $session->session_id) }}" btnSize="btn-xs" />
@if ($session->status == 0)
    <x-buttons.activate action="{{ route('admin.sessions.activate', $session->session_id) }}" btnSize="btn-xs" />
@else
    <x-buttons.deactivate action="{{ route('admin.sessions.deactivate', $session->session_id) }}" btnSize="btn-xs" />
@endif
