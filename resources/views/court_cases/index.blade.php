@extends('layouts.default')

@section('header_styles')
    <link href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="content">
        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">Manage Court Cases</h1>
            </div>
            @can('court-case-create')
                <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                    <a class="btn btn-sm btn-alt-success me-1 mb-3" href="{{ route('courts-cases.create') }}">
                        <i class="fa fa-fw fa-plus me-1"></i> Add Case
                    </a>
                </div>
            @endcan
        </div>

        @include('partials.alerts')

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Court Case List</h3>
            </div>
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="data-table">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Case No</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Court</th>
                            <th>District</th>
                            <th>Next Hearing</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/lib/jquery-datatable.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery-dataTables.bootstrap5.min.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var rowIdToDelete = button.data('row-id');
                var baseUrl = "{{ route('courts-cases.index') }}";
                $('#deleteForm').attr('action', baseUrl + '/' + rowIdToDelete);
            });

            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('courts-cases.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center" },
                    { data: 'case_number', name: 'case_number' },
                    { data: 'title', name: 'title' },
                    { data: 'case_type', name: 'case_type' },
                    { data: 'court.name', name: 'court.name' },
                    { data: 'district.name', name: 'district.name' },
                    { data: 'notes', name: 'notes' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endsection
