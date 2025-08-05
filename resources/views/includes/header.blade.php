<!-- Header Content -->
<div class="content-header">
    <!-- Left Section -->
    <div class="d-flex align-items-center">
        <!-- Toggle Sidebar -->
        <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
        <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" data-toggle="layout"
            data-action="sidebar_toggle">
            <i class="fa fa-fw fa-bars"></i>
        </button>
        <!-- END Toggle Sidebar -->

        <!-- Toggle Mini Sidebar -->
        <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
        @if (!request()->has('no_sidebar'))
            <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-none d-lg-inline-block" data-toggle="layout"
                data-action="sidebar_mini_toggle">
                <i class="fa fa-fw fa-ellipsis-v"></i>
            </button>
        @endif
        <!-- END Toggle Mini Sidebar -->



        <!-- Open Search Section (visible on smaller screens) -->
        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
        <button type="button" class="btn btn-sm btn-alt-secondary d-md-none" data-toggle="layout"
            data-action="header_search_on">
            <i class="fa fa-fw fa-search"></i>
        </button>
        <!-- END Open Search Section -->


        <!-- Search Form (visible on larger screens) -->
        <form class="d-none d-md-inline-block" action="be_pages_generic_search.html" method="POST">
            <div class="input-group input-group-sm">
                {{-- <input type="text" class="form-control form-control-alt" placeholder="Search.." id="page-header-search-input2" name="page-header-search-input2">
        <span class="input-group-text border-0">
          <i class="fa fa-fw fa-search"></i> --}}
                </span>
            </div>
        </form>
        <!-- END Search Form -->

        <!-- User Name and Title -->
        <div>
            {{-- <a class="fw-semibold" href="be_pages_generic_profile.html">{{ Auth::user()->name }}</a> --}}
            <span
                class="fw-semibold fs-xs d-inline-block py-1 px-3 rounded-pill bg-info-light text-info">{{ isset(Auth::user()->name) ? Auth::user()->name : '' }}
            </span>
            @if (isset(Auth::user()->smp->name) && !empty(Auth::user()->smp->name))
                <span
                    class="fw-semibold fs-xs d-inline-block py-1 px-3 rounded-pill bg-success-light text-success">{{ Auth::user()->smp->name }}
                </span>
            @endif
        </div>
        <!--End User Name and Title -->

    </div>
    <!-- END Left Section -->



    <!-- Right Section -->

    <div class="d-flex align-items-center">
        <!-- User Dropdown -->
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary">
            <i class="fa fa-repeat"></i>
        </a>
        <div class="dropdown d-inline-block ms-2">
            <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center"
                id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{-- <img class="rounded-circle"
                    src="{{ !empty(Auth::user()?->media?->file_path) && file_exists(public_path(Auth::user()->media->file_path)) ? asset(Auth::user()->media->file_path) : asset('assets/media/avatars/avatar10.jpg') }}"
                    alt="Header Avatar" style="width: 21px; height: 21px;"> --}}

                <span class="d-none d-sm-inline-block ms-2">{{ Auth::user()?->name }}</span>
                <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block opacity-50 ms-1 mt-1"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0"
                aria-labelledby="page-header-user-dropdown">
                <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                    {{-- <img class="img-avatar img-avatar48 img-avatar-thumb"
                        src="{{ !empty(Auth::user()?->media?->file_path) && file_exists(public_path(Auth::user()->media->file_path)) ? asset(Auth::user()->media->file_path) : asset('assets/media/avatars/avatar10.jpg') }}"
                        alt=""> --}}

                    <p class="mt-2 mb-0 fw-medium">{{ Auth::user()?->name }}</p>

                </div>

                <div class="p-2">

                    {{-- <a class="dropdown-item d-flex align-items-center justify-content-between"
                        href="{{ route('profile.index') }}">
                        <span class="fs-sm fw-medium">Profile</span>

                    </a> --}}
                    <a class="dropdown-item d-flex align-items-center justify-content-between"
                        href="{{ route('profile.create') }}">
                        <span class="fs-sm fw-medium">Profile</span>

                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between"
                        href="{{ route('Profile.ChangePassword') }}">
                        <span class="fs-sm fw-medium">Change Password</span>

                    </a>

                </div>
                <div role="separator" class="dropdown-divider m-0"></div>
                <div class="p-2">

                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <span class="fs-sm fw-medium">{{ __('Logout') }}</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        <!-- END User Dropdown -->
        <!-- Notifications Dropdown -->

        {{-- @php
            $notifications = Auth::user()
                ->notifications()
                ->wherePivot('is_viewed', false)
                ->where(function ($query) {
                    $query->where('sender_id', '!=', Auth::user()->id)->orWhereNull('sender_id');
                })
                ->latest()
                ->take(5)
                ->get();

            $notificationsCount = Auth::user()
                ->notifications()
                ->wherePivot('is_viewed', false)
                ->where(function ($query) {
                    $query->where('sender_id', '!=', Auth::user()->id)->orWhereNull('sender_id');
                })
                ->count();

            $notifications->map(function ($notification) {
                if (strlen($notification->message) > 60) {
                    $truncated = substr($notification->message, 0, 60);
                    $lastSpace = strrpos($truncated, ' ');
                    $notification->message = substr($truncated, 0, $lastSpace) . '...';
                }
            });
        @endphp

        <div class="dropdown d-inline-block ms-2">
            <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-notifications-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-fw fa-bell"></i>
                @if (!empty($notificationsCount))
                    <span class="text-white notf_circle">
                        {{ $notificationsCount > 99 ? '99+' : $notificationsCount }}
                    </span>
                @endif
            </button>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0 fs-sm"
                aria-labelledby="page-header-notifications-dropdown">
                <div class="p-2 bg-body-light border-bottom text-center rounded-top">
                    <h5 class="dropdown-header text-uppercase">Notifications</h5>
                </div>
                <ul class="nav-items mb-0">
                    @forelse($notifications as $notification)
                        <li>
                            <a class="text-dark d-flex py-2 notificationUrl" data-id="{{ $notification->id }}"
                                data-url="{{ $notification->redirect_url }}">
                                <div class="flex-shrink-0 me-2 ms-3">
                                    <i class="fa fa-fw text-success"></i>
                                </div>
                                <div class="flex-grow-1 pe-2">
                                    <div class="fw-semibold">{!! $notification->message !!}</div>
                                    <span
                                        class="fw-medium text-muted">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            </a>
                        </li>

                    @empty
                        <li>
                            <div class="text-center py-2">No notifications</div>
                        </li>
                    @endforelse
                </ul>
                <div class="p-2 border-top text-center">
                    <a class="d-inline-block fw-medium" href="{{ route('notifications.index') }}">
                        Load More..
                    </a>
                </div>
            </div>
        </div> --}}
        <!-- END Notifications Dropdown -->


    </div>
    <!-- END Right Section -->
</div>
<!-- END Header Content -->

<!-- Header Search -->
<div id="page-header-search" class="overlay-header bg-body-extra-light">
    <div class="content-header">
        <form class="w-100" action="be_pages_generic_search.html" method="POST">
            <div class="input-group">
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-alt-danger" data-toggle="layout" data-action="header_search_off">
                    <i class="fa fa-fw fa-times-circle"></i>
                </button>
                <input type="text" class="form-control" placeholder="Search or hit ESC.."
                    id="page-header-search-input" name="page-header-search-input">
            </div>
        </form>
    </div>
</div>
<!-- END Header Search -->

<!-- Header Loader -->
<!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
<div id="page-header-loader" class="overlay-header bg-body-extra-light">
    <div class="content-header">
        <div class="w-100 text-center">
            <i class="fa fa-fw fa-circle-notch fa-spin"></i>
        </div>
    </div>
</div>

<!-- END Header Loader -->

<!-- job progress bar start -->
<div class="progress-container-job" role="alert">
    <div class="progress push progress-bar-striped progress-bar-animated" id="job-progress" role="progressbar"
        aria-valuenow="1" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 1%;">
            <span class="fs-sm fw-semibold">1%</span>
        </div>
    </div>
    {{-- <p class="pt-2 px-5 text-secondary"> Processing please wait ... </p> --}}
</div>
<!-- job progress bar end -->



<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<script>
    $(document).ready(function() {
        $('input, textarea').on('input', function() {
            if($(this).attr('type') == 'email' || $(this).attr('type') == 'password' || $(this).attr('name') == 'password' || $(this).attr('name') == 'confirm_password'){
                return false;
            }

            let value = $(this).val();
            if (value.length > 0) {
                $(this).val(value.charAt(0).toUpperCase() + value.slice(1));
            }
        });
    });
</script>
