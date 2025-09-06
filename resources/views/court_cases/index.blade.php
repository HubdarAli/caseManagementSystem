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
            @can('court-case-export')
                <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                    {{-- <a class="btn btn-sm btn-alt-warning me-1 mb-3" href="{{ route('courts-cases.create') }}">
                        <i class="fa fa-fw fa-download me-1"></i> Export
                    </a> --}}
                    <button type="button" class="btn btn-sm btn-alt-warning me-1 mb-3" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="fa fa-fw fa-download me-1"></i> Export
                    </button>
                </div>
            @endcan
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
                            <th>Applicant</th>
                            <th>Respondent</th>
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

    @can('court-case-export')
        <!-- Export Modal -->
        <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="GET" action="{{ route('courts-cases.pdf') }}" target="_blank">
                    <div class="modal-content">
                        <div class="modal-header bg-warning-light text-warning">
                            <h5 class="modal-title" id="exportModalLabel">Export Court Cases</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label for="from_date" class="form-label">Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control" required>
                            </div>
                            {{-- <div class="mb-3">
                                <label for="to_date" class="form-label">To Date</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" required>
                            </div> --}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-alt-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-alt-success">
                                <i class="fa fa-file-pdf me-1"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    @endcan
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
                scrollX: true,
                ajax: "{{ route('courts-cases.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center" },
                    { data: 'case_number', name: 'case_number' },
                    { data: 'title', name: 'title' },
                    { data: 'applicant', name: 'applicant' },
                    { data: 'respondent', name: 'respondent' },
                    { data: 'case_type', name: 'case_type' },
                    { data: 'court.name', name: 'court.name' },
                    { data: 'district.name', name: 'district.name' },
                    { 
                        data: 'hearing_date', 
                        name: 'hearing_date',
                        // render: function(data, type, row) {
                        //     if (!data) return '<span class="badge bg-danger">Not Set</span>';
                        //     // Format date as DD-MM-YYYY
                        //     var dateObj = new Date(data);
                        //     var day = String(dateObj.getDate()).padStart(2, '0');
                        //     var month = String(dateObj.getMonth() + 1).padStart(2, '0');
                        //     var year = dateObj.getFullYear();
                        //     return day + '-' + month + '-' + year;
                        // }
                    },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endsection
