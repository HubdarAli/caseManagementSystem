@extends('layouts.default')

@section('content')

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
                <h3 class="block-title">View Court Case</h3>
            </div>
            {{-- @dd($court_case) --}}
            <div class="block-content block-content-full">
                
                    <div class="row p-1">
                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="case_number">Case Number </label>
                            <input type="text" class="form-control" id="case_number" name="case_number"
                                   placeholder="Enter Case Number" value="{{ old('case_number',$court_case->case_number) }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="title">Case Title </label>
                            <input type="text" class="form-control" id="title" name="title"
                                   placeholder="Enter Case Title" value="{{ old('title',$court_case->title) }}" disabled>
                        </div>
<div class="col-md-6 mt-3">
                            <label class="form-label" for="applicant">Applicant</label>
                            <input type="text" class="form-control" id="applicant" name="applicant"
                                   placeholder="Enter Applicant" disabled value="{{ old('applicant',$court_case->applicant) }}">
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="respondent">Respondent</label>
                            <input type="text" class="form-control" id="respondent" name="respondent"
                                   placeholder="Enter Respondent"  disabled value="{{ old('respondent',$court_case->respondent) }}">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="case_type">Case Type </label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="{{ old('title',$court_case->case_type) }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="status">Status</label>
                            <input type="text" class="form-control" id="title" name="title"
                                    value="{{ old('title',$court_case->status) }}" disabled>
                            
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="hearing_date">Next Hearing Date</label>
                            <input type="date" class="form-control" id="hearing_date" name="hearing_date"
                                   value="{{ old('hearing_date',$court_case->hearing_date) }}" disabled>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="counsel">Counsel</label>
                            <input type="text" class="form-control" id="counsel" name="counsel"
                                   placeholder="Enter Counsel" value="{{ old('counsel', $court_case->counsel) }}" disabled>
                        
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="file_no">File#</label>
                            <input type="text" class="form-control" id="file_no" name="file_no"
                                   placeholder="Enter File No" value="{{ old('file_no' , $court_case->file_no) }}" disabled>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="district_id">District </label>
                            <input type="text" class="form-control" id="title" name="title"
                                 value="{{ old('title',$court_case->district->name) }}" disabled>
        
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label" for="court_id">Court </label>
                            <input type="text" class="form-control" id="title" name="title"
                                 value="{{ old('title',$court_case->court->name) }}" disabled>
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label" for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Enter any notes..." disabled>{{ old('notes',$court_case->notes) }}</textarea>
                        </div>
                    </div>

            </div>
        </div>
    </div>
@endsection

