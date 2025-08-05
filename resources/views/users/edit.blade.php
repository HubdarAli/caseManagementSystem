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
    </style>
@endpush
@section('content')
    @include('partials.alerts')

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <p class="mb-0">
                {{ Session::get('success') }}
            </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="content">
        <div
            class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">
                    Manage Users
                </h1>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-dark me-1 mb-3"><i
                        class="fa fa-fw fa-angle-left
                    me-1"></i>Back</a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Edit User</h3>
            </div>
            <div class="block-content block-content-full">
                {{-- {{ form layout changed irfan }} --}}
                <form action="{{ route('users.update', $user->id) }}" method="POST" id="user_form">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        <div class="col-4">
                            <label class="form-label">Name <strong style="color:red">*</strong></label>
                            <input type="text" name="name" class="form-control" placeholder="Name"
                                value="{{ old('name', $user->name) }}" maxlength="250">
                            @error('name')
                                <span style="color:red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <label class="form-label">Email <strong style="color:red">*</strong></label>
                            <input type="email" name="email" class="form-control" placeholder="Email" autocomplete="off"
                                value="{{ old('email', $user->email) }}" maxlength="250" />
                            @error('email')
                                <span style="color:red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <label class="form-label">Group <strong style="color:red">*</strong></label>
                            <select name="groups" class="form-control select select2" id="group_id">
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('groups', $user->group_id) == $group->id ? 'selected' : '' }}>
                                        {{ $group->group_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('groups')
                                <span style="color:red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-4">
                            <label class="form-label">Role <strong style="color:red">*</strong></label>
                            <select name="roles" class="form-control select select2" id="role_id">
                                <option value="">-- Select Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('roles', $user->roles->first()?->id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                                <span style="color:red">{{ $message }}</span>
                            @enderror
                        </div>

                        


                        <div class="col-4">
                            <label class="form-label">Status <strong style="color:red">*</strong></label>
                            <select name="is_active" class="form-control select select2">
                                <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>
                                    Active</option>
                                <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                            @error('is_active')
                                <span style="color:red">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-4 mt-5">
                            <a href="{{ url('Profile/update_user_password', ['id' => $user->id]) }}">Update Password</a>
                        </div>

                        <div class="col-sm-12 text-end">
                            <a class="btn btn-xm btn-secondary" href="{{ route('users.index') }}">Cancel</a>
                            <button type="submit" class="btn btn-xm btn-success" id="submit">Update</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    @endsection

    {{-- {{ client validation added irfan }} --}}

    @push('script')
        @include('includes.form-scripts');
        <script>
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $(document).ready(function() {
                $('#district_data').hide();
                $('#taluka_data').hide();
                $('#smp_data').hide();
                var get_group_id = $("#group_id option:selected").val();
                var get_role_id = $("#role_id option:selected").val();
                var selected_group = $("#group_id option:selected").text();
                var selected_role = $("#role_id option:selected").text();
                var logged_in = "{{ Auth::user()->group ? Auth::user()?->group['group_name'] :'' }}";


                $('#group_id').change(function() {
                    selected_group = $("#group_id option:selected").text();
                    get_group_id = $("#group_id option:selected").val();

                    if (selected_group != 'SMP') {
                        $('#district_data').hide();
                        $('#taluka_data').hide();
                        $('#smp_data').hide();
                    }

                    if (get_group_id == '') {
                        $('#district_data').hide();
                        $('#taluka_data').hide();
                        $("#role_id").empty();
                        $("#role_id").append('<option value="">-- Select Role --</option>');
                    } else if (get_group_id != '') {
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            // url: '/get_roles',
                            url: "{{ route('get_roles') }}",
                            data: {
                                "group_id": get_group_id
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(data) {
                                $("#role_id").empty();
                                $("#role_id").append('<option value="">-- Select Role --</option>');
                                for (var i = 0; i < data.length; i++) {
                                    if (selected_role == 'SMP' && data[i].name.toLowerCase() ==
                                        "{{ strtolower(config('global.smp_roles.1')) }}") {
                                        continue;
                                    }
                                    // if(logged_in == 'SMP' && "{{ strtolower(auth()->user()->getRoleNames()[0]) }}" == "{{ strtolower(config('global.smp_roles.3')) }}")
                                    // {
                                    //     if(data[i].name.toLowerCase() == "{{ strtolower(config('global.smp_roles.3')) }}")
                                    //         continue;
                                    //     // $("#role_id").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
                                    // }
                                    if ("{{ strtolower(auth()->user()->getRoleNames()[0]) }}" ==
                                        data[i].name.toLowerCase()) {

                                        if (
                                            logged_in == 'Admin' && data[i].name.toLowerCase() ==
                                            "admin"
                                        ) {
                                            $("#role_id").append('<option value="' + data[i].id +
                                                '">' + data[i].name + '</option>');
                                        } else {
                                            continue;
                                        }

                                    } else {
                                        $("#role_id").append('<option value="' + data[i].id + '">' +
                                            data[i].name + '</option>');
                                    }
                                }
                            }
                        });
                    }
                });
               

                $.validator.addMethod("emailFormat", function(value, element) {
                        return this.optional(element) || /^[a-zA-Z0-9._+-]+@[a-zA-Z.-]+\.[a-zA-Z]{2,}$/.test(value);
                    },
                    "Please enter a valid email address in the format: yourname@example.com. Only letters, numbers, dots (.), plus (+), hyphens (-), and underscores (_) before the @ symbol are allowed."
                    );


                $("#user_form").validate({
                    rules: {
                        name: {
                            required: true,
                        },
                        email: {
                            required: true,
                            emailFormat: true,
                        },
                        groups: {
                            required: true,
                        },
                        roles: {
                            required: true,
                        },
                        smp_id: {
                            required: true,
                        },
                    },
                    messages: {
                        name: {
                            required: "Please Enter Name",
                        },
                        groups: {
                            required: "Select Your Group",
                        },
                        roles: {
                            required: "Select Your Role",
                        },
                        smp_id: {
                            required: "Select SMP",
                        },
                        email: {
                            required: "Please Enter Email",
                            email: "Please Enter Valid Email",
                            maxlength: "Email cannot be more than 30 characters",
                        },
                    }
                })

                // $('#submit').click(function() {
                //     $("#user_form").validate(); // This is not working and is not validating the form
                // });

            });
        </script>
    @endpush
