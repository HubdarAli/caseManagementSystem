$(document).ready(function() {
    $('#page-container').addClass('sidebar-mini');
    $('#scheme-table').DataTable({
        processing: true,
        serverSide: true,
        // fixedColumns: {
        //     left: 2,
        //     right: 2
        // },
        // scrollCollapse: true,
        scrollX: true,
        ajax: scheme_route,
        dom: 'Bfrtip',
        "autoWidth": false,

        buttons: [{
                extend: 'excel',
                exportOptions: {
                    columns: [0, ':visible'],
                    // columns: 'th:not(:last-child)'
                }
            },
            {
                extend: 'colvis',
                postfixButtons: ['colvisRestore'],
                columnText: function(dt, idx, title) {
                    return (idx + 1) + ': ' + title;
                },
                collectionLayout: 'fixed columns',
                collectionTitle: 'Column visibility control',
            }
        ],
        columnDefs: [{
            targets: [2,3,4,7,8,9,10,11,12,14],
            visible: false
        }],
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'scheme_id',
                name: 'scheme_id'
            },
            {
                data: 'district',
                name: 'district'
            },
            {
                data: 'taluka',
                name: 'taluka'
            },
            {
                data: 'uc',
                name: 'uc'
            },
            {
                data: 'village_name',
                name: 'village_name'
            },

            {
                data: 'vo_name',
                name: 'vo_name'
            },
            {
                data: 'vo_id',
                name: 'vo_id'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'climate_resilient_details',
                name: 'climate_resilient_details'
            },
            {
                data: 'physical_progress',
                name: 'physical_progress'
            },
            {
                data: 'madadgar_name',
                name: 'madadgar_name'
            },
            {
                data: 'madadgar_contact',
                name: 'madadgar_contact'
            },
            {
                data: 'scheme_type',
                name: 'scheme_type'
            },
            {
                data: 'scheme_total_cost',
                name: 'scheme_total_cost'
            },
            {
                data: 'submission_date',
                name: 'submission_date'
            },
            {
                data: 'start_date',
                name: 'start_date'
            },
            {
                data: 'end_date',
                name: 'end_date'
            },
            {
                data: 'status',
                name: 'status'
            },

            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,


            },
        ]
    });

    var rowIdToDelete;

    $('#deleteModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        rowIdToDelete = button.data('row-id'); // Extract row ID from data attribute
        $('#deleteForm').attr('action', "{{ route('scheme.destroy', '') }}" + "/" + rowIdToDelete);
    });
});
