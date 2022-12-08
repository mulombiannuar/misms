<x-buttons.button class="btn-primary btn-xs" buttonName="Edit" buttonIcon="fa-edit" data-toggle="modal"
    data-target="#modal-{{ $room->room_id }}" />


<x-form.modal id="modal-{{ $room->room_id }}" modalSize="modal-md" modalTitle="Update Room {{ $room->room_label }}">
    <x-form.form action="{{ route('hostel.rooms.update', $room->room_id) }}" method="put" buttonName="Update Room"
        buttonIcon="fa-edit" buttonClass="btn-info">

        <x-form.input class="col-md-6 col-sm-12" label="Room label" type="text" name="room_label"
            placeholder="Room label e.g J001" value="{{ $room->room_label }}" />

        <x-form.input class="col-md-6 col-sm-12" label="Room Capacity " type="number" name="room_capacity"
            placeholder="Room Capacity e.g 4" value="{{ $room->room_capacity }}" />

    </x-form.form>
</x-form.modal>
