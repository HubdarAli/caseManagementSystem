<<<<<<< .mine
@extends('layouts.default')
@section('content')
@include('partials.alerts')

<div class="content">
  <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
    <div class="flex-grow-1 mb-1 mb-md-0">
      <h1 class="h3 fw-bold mb-1">
        Add District
      </h1>
    </div>
    <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
      <a href="{{ route('district.index') }}" class="btn btn-sm btn-dark me-1 mb-3"><i class="fa fa-fw fa-angle-left
                    me-1"></i>Back</a>
    </div>
  </div>
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Add district</h3>
    </div>
    <div class="block-content block-content-full">
      <form class=" g-3 align-items-center" id="add-form" action="{{ route('district.store') }}" method="POST">
        @csrf
        <div class="row p-1">
          <div class="col-6 mt-3">
            <label class="form-label" for="example-if-name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
          </div>
          <div class="col-6 mt-3">
            <label class="form-label">Short Code</label>
            <input type="text" class="form-control" id="" name="short_code" placeholder="Enter Short Code">
          </div>

          <div class="col-6 mt-3">
            <label class="form-label">Code</label>
            <input type="number" class="form-control" id="" name="code" placeholder="Enter Code">
          </div>
        </div>
        <div class="row mt-2 text-end">
          <div class="col-12">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('footer_scripts')
<script src="{{ asset('assets/js/lib/jquery-form-validation.min.js') }}"></script>

<script>
    $(document).ready(function() {
      $.validator.addMethod("alphaOnly", function(value, element) {
            return /^[a-zA-Z\s]+$/.test(value);
        }, "Please enter alphabetic characters only.");

        $('#add-form').validate({
            rules: {
                name: {
                    required: true,
                    alphaOnly: true,
                    minlength: 3,
                    maxlength: 24
                },
                short_code: {
                    required: true,
                    alphaOnly: true,
                },
                code: {
                    required: true,
                    digits: true,
                    minlength: 3,
                    maxlength: 24
                },
            },
            messages: {
                name: {
                    required: 'Please enter a district name.',
                },
                short_code: {
                    required: 'Please enter a short code.',
                },
                code: {
                    required: 'Please enter a code.',
                    digits: 'Please enter a valid numeric code.',
                },
            },

            errorElement: "span",

            errorPlacement: function(error, element) {
                error.addClass("text-danger");
                error.insertAfter(element);
            },


        });
    });
</script>
@endsection
||||||| .r45
@extends('layouts.default')
@section('content')
@include('partials.alerts')

<div class="content">
  <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
    <div class="flex-grow-1 mb-1 mb-md-0">
      <h1 class="h3 fw-bold mb-1">
        Add District
      </h1>
    </div>
    <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
      <a href="{{ route('district.index') }}" class="btn btn-sm btn-dark me-1 mb-3"><i class="fa fa-fw fa-angle-left
                    me-1"></i>Back</a>
    </div>
  </div>
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Add district</h3>
    </div>
    <div class="block-content block-content-full">
      <form class=" g-3 align-items-center" id="add-form" action="{{ route('district.store') }}" method="POST">
        @csrf
        <div class="row p-1">
          <div class="col-6 mt-3">
            <label class="form-label" for="example-if-name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
          </div>
          <div class="col-6 mt-3">
            <label class="form-label">Short Code</label>
            <input type="text" class="form-control" id="" name="short_code" placeholder="Enter Short Code">
          </div>

          <div class="col-6 mt-3">
            <label class="form-label">Code</label>
            <input type="number" class="form-control" id="" name="code" placeholder="Enter Code">
          </div>
        </div>
        <div class="row mt-2 text-end">
          <div class="col-12">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('footer_scripts')
<script src="{{ asset('assets/js/lib/jquery-form-validation.min.js') }}"></script>

<script>
    $(document).ready(function() {
      $.validator.addMethod("alphaOnly", function(value, element) {
            return /^[a-zA-Z\s]+$/.test(value);
        }, "Please enter alphabetic characters only.");

        $('#add-form').validate({
            rules: {
                name: {
                    required: true,
                    alphaOnly: true,
                    minlength: 3,
                    maxlength: 24
                },
                short_code: {
                    required: true,
                    alphaOnly: true,
                },
                code: {
                    required: true,
                    digits: true,
                    minlength: 3,
                    maxlength: 24
                },
            },
            messages: {
                name: {
                    required: 'Please enter a district name.',
                },
                short_code: {
                    required: 'Please enter a short code.',
                },
                code: {
                    required: 'Please enter a code.',
                    digits: 'Please enter a valid numeric code.',
                },
            },

            errorElement: "span",

            errorPlacement: function(error, element) {
                error.addClass("text-danger");
                error.insertAfter(element);
            },


        });
    });
</script>
@endsection=======
@extends('layouts.default')
@section('content')
@include('partials.alerts')

<div class="content">
  <div class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
    <div class="flex-grow-1 mb-1 mb-md-0">
      <h1 class="h3 fw-bold mb-1">
        Add District
      </h1>
    </div>
    <div class="mt-3 mt-md-0 ms-md-3 space-x-1">
      <a href="{{ route('district.index') }}" class="btn btn-sm btn-dark me-1 mb-3"><i class="fa fa-fw fa-angle-left
                    me-1"></i>Back</a>
    </div>
  </div>


  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Add district</h3>
    </div>
    <div class="block-content block-content-full">
      <form class=" g-3 align-items-center" id="add-form" action="{{ route('district.store') }}" method="POST">
        @csrf
        <div class="row p-1">
          <div class="col-6 mt-3">
            <label class="form-label" for="example-if-name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name">
          </div>
          <div class="col-6 mt-3">
            <label class="form-label">Short Code</label>
            <input type="text" class="form-control" id="" name="short_code" placeholder="Enter Short Code">
          </div>

          <div class="col-6 mt-3">
            <label class="form-label">Code</label>
            <input type="number" class="form-control" id="" name="code" placeholder="Enter Code">
          </div>
        </div>
        <div class="row mt-2 text-end">
          <div class="col-12">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('footer_scripts')
<script src="{{ asset('assets/js/lib/jquery-form-validation.min.js') }}"></script>

<script>
    $(document).ready(function() {
      $.validator.addMethod("alphaOnly", function(value, element) {
            return /^[a-zA-Z\s]+$/.test(value);
        }, "Please enter alphabetic characters only.");

        $('#add-form').validate({
            rules: {
                name: {
                    required: true,
                    alphaOnly: true,
                    minlength: 3,
                    maxlength: 24
                },
                short_code: {
                    required: true,
                    alphaOnly: true,
                },
                code: {
                    required: true,
                    digits: true,
                    minlength: 3,
                    maxlength: 24
                },
            },
            messages: {
                name: {
                    required: 'Please enter a district name.',
                },
                short_code: {
                    required: 'Please enter a short code.',
                },
                code: {
                    required: 'Please enter a code.',
                    digits: 'Please enter a valid numeric code.',
                },
            },

            errorElement: "span",

            errorPlacement: function(error, element) {
                error.addClass("text-danger");
                error.insertAfter(element);
            },


        });
    });
</script>
@endsection>>>>>>> .r57
