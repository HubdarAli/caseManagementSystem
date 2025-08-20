@extends('layouts.default')

@section('content')
    @push('style')
        <style>
            label.error {
                color: red;
            }
            input.error, select.error {
                border-color: red;
            }
        </style>
    @endpush

    @include('partials.alerts')

    <div class="content">
        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">
                    Court
                </h1>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                <a href="{{ route('court.index') }}" class="btn btn-sm btn-dark me-1 mb-3">
                    <i class="fa fa-fw fa-angle-left me-1"></i>
                    Back
                </a>
            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">View Court</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row p-1">
                        <div class="col-6 mt-3">
                            <label class="form-label" for="name">Court Name</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" id="name" name="name"
                                   placeholder="Enter Court Name" value="{{ old('name', $court->name) }}" disabled>
                        </div>

                        <div class="col-6 mt-3">
                            <label class="form-label" for="district_id">Select District</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" id="title" name="title"
                                 value="{{ old('title',$court->district->name) }}" disabled>
                        </div>

                        {{-- <div class="col-6 mt-3">
                            <label class="form-label" for="type">Court Type</label><span class="text-danger">*</span>
                            <select class="form-control" id="type" name="type">
                                <option value="">-- Select Court Type --</option>
                                @foreach(['Civil Suit','Civil Appeal','Suit','Criminal','Family','Labor','Arbitration','Summary Suit','Special Case','PPC','Other'] as $type)
                                    <option value="{{ $type }}" {{ old('type', $court->type) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/lib/jquery-form-validation.min.js') }}"></script>
    <script src="{{ asset('assets/js/lib/jquery-inputmasking.js') }}"></script>
    <script>
        $(document).ready(function() {
            $.validator.addMethod("alphaOnly", function(value, element) {
                return /^[A-Za-z0-9@ ]+$/.test(value);
            }, "Please enter alphabetic characters only.");

            $(":input").inputmask();

            $('#edit-form').validate({
                rules: {
                    name: {
                        required: true,
                        alphaOnly: true,
                        minlength: 3,
                        maxlength: 50,
                    },
                    district_id: {
                        required: true,
                    },
                    // type: {
                    //     required: true,
                    // }
                },
                messages: {
                    name: {
                        required: 'Please Enter Court Name.',
                        alphaOnly: 'Please Enter Alphabetic Characters Only',
                        minlength: 'Please Enter Minimum 3 Characters',
                    },
                    district_id: {
                        required: 'Please Select District.',
                    },
                    type: {
                        required: 'Please Select Court Type.',
                    }
                },
            });
        });
    </script>
@endsection
