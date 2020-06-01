var last_block_assign_to = [];
var _data_assign_to = [];

function update_block_ajax_msg(msg = '', show = false){
	var _div = jQuery('.update-block-ajax-msg');

	_div.hide();
	_div.html('');
	if(show){
		_div.show();
		_div.html(msg);
	}
}

function sort_block() {
	var _children = jQuery(".sortable");
	_children.sortable({
		items: "tr:not(.ui-is-covered)",
		placeholder: "ui-state-highlight",
		update: function( event, ui ) {
			//console.log('update start');
			toggle_loader(true);
			/*submit_form_data().done(function(res){
				var _parent_id = jQuery('#yearbook_id').val();
				get_data(_parent_id);
			});*/
			var form = jQuery('#yearbook-plan-form').serialize();
			var _parent_id = jQuery('#yearbook_id').val();

			var data = {
				'action': 'yb_sort_blocks',
				'form': form
			};

			var xmlRequest = jQuery.ajax({
				url: ajaxurl,
				method: "POST",
				data: data
			});

			xmlRequest.done(function(res){
				//console.log(res);
				//console.log('update stop');
				get_data(_parent_id);
			});

		}
	});
	_children.disableSelection();
}

function toggle_add_block(_show = true) {
	var add_block = jQuery('.add-block');
	if(_show) {
		add_block.show();
	}else{
		add_block.hide();
	}
}

function toggle_move_button(_show = true) {
	var _move_btn = jQuery('.dashicons-move');
	if(_show) {
		_move_btn.show();
	}else{
		_move_btn.hide();
	}
}

function toggle_btn(_show = true) {
	var _btn = jQuery(document).find('.btn');
	if(_show) {
		_btn.show();
	}else{
		_btn.hide();
	}
}

function toggle_update_button(_show = true) {
	var _btn = jQuery('#publish');
	if(_show) {
		_btn.show();
	}else{
		_btn.hide();
	}
}

function toggle_add_section(_show = true) {
	var add_section = jQuery('.add-page');
	if(_show) {
		add_section.show();
	}else{
		add_section.hide();
	}
}

function get_contributors(school_admin_id) {
	var ajaxGetUsers = jQuery.ajax({
		url: rest_object.yearbook_api_url + 'get-school-admin-contributor/' + school_admin_id,
		method: "GET",
		async: false
	});

	ajaxGetUsers.done(function(data){
		_data_assign_to = data;
	});
}

function init_contributors() {
	var sel_school_admin_id = jQuery('#school_admin');
	school_admin_id = sel_school_admin_id.val();
	get_contributors(school_admin_id);
	jQuery(document).on('change', '#school_admin', function(){
		school_admin_id = jQuery(this).val();
		jQuery('#app-lists')
			.find('.assign_to')
			.empty();
		get_contributors(school_admin_id);
		jQuery.each(_data_assign_to, function (i, item) {
				jQuery('#app-lists')
					.find('.assign_to')
					.append(new Option(item.name, item.id));
		});
		jQuery('#app-lists').find('.selectpicker').selectpicker();
	});
}

function submit_form_data() {

	var form = jQuery('#yearbook-plan-form').serialize();

	var data = {
		'action': 'yb_create_yearbook',
		'form': form      // We pass php values differently!
	};

	var xmlRequest = jQuery.ajax({
		url: ajaxurl,
		method: "POST",
		data: data
	});
	//console.log(data);
	return xmlRequest;
}

function get_data(_id) {

	toggle_loader(true);
	toggle_btn(false);

	var ajax_data = {
		'action': 'yb_get_yearbook',
		'yearbook_id' : _id
	};

	var xmlRequest = jQuery.ajax({
		url: ajaxurl,
		method: "POST",
		data: ajax_data
	});

	xmlRequest.done(function(res){
		//jQuery('.content-placeholder').html('');

		var data = {
				tasks: res,
				assign_to: _data_assign_to
		};
		console.log(data);
		/*console.log(data.tasks.blocks);
		console.log(data.tasks.blocks.page);
		*/
		jQuery('#yearbook-page-name').val(data.tasks.parent.title);
		jQuery('#yearbook_id').val(data.tasks.parent.id);

		var theCompiledHtml = theTemplate(data);
		// Add the compiled html to the page
		jQuery('.content-placeholder').html(theCompiledHtml);

		btn_publish(true);
		sort_block();
		//sort_sections();
		toggle_loader();
		toggle_btn();
		//toggle_move_button(true);
		toggle_update_button(true);

		//jQuery('.update-section-ajax-msg').hide();
		jQuery('.update-block-ajax-msg').hide();

		initDatePicker('.content-placeholder');
		jQuery('.content-placeholder').find('.selectpicker').selectpicker();
		//autocomplete_section();
		jQuery('.block-action').find('.add-block-ajax-msg').remove();
	});
}

function initDatePicker($sel) {
	jQuery($sel).find('.due_date').datepicker({
		dateFormat: 'yy-mm-dd'
	});
}

function autocomplete_section() {
	var _autocomplete_sel = jQuery('.section-name-input');
	var school_admin_id = jQuery('#school_admin').val();

	_autocomplete_sel.autocomplete({
		minLength: 0,
		source: rest_object.yearbook_api_url + 'get-sections/' + school_admin_id,
		focus: function( event, ui ) {
			return false;
		},
		select: function( event, ui ) {
			var section_id = jQuery(this).val( ui.item.value );
			return false;
		}
	});
}

function toggle_section_ajax_msg(section_id = 0, msg = '', show = false) {
	var _msg = jQuery('.ajax-msg');
	var _msg_html = jQuery('.ajax-msg-alert-'+section_id);

	_msg.hide();

	if(show){
		_msg.show();
		_msg_html.show();
		_msg_html.html(msg);
	}
}

function btn_publish(show = false) {
	var btn_publish = jQuery('#publish');
	if(show) {
		btn_publish.show();
	}else{
		btn_publish.hide();
	}

}

function toggle_loader(show = false) {
	var _loader = jQuery('#loader');
	var bg = jQuery('.allteams-sendtask');

	if(show) {
			bg.css('opacity','0.5');
			_loader.show();
	}else{
		bg.css('opacity','1');
		_loader.hide();
	}

}

var AddTask = function() {
	return {
		init: function(){

			toggle_loader();

			init_contributors();

			data = {
					assign_to: _data_assign_to,
			};

			var theCompiledHtml = theTemplate(data);
			// Add the compiled html to the page
			jQuery('.content-placeholder').html(theCompiledHtml);

			jQuery(document).on('click', '.edit-block', function(e){
				e.preventDefault();
				var _edit_tr = jQuery('.edit-tr');
				var _this = jQuery(this);
				var _edit_current_block = '.edit-tr-' + _this.data('block-id');
				var _edit_block_div = '.edit-div-' + _this.data('block-id');
				_edit_tr.hide();
				update_block_ajax_msg('');
				//console.log(_this);
				//console.log(_edit_block_div);
				//console.log(_edit_current_block);
				jQuery(_edit_current_block).show();
				//jQuery('.selectpicker').selectpicker();
			});

			jQuery(document).on('change', '.block-size_fullpage', function(e){
				var _this = jQuery(this);
				if ( _this.val() == '-1' ) {
					jQuery('.custom-page-size-container').show();
				} else {
					jQuery('.custom-page-size-container').hide();
				}
			});

			jQuery(document).on('click', '.add-block', function(){
					var last_block = jQuery('.content-placeholder .edit-tr').last().find('.row-blocks');
					var _block_id = last_block.data('block-id');
					//console.log(_block_id);
					var _edit_div = '.edit-div-' + _block_id;
					var last_block_full_page_sel = last_block.find('.block-size_fullpage').val();
					var last_block_part_page_sel = last_block.find('.block-size_partpage').val();
					var last_block_due_date = last_block.find('.due_date').val();
					var last_block_assign_to = last_block.find('.assign_to .selectpicker').val();
					var last_block_menu_order = last_block.find('.block-menu_order').val();
					var last_block_is_cover = last_block.find('.block-is_cover').val();

					if ( typeof last_block_menu_order == 'undefined' ) {
						var new_menu_order = 1;
					}else{
						var new_menu_order = (parseInt(last_block_menu_order) + 1);
					}

					if ( last_block_full_page_sel == '-1' ) {
						last_block_full_page_sel = 0;
					}

					jQuery('.custom-page-size-container').hide();
					// console.log(last_block);
					// console.log(last_block_assign_to);
					// console.log(last_block_is_cover);
					// console.log(last_block_menu_order);
					//convert assign to into integers
					if(last_block_assign_to){
						var last_block_assign_to_int = last_block_assign_to.map(function (x) {
						  return parseInt(x, 10);
						});
					}

					//console.log(last_block_assign_to_int);
					jQuery('.block-action').append(block_template());
					jQuery('.block-action').find('.block-size_fullpage').val(last_block_full_page_sel);
					jQuery('.block-action').find('.block-size_partpage').val(last_block_part_page_sel);
					jQuery('.block-action').find('.due_date').val(last_block_due_date);
					jQuery('.block-action').find('.add-block-buttons').append(block_template_btn());
					jQuery('.block-action').find('.block-menu_order').val(new_menu_order);
					jQuery('.block-action').show();
					jQuery('.add-block-ajax-msg').hide();
					jQuery('.block-action').find('.is-cover-container').remove();
					initDatePicker('.block-action');

					jQuery.each(_data_assign_to, function (i, item) {
						$def_sel = false;
						$selected = false;
						//console.log(item.id);
						if(last_block_assign_to_int != null && last_block_assign_to_int.indexOf(item.id) !== -1 ){
							$def_sel = true;
							$selected = true;
						}
						jQuery('.block-action').find('.assign_to').append(new Option(item.name, item.id, $def_sel, $selected));
					});

					jQuery('.block-action').find('.assign_to').selectpicker();
					//autocomplete_section();
					btn_publish(false);
					toggle_btn(false);
					toggle_move_button(false);
					toggle_update_button(false);
			});

			jQuery(document).on('click', '.save_block', function(e){
				e.preventDefault();
				jQuery('.add-block-ajax-msg').show();
				jQuery('.add-block-ajax-msg').html('Adding Block Please Wait');
				jQuery('.new-block-btn').hide();

				submit_form_data().done(function(res){
						var data = {
								assign_to: _data_assign_to,
								tasks: res,
						};
						console.log(res);
						console.log(data);
						var yearbook_id = data.tasks.parent.id;

						jQuery('#yearbook-page-name').val(data.tasks.parent.title);
						jQuery('#yearbook_id').val(data.tasks.parent.id);

						var theCompiledHtml = theTemplate(data);
						// Add the compiled html to the page
						//jQuery('.content-placeholder').html(theCompiledHtml);
						jQuery('.block-action').hide();
						get_data(yearbook_id);

						jQuery('.add-block-ajax-msg').hide();
						jQuery('.add-block-ajax-msg').html('');
					 jQuery('.new-block').remove();
				});
			});//.save block

			jQuery(document).on('click', '.update-block', function(e){
				e.preventDefault();

				//console.log('update-block');
				var _block_id = jQuery(this).data('block-id');
				//console.log(_block_id);
				var _parent_block = jQuery('.edit-div-'+_block_id);
				var _post_status = jQuery('#status').val();

				var _block_size_full_page = _parent_block.find('.block-size_fullpage').val();
				var _block_size_part_page = _parent_block.find('.block-size_partpage').val();
				var _block_size = _block_size_full_page + '' + _block_size_part_page ;
				var _block_title = _parent_block.find('.block-title').val();
				//var _assign_to = _parent_block.find('.assign_to').val();
				var _menu_order = _parent_block.find('.block-menu_order').val();
				var _due_date = _parent_block.find('.due_date').val();
				var _parent_id = jQuery('#yearbook_id').val();
				var _assign_to = _parent_block.find('#assign_to-'+_block_id).val();
				var custom_block_size_fullpage = _parent_block.find('.custom-block-size').val();

				var _is_cover_val = 0;
				if ( _parent_block.find('.block-is_cover').is(':checked') ) {
					_is_cover_val = 1;
				}

				update_block_ajax_msg('Updating Block please Wait', _block_id, true);
				toggle_btn(false);
				jQuery('.btn-action').hide();
				var update_ajax_data = {
					'action': 'yb_update_yearbook',
					'yearbook_id' : _block_id,
					'assign_to' : _assign_to,
					'block_size_fullpage' : _block_size_full_page,
					'block_size_partpage' : _block_size_part_page,
					'block_size' : _block_size,
					'block_title' : _block_title,
					'is_cover' : _is_cover_val,
					'menu_order' : _menu_order,
					'custom_block_size_fullpage' : custom_block_size_fullpage,
					'due_date' : _due_date,
					'post_status' : _post_status,
					'parent_id' : _parent_id,
				};
				// console.log('_assign_to' + _assign_to);
				// console.log('update : ' + _block_title);
				// console.log(update_ajax_data);
				var xmlRequest = jQuery.ajax({
					url: ajaxurl,
					method: "POST",
					data: update_ajax_data
				});

				xmlRequest.done(function(res){
					//console.log(res);
					jQuery('.content-placeholder').html('');
					get_data(_parent_id);
					jQuery('.btn-action').show();
				});

			});

			jQuery(document).on('click', '.cancel-update-block', function(e){
				e.preventDefault();
				var _edit_tr = jQuery('.edit-tr');
				_edit_tr.hide();
				toggle_btn(true);
				btn_publish(true);
				toggle_move_button(true);
				toggle_update_button(true);
			});

			jQuery(document).on('click', '.task-notify', function(e){
				e.preventDefault();

				var task_id = jQuery(this).data('post-id');

				jQuery('.btn-action').hide();

				update_block_ajax_msg('Sending Notification', task_id, true);

				var data = {
					'action': 'task_notify',
					'post_id': task_id,
					'notify_mode': 'manual',
				};

				var ajaxSendNotificationTask = jQuery.ajax({
					url: ajaxurl,
					data: data,
					method: "POST",
				});

				ajaxSendNotificationTask.done(function(data){
					jQuery('.update-block-ajax-msg').html('Sent Notification');
					setTimeout(function(){
						jQuery('.update-block-ajax-msg').hide();
						jQuery('.btn-action').show();
					}, 3000);

				});
			});

			jQuery(document).on('click', '.cancel-save-block', function(e){
				e.preventDefault();
				jQuery('.new-block').remove();
				toggle_btn(true);
				btn_publish(true);
				toggle_move_button(true);
				toggle_update_button(true);
			});

			jQuery(document).on('change', '.status-project', function(){
				var _this = jQuery(this);
				var _block_id = _this.data('block-id');
				var _ajax_msg = jQuery('.status-ajax-msg-' + _block_id);
				//console.log(_block_id);
				var ajax_data = {
					'action': 'yb_change_status',
					'block_id' : _block_id,
					'status' : _this.val()
				};

				var xmlRequest = jQuery.ajax({
					url: ajaxurl,
					method: "POST",
					data: ajax_data
				});
				_ajax_msg.html('Updating Status please wait.');
				xmlRequest.done(function(res){
					//console.log(res);
					//console.log('update stop');
					//get_data(_parent_id);
					_ajax_msg.html('Update successfully.');
					setTimeout(function(){
						_ajax_msg.html('');
					}, 3000);
				});

			})

			function init_default_page()
			{
				var default_page = [
					'Front Cover',
					'Inside Front Cover',
					'Inside Back Cover',
					'Back Cover',
				];
				jQuery('.custom-page-size-container').hide();
				jQuery.each(default_page, function(i,item){
					var _arg = {
						'title' : default_page[i],
						'css_prefix' : i,
						'index' : i,
						'is_default_page': 1,
						'default_full_page': 1,
						'is_cover': 1,
					}
					jQuery('.block-action').append(block_template(_arg));
					jQuery('.block-action').find('.block-size_fullpage').val(_arg.default_full_page);

				});
				jQuery('.add-block-ajax-msg').hide();
				initDatePicker('.block-action');
				//console.log(_data_assign_to);
				jQuery.each(_data_assign_to, function (i, item) {
						jQuery('.block-action').find('.assign_to').append(new Option(item.name, item.id));
				});
				jQuery('.block-action').find('.selectpicker').selectpicker();
				jQuery('.block-action .row-blocks .btn-primary').hide();

				jQuery('.block-action').append(block_template_btn());
				jQuery('.block-action').find('.cancel-save-block').hide();
				jQuery('.add-block-ajax-msg').hide();
				jQuery('.yb-left-container').find('.add-block').hide();
			}
			if(show_controller) {
				var yearbook_id = jQuery('#yearbook_id');
				get_data(yearbook_id.val());
			}else{
				//alert('xx');
				init_default_page();
			}
			//init_default_page();
			toggle_update_button(false);
		}//init: function()
	};
}();
jQuery(function () {
	AddTask.init();
});
