var BulkEdit = function() {
	return {
		init: function(){
			//console.log('bulk-edit');
			var _bulk_edit_btn = jQuery('.bulk-edit-btn');

			function initBulkEdit()
			{
				var _bulk_edit_val = jQuery("input[name='bulk-edit[]']:checkbox:checked")
              .map(function(){return jQuery(this).val();}).get();

				var ajax_data = {
					'action': 'bulk_edit_init',
					'yearbook_id' : _yearbook_id,
					'edit_block_id' : _bulk_edit_val
				};
				//console.log(ajax_data);
				var xmlRequest = jQuery.ajax({
					url: ajaxurl,
					method: "POST",
					data: ajax_data
				});

				return xmlRequest;
			}

			function initBulkUpdate()
			{
				var _bulk_edit_val = jQuery("input[name='bulk-edit-ids[]']")
              .map(function(){return jQuery(this).val();}).get();
				var _due_date = jQuery('.due_date').val();
				var _status = jQuery('.block_status').val();
				var _assign_to = jQuery('.assign_to').val();
				var _is_cover = jQuery('.block-is_cover');

				var _is_cover_val = 0;
				if ( _is_cover.is(':checked') ) {
					_is_cover_val = 1;
				}

				var ajax_data = {
					'action': 'bulk_edit_udpate',
					'edit_block_ids' : _bulk_edit_val,
					'due_date' : _due_date,
					'status' : _status,
					'assign_to' : _assign_to,
					'is_cover' : _is_cover_val,
				};

				var xmlRequest = jQuery.ajax({
					url: ajaxurl,
					method: "POST",
					data: ajax_data
				});

				return xmlRequest;
			}

			jQuery('body').on('click', '.bulk-edit-btn', function(e){
				e.preventDefault();
				//console.log('bulk edit click');
				//get_data(_yearbook_id);
				initBulkEdit().done(function(data){
					jQuery('.bulk-edit-container').html(data);
					jQuery('.due_date').datepicker({
						dateFormat: 'yy-mm-dd'
					});
				});
			});

			//update bulk button
			jQuery('body').on('click', '.update-bulk-edit', function(e){
				e.preventDefault();
				initBulkUpdate().done(function(data){
					console.log(data);
					get_data(_yearbook_id);
				});
			});

			//cancel or close the bulk edit UI
			jQuery('body').on('click', '.cancel-bulk-edit', function(e){
				e.preventDefault();
				console.log('close');
				jQuery('.bulk-edit-container').html('');
			});

			jQuery("#checkAll").change(function () {
			    jQuery(".bulk-edit-blocks").prop('checked', jQuery(this).prop("checked"));
			});
			console.log(_yearbook_id);
		}//init: function()
	};
}();

jQuery(function () {
	BulkEdit.init();
});
