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

        .position-relative {
            position: relative;
        }

        .password-eye-show {
            position: absolute;
            right: 10px;
            top: 40%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .row.g-3 {
            margin-bottom: 0 !important;
        }

        .mb-2 {
            margin-bottom: 0.5rem !important;
        }

        .form-label {
            margin-bottom: 0.25rem !important;
        }
    </style>
@endpush

@section('content')
    <div class="content">

        <!-- Notification Section with Close Button -->
        <div class="notification">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="col-8 mx-auto block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Update Password</h3>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route('UpdatePassword') }}" method="POST" autocomplete="off" id="user_form">
                    @csrf
                    @method('POST')
                    <div class="row g-3">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($user_current_password)
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                <div class="mb-2 position-relative">
                                    <input type="password" name="old_password" id="old_password" placeholder="Enter Current Password" class="form-control" />
                                    <span class="password-eye-show" id="password-show"><i class="fa fa-eye-slash current_pass"></i></span>
                                </div>
                                @if (isset($errors) && $errors->has('old_password'))
                                    <span style="color:red">{{ $errors->first('old_password') }}</span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-12">
                                <input type="hidden" name="user_id" id="user_id" value="{{ $id ?? '' }}" />
                                <input type="hidden" name="user_old_password" id="user_old_password" value="{{ $user_current_password ? 'true' : 'false' }}" />
                                <label class="form-label">New Password <span class="text-danger">*</span></label>
                                <div class="mb-2 position-relative">
                                    <input type="password" name="password" placeholder="Enter New Password" class="form-control" id="password" />
                                    <span class="password-eye-show" id="new_password"><i class="fa fa-eye-slash new_pass"></i></span>
                                </div>
                                @if (isset($errors) && $errors->has('password'))
                                    <span style="color:red">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                <div class="mb-2 position-relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Enter New Confirm Password" class="form-control" />
                                    <span class="password-eye-show" id="confirm_password"><i class="fa fa-eye-slash confirm_pass"></i></span>
                                </div>
                                @if (isset($errors) && $errors->has('password.confirmed'))
                                    <span style="color:red">{{ $errors->first('password.confirmed') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-12 text-end">
                            <button type="submit" class="btn btn-xm btn-success" id="submit">Update Password</button>
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
            $.validator.addMethod('strongPassword', function(value) {
                    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(value);
                },
                'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
            );
            $("#user_form").validate({
                rules: {
                    old_password: {
                        required: true,
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        strongPassword: true
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password",
                    },
                },
                messages: {
                    old_password: {
                        required: "Please Enter Current Password"
                    },
                    password: {
                        required: "Please Enter New Password",
                        minlength: "Password must be at least 8 characters"
                    },
                    password_confirmation: {
                        required: "Please Enter Confirm Password",
                        equalTo: "Password and confirm password should match"
                    }
                }
            });

            $('#password-show').click(function(e) {
                var type = $('#old_password').attr('type');
                if (type == 'password') {
                    $('#old_password').attr('type', 'text');
                    $('.current_pass').addClass('fa-eye').removeClass('fa-eye-slash');
                } else {
                    $('#old_password').attr('type', 'password');
                    $('.fa-eye').addClass('fa-eye-slash').removeClass('fa-eye');
                }
            });

            $('#new_password').click(function(e) {
                var type = $('#password').attr('type');
                if (type == 'password') {
                    $('#password').attr('type', 'text');
                    $('.new_pass').addClass('fa-eye').removeClass('fa-eye-slash');
                } else {
                    $('#password').attr('type', 'password');
                    $('.fa-eye').addClass('fa-eye-slash').removeClass('fa-eye');
                }
            });

            $('#confirm_password').click(function(e) {
                var type = $('#password_confirmation').attr('type');
                if (type == 'password') {
                    $('#password_confirmation').attr('type', 'text');
                    $('.confirm_pass').addClass('fa-eye').removeClass('fa-eye-slash');
                } else {
                    $('#password_confirmation').attr('type', 'password');
                    $('.fa-eye').addClass('fa-eye-slash').removeClass('fa-eye');
                }
            });
        });
    </script>
@endpush
