<header id="page-header">
    <div class="content-header">
        <div class="d-flex align-items-center">
            <a class="fw-semibold fs-5 tracking-wider text-dual me-3" href="{{ route('home') }}">
                 <img src="/assets/media/photos/inner-logo.png" class="img-fluid" width="180" alt="" />
            </a>
        </div>
        <h1 class="text-white mb-1">Sindh Flood Emergency Rehabilitation Project</h1>
        <div class="d-flex align-items-center">
            <div class="dropdown d-inline-block ms-2 @if (Str::contains(request()->url(), '/complaints')) d-none @endif"></div>
        </div>


        <div class="d-flex justify-content-end">
            <a class="fw-semibold fs-5 tracking-wider text-dual btn btn-md" href="{{ url('home') }}" style="background-color: #282525;">
                Back
            </a>
        </div>
    </div>


    <div id="page-header-search" class="overlay-header bg-body-extra-light">

        <div class="content-header">
            <form class="w-100" action="bd_search.html" method="POST">
                <div class="input-group">
                    <button type="button" class="btn btn-alt-danger" data-toggle="layout"
                        data-action="header_search_off">
                        <i class="fa fa-fw fa-times-circle"></i>
                    </button>
                    <input type="text" class="form-control" placeholder="Search or hit ESC.."id="page-header-search-input" name="page-header-search-input" />
                 </div>
            </form>
        </div>
    </div>

    <div id="page-header-loader" class="overlay-header bg-primary-lighter">
        <div class="content-header">
            <div class="w-100 text-center">
                <i class="fa fa-fw fa-circle-notch fa-spin text-primary"></i>
            </div>
        </div>
    </div>

</header>

