(function( $ ) {
	'use strict';

	 $(window).load(function(){
		 function ajaxGetImages() {
			 var post_id = $('#post_ID').val()
			 var data = {
				 'action': 'show_image',
				 'post_image_id': post_id     // We pass php values differently!
			 };
			 var show_image = jQuery.post( ajaxurl , data );
			 show_image.done(function(ret){
				 $('.list-images-data').html(ret);
			 });
		 }

		 function uploadImageStatus($status) {
			 var status = $('.upload-image-ajax-status');
			 status.text($status);
		 }

		 var _count_images = function() {
			let _count_images = $('.list-images div.card').length;
			$('.img-count').text(_count_images);
		 }

		 $(".remove-img-db").on('click', function(e){
			 e.preventDefault();
			 var _id = $(this).data('id');
			 // Send the data using post
			 var data = {
				 'action': 'remove_image',
				 'post_image_id': _id     // We pass php values differently!
			 };
			 var removed_image = jQuery.post( ajaxurl , data );
			 // Put the results in a div
			 removed_image.done(function( data ) {
				 $(".image-"+_id).remove();
				 _count_images();
			 });

		 });

		 var _wpMedia = function() {
			 var mediaUploader;
			 var main_post_id = $('#post_ID');
			 return {
				 init: function(){
					 $('#upload_image_button').click(function(e) {
						 e.preventDefault();
							 if (mediaUploader) {
							 mediaUploader.open();
							 return;
						 }
						 mediaUploader = wp.media.frames.file_frame = wp.media({
							 library: {
								 type: 'image' // limits the frame to show only images
							 },
							 multiple: true
						 });

						 mediaUploader.on('select', function() {
							 var uploaded_images = mediaUploader.state().get('selection');

							 var attachment_ids = uploaded_images.map( function( attachment ) {
									 attachment = attachment.toJSON();
									 //run ajax to insert the attach by id
									 var request = $.ajax({
										 url: ajaxurl,
										 method: "POST",
										 data: {
											 action: 'upload_image_admin',
											 attachment_id : attachment.id ,
											 parent_id : main_post_id.val()
										 }
									 });
									 request.done(function( msg ) {
										 ajaxGetImages();
									 });

							 }).join();

						 });
						 mediaUploader.on('open', function() {
 					    if (wp.media.frame.content.get() !== null) {
 					        // this forces a refresh of the content
 					        wp.media.frame.content.get().collection._requery(true);

 					        // optional: reset selection
 					        wp.media.frame.content.get().options.selection.reset();
 					    }
 						}, this);
						 mediaUploader.open();
						});
				 }//init: function()
			 };
		 }();
		 _wpMedia.init();

		 jQuery('.page-title-action').attr('href', 'admin.php?page=YearBook&_method=add-new');

		 jQuery('.export-btn').on('click', function(){
			 var _this = jQuery(this);
			 _this.text("Exporting Please Wait.");
		 })

	 });
})( jQuery );
