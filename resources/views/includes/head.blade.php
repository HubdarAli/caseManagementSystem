<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>
    @section('title')
        :: Case Management System ::
    @show
</title>

<script async src="https://www.googletagmanager.com/gtag/js?id=G-2TWKMWHJ4J"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-2TWKMWHJ4J');
</script>
<!-- Icons -->
<!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
<link rel="shortcut icon" href="{{-- asset('assets/media/favicons/favicon.ico') --}}">
<!-- END Icons -->

<!-- Stylesheets -->
<link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.min.css') }}">
<link rel="stylesheet" id="css" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" id="css" href="{{ asset('assets/css/style.css') }}">
<link href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">

<style>
    .select2-selection.select2-selection--single {
        --bs-form-select-bg-img: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e);
        display: block !important;
        width: 100% !important;
        padding: 0.375rem 2.25rem 1.90rem 0.50rem !important;
        font-size: 1rem !important;
        font-weight: 400 !important;
        line-height: 1.5 !important;
        color: #212529 !important;
        background-color: #fff !important;
        background-image: var(--bs-form-select-bg-img), var(--bs-form-select-bg-icon, none) !important;
        background-repeat: no-repeat !important;
        background-position: right 0.75rem center !important;
        background-size: 16px 12px !important;
        border: var(--bs-border-width) solid #dfe3ea !important;
        border-radius: var(--bs-border-radius) !important;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
    }

    .select2-selection__arrow {
        top: 7px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #006838 !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #5897fb;
        color: white;
    }

    .select2-container--default .select2-search--inline .select2-search__field {
        width: 24.5em !important;
    }

    .select2.select2-container.select2-container--default{
        width:100%!important;
    }
    
    .dataTable {
        min-height: 150px;
    }

    .locked {
        position: relative;
        display: inline-block;
    }

    .locked::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("{{ asset('assets/media/photos/lock.jpg') }}");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        opacity: 0.8;
        z-index: 1;
    }

    div.dataTables_wrapper div.dataTables_processing {

        z-index: 9;
    }

    .dataTables_processing.card {
        color: white;
        background: #878a8f;
    }

    @page {
        margin: 0;
    }

    @media print {
        @page {
            size: auto;
            margin: 0;
        }

        body {
            margin: 0;
        }
    }

    .progress-container-job {
        display: none;
        position: absolute;
        top: 100%;
        right: 0%;
        width: 20%;
    }
</style>
<!-- BEGIN HEADER CSS -->
@yield('header_styles')
<!-- END HEADER CSS -->
