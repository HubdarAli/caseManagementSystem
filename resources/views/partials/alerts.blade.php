@if ($errors->any())
    <div class="alert alert-danger alert-dismissible " role="alert">
        <p class="mb-0">
            @foreach ($errors->all() as $error)
                <span><b>{{ $error }}</b></span><br>
            @endforeach
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


@if (Session::has('alert-success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <p class="mb-0">
            {{ Session::get('alert-success') }}
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


@if (Session::has('alert-danger'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <p class="mb-0">
            {{ Session::get('alert-danger') }}
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


{{-- <div class="alert alert-info alert-dismissible" role="alert">
    @foreach (Session::get('modal_data') as $key => $item)
    <p class="mb-0">
        <strong>{{ Str::of($key)->replace("_", " ")->ucfirst(); }}:</strong>{{ $item }}
    </p>
    @endforeach
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div> --}}


