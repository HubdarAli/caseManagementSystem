@extends('layouts.default')
@section('content')
    @include('partials.alerts')
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
    @endpush
    <div class="content">
        <div
            class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">
                    District
                </h1>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                <a href="{{ route('district.index') }}" class="btn btn-sm btn-dark me-1 mb-3"><i
                        class="fa fa-fw fa-angle-left
                    me-1"></i>Back</a>
            </div>
        </div>
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">District</h3>
            </div>
            <div class="block-content block-content-full">
                <form class=" g-3 align-items-center" id="edit-form" action="{{ route('district.update', $district->id) }}"
                    method="POST">
                    @method('PUT')
                    @csrf
                    <div class="row p-1">
                        <div class="col-6 mt-3">
                            <label class="form-label" for="example-if-name">District Name</label>
                            <input type="hidden" name="id" value="{{ $district->id }}">
                            <input type="text" class="form-control" id="" value="{{ $district->name }}"
                                name="name" placeholder="Name" disabled>
                        </div>
                        <div class="col-6 mt-3">
                            <label class="form-label">Code</label>
                            <input type="number" class="form-control" id="code" disabled
                                oninput="this.value = this.value.slice(0, 2);" value="{{ $district->code }}"
                                placeholder="Enter Code">
                        </div>
                        <div class="col-6 mt-3">
                            <label class="form-label" for="latitude">Latitude</label>
                            <input type="text" class="form-control" value="{{ $district->latitude }}"
                                name="latitude" placeholder="Latitude" oninput="validateCoordinates(this)" disabled>
                        </div>
                        <div class="col-6 mt-3">
                            <label class="form-label" for="longitude">Longitude</label>
                            <input type="text" class="form-control" value="{{ $district->longitude }}"
                                name="longitude" placeholder="Longitude" oninput="validateCoordinates(this)" disabled>
                        </div>

                        <!-- <div class="col-6 mt-3">
                  <label class="form-label">Short Code</label><span class="text-danger">*</span>
                  <input type="text" class="form-control" id="" value="{{ $district->formatted_shortcode }}" name="short_code" placeholder="Enter Short Code">
                </div> -->
                    </div>
                
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/lib/jquery-form-validation.min.js') }}"></script>

    <script>
        function validateCoordinates(input) {
            input.value = input.value.replace(/[^0-9.,\-']/g,'');
          }
          
        $(document).ready(function() {

            $.validator.addMethod("alphaOnly", function(value, element) {
                return /^[a-zA-Z\s]+$/.test(value);
            }, "Please enter alphabetic characters only.");

            $('#edit-form').validate({
                rules: {
                    name: {
                        required: true,
                        alphaOnly: true,
                        minlength: 3,
                        maxlength: 24
                    },
                    // short_code: {
                    //     required: true,
                    //     alphaOnly: true,
                    // },
                    code: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                        minlength: 2,
                    },
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
                    //     maxlength: 'Please Enter Maximum 2 Digits'
                    //     minlength: 'Please Enter Maximum 2 Digits'
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
