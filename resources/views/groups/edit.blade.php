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
    </style>
@endpush

@section('content')
    <div class="content">
        <div
            class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">
                    Manage Groups
                </h1>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                <a href="{{ route('groups.index') }}" class="btn btn-sm btn-dark me-1 mb-3"><i
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

        <form id="group_form" method="POST" action="{{ route('groups.update', $group->id) }}">
            @csrf
            @method('PATCH')

            <!-- Inline -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Edit Group</h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-lg-6 space-y-2">
                            <label class="form-label">Group Name <strong style="color:red">*</strong></label>
                            <input type="text" class="form-control" maxlength="250" name="name"
                                value="{{ old('name', $group->group_name) }}">
                        </div>

                        <div class="col-lg-6 space-y-2">
                            <label class="form-label">Parent Permission <strong style="color:red">*</strong></label>
                            <select name="parent_permission[]" multiple class="form-control select select2">
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->id }}" @if (in_array($permission->id, $groupMenuPermissions)) selected @endif>
                                        {{ $permission->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label id="parent_permission[]-error" class="error" for="parent_permission[]"></label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mt-3" align="right">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


@endsection
@push('script')
    @include('includes.form-scripts');
    <script>
        $(document).ready(function() {
            $("#group_form").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    'parent_permission[]': {
                        required: true,
                    },

                },
                messages: {
                    name: {
                        required: "Please Enter Group Name",
                    },
                    'parent_permission[]': {
                        required: " Please Choose Parent Permission",
                    },

                }
            })

            $('#submit').click(function() {
                // $("#user_form").validate(); // This is not working and is not validating the form
            });

        });
    </script>
@endpush
