$(document).ready(function() {
	var sb = $('#show-albums-button');
			sb.click(function(){

				let params = {
                    v_id: $(this).attr('v_id'),
                }

				$.ajax({
                url: site_schemes,
                type: "post",
                dataType: "json",
                data: params,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {

                	$('#modal_gellary').find('.modal-title').text('Site Images');
                	$('#modal_gellary').find('.modal_body_dd').html(response.table_view_dd);
                	//$('#modal').find('.modal-body').html(response.table_view);
                	$('#modal_gellary').find('.modal_body').html("");
                	$('#modal_gellary').modal('show');
                    
                },
                error: function(response) {
                    //Do Something to handle error
                    console.log(response);
                }
            });
        });





			$(document.body).on('change', "#scheme_id", function(){

				if($(this).val()){

						let params = {
	                    	v_id: $(this).val(),
		                }

						$.ajax({
		                url: site_images,
		                type: "post",
		                dataType: "json",
		                data: params,
		                headers: {
		                    'X-CSRF-TOKEN': csrfToken
		                },
		                success: function(response) {

		                	//$('#modal_gellary').find('.modal-title').text('Site Images');
		                	//$('#modal_gellary').find('.modal_body_dd').html(response.table_view_dd);
		                	$('#modal_gellary').find('.modal_body').html(response.table_view);
		                	$('#modal_gellary').modal('show');
		                    
		                },
		                error: function(response) {
		                    //Do Something to handle error
		                    console.log(response);
		                }
		            });

				}else{
					$('#modal_gellary').find('.modal_body').html("");
				}

				
        });
	});