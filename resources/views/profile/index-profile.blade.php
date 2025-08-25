@extends('layouts.default')
@section('content')
@section('content')
    <div class="content">
        <div
            class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">
                    Manage Users
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded" style="border: 1px solid #006034">
            <div class="block-header block-header-default" style="background-color: #006034;color:white">
                <h3 class="block-title">Profile</h3>
            </div>

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (isset($user_profile) && !empty($user_profile))
                <div class="block-content block-content-full">
                    <div class="row g-3">
                        <div class="col-10">
                            <div class="row g-3">
                                <div class="col-4">
                                    <label class="form-label">First Name:</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ $user_profile->first_name ?? '' }}">

                                </div>
                                <div class="col-4">
                                    <label class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ $user_profile->last_name ?? '' }}">

                                </div>

                                <div class="col-4">
                                    <label class="form-label">CNIC</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ $user_profile->cnic ?? '' }}">

                                </div>
                                <div class="col-4">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ $user_profile->phone ?? '' }}">

                                </div>

                                {{-- @php
                                    use Carbon\Carbon;
                                    $formattedDate = Carbon::parse($user_profile->dob)->format('d-m-Y');
                                @endphp --}}

                                <div class="col-4">
                                    <label class="form-label">Date Of Birth</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ $user_profile->dob ?? '' }}">

                                </div>
                                <div class="g-3 col-6">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" readonly
                                        value="{{ $user_profile->address ?? '' }}" />
                                </div>


                                {{-- <div class="col-sm-12 text-end">
                                        <input type="submit" id="submit" name="{{ $value }}"
                                            class="btn btn-xm btn-secondary"
                                            onclick="next('pills-home-tab','pills-home', 'pills-profile-tab','pills-profile')"
                                            value="{{ $value }}" />
                                        <input type="hidden" name="form_type" id="form_type" value="{{ $value }}">
                                    </div> --}}
                                {{-- @dd($user_profile->media); --}}
                                @if (isset($user_profile->media) && !empty($user_profile->media))
                                    <div class="col-6 d-flex justify-content-end">

                                        <a href="{{ url($user_profile->media->file_path) }}" target="_blank">
                                            <img src="{{ asset($user_profile->media->file_path) }}"
                                                class="img-fluid rounded-circle" alt="User Photo"
                                                style="width: 200px; height:  200px;">
                                        </a>


                                        {{-- <a href="{{ url($user_profile->media->file_path) }}" target="_blank"
                                            style="font-size: 12px">
                                            <i class="fa fa-{{ $user_profile->media->file_path == 'jpg' || $user_profile->media->file_type == 'jpeg' || $user_profile->media->file_type == 'png' ? 'image' : 'file' }}"
                                                aria-hidden="true"></i>
                                            View Image
                                        </a> --}}
                                    </div>
                                @endif

                            </div>

                            {{-- <div class="row g-3">
                                <div class="col-4">
                                    <label>Name:
                                        {{ isset($user_profile->first_name) ? $user_profile->first_name : '' . ' ' }}
                                        {{ isset($user_profile->last_name) ? $user_profile->last_name : '' }}
                                    </label>
                                </div>
                                <div class="col-4">
                                    <label>CNIC: {{ isset($user_profile->cnic) ? $user_profile->cnic : '' }} </label>
                                </div>
                                <div class="col-4">
                                    <label>Phone: {{ isset($user_profile->phone) ? $user_profile->phone : '' }} </label>
                                </div>

                            </div>
                            <br />
                            <div class="row g-3">
                                <div class="col-4">
                                    <label>Date of Birth: {{ isset($user_profile->dob) ? $user_profile->dob : '' }} </label>
                                </div>
                                <div class="col-4">
                                    <label>Address: {{ isset($user_profile->address) ? $user_profile->address : '' }}
                                    </label>
                                </div>
                            </div> --}}

                        </div>

                        {{--
                        @if (isset($user_photo->file_path) && !empty($user_photo->file_path))
                            <div class="col-2">
                                <a href="{{ url($user_photo->file_path) }}" target="_blank">
                                    <img src="{{ asset($user_photo->file_path) }}"
                                        class="img-fluid w-50 h-100 rounded-circle" alt="User Photo" width="200px"
                                        height="200px">
                                </a>
                            </div>
                        @endif
                        --}}

                    </div>
                </div>
            @else
                {{-- <h2>No Record Found</h2> --}}
                <div class="text-center">
                    <label for="address">No Record Added</label>

                </div>
            @endif
        </div>
    </div>
@endsection
@endsection
