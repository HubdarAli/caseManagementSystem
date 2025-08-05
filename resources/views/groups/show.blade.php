@extends('layouts.default')

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

    <div class="content">
        <!-- Inline -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">View Group</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-lg-6 space-y-2">
                        <label class="form-label">Group Name</label>
                        <input type="text" value=" {{ $group->group_name }}" class="form-control" readonly>
                    </div>
                    <div class="col-lg-6 space-y-2">
                        <label class="form-label">Group Premission</label>
                        <select name="parent_permission[]" id="group" multiple class="form-control select select2"
                            disabled>
                            @foreach ($groupMenuPermissions as $permission)
                                <option value="{{ $permission->id }}" selected>{{ $permission->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>




            </div>
        </div>
    </div>


@endsection
