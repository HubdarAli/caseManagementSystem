$(document).ready(function() {
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $('#scheme_id').change(function() {
        let params = {
            scheme_id: $(this).val(),
        };

        $.ajax({
            url: site_images,
            type: "post",
            dataType: "json",
            data: params,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                // Check the response structure and update accordingly
                $('#draft_project').find('.modal-title').text('Site Images Gallery');
                // $('#modal_gellary').find('.modal_body_dd').html(response.table_view_dd);
                $('#draft_project').find('.modal_body_dd').html(response.table_view);
                $('#draft_project').modal('show');
            },
            error: function(error) {
                // Do Something to handle error
                // console.log(response);
            }
        });
    });
});
