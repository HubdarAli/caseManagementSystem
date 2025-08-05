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
                     Permission
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
                <h3 class="block-title"> Permission</h3>
            </div>

            <div class="block-content block-content-full">
                <form class=" g-3 align-items-center" id="edit-from" action="{{ route('permissions.update', $permission) }}"
                    method="POST">
                    @method('PUT')
                    @csrf
                    <div class="row p-1">
                        <div class="col-6 mt-3">
                            <label class="form-label" for="example-if-name">Permission Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Permission Name" value="{{ $permission->name }}">
                        </div>
                        <div class="col-6 mt-3">
                            <label class="form-label">Permission Link</label>
                            <input type="text" class="form-control" id="permission_link" name="permission_link" readonly
                                placeholder="Permission Link" value="{{ $permission->permission_link }}">
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
                                placeholder="Icon Name" value="{{ $permission->icon_name }}">
                        </div>
                        <div class="col-6 mt-3" id="component-box">
                            <label class="form-label">Component</label>
                            {!! Form::select('component', $components, $permission->component, [
                                'class' => 'form-control select select2',
                                'id' => 'component',
                                'placeholder' => 'Select component',
                                empty($permission->component) ? 'disabled' : '',
                                'data-disabled' => empty($permission->component) ? 'true' : 'false',
                            ]) !!}
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="description" rows="1">{{ $permission->description }}</textarea>
                        </div>
                        <div class="col-6 mt-3">
                            <label class="form-label">Permission Type</label>
                            <div>
                                <input type="radio" class="form-check-input permission-type-radio" name="permission_type"
                                    value="menu" {{ $permission->permission_type === 'menu' ? 'checked' : '' }}>
                                <label class="form-label">Menu</label>
                            </div>
                            <div>
                                <input type="radio" class="form-check-input permission-type-radio" name="permission_type"
                                    value="permission" {{ $permission->permission_type === 'permission' ? 'checked' : '' }}>
                                <label class="form-label">Permission</label>
                            </div>
                        </div>
                        <div class="col-6 mt-3 p-0">
                            <label class="form-label">Access Type</label>
                            <div class="">
                                <input type="checkbox" class="form-control-checkbox" id="is_web" name="access_type[]"
                                    placeholder="For Web" value="for_web" {{ $permission->is_web == 1 ? 'checked' : '' }}>
                                <label class="form-label" for="example-if-is_web">For Web</label>
                            </div>
                            <div class="">
                                <input type="checkbox" class="form-control-checkbox" id="is_mobile" name="access_type[]"
                                    placeholder="For Mobile" value="for_mobile"
                                    {{ $permission->is_mobile == 1 ? 'checked' : '' }}>
                                <label class="form-label" for="example-if-is_mobile">For Mobile</label>
                            </div>
                            <label id="checkbox_error"></label>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @include('includes.form-scripts');
    <script>
        $(document).ready(function() {
            $('.permission-type-radio').change(function() {
                var selectedValue = $(this).val();

                if (selectedValue === 'menu') {
                    $('#parent').show();
                    $('#parent').html('{!! Form::select('parent_id', $parents['when_menu'], $permission->parent_id ?? '0', [
                        'class' => 'form-control select select2',
                        'id' => 'parent',
                        'placeholder' => '-',
                    ]) !!}');
                } else if (selectedValue === 'permission') {
                    $('#parent').show();
                    $('#parent').html('{!! Form::select('parent_id', $parents['when_permission'], $permission->parent_id ?? '0', [
                        'class' => 'form-control select select2',
                        'id' => 'parent',
                        'placeholder' => 'Select a permission',
                    ]) !!}');
                }
            });

            $("#edit-from").validate({
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

            $('.permission-type-radio:checked').trigger('change');

            if ($('#component').data('disabled')) {
                $('#component-box').hide();
            }

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

            $('input, select, textarea, button').prop('disabled', true);
        });
    </script>
@endsection
