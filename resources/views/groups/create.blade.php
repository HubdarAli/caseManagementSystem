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
                    Manage Group
                </h1>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                <a href="{{ route('groups.index') }}" class="btn btn-sm btn-dark me-1 mb-3"><i
                        class="fa fa-fw fa-angle-left
            me-1"></i>Back</a>
            </div>
        </div>
        @include('partials.alerts')

        <form method="POST" action="{{ route('groups.store') }}" id="group_form">
            @csrf
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Create Group</h3>
                </div>

                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="form-label">Group Name <strong style="color:red">*</strong></label>
                            <input type="text" name="name" class="form-control" placeholder="Group Name"
                                maxlength="250" value="{{ old('name') }}">
                            @error('name')
                                <span style="color:red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-lg-6 space-y-2">
                            <label class="form-label">Parent Permission <strong style="color:red">*</strong></label>
                            <select name="parent_permission[]" id="parent_permission" class="form-control select select2"
                                multiple>
                                @foreach ($permissions ?? [] as $permission)
                                    <option value="{{ $permission->id }}"
                                        {{ collect(old('parent_permission'))->contains($permission->id) ? 'selected' : '' }}>
                                        {{ $permission->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_permission')
                                <span style="color:red">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mt-3 text-end">
                            <button type="submit" class="btn btn-success">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


    </div>
@endsection

{{-- {{ client validatiaon 'irfan'}} --}}
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
