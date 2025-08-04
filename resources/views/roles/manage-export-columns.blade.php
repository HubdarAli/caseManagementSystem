@extends('layouts.default')
@push('style')
    <style>
        label.error {
            color: red;
        }

        input.error {
            border-color: red;
        }

        select.error {
            border-color: red;
        }

        .select2-container .select2-selection--single {
            margin-top: 5px;
            height: 40px;
            /* Set your desired height */
        }

        .parent-only {
            font-weight: bold;
            color: #000000 !important;
            /* Dark grey for "Parent only" */
        }

        .parent-with-permissions {
            font-weight: bold;
            color: #083C5D !important;
            /* Soft blue for "Parent with permissions" */
        }

        .child-label {
            font-style: italic;
            color: #4d4d4d !important;
            /* Muted grey for "Child" */
        }

        /* .accordion-button.collapsed::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        }

        .accordion-button.collapsed::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        } 

        .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
        }*/

        .accordion-button.collapsed{
            /* background-color: #adc4db!important; */
            /* color: #000000!important; */
            background-color: #ced4da!important;
            color: rgb(51 65 80)!important;
        }

    </style>
@endpush
@section('content')
    <div class="content">
        <div
            class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">
                    Manage Export Columns for Role
                </h1>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-dark me-1 mb-3"><i
                        class="fa fa-fw fa-angle-left
                    me-1"></i>Back</a>
            </div>
        </div>

        @include('partials.alerts')

        <!-- Inline -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Manage Export Columns</h3>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route('roles.export-save') }}" method="post">
                    @csrf
                    <div class="row">

                        <div class="col-lg-6 space-y-2">
                            <label class="form-label">Role Name</label>
                            <input type="text" disabled value="{{ $role->name }}" class="form-control">
                            <input type="hidden" name="role_id" value="{{ $role->id }}">
                        </div>
                        <div class="col-lg-6 space-y-2">
                            <label class="form-label"> Group</label>
                            <input type="text" disabled value="{{ $role->group->group_name }}" class="form-control">
                        </div>
                        <div class="col-lg-12 space-y-2">
                            <br />
                        </div>
                    </div>
                    <div class="row" id="permissionsContainer1">
                        <h2>Reports Configuration</h2>
                        <div class="accordion" id="reportsAccordion">
                            @foreach ($reports as $reportName => $columns)
                                @php
                                    $reportColumnsArray = json_decode(
                                        $role->exportColumnPermissions
                                            ->where('report_name', $reportName)
                                            ->pluck('report_columns')
                                            ->first(),
                                        true,
                                    );

                                @endphp
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading-{{ $loop->index }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse-{{ $loop->index }}" aria-expanded="false"
                                            aria-controls="collapse-{{ $loop->index }}">
                                            {{ ucWords(str_replace(['-', '_'], ' ', $reportName)) }}
                                        </button>
                                        <input type="hidden" name="report_name[{{ $loop->index }}]"
                                            value="{{ $reportName }}">
                                    </h2>
                                    <div id="collapse-{{ $loop->index }}" class="accordion-collapse collapse"
                                        aria-labelledby="heading-{{ $loop->index }}" data-bs-parent="#reportsAccordion">
                                        <div class="accordion-body">
                                            <!-- Table Format -->
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Column Name</th>
                                                        <th>
                                                            <div class="form-check">
                                                                <label class="form-check-label"
                                                                    for="check-all-{{ $loop->index }}">
                                                                    Permission
                                                                </label>
                                                                <input class="form-check-input float-end mx-2 check-all"
                                                                    type="checkbox" id="check-all-{{ $loop->index }}"
                                                                    data-target="report-{{ $loop->index }}">
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="report-{{ $loop->index }}" class="columns-group">
                                                    @foreach ($columns as $column)
                                                        <tr>
                                                            <td>{{ ucfirst(str_replace('_', ' ', $column)) }}</td>
                                                            <td>
                                                                <div class="form-check float-end">
                                                                    <input class="form-check-input child-checkbox"
                                                                        {{ $reportColumnsArray && in_array($column, $reportColumnsArray) ? 'checked' : '' }}
                                                                        type="checkbox"
                                                                        name="columns[{{ $loop->parent->index }}][]"
                                                                        id="column-{{ $loop->parent->index }}-{{ $loop->index }}"
                                                                        value="{{ $column }}">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mt-3" align="right">
                            <button type="submit" class="btn btn-success" {{ count($reports) == 0 ? 'disabled' : '' }}>Submit</button>
                        </div>
                    </div>

                </form>
            </div>
        @endsection
        @push('script')
            @include('includes.form-scripts');
            <script>
                $(document).ready(function() {
                    checkAll();
                    // Handle "Check All" functionality
                    $('.check-all').on('change', function() {
                        const targetGroup = $('#' + $(this).data('target')); // Get target group by ID
                        const isChecked = $(this).is(':checked'); // Check if "Check All" is checked
                        targetGroup.find('.child-checkbox').prop('checked', isChecked); // Toggle child checkboxes
                    });

                    // Handle individual checkbox uncheck affecting "Check All"
                    $('.columns-group .child-checkbox').on('change', function() {

                        const group = $(this).closest('.columns-group'); // Find the closest parent group
                        const checkAll = group.closest('.accordion-body').find(
                            '.check-all'); // Find the "Check All" checkbox within the same accordion
                        const allChecked = group.find('.child-checkbox').length === group.find(
                            '.child-checkbox:checked').length; // Check if all child checkboxes are checked
                        checkAll.prop('checked', allChecked); // Set "Check All" checkbox state
                    });

                });

                function checkAll() {
                    $('.columns-group .child-checkbox').each(function(index, item) {

                        let group = item.closest('.columns-group'); // Find the closest parent group
                        let checkAll = $(group).parents('table').find(
                            '.check-all'); // Find the "Check All" checkbox within the same accordion
                        let allChecked = $(group).find('.child-checkbox').length === $(group).find(
                            '.child-checkbox:checked').length; // Check if all child checkboxes are checked
                        checkAll.prop('checked', allChecked); // Set "Check All" checkbox state
                    });
                }
            </script>
        @endpush
