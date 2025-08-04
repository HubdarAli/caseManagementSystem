 <!-- Header -->
 <header id="page-header">
     <!-- Header Content -->
     <div class="content-header">
         <!-- Left Section -->
         <div class="d-flex align-items-center">
             <!-- Logo -->
             <a class="fw-semibold fs-5 tracking-wider text-dual me-3" href="{{ url('/') }}">
                 <img src="/assets/media/photos/inner-logo.png" class="img-fluid" width="180" alt="" /> </a>
             <!-- END Logo -->


         </div>
         <!-- END Left Section -->

         <!-- Right Section -->
         <div class="d-flex align-items-center">
             <!-- Open Search Section (visible on smaller screens) -->
             <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
             {{-- <button type="button" class="btn btn-sm btn-alt-secondary d-md-none" data-toggle="layout"
                 data-action="header_search_on">
                 <i class="fa fa-fw fa-search"></i>
             </button> --}}
             <!-- END Open Search Section -->

             <!-- Search Form (visible on larger screens) -->
             {{-- <form class="d-none d-md-inline-block" action="bd_search.html" method="POST">
                 <div class="input-group input-group-sm">
                     <input type="text" class="form-control form-control-alt" placeholder="Search.."
                         id="page-header-search-input2" name="page-header-search-input2" />
                     <span class="input-group-text bg-body border-0">
                         <i class="fa fa-fw fa-search"></i>
                     </span>
                 </div>
             </form> --}}
             <!-- END Search Form -->

             <!-- User Dropdown -->
             <div class="dropdown d-inline-block ms-2 @if (Str::contains(request()->url(), '/complaints')) d-none @endif">
                 <a type="button" href="{{ url('/dashboard') }}"
                     class="btn btn-sm btn-alt-secondary  d-flex align-items-center">
                     <span class="d-none d-sm-inline-block">Login</span>
                 </a>
                 {{-- <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center"
                     id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                     aria-expanded="false">
                     <img class="rounded-circle" src="assets/media/avatars/avatar10.jpg" alt="Header Avatar"
                         style="width: 21px;" />
                     <span class="d-none d-sm-inline-block ms-2">Masood Arsalan</span>
                     <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block opacity-50 ms-1"></i>
                 </button> --}}
                 {{-- <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0"
                     aria-labelledby="page-header-user-dropdown">
                     <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                         <img class="img-avatar img-avatar48 img-avatar-thumb" src="assets/media/avatars/avatar10.jpg"
                             alt="">
                         <p class="mt-2 mb-0 fw-medium">Masood Arsalan</p>
                         <p class="mb-0 text-muted fs-sm fw-medium">UI Designer</p>
                     </div>

                     <div role="separator" class="dropdown-divider m-0"></div>
                     <div class="p-2">

                         <a class="dropdown-item d-flex align-items-center justify-content-between"
                             href="op_auth_signin.html">
                             <span class="fs-sm fw-medium">Log Out</span>
                         </a>
                     </div>
                 </div> --}}
             </div>
             <!-- END User Dropdown -->
         </div>
         <!-- END Right Section -->
     </div>
     <!-- END Header Content -->

     <!-- Header Search -->
     <div id="page-header-search" class="overlay-header bg-body-extra-light">
         <div class="content-header">
             <form class="w-100" action="bd_search.html" method="POST">
                 <div class="input-group">
                     <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                     <button type="button" class="btn btn-alt-danger" data-toggle="layout"
                         data-action="header_search_off">
                         <i class="fa fa-fw fa-times-circle"></i>
                     </button>
                     <input type="text" class="form-control" placeholder="Search or hit ESC.."
                         id="page-header-search-input" name="page-header-search-input" />
                 </div>
             </form>
         </div>
     </div>
     <!-- END Header Search -->

     <!-- Header Loader -->
     <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
     <div id="page-header-loader" class="overlay-header bg-primary-lighter">
         <div class="content-header">
             <div class="w-100 text-center">
                 <i class="fa fa-fw fa-circle-notch fa-spin text-primary"></i>
             </div>
         </div>
     </div>
     <!-- END Header Loader -->
 </header>
 <!-- END Header -->



