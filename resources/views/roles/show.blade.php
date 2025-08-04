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
    </style>
@endpush
@section('content')
    <div class="content">
        <div
            class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">
                     Role
                </h1>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-dark me-1 mb-3"><i
                        class="fa fa-fw fa-angle-left
            me-1"></i>Back</a>
            </div>
        </div>



        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Something went wrong.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {!! Form::model($role, ['method' => 'PATCH', 'id' => 'roles_form', 'route' => ['roles.update', $role->id]]) !!}

        <!-- Inline -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title"> Role</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-6 space-y-2">
                        <label class="form-label">Role Name</label>
                        {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                    </div>
                    <div class="col-lg-6 space-y-2">
                        <label class="form-label"> Group </label>
                        <select name="group_id" id="group_id" class="form-control js-example-basic-single select select2">
                            <option value="">Choose Group</option>

                            @isset($groups)
                                @if (!empty($groups))
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}"
                                            {{ $group->id === $role?->group?->id ? 'selected' : '' }}>
                                            {{ $group->group_name }}
                                        </option>
                                    @endforeach
                                @endif
                            @endisset
                        </select>
                    </div>
                    <div class="col-lg-12 space-y-2">
                        <br />
                    </div>
                </div>
                <div class="row" id="permissionsContainer">
                </div>
            </div>
            {!! Form::close() !!}


        @endsection
        @section('footer_scripts')
            <script>
                var group_load_id = {{ $role->group_id }};
                var role_id = {{ $role->id }}
                if (group_load_id) {
                    permission_ajax(group_load_id, role_id);
                }
                $(document).ready(function() {
                    $('#group_id').on('change', function() {
                        let group_id = $(this).val();
                        if (group_id) {
                            permission_ajax(group_id);
                        } else {
                            $('#permissionsContainer').html('');
                        }
                    });
                });

                function permission_ajax(group_id, role_id = null) {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        // url: '/get_permission_with_group_id',
                        url: "{{ route('get_permission_with_group_id') }}",
                        type: 'POST',
                        data: {
                            group_id: group_id,
                            role_id: role_id
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            var permissionsHtml = `<div class="table-responsive new-products">
                    <table id="user_group" class="table table-striped table-bordered table-hover table-full-width">
                        <thead>
                            <tr>
                                <th >Name</th>
                                <th >Description</th>
                                <th >Permission</th>
                            </tr>
                        </thead>
                        <tbody>`;
                            $.each(response, function(index, permission) {
                                var check = permission.checked ? 'checked' : '';
                                permissionsHtml += '<tr>';
                                permissionsHtml += '<td class="parent-only"><h4>' + permission.name +
                                    '</h4></td>';
                                permissionsHtml += '<td><label class="form-check-label">' + permission.name +
                                    '</label></td>';
                                permissionsHtml += '<td><input disabled type="checkbox" name="permission[]" id="' +
                                    permission.id + '" value="' + permission.id + '"  data-check_value="' +
                                    permission.id +
                                    '" data-parent_id="false" data-has_child="true" class="form-check-input parent myCheckbox" ' +
                                    check + '></td>';
                                $.each(permission.subMenus, function(index1, permission1) {
                                    var check1 = permission1.checked ? 'checked' : '';
                                    permissionsHtml += '<tr>';
                                    permissionsHtml += '<tr>';
                                    // permissionsHtml += '<td><h5 style="margin-left: 15px;">' +
                                    //     permission1.name + '<h5></td>';
                                    permissionsHtml += `<td class="${permission1.permission_type == 'menu' ? 'parent-with-permissions' : 'child-label'}">${permission1.name}</td>`;
                                    permissionsHtml += '<td><label class="form-check-label">' +
                                    permission1.name + '</label></td>';

                                    permissionsHtml +=
                                        '<td><input disabled type="checkbox" name="permission[]" id="' +
                                        permission1.id + '" value="' + permission1.id +
                                        '"  data-check_value="' + permission.id + '" data-parent_id="' +
                                        permission1.parent_id +
                                        '" data-has_child="true" class="form-check-input child myCheckbox" ' +
                                        check1 + '></td>';
                                    permissionsHtml += '</tr>';
                                    $.each(permission1.subMenus, function(index2, permission2) {
                                        var check2 = permission2.checked ? 'checked' : '';
                                        permissionsHtml += '<tr>';
                                        permissionsHtml +=
                                            '<td><p class="child-label">' +
                                            permission2
                                            .name + '</p></td>';
                                        permissionsHtml +=
                                            '<td><label  class="form-check-label">' +
                                            permission2.name + '</label></td>';
                                        permissionsHtml +=
                                            '<td><input disabled type="checkbox" name="permission[]" id="' +
                                            permission2.id + '" value="' + permission2.id +
                                            '"  data-check_value="' + permission.id +
                                            '" data-parent_id="' + permission2.parent_id +
                                            '" data-has_child="false" class="form-check-input subchild myCheckbox" ' +
                                            check2 + '></td>';
                                        permissionsHtml += '</tr>';

                                    });
                                });
                                permissionsHtml += '</tr>';
                            });
                            permissionsHtml += `</tbody>
                        </table></div>`;
                            $('#permissionsContainer').html(permissionsHtml);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            </script>
            @push('script')
                @include('includes.form-scripts');
                <script>
                    $(document).ready(function() {
                        $("#roles_form").validate({
                            rules: {
                                name: {
                                    required: true,
                                },
                                group_id: {
                                    required: true,
                                },
                            },
                            messages: {
                                name: {
                                    required: "Please Enter Role Name",
                                },
                                group_id: {
                                    required: "Please Choose Group Name",
                                },
                            }
                        })

                        $('#submit').click(function() {
                            $("#roles_form").validate(); // This is not working and is not validating the form
                        });
                    });

                    $(document).on('click', 'input[type="checkbox"].form-check-input', function() {
                        var parentId = $(this).data('parent_id');
                        var Value = $(this).data('check_value');
                        var Id = $(this).attr('id');

                        flag = false;

                        $.each($('input[data-parent_id="' + parentId + '"]'), function(index, value_new) {
                            if ($(value_new).prop('checked')) {
                                flag = true;
                            }
                        });

                        console.log([flag, Id, Value, parentId]);

                        $('input[type="checkbox"].form-check-input[id="' + parentId + '"]').prop('checked', flag);
                        $('input[type="checkbox"].form-check-input[data-parent_id="' + Id + '"]').prop('checked', $(this).prop(
                            'checked'));

                        if (parentId) {
                            $.each($('input[id="' + parentId + '"]'), function(index, value_new) {
                                var parentId = $(this).data('parent_id');
                                var Value = $(this).data('check_value');
                                var Id = $(this).attr('id');
                                $.each($('input[data-parent_id="' + parentId + '"]'), function(index, value_ne) {
                                    if ($(value_ne).prop('checked')) {
                                        flag = true;
                                    }
                                });

                            });

                            $('input[type="checkbox"].form-check-input[id="' + Value + '"]').prop('checked', flag);


                        }
                        $.each($('input[data-parent_id="' + Id + '"]'), function(index, value_new) {
                            var parentId = $(value_new).data('parent_id');
                            var Value = $(value_new).attr('value');
                            var Id = $(value_new).attr('id');
                            $('input[type="checkbox"].form-check-input[data-parent_id="' + Id + '"]').prop('checked', $(
                                this).prop('checked'));
                        });
                    });

                    $("input, select, textarea, button").prop("disabled", true);
                </script>
            @endpush
        @endsection
