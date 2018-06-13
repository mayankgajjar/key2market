jQuery(document).ready(function($) {


      $('#block_3 select#dates_column, #block_3 select#values_column').on('change', function(){
	      var dates_selected=$('#block_3 select#dates_column').val();
	      var vals_selected=$('#block_3 select#values_column').val();
	     var checkboxes=$('#result_table input[type="checkbox"][name="col_include[]"]');
	     //checkbox;
	     $.each(checkboxes, function(){
		     if($(this).val()==dates_selected || $(this).val()==vals_selected){
			     $(this).prop('checked', true).attr('disabled','disabled');
		     }
		     else{
			     $(this).removeAttr('disabled');
		     }
	     });

      });


});