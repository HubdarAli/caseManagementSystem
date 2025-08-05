@extends('layouts.default')
@section('header_styles')
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endsection
@section('content')
    <div class="content">
        <div
            class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">
                    Manage Groups
                </h1>

            </div>
            @can('group-create')
                <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                    <a class="btn btn-sm btn-alt-success me-1 mb-3" href="{{ route('groups.create') }}">
                        <i class="fa fa-fw fa-plus me-1"></i>
                        Add
                    </a>
                </div>
            @endcan
        </div>



    @include('partials.alerts')

 
        <!-- Overview -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Group Management</h3>
            </div>
            <div class="block-content block-content-full">
                <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _js/pages/be_tables_datatables.js -->
                <table class="table table-striped table-vcenter js-dataTable-full dataTable responsive" id="groups-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Overview -->
    </div>
@endsection
@section('footer_scripts')
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            var rowIdToDelete;

            // $('#deleteModal').on('show.bs.modal', function(event) {
            //     var button = $(event.relatedTarget);
            //     rowIdToDelete = button.data('row-id');
            //     $('#deleteForm').attr('action', "{{-- route('', '') --}}" + "/" + rowIdToDelete);
            // });
            
            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var rowIdToDelete = button.data('row-id');

                // Get the base route from a data attribute or set it via Blade
                var baseUrl = "{{ route('groups.destroy', ['group' => '__id__']) }}".replace('__id__', rowIdToDelete);

                $('#deleteForm').attr('action', baseUrl);
            });

            $('#groups-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('groups.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'group_name',
                        name: 'group_name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endsection
