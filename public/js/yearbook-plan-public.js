(function( $ ) {
	'use strict';

	 $(window).load(function(){

		 function ajaxGetImages() {
			 var post_id = $('#post_id').val()
			 var data = {
				 'action': 'show_image',
				 'post_image_id': post_id     // We pass php values differently!
			 };
			 var show_image = jQuery.post( yb.ajaxurl , data );
			 show_image.done(function(ret){
				 $('#list-images-data').html(ret);
			 });
		 }
		 function uploadImageStatus($status) {
			 var status = $('.upload-image-ajax-status');
			 status.text($status);
		 }
		var ajaxAttachImageToTask = function() {
			return {
				init: function(){
				var _count_images = function() {
					 let _count_images = $('.list-images div.card').length;
					 $('.img-count').text(_count_images);
					}

					$('body').on('click', '.remove-img-db', function(e){
						e.preventDefault();
					$('#list-images-data').css('opacity', '0.4');
					$('.remove-img-db').hide();
						var _id = $(this).data('id');

						// Send the data using post
						var data = {
							'action': 'remove_image',
							'post_image_id': _id
						};
						var removed_image = jQuery.post( yb.ajaxurl , data );
						// Put the results in a div
						removed_image.done(function( data ) {
							$(".image-"+_id).remove();
						$('#list-images-data').css('opacity', '1');
						$('.remove-img-db').show();
							_count_images();
						});
					});

		  	}//init: function()
			};
		}();

		var takeOverContent = function() {
			return {
				init: function(){

					$('.yb-take-over-content').on('click', function(e){
						e.preventDefault();

						var _content_id = $(this).data('content-id');
						var _user_id = $(this).data('current-user-id');

						var data = {
							'action': 'take_over_content',
							'content_id': _content_id,
							'current_user_id': _user_id
						};

						// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
						jQuery.post(yb.ajaxurl, data, function(response) {
							location.reload();
						});
					});


		  	}//init: function()
			};
		}();

		ajaxAttachImageToTask.init();

		takeOverContent.init();

		var post_id = $('#post_id').val();

		$('#files').fileupload({
        dataType: 'json',
				url: yb.ajaxurl,
				formData: {
					action: 'upload_image',
					post_image_id: post_id
				},
				add: function(e, data) {
					$(this).hide();
					var _html = `<div class="alert alert-success alert-dismissible fade show" role="alert">
					  <button type="button" class="close d-none" data-dismiss="alert" aria-label="Close">
					    <span aria-hidden="true">&times;</span>
					  </button>
					</div>`;
			    data.context = $(_html)
			      .append( $('<p class="file">').text(data.files[0].name) )
			      .appendTo('.blueimp-img-list');
			    data.submit();
			  },
			  progress: function(e, data) {
			    var progress = parseInt((data.loaded / data.total) * 100, 10);
					data.context.find('.file').css("background-position-x", 100 - progress + "%");
				},
        done: function (e, data) {
					if ( data.jqXHR.responseText  == 1 ) {
						data.context.remove();
					} else {
						data.context
							.removeClass('alert-success')
							.addClass('alert-warning')
							.find('.file')
							.html(data.jqXHR.responseText);
						data.context.find('.close').removeClass('d-none');
					}
				}
    }).on('fileuploadstop', function(e) {
			ajaxGetImages();
			$(this).show();
		});

	 });
})( jQuery );
