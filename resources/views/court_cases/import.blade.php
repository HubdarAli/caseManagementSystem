@extends('layouts.default')

@section('content')
    @push('style')
        <style>
            label.error {
                color: red;
            }

            input.error,
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

    @include('partials.alerts')

    <div class="content">
        <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">Add Court Case</h1>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                <a href="{{ route('courts-cases.index') }}" class="btn btn-sm btn-dark me-1 mb-3">
                    <i class="fa fa-fw fa-angle-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Add Court Case</h3>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route('courts-cases.import') }}" id="import-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="file" class="form-label">Select Excel File</label>
                        <input type="file" name="file" id="file" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Import Court Cases</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/lib/jquery-form-validation.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#import-form').validate({
                rules: {
                    file: {
                        required: true,
                        extension: "xlsx|xls,csv"
                    }
                },
                messages: {
                    file: {
                        required: "Please select a file to upload.",
                        extension: "Please upload a valid Excel or CSV file."
                    }
                }
            });

            $('.clear_date').click(function(e) {
                e.preventDefault();
                $(this).parent('div').find('input.form-control').val('');
            });
        });
    </script>
@endsection
