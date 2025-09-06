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
                <h1 class="h3 fw-bold mb-1">Court Case</h1>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                <a href="{{ route('courts-cases.index') }}" class="btn btn-sm btn-dark me-1 mb-3">
                    <i class="fa fa-fw fa-angle-left me-1"></i> Back
                </a>
            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Edit Court Case</h3>
            </div>
            {{-- @dd($court_case) --}}
            <div class="block-content block-content-full">
                <form id="add-form" action="{{ route('courts-cases.update',$court_case) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row p-1">
                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="case_number">Case Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="case_number" name="case_number"
                                   placeholder="Enter Case Number" value="{{ old('case_number',$court_case->case_number) }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="title">Case Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                   placeholder="Enter Case Title" value="{{ old('title',$court_case->title) }}">
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="applicant">Applicant <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="applicant" name="applicant"
                                   placeholder="Enter Applicant" value="{{ old('applicant',$court_case->applicant) }}">
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="respondent">Respondent <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="respondent" name="respondent"
                                   placeholder="Enter Respondent" value="{{ old('respondent',$court_case->respondent) }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="case_type">Case Type <span class="text-danger">*</span></label>
                            {{-- <input type="text" class="form-control" id="case_type" name="case_type"
                                   placeholder="Civil/Criminal etc." value="{{ old('case_type') }}"> --}}
                            <select class="form-control" id="case_type" name="case_type">
                                <option value="">-- Select Case Type --</option>
                                <option value="Civil Suit" {{ old('case_type',$court_case->case_type) == 'Civil Suit' ? 'selected' : '' }}>Civil Suit</option>
                                <option value="Civil Appeal" {{ old('case_type',$court_case->case_type) == 'Civil Appeal' ? 'selected' : '' }}>Civil Appeal</option>
                                <option value="Summary Suit" {{ old('case_type',$court_case->case_type) == 'Summary Suit' ? 'selected' : '' }}>Summary Suit</option>
                                <option value="Suit" {{ old('case_type',$court_case->case_type) == 'Suit' ? 'selected' : '' }}>Suit</option>
                                <option value="Criminal" {{ old('case_type',$court_case->case_type) == 'Criminal' ? 'selected' : '' }}>Criminal</option>
                                <option value="Family" {{ old('case_type',$court_case->case_type) == 'Family' ? 'selected' : '' }}>Family</option>
                                <option value="Labor" {{ old('case_type',$court_case->case_type) == 'Labor' ? 'selected' : '' }}>Labor</option>
                                <option value="Special Case" {{ old('case_type',$court_case->case_type) == 'Special Case' ? 'selected' : '' }}>Special Case</option>
                                <option value="Arbitration" {{ old('case_type',$court_case->case_type) == 'Arbitration' ? 'selected' : '' }}>Arbitration</option>
                                <option value="PPC" {{ old('case_type',$court_case->case_type) == 'PPC' ? 'selected' : '' }}>PPC</option>
                                <option value="Other" {{ old('case_type',$court_case->case_type) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">-- Select Status --</option>
                                <option value="Open" {{ old('status',$court_case->status) == 'Open' ? 'selected' : '' }}>Open</option>
                                <option value="Closed" {{ old('status',$court_case->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                                <option value="In Progress" {{ old('status',$court_case->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="hearing_date">Next Hearing Date</label>
                            <input type="date" class="form-control" id="hearing_date" name="hearing_date"
                                   value="{{ old('hearing_date',$court_case->hearing_date) }}">
                            <span class="clear_date small">
                                <a href="">Clear Date</a>
                            </span>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="district_id">District <span class="text-danger">*</span></label>
                            <select class="form-control" id="district_id" name="district_id">
                                <option value="">-- Select District --</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}" {{ old('district_id',$court_case->district_id) == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="court_id">Court <span class="text-danger">*</span></label>
                            <select class="form-control" id="court_id" name="court_id">
                                <option value="">-- Select Court --</option>
                                @foreach ($courts as $court)
                                    <option value="{{ $court->id }}" {{ old('court_id',$court_case->court_id) == $court->id ? 'selected' : '' }}>
                                        {{ $court->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        

                        {{-- <div class="col-md-6 mt-3">
                            <label class="form-label" for="user_id">Assigned User</label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="">-- Select User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="col-12 mt-3">
                            <label class="form-label" for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Enter any notes...">{{ old('notes',$court_case->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="row mt-4 text-end">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script src="{{ asset('assets/js/lib/jquery-form-validation.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#add-form').validate({
                rules: {
                    case_number: { required: true },
                    title: { required: true, minlength: 3 },
                    applicant: { required: true, minlength: 3 },
                    respondent: { required: true, minlength: 3 },
                    case_type: { required: true },
                    court_id: { required: true },
                    district_id: { required: true },
                },
                messages: {
                    case_number: { required: 'Please enter the case number.' },
                    title: { required: 'Please enter the case title.' },
                    applicant: { required: 'Please enter the Applicant.' },
                    respondent: { required: 'Please enter the Respondent.' },
                    case_type: { required: 'Please enter the case type.' },
                    court_id: { required: 'Please select a court.' },
                    district_id: { required: 'Please select a district.' },
                }
            });

            $('.clear_date').click(function(e) {
                e.preventDefault();
                $(this).parent('div').find('input.form-control').val('');
            });
        });
    </script>
@endsection
