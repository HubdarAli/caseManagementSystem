@extends('layouts.default')

@section('header_styles')
    <link href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="content">
        <div
            class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">
                    Manage District
                </h1>

            </div>
            @can('district-create')
                <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                    <a class="btn btn-sm btn-alt-success me-1 mb-3" href="{{ route('district.create') }}">
                        <i class="fa fa-fw fa-plus me-1"></i>
                        Add
                    </a>
                </div>
            @endcan
        </div>




        @include('partials.alerts')


        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Manage District</h3>
            </div>
            <div class="block-content block-content-full">

                <table class="table table-bordered table-striped table-vcenter js-dataTable-full" id="data-table">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>District Name</th>
                            <!-- <th class="d-none d-sm-table-cell">Short Code</th> -->
                            <th class="d-none d-sm-table-cell">District Code</th>
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
    <script src="{{ asset('assets/js/lib/jquery-datatable.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery-dataTables.bootstrap5.min.js') }}"></script>

    <script type="text/javascript">
        $(function() {

            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                rowIdToDelete = button.data('row-id');
                $('#deleteForm').attr('action', "{{ route('district.destroy', '') }}" + "/" +
                    rowIdToDelete);
            });

            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('district.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    // {
                    //     data: 'formatted_shortcode',
                    //     name: 'short_code'
                    // },
                    {
                        data: 'code',
                        name: 'code'
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
