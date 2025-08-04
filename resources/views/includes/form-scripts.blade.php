<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/jquery-validation/additional-methods.js') }}"></script>
<script src="{{ asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/jquery.replicate.js') }}"></script>
<script>
    $('.select2').select2();

    $(".select2Tags").each(function(index, element) {
        $(this).select2({
            tags: true,
            // width: "100%" // just for stack-snippet to show properly
        });
    });

    $(".datepicker").datepicker({
        'format': 'dd-mm-yyyy',
    });
   
</script>
