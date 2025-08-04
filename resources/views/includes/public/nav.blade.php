 <!-- Navigation -->
 <div class="bg-primary-darker1">
    <div class="bg-black-10">
        <div class="content py-3">
            <!-- Toggle Main Navigation -->
            <div class="d-lg-none">
                <!-- Class Toggle, functionality initialized in Helpers.oneToggleClass() -->
                <button type="button"
                    class="btn w-100 btn-alt-secondary d-flex justify-content-between align-items-center"
                    data-toggle="class-toggle" data-target="#main-navigation" data-class="d-none">
                    Menu
                    <i class="fa fa-bars"></i>
                </button>
            </div>
            <!-- END Toggle Main Navigation -->

            <!-- Main Navigation -->
            <div id="main-navigation" class="d-none d-lg-block mt-2 mt-lg-0">
                <ul class="nav-main nav-main-dark nav-main-horizontal nav-main-hover">
                    
                    <li class="nav-main-item">
                        <a class="nav-main-link @if( isset($component) && $component == 'livelihood') active @endif" href="{{ route("livelihood-dashboard.livelihood_public_dashboard")  }}">
                            <i class="nav-main-link-icon fa fa-1x fa-wheat-awn"></i>
                            <span class="nav-main-link-name">Livelihood</span>
                        </a>

                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(isset($component) && $component == 'roads') active @endif" href="{{ route("livelihood-dashboard.roads_public_dashboard") }}">
                            <i class="nav-main-link-icon fa fa-1x fa-road"></i>
                            <span class="nav-main-link-name">Roads</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if( isset($component) && $component == 'waters') active @endif" href="{{ route("livelihood-dashboard.water_public_dashboard") }}">
                            <i class="nav-main-link-icon fa fa-1x fa-water"></i>
                            <span class="nav-main-link-name">Water Supply & Drainage</span>
                        </a>
                    </li>
                    <li class="nav-main-item">
                        <a class="nav-main-link @if(isset($component) && $component == 'irrigation') active @endif" href="{{ route("livelihood-dashboard.irrigation_public_dashboard") }}">
                            <i class="nav-main-link-icon fa fa-1x fa-shower"></i>
                            <span class="nav-main-link-name">Irrigation</span>
                        </a>
                    </li>

                </ul>
            </div>
            <!-- END Main Navigation -->
        </div>
    </div>
</div>
<!-- END Navigation -->
