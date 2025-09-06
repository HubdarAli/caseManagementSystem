<div class="content-header">
    <div class="d-flex align-items-center">
        <ul class="nav nav-pills">
            @foreach ($menus ?? [] as $menu)
                @php
                    $hasOpenSubmenu = false;
                    foreach ($menu->subMenus as $subMenu) {
                       if ('/' . request()->path() === $subMenu->permission_link) {
                            $hasOpenSubmenu = true;
                            break;
                        }
                    }
                @endphp
                @if (Auth::user()->hasPermissionTo($menu))
                    <li class="nav-item dropdown">
                        <a class="nav-link {{ $menu?->subMenus->count() > 0 ? 'dropdown-toggle' : '' }} {{ $hasOpenSubmenu || str_starts_with('/' . request()->path(), $menu->permission_link) ? 'active' : '' }}"
                           @if ($menu->subMenus->count() > 0)
                               data-bs-toggle="dropdown" aria-expanded="false"
                           @endif
                           href="{{ url($menu->permission_link) }}">
                            <i class="{{ strstr($menu->icon_name,'si')?'si':'' }} {{ $menu->icon_name }}"></i>
                            <span>{{ $menu->name }}</span>
                        </a>
                        @if ($menu?->subMenus->count() > 0)
                            <ul class="dropdown-menu">
                                @foreach ($menu->subMenus->sortBy('sort_by') as $submenu)
                                    @if (Auth::user()->hasPermissionTo($submenu))
                                        <li>
                                            <a class="dropdown-item" href="{{ url($submenu->permission_link) }}">
                                                {{ $submenu->name }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </div>

    <!-- END Logo -->

    <div class="d-flex align-items-center">
        <!-- User Dropdown -->
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