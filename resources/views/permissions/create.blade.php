@extends('layouts.default')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
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
    </style>
@endpush
@section('content')
    @include('partials.alerts')

    <div class="content">
        <div
            class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">
                    Add Permissions
                </h1>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                <a href="{{ route('permissions.index') }}" class="btn btn-sm btn-dark me-1 mb-3"><i
                        class="fa fa-fw fa-angle-left
                    me-1"></i>Back</a>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Add Permissions</h3>
            </div>
            <div class="block-content block-content-full">
                <form class=" g-3 align-items-center" id="add-from" action="{{ route('permissions.store') }}"
                    method="POST">
                    @csrf
                    <div class="row p-1">
                        <div class="col-6 mt-3">
                            <label class="form-label" for="example-if-name">Permission Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Permission Name" maxlength="250">
                            @if (isset($errors) && $errors->has('name'))
                                <span style="color:red">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-6 mt-3">
                            <label class="form-label">Permission Link<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="permission_link" name="permission_link"
                                placeholder="Permission Link" maxlength="250">
                            @if (isset($errors) && $errors->has('permission_link'))
                                <span style="color:red">{{ $errors->first('permission_link') }}</span>
                            @endif
                        </div>
                        <div class="col-6 mt-3">
                            <label class="form-label">Parent</label>
                            {!! Form::select('parent_id', [], '0', [
                                'class' => 'form-control select select2',
                                'id' => 'parent',
                                'placeholder' => 'Select Permission Type First',
                            ]) !!}
                        </div>
                        <div class="col-6 mt-3">
                            <label class="form-label">Icon Name</label>
                            <input type="text" class="form-control" id="icon_name" name="icon_name"
                                placeholder="Icon Name" maxlength="250">
                            <label id="icon_name"></label>
                        </div>
                        <div class="col-6" id="component-box">
                            <label class="form-label">Component</label>
                            {!! Form::select('component', $components, '0', [
                                'class' => 'form-control select select2',
                                'id' => 'component',
                                'placeholder' => 'Select component',
                                'disabled' => 'disabled',
                            ]) !!}
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="description" rows="1" maxlength="250"></textarea>
                        </div>
                        <div class="col-6 mt-3">
                            <label class="form-label">Permission Type<span class="text-danger">*</span></label>
                            <div>
                                <input type="radio" class="form-check-input permission-type-radio" name="permission_type"
                                    value="menu">
                                <label class="form-label">Menu</label>
                            </div>
                            <div>
                                <input type="radio" class="form-check-input permission-type-radio" name="permission_type"
                                    value="permission">
                                <label class="form-label">Permission</label>
                            </div>
                            @if (isset($errors) && $errors->has('permission_type'))
                                <span style="color:red">{{ $errors->first('permission_type') }}</span>
                            @endif
                            <label id="permission_type_error"></label>
                        </div>
                        <div class="col-6 mt-3 p-0" id="check">
                            <label class="form-label">Access Type<span class="text-danger">*</span></label>
                            <div>
                                <input type="checkbox" class="form-control-checkbox" value="for_web" name="access_type[]"
                                    placeholder="For Web">
                                <label class="form-label" for="example-if-is_web">For Web</label>
                            </div>
                            <div>
                                <input type="checkbox" class="form-control-checkbox" value="for_mobile" name="access_type[]"
                                    placeholder="For Mobile">
                                <label class="form-label" for="example-if-is_mobile">For Mobile</label>
                            </div>
                            <label id="checkbox_error"></label>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    @include('includes.form-scripts');
    <script>
        $(document).ready(function() {
            $('.permission-type-radio').change(function() {
                var selectedValue = $(this).val();

                if (selectedValue === 'menu') {
                    $('#parent').show();
                    $('#parent').html('{!! Form::select('parent_id', $parents['when_menu'], '0', [
                        'class' => 'form-control select select2',
                        'id' => 'parent',
                        'placeholder' => 'Select a menu',
                    ]) !!}');
                } else if (selectedValue === 'permission') {
                    $('#parent').show();
                    $('#parent').html('{!! Form::select('parent_id', $parents['when_permission'], '0', [
                        'class' => 'form-control select select2',
                        'id' => 'parent',
                        'placeholder' => 'Select a permission',
                    ]) !!}');
                }
            });

            $("#add-from").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    permission_link: {
                        required: true,
                    },
                    permission_type: {
                        required: true
                    },
                    'access_type[]': {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "Please Enter Permission Name",
                    },
                    permission_link: {
                        required: "Please Enter Permission Link",
                    },
                    parent_id: {
                        required: "Please Choose Perent Permission"
                    },
                    permission_type: {
                        required: "Please Choose Permission Type"
                    },
                    "access_type[]": {
                        required: "Please check at least one"
                    },

                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") === 'permission_type') {
                        error.appendTo("#permission_type_error");
                    } else if (element.attr('name') === "access_type[]") {
                        error.appendTo("#checkbox_error");
                    } else {
                        error.insertAfter(element);
                    }
                },

            });

            $('#submit').click(function() {
                $("#add-from").validate();
            });

            var componentField = $('#component-box');
            componentField.hide();

            $('.permission-type-radio').change(function() {
                var selectedValue = $(this).val();
                var parentField = $('#parent');
                var componentField = $('#component-box');

                if (selectedValue === 'menu') {
                    parentField.show();

                    if (!parentField.val()) {
                        componentField.show();
                        $('#component').prop('disabled', false)
                    } else {
                        componentField.hide();
                        $('#component').prop('disabled', true)
                    }
                } else if (selectedValue === 'permission') {
                    parentField.show();
                    componentField.hide();
                    $('#component').prop('disabled', true)
                }
            });

            $('#parent').change(function() {
                var selectedPermissionType = $('input[name="permission_type"]:checked').val();
                var componentField = $('#component-box');

                if (selectedPermissionType === 'menu' && !$(this).val()) {
                    componentField.show();
                    $('#component').prop('disabled', false)
                } else {
                    componentField.hide();
                    $('#component').prop('disabled', true)
                }
            });
        });
    </script>
@endpush
