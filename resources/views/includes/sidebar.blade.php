<!-- Side Header -->
<div class="content-header">
    <!-- Logo -->
    <a class="fw-semibold text-dual" href="{{ route('home') }}">
        <span class="smini-visible">
            <img src="{{ asset('assets/media/photos/inner-logo-icon.png') }}" height="55" alt=""
                style="position:absolute; top:3px; left:3px;" /> </span>
        <span class="smini-hide fs-5 tracking-wider"><img src="{{ asset('assets/media/photos/inner-logo.png') }}"
                class="img-fluid  px-3" alt="" /></span>
    </a>
    <!-- END Logo -->

    <!-- Extra -->
    <div>
        <!-- Close Sidebar, Visible only on mobile screens -->
        <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout" data-action="sidebar_close"
            href="javascript:void(0)">
            <i class="fa fa-fw fa-times"></i>
        </a>
        <!-- END Close Sidebar -->
    </div>
    <!-- END Extra -->
</div>
<!-- END Side Header -->

<!-- Sidebar Scrolling -->
<div class="js-sidebar-scroll">
    <!-- Side Navigation -->
    <div class="content-side">
        <ul class="nav-main">
            @foreach ($menus ?? [] as $menu)
                @php
                    $hasOpenSubmenu = false;
                    foreach ($menu->subMenus as $subMenu) {
                        if ('/' . request()->path() === $subMenu->permission_link) {
                            $hasOpenSubmenu = true;
                            break;
                        }

                        if (str_starts_with('/' . request()->path(), $subMenu->permission_link)) {
                            $hasOpenSubmenu = true;
                            break;
                        }
                    }
                @endphp
                @if (Auth::user()->hasPermissionTo($menu))
                    <li class="nav-main-item {{ $hasOpenSubmenu ? 'open' : '' }}">
                        <a class="nav-main-link {{ $menu?->subMenus->count() > 0 ? 'nav-main-link-submenu' : 'active' }}"
                            @if ($menu->subMenus->count() > 0) data-toggle="submenu" aria-haspopup="true" aria-expanded="false"
                        href="{{ url($menu->permission_link) }}"
                       @else
                           href="{{ url($menu->permission_link) }}" @endif>

                            <i class="nav-main-link-icon {{ strstr($menu->icon_name,'si')?'si':'' }} {{ $menu->icon_name }}"></i>{{-- changed By Hubdar --}}
                            <span class="nav-main-link-name">{{ $menu->name }}</span>
                        </a>

                        @if ($menu?->subMenus->count() > 0)
                            <ul class="nav-main-submenu">
                                @foreach ($menu->subMenus->sortBy('sort_by') as $submenu)
                                    @if (Auth::user()->hasPermissionTo($submenu))
                                        <li class="nav-main-item">
                                            <a class="nav-main-link" href="{{ url($submenu->permission_link) }}">
                                                <span class="nav-main-link-name">{{ $submenu->name }}</span>
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
    <!-- END Side Navigation -->
</div>
<!-- END Sidebar Scrolling -->
@push('script')

<script>

    $(document).ready(function(){
        $('#page-container').addClass('sidebar-mini');
    });
</script>
@endpush
