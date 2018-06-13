jQuery(document).ready(function($) {
	$(".known_anomaly_call").click(function() {

		var but=$(this);
	    var pipeID = $(this).attr('ajax-data-pipe');
	    var streamID = $(this).attr('ajax-data-stream-id');
	    var clientID = $(this).attr('ajax-client-id');
	    var ajaxDate = $(this).attr('ajax-date');

        $.ajax({
		url : postaddknown.ajax_url,
		type : 'post',
		data : {
			action : 'post_add_known',
			pipeID : pipeID,
			streamID : streamID,
			clientID : clientID,
			ajaxDate : ajaxDate
		},
		success : function( response ) {
			if(response.indexOf("error") !== -1){
				alert(response);
			}
			else{
			var inserted_id=response.replace(/\s+/g,"");
			but.parent().slideUp();
			but.closest('td').find('.knan_input_cont').slideDown();

			but.closest('td').find('input[name="known_inserted_id"]').val(inserted_id);
			}
		},
		error: function(errorThrown){
			alert(errorThrown);
  		}
		});
		 return false;

        });

    $(".known_save_true_val").click(function() {

		var inp=$(this);
	    var trueVal = $(this).closest('td').find('input[name="true_val"]').val();
	    var lasInsert = $(this).closest('td').find('input[name="known_inserted_id"]').val();

        $.ajax({
		url : postaddknown.ajax_url,
		type : 'post',
		data : {
			action : 'post_know_true_val',
			trueVal: trueVal,
			lasInsert : lasInsert,
		},
		success : function( response ) {
			inp.parent().fadeOut('fast').html(response).fadeIn('slow');
			//alert(but.closest('td').find('.knan_input_cont').attr('test'));
		},
		error: function(errorThrown){
			alert(errorThrown);
  		}
		});
		 return false;

        });



	});