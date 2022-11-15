<script>
    $(document).ready(function() {
        $("#datatable").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.users.get') }}",
            },
            searching: true,
            scrollY: 500,
            scrollX: true,
            scrollCollapse: true,
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
            columns: [{
                    data: "id"
                },
                {
                    data: "name"
                },
                {
                    data: "email"
                }
            ]
        }).buttons().container().appendTo('#datatable_wrapper .col-md-6:eq(0)');
    });
</script>
