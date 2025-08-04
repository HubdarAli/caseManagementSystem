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
                    Manage Users
                </h1>

            </div>

            @can('user-create')
                <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                    <a class="btn btn-sm btn-alt-success me-1 mb-3" href="{{ route('users.create') }}">
                        <i class="fa fa-fw fa-plus me-1"></i>
                        Add
                    </a>
                </div>
            @endcan
        </div>


        @include('partials.alerts')


        <div class="row m-2 mb-5">

            <div id="success-message" class="alert alert-success alert-dismissible" role="alert" style="display: none;">
                <p id="success-text" class="mb-0"></p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="col-3">
                <label class="form-label" for="example-select">SMPs</label>
                <select class="form-select select2" id="smp_id" name="smp_id">
                    <option selected value="">Select</option>
                    @if (!empty($smps))
                        {{-- @if (Auth::user()->smp->name ?? null)
                            <option value="{{ Auth::user()->smp->id }}" selected>{{ Auth::user()->smp->name }}</option>
                        @else --}}
                        {{-- <option value="" {{ session('filter_labour_smp_id') == '' ? 'selected' : '' }}>Select
                        </option> --}}
                        @foreach ($smps as $smp)
                            <option value="{{ $smp->id }}"
                                {{ session('filter_labour_smp_id') == $smp->id ? 'selected' : '' }}>
                                {{ $smp->name }}
                            </option>
                        @endforeach
                        {{-- @endif --}}
                    @endif
                </select>
            </div>
            <div class="col-3">
                <label class="form-label" for="example-select">Group</label>
                <select class="form-select select2" id="group_id" name="group_id">
                    <option selected value="">Select</option>
                    @if (!empty($group))
                        @foreach ($group as $groups)
                            <option value="{{ $groups->id }}"
                                {{ session('filter_labour_smp_id') == $groups->id ? 'selected' : '' }}>
                                {{ $groups->group_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="col-3 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-alt-success me-1 mb-1" id="search-button">
                    Search
                </button>
            </div>
        </div>
        @can('user-status')
            <div class="row m-3 mb-4 status-check" style="display: none;">
                <div class="col-3">
                    <select id="status-status" class="form-select select2">
                        <option value="">Select Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-3 d-flex align-items-end">
                    <button id="status-update" class="btn btn-sm btn-alt-success me-1 mb-1">Update Status</button>
                </div>
            </div>
        @endcan
        <div id="error-message" class="alert alert-danger alert-dismissible" role="alert" style="display: none;">
            <p id="error-text" class="mb-0"></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>


        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Manage User Accounts</h3>
                {{-- <a class="btn btn-sm btn-info text-white me-1 my-2" href="{{ route('users.export') }}">Export Users</a> --}}
                <form action="{{ route('users.export') }}" method="GET">
                    <input type="hidden" name="smp_id" id="export_smp_id" value="">
                    <input type="hidden" name="group_id" id="export_group_id" value="">
                    <button type="submit" class="btn btn-sm btn-info text-white me-1 my-2">Export Users</button>
                </form>
            </div>

            <div class="block-content block-content-full">
                <table class="table table-striped table-vcenter js-dataTable-full dataTable" id="permissions-table">
                    <thead>
                        <tr>
                            @can('user-status')
                                <th><input type="checkbox" id="select-all" value="1"></th>
                            @endcan
                            <th class="text-center">No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>District</th>
                            <th>Taluka</th>
                            <th>Group</th>
                            <th>SMP Name</th>
                            <th>Roles</th>
                            <th>Status</th>
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
    @include('includes.form-scripts')
    <script src="{{ asset('assets/js/lib/jquery-datatable.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery-dataTables.bootstrap5.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var selectedIds = [];
            var rowIdToDelete;

            $('#deleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                rowIdToDelete = button.data('row-id');
                $('#deleteForm').attr('action', "{{ route('users.destroy', '') }}" + "/" + rowIdToDelete);
            });

            var table = $('#permissions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.index') }}",
                    data: function(d) {
                        d.smp_id = $('#smp_id').val();
                        d.group_id = $('#group_id').val();
                    }
                },
                columns: [
                    @can('user-status')
                        {

                            data: 'id',
                            render: function(data, type, row) {
                                return `<input type="checkbox" class="record-checkbox" value="${data}">`;
                            },
                            orderable: false,
                            searchable: false
                        },
                    @endcan {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
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
                        data: 'group',
                        name: 'group'
                    },
                    {
                        data: 'smp_name',
                        name: 'smp_name'
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#search-button').on('click', function() {
                var smpId = $('#smp_id').val();
                console.log('Selected SMP ID:', smpId);
                table.ajax.reload();
            });

            $('#select-all').on('change', function() {
                var isChecked = $(this).prop('checked');
                $('.record-checkbox').prop('checked', isChecked);

                $('.record-checkbox').each(function() {
                    var id = $(this).val();
                    if (isChecked) {
                        if (!selectedIds.includes(id)) {
                            selectedIds.push(id);
                        }
                    } else {
                        selectedIds = selectedIds.filter(item => item !== id);
                    }
                });

                toggleStatusCheckDiv();
            });

            $(document).on('change', '.record-checkbox', function() {
                var id = $(this).val();
                if ($(this).prop('checked')) {
                    if (!selectedIds.includes(id)) {
                        selectedIds.push(id);
                    }
                } else {
                    selectedIds = selectedIds.filter(item => item !== id);
                }

                toggleStatusCheckDiv();
            });

            $('#permissions-table').on('draw.dt', function() {
                $('.record-checkbox').each(function() {
                    var id = $(this).val();
                    if (selectedIds.includes(id)) {
                        $(this).prop('checked', true);
                    } else {
                        $(this).prop('checked', false);
                    }
                });
                var allChecked = $('.record-checkbox').length > 0 && $('.record-checkbox:checked')
                    .length === $('.record-checkbox').length;
                $('#select-all').prop('checked', allChecked);

                toggleStatusCheckDiv();
            });


            $('#status-update').on('click', function() {
                var selectedStatus = $('#status-status').val();

                if (!selectedStatus || selectedIds.length === 0) {
                    $('#error-text').text('Please select at least one record and a status.');
                    $('#error-message').show();

                    setTimeout(function() {
                        $('#error-message').fadeOut();
                    }, 3000);

                    return;
                }

                $.ajax({
                    url: "{{ route('users.status-update') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: selectedIds,
                        status: selectedStatus,
                    },
                    success: function(response) {
                        table.ajax.reload();
                        selectedIds = [];
                        $('#select-all').prop('checked', false);
                        $('#success-text').text(response.message);
                        $('#success-message').show();
                        setTimeout(function() {
                            $('#success-message').fadeOut();
                        }, 3000);
                        toggleStatusCheckDiv();
                    },
                    error: function(xhr) {

                        alert('Something went wrong. Please try again.');
                    }
                });
            });


            function toggleStatusCheckDiv() {
                if (selectedIds.length > 0) {
                    $('.status-check').show();
                } else {
                    $('.status-check').hide();
                }
            }

        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('export_smp_id').value = document.getElementById('smp_id').value;
            document.getElementById('export_group_id').value = document.getElementById('group_id').value;

            document.getElementById('smp_id').addEventListener('change', function() {
                document.getElementById('export_smp_id').value = this.value;
            });

            document.getElementById('group_id').addEventListener('change', function() {
                document.getElementById('export_group_id').value = this.value;
            });


        });
    </script>
@endsection
