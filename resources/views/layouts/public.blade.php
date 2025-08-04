<!doctype html>
<html lang="en">
  <head>
    @include('includes.head')
    {{-- {{ added irfan }} --}}
    @stack('style')
    {{-- {{ added irfan }} --}}
  </head>

  <body>

    <div id="page-container" class="page-header-dark main-content-boxed">

      <!-- Header -->
      <header id="page-header">
        @include('includes.public.header')
      </header>
      <!-- END Header -->

      <!-- Main Container -->
      <main id="main-container">
        @yield('content')
      </main>
      <!-- END Main Container -->

      <!-- Footer -->
      <footer id="page-footer" class="bg-body-light">
        @include('includes.public.footer')
      </footer>
      <!-- END Footer -->
    </div>
    <!-- END Page Container -->

    @include('includes.foot')
    {{-- {{ added irfan }} --}}
    @stack('script')
    {{-- {{ added irfan }} --}}
  </body>
</html>
