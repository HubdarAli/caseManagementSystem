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
                <h3 class="block-title">Create User</h3>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route('users.store') }}" method="POST" autocomplete="off" id="user_form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-4">
                            <label class="form-label">Name <strong style="color:red">*</strong></label>
                            <input type="text" name="name"class="form-control" placeholder="Name" maxlength="250">

                            @if (isset($errors) && $errors->has('name'))
                                <span style="color:red">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-4">
                            <label class="form-label">Email <strong style="color:red">*</strong></label>
                            <input type="email" name="email" class="form-control" placeholder="Email"
                                autocomplete="off" maxlength="250"/>
                            @if (isset($errors) && $errors->has('email'))
                                <span style="color:red">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="col-4">
                            <label class="form-label">Group <strong style="color:red">*</strong></label>
                            <select name="groups" class="form-control select select2" id="group_id">
                                <option value="">-- Select Group --</option>
                                @foreach ($groups as $group)
                                    <option value=" {{ $group->id }}">{{ $group->group_name }}</option>
                                @endforeach
                            </select>
                            <label id="group_id-error" class="error" for="group_id"></label>
                            @if (isset($errors) && $errors->has('groups'))
                                <span style="color:red">{{ $errors->first('groups') }}</span>
                            @endif
                        </div>
                        <div class="col-4">
                            <label class="form-label">Role <strong style="color:red">*</strong></label>
                            <select name="roles" class="form-control select select2" id="role_id">
                                <option value="">-- Select Role --</option>
                            </select>
                            <label id="role_id-error" class="error" for="role_id"></label>
                            @if (isset($errors) && $errors->has('roles'))
                                <span style="color:red">{{ $errors->first('roles') }}</span>
                            @endif
                        </div>
                        
                        <div class="col-4">
                            <label class="form-label">Password <strong style="color:red">*</strong></label>
                            {{-- {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!} --}}
                            <input type="password" name="password" placeholder="Password" class="form-control"
                                id="password" maxlength="250"/>
                            @if (isset($errors) && $errors->has('password'))
                                <span style="color:red">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                        <div class="col-4">
                            <label class="form-label">Confirm Password <strong style="color:red">*</strong></label>
                            <input type="password" name="confirm_password" placeholder="confirm-password"
                                class="form-control" maxlength="250">
                        </div>
                        @if (isset($errors) && $errors->has('confirm_password'))
                            <span style="color:red">{{ $errors->first('confirm_password') }}</span>
                        @endif

                        <div class="col-4">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-control select select2">
                                <option value="1" selected>Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @if (isset($errors) && $errors->has('is_active'))
                                <span style="color:red">{{ $errors->first('is_active') }}</span>
                            @endif
                        </div>

                        <div class="col-sm-12 text-end">
                            {{-- <button type="reset" class="btn btn-xm btn-secondary">Cancel</button> --}}
                            <button type="submit" class="btn btn-xm btn-success" id="submit">Submit</button>
                        </div>
                        {{-- {{ $smp_role = strtolower(auth()->user()->getRoleNames()[0])}} --}}
                </form>
            </div>
        </div>
    @endsection


    @push('script')
        @include('includes.form-scripts')
        <script>
            $(document).ready(function() {
                var logged_in = "{{ Auth::user()->group ? Auth::user()?->group['group_name'] :'' }}";
                var selected_group = null;
                var selected_role = null;
                var selected_smp = null;
                $('#district_data').hide();
                $('#taluka_data').hide();
                $('#smp_data').hide();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $('#group_id').change(function() {
                    selected_group = $("#group_id option:selected").text();
                    var group_id = $(this).val();
                    $('#smp_id').val('').trigger('change');
                    if (selected_group != 'SMP') {
                        $('#district_data').hide();
                        $('#taluka_data').hide();
                        $('#smp_data').hide();
                    } else if (selected_group = 'SMP') {
                        $('#smp_data').show();
                    }

                    if (group_id == '') {
                        
                        $("#role_id").empty();
                        $("#role_id").append('<option value="">-- Select Role --</option>');
                    } else if (group_id != '') {
                        $.ajax({
                            type: 'POST',
                            dataType: 'json',
                            // url: '/get_roles',
                            url: "{{ route('get_roles') }}",
                            data: {
                                "group_id": group_id
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(data) {
                                $("#role_id").empty();
                                $("#role_id").append('<option value="">-- Select Role --</option>');
                                for (var i = 0; i < data.length; i++) {
                                    
                                    // if ("{{ strtolower(auth()->user()->getRoleNames()[0]) }}" ==
                                    //     data[i].name.toLowerCase()) {

                                    //     if (
                                    //         logged_in == 'Admin' && data[i].name.toLowerCase() ==
                                    //         "admin"
                                    //     ) {
                                    //         $("#role_id").append('<option value="' + data[i].id +
                                    //             '">' + data[i].name + '</option>');
                                    //     } else {
                                    //         continue;
                                    //     }

                                    // } else {
                                    // }
                                        $("#role_id").append('<option value="' + data[i].id + '">' +
                                            data[i].name + '</option>');
                                }
                            }
                        });
                    }
                });
                $('#role_id').change(function() {
                    selected_role = $("#role_id option:selected").text();
                    
                    if (selected_role == '-- Select Role --') {
                        $('#smp_id').val('').trigger('change');
                        $("#smp_id").prop('disabled', true);
                        $('#district_data').hide();
                    }
                });

                $.validator.addMethod("alphaOnly", function(value, element) {
                    return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
                }, "Only alphabetic characters and spaces are allowed");



                // $.validator.addMethod("emailFormat", function(value, element) {
                //         return this.optional(element) || /^[a-zA-Z.@]+@[a-zA-Z.-]+\.[a-zA-Z]{2,}$/.test(value);
                //     }, "Please enter a valid email address in the format: yourname@example.com. Only letters, dots (.) symbol (@) are allowed.");

                $.validator.addMethod("emailFormat", function(value, element) {
                    return this.optional(element) || /^[a-zA-Z0-9._+-]+@[a-zA-Z.-]+\.[a-zA-Z]{2,}$/.test(value);
                }, "Please enter a valid email address in the format: yourname@example.com. Only letters, numbers, dots (.), plus (+), hyphens (-), and underscores (_) before the @ symbol are allowed.");


                $("#user_form").validate({
                    rules: {
                        name: {
                            required: true,
                            alphaOnly :true,
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
                        password: {
                            required: true,
                            minlength: 8
                        },
                        confirm_password: {
                            required: true,
                            equalTo: "#password"
                        },
                    },
                    messages: {
                        name: {
                            required: "Please Enter Name",
                            regex :"Only Characters allowed" //Only Characters
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
                            // email: "Please Enter Valid Email",
                        },
                        password: {
                            required: "Please Enter Password",
                            minlength: "Password must be at least 8 characters"
                        },
                        confirm_password: {
                            required: "Please Enter Confirm Password",
                            equalTo: "Password and confirm password should same"
                        }
                    }
                })

                $('#submit').click(function() {
                    $("#user_form").validate(); // This is not working and is not validating the form
                });

            });
        </script>
    @endpush
