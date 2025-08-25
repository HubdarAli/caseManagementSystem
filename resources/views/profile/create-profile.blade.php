@extends('layouts.default')

@push('style')
    <style>
        .nav-link {
            background-color: #006034;
            margin: 0px;
            font-weight: normal;

        }

        .nav-pills .nav-link {
            border-radius: 0;
            color: white;
            padding: 10px;
            border: 1px solid white;
        }

        .nav-pills .nav-link:focus,
        .nav-pills .nav-link:hover {
            background-color: #006034;
        }

        .nav-pills .nav-link.active {
            background-color: white;
            color: #006034;
            border-top: 2px solid #006034;
        }

        .custom-tab {
            width: 200px;
            /* Set the desired width */
        }

        .image-preview {
            width: 100%;
            border: 1px solid gray;
            border-radius: 5px;
        }

        .image-preview>p {
            text-align: center;
            margin-top: 20px;
        }

        .nav-pills .nav-link {
            text-align: center;
        }

        label.error {
            color: red;
        }

        input.error {
            border-color: red;
        }

        select.error {
            border-color: red;
        }

        .btn-secondary {
            background-color: #006034;
            border: 1px solid #006034;
            color: white;
        }
    </style>
@endpush
@section('content')
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Rest of your view content -->

    <div class="content">
        <div id="success">

        </div>
        <div class="col-12 mx-auto block block-rounded">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link custom-tab active" id="pills-home-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-home" href="#pills-home" role="tab" aria-controls="pills-home"
                        aria-selected="true">Basic Info</a>
                </li>
                {{-- <li class="nav-item" role="presentation">
                    <a class="nav-link custom-tab" id="pills-profile-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-profile" href="#pills-profile" role="tab" aria-controls="pills-profile"
                        aria-selected="false">Experiance</a>
                </li> --}}
                {{-- <li class="nav-item" role="presentation">
                    <a class="nav-link custom-tab" id="pills-contact-tab" data-bs-toggle="pill"
                        data-bs-target="#pills-contact" href="#pills-contact" role="tab" aria-controls="pills-contact"
                        aria-selected="false">Working Experience</a>
                </li> --}}
            </ul>
            <form action="{{ route('profile.update', $user->id) }}" method="POST" id="user_form"
                enctype="multipart/form-data">
                @method('PATCH')
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <!-- Content for the first tab -->

                        <div class="container mt-5 p-3">
                            <div>

                                @csrf
                                <div class="row g-3">
                                    <div class="col-4">
                                        <label class="form-label">Name <strong style="color:red">*</strong></label>
                                        <input type="text" name="name"class="form-control" placeholder="Name"
                                            value="{{ $user->name }}">

                                        @if (isset($errors) && $errors->has('name'))
                                            <span style="color:red">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Email <strong style="color:red">*</strong></label>
                                        <input type="email" name="email" class="form-control" placeholder="Email"
                                            autocomplete="off" value="{{ $user->email }}" disabled />

                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Group <strong style="color:red">*</strong></label>
                                        <select name="groups" class="form-control select select2" id="group_id" disabled>
                                            <option value="">-- Select Group --</option>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}"
                                                    {{ $group->id == $user->group_id ? 'selected' : '' }}>
                                                    {{ $group->group_name }}</option>
                                            @endforeach
                                        </select>
                                        <label id="group_id-error" class="error" for="group_id"></label>
                                        @if (isset($errors) && $errors->has('groups'))
                                            <span style="color:red">{{ $errors->first('groups') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label">Role <strong style="color:red">*</strong></label>
                                        <select name="roles" class="form-control select select2" id="role_id" disabled>
                                            <option value="">-- Select Role --</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ $user->roles[0]->id == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        <label id="role_id-error" class="error" for="role_id"></label>
                                        @if (isset($errors) && $errors->has('roles'))
                                            <span style="color:red">{{ $errors->first('roles') }}</span>
                                        @endif
                                    </div>
                                    {{-- <div class="col-4 mt-5">
                                        <a href="{{ url('Profile/update_user_password', ['id' => $user->id]) }}">Update Password</a>
                                    </div> --}}
                                    <div class="col-sm-12 text-end">
                                        {{-- <button type="reset" class="btn btn-xm btn-secondary">Cancel</button> --}}
                                        <button type="submit" class="btn btn-xm btn-success" id="submit">Update</button>
                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>

                </div>
            </form>



        </div>
    </div>
@endsection

@push('script')
    @include('includes.form-scripts')
    <script src="{{ asset('assets/js/lib/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.inputmask.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $(function() {
                $('#datepicker').datepicker({
                    format: 'dd-mm-yyyy',
                    endDate: '-18y'

                });
            });

        });
        // This is not working and is not validating the form



        function next(CurrentTabId, CurrentTextId, nextTabId, nextTextId) {
            // $("#" + CurrentTabId).removeClass("active");
            // $("#" + CurrentTextId).removeClass("show active");
            // $("#" + nextTabId).addClass("active");
            // $("#" + nextTextId).addClass("show active");

        }

        // $("#user_form").on("submit", function(event) {
        //     event.preventDefault();
        //     var formData = new FormData(this);
        //     $.ajax({
        //         type: "POST",
        //         contentType: false,
        //         processData: false,
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: "{{ route('profile.store') }}", // Replace with your actual URL
        //         data: formData,
        //         success: function(response) {
        //             // Handle the successful response
        //             window.location.href = "{{ route('profile.index') }}";
        //             $('#success').html(
        //                 '<p class="alert alert-success">Profile Created SuccessFully</p>');
        //         },
        //         error: function(error) {
        //             console.log(error.responseJSON.errors);
        //             $.each(error.responseJSON.errors, function(field, error) {
        //                 $("#" + field).html(error[0]);
        //             });
        //         }
        //     });

        // });

        $(document).ready(function() {
            var photo_required = $('#form_type').val();
            photo_required = photo_required != 'Update Profile' ? true : false;
            $('#cnic').inputmask("99999-9999999-9");
            $('#phone').inputmask("9999-9999999");

            // if ($('#cnic').val() !== null) {
            //     $('#cnic').attr('disabled', 'disabled');
            // }
            // if ($('#dob').val() !== null) {
            //     $('#dob').attr('disabled', 'disabled');

            // }

            // var cnicRules = {
            //     required: true,
            //     maxlength: 15
            // };
            // if ($("#cnic").prop("disabled")) {
            //     cnicRules.required = false;
            // }
            // var dobRules = {
            //     required: true
            // };
            // if ($("#cnic").prop("disabled")) {
            //     required.required = false;
            // }

            $("#user_form").validate({
                rules: {
                    first_name: {
                        required: true,
                        maxlength: 10,
                    },
                    last_name: {
                        required: true,
                        maxlength: 10,
                    },
                    photo: {
                        // required: photo_required,
                        extension: "jpg,jpeg,png"
                    },
                    phone: {
                        required: true,
                        maxlength: 12
                    },
                    cnic: {
                        required: true,
                        maxlength: 15
                    },
                    dob: {
                        required: true
                    },
                    address: {
                        required: true,
                        maxlength: 100
                    }
                },
                messages: {
                    first_name: {
                        required: "Please Enter  First Name",
                        maxlength: "First Name cannot be more than 10 characters"
                    },
                    last_name: {
                        required: "Please Enter last Name",
                        maxlength: "Last Name cannot be more than 10 characters"
                    },
                    photo: {
                        required: "Please Upload Photo",
                        extension: "Upload only JPEG and PNG"
                    },
                    cnic: {
                        required: "Please Enter CNIC",

                    },
                    phone: {
                        required: "Please Enter Phone Number",

                    },
                    dob: {
                        required: "Please Select Date of Birth",

                    },
                    address: {
                        required: "Please Enter Address",

                    },

                }


            });




        });
    </script>
@endpush
