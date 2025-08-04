@extends('layouts.default')
@push('style')
    <style>
        label.error {
            color: red;
        }
        input.error {
             border-color: red;
         }

         select.error{
            border-color: red;
         }

         .select2-container .select2-selection--single {
            margin-top: 5px;
            height: 40px; /* Set your desired height */
        }
    </style>
@endpush
@section('content')
@include('partials.alerts')

    @if (Session::has('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <p class="mb-0">
            {{ Session::get('success') }}
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="content">
        <div
            class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-1">
                     User
                </h1>
            </div>
            <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-dark me-1 mb-3"><i class="fa fa-fw fa-angle-left
                    me-1"></i>Back</a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">User Details</h3>
            </div>
            <div class="block-content block-content-full">
                {{-- {{ form layout changed irfan }} --}}
                {!! Form::open(['route' => ['users.update', $user->id], 'method' => 'PATCH', 'id' => 'user_form']) !!}

                    @csrf
                    <div class="row g-3">
                        <div class="col-4">
                            <label class="form-label">Name </label>
                            <input disabled type="text" name="name"class="form-control" placeholder="Name" value="{{ $user->name }}">

                            @if (isset($errors) && $errors->has('name'))
                                <span style="color:red">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                        <div class="col-4">
                            <label class="form-label">Email </label>
                            <input disabled type="email" name="email" class="form-control" placeholder="Email"
                                autocomplete="off" value="{{ $user->email }}" />
                            @if (isset($errors) && $errors->has('email'))
                                <span style="color:red">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                        <div class="col-4">
                            <label class="form-label">Group </label>
                            <select disabled name="groups" class="form-control select select2" id="group_id">
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}" {{ $group->id == $user->group_id  ? 'selected': ''}}>{{ $group->group_name }}</option>
                                @endforeach
                            </select>
                            <label id="group_id-error" class="error" for="group_id"></label>
                            @if (isset($errors) && $errors->has('groups'))
                                <span style="color:red">{{ $errors->first('groups') }}</span>
                            @endif
                        </div>
                        <div class="col-4">
                            <label class="form-label">Role </label>
                            <select disabled name="roles" class="form-control select select2" id="role_id">
                                <option value="">-- Select Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $user->roles[0]->id == $role->id ? 'selected' : ''}}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <label id="role_id-error" class="error" for="role_id"></label>
                            @if (isset($errors) && $errors->has('roles'))
                                <span style="color:red">{{ $errors->first('roles') }}</span>
                            @endif
                        </div>
                        <div class="col-4" id="smp_data">
                            <label class="form-label">SMPs </label>
                            <select disabled name="smp_id" class="form-control select select2" id="smp_id">
                                <option value="">-- Select SMP --</option>
                                @isset($smps)
                                    @foreach ($smps as $smp)
                                        <option value="{{ $smp->id }}"  {{ $smp->id == $user->smp_id ?? null  ? 'selected': ''}}>{{ $smp->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            <label id="smp_id-error" class="error" for="smp_id"></label>
                        </div>
                        <div class="col-4" id="district_data">
                            <label class="form-label">District:</label>
                            <select disabled name="district_id" class="form-control select2" id="district_id" multiple>
                            </select>
                        </div>
                        <div class="col-4" id="taluka_data">
                            <label class="form-label">Taluka:</label>
                            <select disabled name="taluka_id[]" class="form-control select select2" id="taluka_id" multiple>
                            </select>
                        </div>
                        <div class="col-4">
                            <label class="form-label">Status <strong style="color:red">*</strong></label>
                            <select disabled name="is_active" class="form-control select select2">
                                <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @if (isset($errors) && $errors->has('is_active'))
                                <span style="color:red">{{ $errors->first('is_active') }}</span>
                            @endif
                        </div>
                        {!! Form::close() !!}
            </div>
        </div>
    @endsection

{{-- {{ client validation added irfan }} --}}

 @push('script')
@include('includes.form-scripts');
        <script>
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $(document).ready(function() {
                $('#district_data').hide();
                $('#taluka_data').hide();
                $('#smp_data').hide();
                var get_group_id = $("#group_id option:selected").val();
                var get_role_id = $("#role_id option:selected").val();
                var selected_group = $("#group_id option:selected").text();
                var selected_role = $("#role_id option:selected").text();
                var smp_id = $("#smp_id option:selected").val();
                var logged_in = "{{ Auth::user()->group['group_name'] }}";

                if(smp_id)
                    $('#smp_data').show();

                if (selected_role.toLowerCase() == "{{strtolower(config('global.smp_roles.2'))}}" && selected_group == 'SMP')
                {
                    $('#district_data').show();
                    $('#taluka_data').show();
                    $.ajax({
                        type:'POST',
                        dataType: 'json',
                        // url: '/get_talukas',
                        url:"{{ route('get_district_smp') }}",
                        data:{"smp_id":smp_id},
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success:function(data){
                            $("#district_id").empty();
                            $("#district_id").append('<option value="">-- Select District --</option>');
                            $("#district_id").attr('multiple',false);
                            $("#district_id").select2();
                            $("#district_id").attr('name','district_id');
                            $("#taluka_id").empty();
                            $("#taluka_id").append('<option value="">-- Select Taluka --</option>');
                            var userDistrictIds = {!! json_encode($user->districts->pluck('id')) !!};
                            var userTalukaIds = {!! json_encode($user->talukas->pluck('id')) !!};
                            var selectedDistrictId = null;
                            for (var i = 0; i < data.length; i++) {
                                var selected = userDistrictIds.includes(data[i].district_id ?? data[i].id) ? 'selected' : '';
                                if(selected)
                                {
                                    $("#district_id").append('<option value="' + (data[i].district_id ?? data[i].id) + '" ' + selected +
                                        '>' + data[i].name + '</option>');
                                    selectedDistrictId = (data[i].district_id ?? data[i].id);
                                }
                                else{
                                    $("#district_id").append('<option value="' + (data[i].district_id ?? data[i].id) + '" ' + selected +
                                    '>' + data[i].name + '</option>');

                                }

                            }
                            $("#taluka_id").empty();
                            $("#taluka_id").append('<option value="">-- Select Taluka --</option>');
                            if (selectedDistrictId) {
                                $.ajax({
                                    type:'POST',
                                    dataType: 'json',
                                    // url: '/get_talukas',
                                    url:"{{ route('get_taluka_smp') }}",
                                    data:{"district_id":selectedDistrictId,"smp_id":smp_id},
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    success:function(data){
                                        $("#taluka_id").empty();

                                        for(var i=0; i<data.length;i++)
                                        {
                                            var selected = userTalukaIds.includes(data[i].id) ? 'selected' : '';
                                            if (selected) {
                                                $("#taluka_id").append('<option value="' + data[i].id + '"'+ selected +
                                                    '>' + data[i].name + '</option>');
                                            }
                                            else{
                                                $("#taluka_id").append('<option value="' + data[i].id + '"'+ selected +
                                                    '>' + data[i].name + '</option>');
                                            }

                                        }
                                    }
                                });
                            }

                        }
                    });
                }
                else if (selected_role.toLowerCase() == "{{strtolower(config('global.smp_roles.3'))}}" && selected_group == 'SMP')
                {
                    $('#district_data').show();

                    $.ajax({
                        type:'POST',
                        dataType: 'json',
                        // url: '/get_talukas',
                        url:"{{ route('get_district_smp') }}",
                        data:{"smp_id":smp_id},
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success:function(data){
                            $("#district_id").empty();
                            $("#district_id").append('<option value="">-- Select District --</option>');
                            $("#district_id").attr('multiple',false);
                            $("#district_id").select2();
                            $("#district_id").attr('name','district_id');
                            $("#taluka_id").empty();
                            $("#taluka_id").append('<option value="">-- Select Taluka --</option>');
                            var userDistrictIds = {!! json_encode($user->districts->pluck('id')) !!};
                            var userTalukaIds = {!! json_encode($user->talukas->pluck('id')) !!};
                            var selectedDistrictId = null;
                            for (var i = 0; i < data.length; i++) {
                                var selected = userDistrictIds.includes(data[i].district_id ?? data[i].id) ? 'selected' : '';
                                if(selected)
                                {
                                    $("#district_id").append('<option value="' + (data[i].district_id ?? data[i].id) + '" ' + selected +
                                        '>' + data[i].name + '</option>');
                                    selectedDistrictId = (data[i].district_id ?? data[i].id);
                                }
                                else{
                                    $("#district_id").append('<option value="' + (data[i].district_id ?? data[i].id) + '" ' + selected +
                                    '>' + data[i].name + '</option>');

                                }

                            }
                            $("#taluka_id").empty();


                        }
                    });
                }
                else if(selected_role.toLowerCase() == "{{strtolower(config('global.smp_roles.1'))}}" && selected_group == 'SMP')
                {
                    $('#district_data').show();
                    $("#district_id").empty();
                    $.ajax({
                        type:'POST',
                        dataType: 'json',
                        url:"{{ route('get_district_smp') }}",
                        data:{"smp_id":smp_id},
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success:function(data){
                            var userDistrictIds = {!! json_encode($user->districts->pluck('id')) !!};
                            $("#district_id").empty();
                            $("#district_id").attr('multiple',true);
                            $("#district_id").select2();
                            $("#district_id").attr('name','district_id[]');
                            for (var i = 0; i < data.length; i++) {
                                // $("#district_id").append('<option value="' + data[i].district_id + '" ' + ' selected >' + data[i].name + '</option>');
                                var selected = userDistrictIds.includes(data[i].district_id ?? data[i].id) ? 'selected' : '';
                                if(selected)
                                {
                                    $("#district_id").append('<option value="' + (data[i].district_id ?? data[i].id) + '" ' + selected +
                                        '>' + data[i].name + '</option>');
                                    selectedDistrictId = (data[i].district_id ?? data[i].id);
                                }
                                else{
                                    $("#district_id").append('<option value="' + (data[i].district_id ?? data[i].id) + '" ' + selected +
                                    '>' + data[i].name + '</option>');

                                }
                            }
                        }
                    });
                    $('#taluka_data').hide();
                }
                else if(selected_role.toLowerCase() == "{{strtolower(config('global.smp_roles.4'))}}" && selected_group == 'SMP')
                {

                    $('#district_data').show();
                    $('#taluka_data').show();
                    $.ajax({
                        type:'POST',
                        dataType: 'json',
                        // url: '/get_talukas',
                        url:"{{ route('get_district_smp') }}",
                        data:{"smp_id":smp_id},
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success:function(data){
                            $("#district_id").empty();
                            $("#district_id").append('<option value="">-- Select District --</option>');
                            $("#district_id").attr('multiple',false);
                            $("#district_id").select2();
                            $("#district_id").attr('name','district_id');
                            $("#taluka_id").empty();
                            $("#taluka_id").append('<option value="">-- Select Taluka --</option>');
                            var userDistrictIds = {!! json_encode($user->districts->pluck('id')) !!};
                            var userTalukaIds = {!! json_encode($user->talukas->pluck('id')) !!};
                            var selectedDistrictId = null;
                            for (var i = 0; i < data.length; i++) {
                                var selected = userDistrictIds.includes(data[i].district_id ?? data[i].id) ? 'selected' : '';
                                if(selected)
                                {
                                    $("#district_id").append('<option value="' + (data[i].district_id ?? data[i].id) + '" ' + selected +
                                        '>' + data[i].name + '</option>');
                                    selectedDistrictId = (data[i].district_id ?? data[i].id);
                                }
                                else{
                                    $("#district_id").append('<option value="' + (data[i].district_id ?? data[i].id) + '" ' + selected +
                                    '>' + data[i].name + '</option>');

                                }

                            }
                            $("#taluka_id").empty();
                            $("#taluka_id").append('<option value="">-- Select Taluka --</option>');
                            if (selectedDistrictId) {
                                $.ajax({
                                    type:'POST',
                                    dataType: 'json',
                                    // url: '/get_talukas',
                                    url:"{{ route('get_taluka_smp') }}",
                                    data:{"district_id":selectedDistrictId,"smp_id":smp_id},
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    success:function(data){
                                        $("#taluka_id").empty();
                                        $("#taluka_id").attr('multiple', false);
                                        $("#taluka_id").attr('name', 'taluka_id');
                                        $("#taluka_id").select2('destroy');
                                        $("#taluka_id").select2();
                                        for(var i=0; i<data.length;i++)
                                        {
                                            var selected = userTalukaIds.includes(data[i].id) ? 'selected' : '';
                                            if (selected) {
                                                $("#taluka_id").append('<option value="' + data[i].id + '"'+ selected +
                                                    '>' + data[i].name + '</option>');
                                            }
                                            else{
                                                $("#taluka_id").append('<option value="' + data[i].id + '"'+ selected +
                                                    '>' + data[i].name + '</option>');
                                            }

                                        }
                                    }
                                });
                            }

                        }
                    });
                }

                else
                {
                    $('#district_data').hide();
                    $('#taluka_data').hide();
                    $('#smp_data').hide();
                }

                $('#smp_id').off('change').on('change', function(){
                    smp_id = $("#smp_id option:selected").val();
                    $.ajax({
                        type:'POST',
                        dataType: 'json',
                        url:"{{ route('get_district_smp') }}",
                        data:{"smp_id":smp_id},
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success:function(data){
                            var selected = '';
                            if (selected_role.toLowerCase() == "{{strtolower(config('global.smp_roles.2'))}}" && selected_group == 'SMP')
                            {
                                $("#district_id").empty();
                                $("#district_id").append('<option value="">-- Select District --</option>');
                                $("#district_id").attr('multiple',false);
                                $("#district_id").select2();
                                $("#district_id").attr('name','district_id');
                                $("#taluka_id").empty();
                            }
                            else if(selected_role.toLowerCase() == "{{strtolower(config('global.smp_roles.3'))}}" && selected_group == 'SMP')
                            {
                                // selected = 'selected';
                                $("#district_id").empty();
                                $("#district_id").append('<option value="">-- Select District --</option>');
                                $("#district_id").attr('multiple',false);
                                $("#district_id").select2();
                                $("#district_id").attr('name','district_id');
                            }
                            else if(selected_role.toLowerCase() != "{{strtolower(config('global.smp_roles.2'))}}" && selected_group == 'SMP')
                            {
                                selected = 'selected';
                                $("#district_id").empty();
                                $("#district_id").attr('multiple',true);
                                $("#district_id").select2();
                                $("#district_id").attr('name','district_id[]');
                            }

                            else
                            {
                                $('#district_data').hide();
                                $('#taluka_data').hide();
                                $('#smp_data').hide();
                            }
                            for(var i=0; i<data.length;i++)
                            {
                                $("#district_id").append('<option value="'+ (data[i].district_id ?? data[i].id) +'" '+selected+'>'+data[i].name+'</option>');
                            }

                            $('#smp_id').select2('destroy');
                            $('#smp_id').val(smp_id).select2();
                        }
                    });
                });

                $('#group_id').change(function(){
                    selected_group = $("#group_id option:selected").text();
                    get_group_id = $("#group_id option:selected").val();

                    if(selected_group != 'SMP')
                    {
                        $('#district_data').hide();
                        $('#taluka_data').hide();
                        $('#smp_data').hide();
                    }

                    if(get_group_id == '')
                    {
                        $('#district_data').hide();
                        $('#taluka_data').hide();
                        $("#role_id").empty();
                        $("#role_id").append('<option value="">-- Select Role --</option>');
                    }
                    else if (get_group_id != '')
                    {
                        $.ajax({
                            type:'POST',
                            dataType: 'json',
                                // url: '/get_roles',
                            url:"{{ route('get_roles') }}",
                            data:{"group_id":get_group_id},
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success:function(data){
                                $("#role_id").empty();
                                $("#role_id").append('<option value="">-- Select Role --</option>');
                                for(var i=0; i<data.length;i++)
                                {
                                    if(selected_role == 'SMP' && data[i].name.toLowerCase() == "{{strtolower(config('global.smp_roles.1'))}}"  )
                                    {
                                        continue;
                                    }
                                    // if(logged_in == 'SMP' && "{{strtolower(auth()->user()->getRoleNames()[0])}}" == "{{strtolower(config('global.smp_roles.3'))}}")
                                    // {
                                    //     if(data[i].name.toLowerCase() == "{{strtolower(config('global.smp_roles.3'))}}")
                                    //         continue;
                                    //     // $("#role_id").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
                                    // }
                                    if("{{strtolower(auth()->user()->getRoleNames()[0])}}" == data[i].name.toLowerCase())
                                    {

                                        if (
                                            logged_in == 'Admin' && data[i].name.toLowerCase() ==
                                            "admin"
                                        ) {
                                            $("#role_id").append('<option value="' + data[i].id +
                                                '">' + data[i].name + '</option>');
                                        } else {
                                            continue;
                                        }

                                    }
                                    else
                                    {
                                        $("#role_id").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
                                    }
                                }
                            }
                        });
                    }
                });
                $('#role_id').change(function(){
                    $('#smp_id').val('').trigger('change');
                    $('#smp_id').select2('destroy');
                                    $('#smp_id').val('').select2();
                    selected_role = $("#role_id option:selected").text();

                    if(selected_role.toLowerCase() == "{{strtolower(config('global.smp_roles.2'))}}" && selected_group == 'SMP') {
                        $('#district_data').show();
                        $('#smp_data').show();
                        $("#district_id").empty();
                        $("#taluka_id").empty();
                        $('#smp_id').prop('disabled',false);
                        $('#smp_id').off('change').on('change', function(){
                            smp_id = $("#smp_id option:selected").val();
                            $.ajax({
                                type:'POST',
                                dataType: 'json',
                                url:"{{ route('get_district_smp') }}",
                                data:{"smp_id":smp_id},
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                success:function(data){
                                    $("#district_id").empty();
                                    $("#district_id").html('<option value="">-- Select District --</option>');
                                    $("#district_id").attr('multiple',false);
                                    $("#district_id").select2();
                                    $("#district_id").attr('name','district_id');
                                    $("#taluka_id").empty();
                                    for(var i=0; i<data.length;i++)
                                    {
                                        $("#district_id").append('<option value="'+ (data[i].district_id ?? data[i].id) +'">'+data[i].name+'</option>');
                                    }

                                    $('#smp_id').select2('destroy');
                                    $('#smp_id').val(smp_id).select2();
                                }
                            });
                        });
                        $('#taluka_data').show();
                    }
                    if(selected_role.toLowerCase() != "{{strtolower(config('global.smp_roles.2'))}}" && selected_group == 'SMP')
                    {
                        $('#smp_id').prop('disabled',false);
                        $('#smp_data').show();
                        $('#district_data').show();
                        $('#smp_id').off('change').on('change', function(){

                            smp_id = $("#smp_id option:selected").val();
                            $.ajax({
                                type:'POST',
                                dataType: 'json',
                                url:"{{ route('get_district_smp') }}",
                                data:{"smp_id":smp_id},
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                success:function(data){
                                    $("#district_id").empty();
                                    $("#district_id").attr('multiple',true);
                                    $("#district_id").select2();
                                    $("#district_id").prop('readonly',true);
                                    $("#district_id").attr('name','district_id[]');
                                    $("#taluka_id").empty();
                                    for(var i=0; i<data.length;i++)
                                    {
                                        $("#district_id").append('<option value="'+ (data[i].district_id ?? data[i].id) +'" selected>'+data[i].name+'</option>');
                                    }

                                    $('#smp_id').select2('destroy');
                                    $('#smp_id').val(smp_id).select2();
                                }
                            });
                        })
                        $('#taluka_data').hide();
                    }
                    if(selected_role.toLowerCase() == "{{strtolower(config('global.smp_roles.3'))}}" && selected_group == 'SMP') {
                        $('#district_data').show();
                        $('#smp_data').show();
                        $("#district_id").empty();
                        $("#taluka_id").empty();
                        $('#smp_id').prop('disabled',false);
                        $('#smp_id').off('change').on('change', function(){
                            smp_id = $("#smp_id option:selected").val();
                            $.ajax({
                                type:'POST',
                                dataType: 'json',
                                url:"{{ route('get_district_smp') }}",
                                data:{"smp_id":smp_id},
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                success:function(data){
                                    $("#district_id").empty();
                                    $("#district_id").html('<option value="">-- Select District --</option>');
                                    $("#district_id").attr('multiple',false);
                                    $("#district_id").select2();
                                    $("#district_id").attr('name','district_id');
                                    $("#taluka_id").empty();
                                    for(var i=0; i<data.length;i++)
                                    {
                                        $("#district_id").append('<option value="'+ (data[i].district_id ?? data[i].id) +'">'+data[i].name+'</option>');
                                    }

                                    $('#smp_id').select2('destroy');
                                    $('#smp_id').val(smp_id).select2();
                                }
                            });
                        });
                        $('#taluka_data').hide();
                    }

                    if(selected_role.toLowerCase() == "{{strtolower(config('global.smp_roles.4'))}}" && selected_group == 'SMP')
                    {


                        $('#taluka_data').show();
                        $('#district_data').show();
                        $("#district_id").empty();
                        $("#taluka_id").empty();
                        $('#smp_id').prop('disabled',false);
                        $('#smp_id').off('change').on('change',function(){
                            smp_id = $("#smp_id option:selected").val();
                            $.ajax({
                                type:'POST',
                                dataType: 'json',
                                url:"{{ route('get_district_smp') }}",
                                data:{"smp_id":smp_id},
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                success:function(data){
                                    $("#district_id").empty();
                                    $("#district_id").html('<option value="">-- Select District --</option>');
                                    $("#district_id").attr('multiple',false);
                                    $("#district_id").select2();
                                    $("#district_id").attr('name','district_id');
                                    $("#taluka_id").empty();
                                    for(var i=0; i<data.length;i++)
                                    {
                                        let districtIdTemp = data[i].district_id ?? data[i].id;

                                        $("#district_id").append('<option value="'+ districtIdTemp +'">'+data[i].name+'</option>');
                                    }

                                    $('#smp_id').select2('destroy');
                                    $('#smp_id').val(smp_id).select2();
                                }
                            });
                        });

                    }

                    if(selected_role == '-- Select Role --')
                    {
                        $('#smp_id').val('').trigger('change');
                        $("#smp_id").prop('disabled',true);
                        $('#district_data').hide();
                    }

                });
                // $("#district_id").change(function(){
                //     var district_id = $(this).val();
                //     if(district_id == '')
                //     {
                //         $("#taluka_id").empty();
                //     }
                //     else if (district_id != '')
                //     {
                //         if(selected_role.toLowerCase() == "{{strtolower(config('global.smp_roles.4'))}}" && selected_group == 'SMP'){
                //                 $("#taluka_id").append('<option value="">Select Taluka</option>');
                //                 $("#taluka_id").attr('multiple',false);
                //                 $("#taluka_id").attr('name','taluka_id');
                //             }else {
                //                 $("#taluka_id").attr('multiple',true);
                //                 $("#taluka_id").attr('name','taluka_id[]');
                //             }
                //         $.ajax({
                //             type:'POST',
                //             dataType: 'json',
                //             // url: '/get_talukas',
                //             url:"{{ route('get_taluka_smp') }}",
                //             data:{"district_id":district_id,"smp_id":smp_id},
                //             headers: {
                //                 'X-CSRF-TOKEN': csrfToken
                //             },
                //             success:function(data){
                //                 $("#taluka_id").empty();
                //                 for(var i=0; i<data.length;i++)
                //                 {
                //                     $("#taluka_id").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
                //                 }
                //             }
                //         });
                //     }
                // });

                $("#district_id").change(function(){
                        district_id = $(this).val();
                        if(district_id == '')
                        {
                            $("#taluka_id").empty();
                            $("#taluka_id").append('<option value="">-- Select Taluka --</option>');
                        }
                        else if (district_id != '')
                        {
                            $("#taluka_id").select2('destroy');
                            $("#taluka_id").empty();

                            if(selected_role.toLowerCase() == "{{strtolower(config('global.smp_roles.4'))}}" && selected_group == 'SMP'){
                                $("#taluka_id").append('<option value="">Select Taluka</option>');
                                $("#taluka_id").attr('multiple',false);
                                $("#taluka_id").attr('name','taluka_id');
                            }else {
                                $("#taluka_id").attr('multiple',true);
                                $("#taluka_id").attr('name','taluka_id[]');
                            }

                            $("#taluka_id").select2();
                            district_ajax(district_id, smp_id)
                        }
                });


                function district_ajax(district_id, smp_id){
                    $.ajax({
                        type:'POST',
                        dataType: 'json',
                        // url: '/get_talukas',
                        url:"{{ route('get_taluka_smp') }}",
                        data:{"district_id":district_id,"smp_id":smp_id},
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success:function(data){
                            // $("#taluka_id").empty();
                            for(var i=0; i<data.length;i++)
                            {
                                $("#taluka_id").append('<option value="'+data[i].id+'">'+data[i].name+'</option>');
                            }
                        }
                    });
                }

                $.validator.addMethod("emailFormat", function(value, element) {
                    return this.optional(element) || /^[a-zA-Z0-9._+-]+@[a-zA-Z.-]+\.[a-zA-Z]{2,}$/.test(value);
                }, "Please enter a valid email address in the format: yourname@example.com. Only letters, numbers, dots (.), plus (+), hyphens (-), and underscores (_) before the @ symbol are allowed.");


                $("#user_form").validate({
                    rules: {
                        name: {
                            required: true,
                        },
                        email: {
                            required: true,
                            emailFormat: true,
                        },
                        groups:{
                            required:true,
                        },
                        roles:{
                            required:true,
                        },
                        smp_id:{
                            required:true,
                        },
                    },
                    messages: {
                        name: {
                            required: "Please Enter Name",
                        },
                        groups:{
                            required:"Select Your Group",
                        },
                        roles:{
                            required:"Select Your Role",
                        },
                        smp_id:{
                            required:"Select SMP",
                        },
                        email: {
                            required: "Please Enter Email",
                            email: "Please Enter Valid Email",
                            maxlength: "Email cannot be more than 30 characters",
                        },
                    }
                })

                // $('#submit').click(function() {
                //     $("#user_form").validate(); // This is not working and is not validating the form
                // });

            });
        </script>
    @endpush
