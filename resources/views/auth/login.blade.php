<!doctype html>
<html lang="en">

<head>
    @include('includes.head')
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

        #password {
            display: inline;
        }

        .position-relative {
            position: relative;
        }

        .password-eye-show {
            position: absolute;
            right: 10px; /* Adjust as needed */
            top: 40%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .form-control {
            padding-right: 40px; /* Make space for the icon */
        }
    </style>
</head>

<body>
    <div id="page-container">
        <main id="main-container">
            <div class="bg-image" style="background-image: url("{{ asset('assets/media/photos/photo28@2x.jpg') }}");">
                <div class="row g-0 bg-primary-dark-op">
                    <div class="hero-static col-lg-5 d-flex flex-column align-items-center bg-body-extra-light">
                        <div class="hero-static d-flex align-items-center">
                            <div class="content">
                                <div class="row justify-content-center push">
                                    <div class="col-md-11">
                                        <!-- Sign In Block -->
                                        <div class="block block-rounded mb-0">
                                            <div class="block-header block-header-default">
                                                <h3 class="block-title">Sign In</h3>
                                                <div class="block-options">
                                                    {{-- <a class="btn-block-option fs-sm" href="op_auth_forgot.html">Forgot --}}
                                                    {{-- Password</a> --}}
                                                </div>
                                            </div>
                                            <div class="block-content">
                                                <div class="p-sm-3 px-lg-2 px-xxl-5 py-lg-3">
                                                    <div class="text-center mb-3">
                                                        <img src="{{ asset('assets/media/photos/logo1.png') }}"
                                                            width="380" class="img-fluid" alt="" />
                                                    </div>
                                                    <form class="js-validation-signin" action="{{ route('login') }}"
                                                        method="POST" id="login-form">
                                                        @csrf
                                                        <div class="py-3">
                                                            <div class="mb-4">
                                                                <input type="text"
                                                                    class="form-control form-control-alt form-control-lg"
                                                                    id="email" name="email" placeholder="Username" autocomplete="off">
                                                            </div>
                                                            <div class="mb-4 position-relative">
                                                                <input type="password" class="form-control form-control-alt form-control-lg" id="password" name="password" placeholder="Password" autocomplete="off">
                                                                <span class="password-eye-show" id="password-show"><i class="fa fa-eye-slash"></i></span>
                                                            </div>

                                                            <div class="mb-4">
                                                                @error('error')
                                                                    <span class="text-danger">
                                                                        {{ $message }}
                                                                    </span>
                                                                @enderror
                                                                @if (session('message'))
                                                                    <span class="text-danger">
                                                                        {{ session('message') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            <div class="mb-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="remember" name="remember">
                                                                    <label class="form-check-label"
                                                                        for="login-remember">Remember Me</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <div class="col-md-12 text-end">
                                                                <button type="submit" class="btn btn-lg btn-success"
                                                                    id="login-button">
                                                                    <i class="fa fa-fw fa-sign-in-alt me-1"></i> Sign In
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-static col-lg-7 d-none d-lg-flex flex-column justify-content-center">
                        <div class="p-4 p-xl-5 flex-grow-1 d-flex align-items-center">
                            <div class="w-100">
                                <h4 class="link-fx fw-semibold fs-2 text-white">
                                    Sindh Flood Emergency Rehabilitation Project
                                </h4>                                  
                                <p class="text-white-75 me-xl-8 mt-2" style="text-align: justify;">
                                    The project’s development objectives are to Rehabilitate damaged infrastructure and
                                    provide short-term livelihood opportunities in selected areas of Sindh province
                                    affected by the 2022 floods and Strengthen the Government of Sindh’s capacity to
                                    respond to the impacts of climate change and natural hazards.
                                </p>
                            </div>
                        </div>
                        <div class="p-4 p-xl-5 d-xl-flex justify-content-between align-items-center fs-sm">
                            <p class="fw-medium text-white mb-0">
                                <strong>Copyright © <span data-toggle="year-copy"></span>, Sindh Flood Emergency
                                    Rehabilitation Project (Govt of Sindh)</strong>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @include('includes.foot')
    {{-- <script src="{{ asset('assets/js/pages/op_auth_signin.min.js') }}"></script> --}}
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#login-form').validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                    },
                    password: "required",
                },
                submitHandler: function(form) {
                    $('#login-button').attr('disabled', 'disabled');
                    form.submit();
                }
            });

            $('#password-show').click(function(e) {
                var typesd = $('#password').attr('type')
                if (typesd == 'password') {
                    $('#password').attr('type', 'text');
                    $('.fa-eye-slash').addClass('fa-eye').removeClass('fa-eye-slash');
                } else {
                    $('#password').attr('type', 'password');
                    $('.fa-eye').addClass('fa-eye-slash').removeClass('fa-eye');
                }
            });

        });
    </script>
</body>

</html>
