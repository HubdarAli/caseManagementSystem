@extends('layouts.default')
@section('content')
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

            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
        </style>
        @include('partials.alerts')
        <div class="content">
            <div
                class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
                <div class="flex-grow-1 mb-1 mb-md-0">
                    <h1 class="h3 fw-bold mb-1">
                        Add District
                    </h1>
                </div>
                <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                    <a href="{{ route('district.index') }}" class="btn btn-sm btn-dark me-1 mb-3">
                        <i class="fa fa-fw fa-angle-left me-1"></i>
                        Back
                    </a>
                </div>
            </div>
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Add District</h3>
                </div>
                <div class="block-content block-content-full">
                    <form class=" g-3 align-items-center" id="add-form" action="{{ route('district.store') }}" method="POST">
                        @csrf
                        <div class="row p-1">
                            <div class="col-6 mt-3">
                                <label class="form-label" for="name">District Name</label><span class="text-danger">*</span>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter Name" value="{{ old('name') }}">
                            </div>
                            <div class="col-6 mt-3">
                                <label class="form-label" for="latitude">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude"
                                    placeholder="Enter Latitude" value="{{ old('latitude') }}"
                                    oninput="validateCoordinates(this)">
                            </div>
                            <div class="col-6 mt-3">
                                <label class="form-label" for="longitude">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude"
                                       placeholder="Enter Longitude" value="{{ old('longitude') }}"
                                       oninput="validateCoordinates(this)">
                            </div>
                            <!-- <div class="col-6 mt-3">
                                <label class="form-label">Short Code </label><span class="text-danger">*</span>
                                <input type="text" class="form-control" id="" name="short_code" placeholder="Enter Short Code" value="{{ old('short_code') }}">
                            </div> -->

                            {{-- <div class="col-6 mt-3">
                                <label class="form-label">Code </label><span class="text-danger">*</span>
                                <input type="number" class="form-control" id="" name="code"  oninput="this.value = this.value.slice(0, 2);" placeholder="Enter Code" value="{{old('code')}}">
                            </div> --}}
                        </div>
                        <div class="row mt-2 text-end">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/lib/jquery-form-validation.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery-inputmasking.js') }}"></script>
    <script>
        function validateCoordinates(input) {
            input.value = input.value.replace(/[^0-9.,\-']/g,'');
          }

        $(document).ready(function() {
            $.validator.addMethod("alphaOnly", function(value, element) {
                return /^[A-Za-z0-9@ ]+$/.test(value);
            }, "Please enter alphabetic characters only.");
           
           
            $(":input").inputmask();
            $('#add-form').validate({
                rules: {
                    name: {
                        required: true,
                        alphaOnly: true,
                        minlength: 3,
                        maxlength: 24,
                    },
                    // short_code: {
                    //     required: true,
                    //     alphaOnly: true,
                    // },
                    // code: {
                    //     required: true,
                    //     digits: true,
                    //     maxlength: 2,
                    //     minlength: 2
                    // },
                },
                messages: {
                    name: {
                        required: 'Please Enter District Name.',
                        alphaOnly: 'Please Enter Alphabetic Characters Only',
                        minlength: 'Please Enter Minimum 3 Characters',
                    },
                    // short_code: {
                    //     required: 'Please Enter District Short Code.',
                    //     alphaOnly: 'Please Enter Alphabetic Characters Only'
                    // },
                    // code: {
                    //     required: 'Please Enter District Code.',
                    //     digits: 'Please Enter Valid Numeric Code.',
                    //     maxlength: 'Please Enter Maximum 2 Digits',
                    //     minlength: 'Please Enter Minimum 2 Digits'
                    // },
                },

                // errorElement: "span",

                // errorPlacement: function(error, element) {
                //     error.addClass("text-danger");
                //     error.insertAfter(element);
                // },


            });
        });
    </script>
@endsection
