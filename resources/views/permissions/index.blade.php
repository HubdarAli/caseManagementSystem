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
                    Manage Permissions
                </h1>
            </div>
            @can('permission-create')
                <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                    <a class="btn btn-sm btn-alt-success me-1 mb-3" href="{{ route('permissions.create') }}">
                        <i class="fa fa-fw fa-plus me-1"></i>
                        Add
                    </a>
                </div>
            @endcan
        </div>
  

    @include('partials.alerts')

<div class="block-header block-header-default">
      <h3 class="block-title">Permissions List</h3>
    </div>
		
		
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <table class="table table-striped table-vcenter js-dataTable-full dataTable" id="permissions-table">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Name</th>
                            <th>Permission Type</th>
                            <th>Parent</th>
                            <th>Is Web</th>
                            <th>Is Mobile</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            var rowIdToDelete;

            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                rowIdToDelete = button.data('row-id');
                $('#deleteForm').attr('action', "{{-- route('permissions.destroy', '') --}}" + "/" + rowIdToDelete);
            });

            $('#permissions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('permissions.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'permission_type',
                        name: 'permission_type'
                    },
                    {
                        data: 'parent_id',
                        name: 'parent_id'
                    },
                    {
                        data: 'is_web',
                        name: 'is_web'
                    },
                    {
                        data: 'is_mobile',
                        name: 'is_mobile'
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
