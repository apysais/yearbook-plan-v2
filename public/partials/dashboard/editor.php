<?php
get_header();
$lock_post = YB_Lock_Post::get_instance()->init($task_id, $current_user_id);
$author_complete = YB_Lock_Author::get_instance()->authorComplete($task_id);
?>
<div class="bootstrap-iso">
  <div class="container-fluid">
      <?php
        if( !$lock_post && !$author_complete ){
          YB_View::get_instance()->public_partials('partials/dashboard/part-editor-form.php', $data);
        }else{
          if($lock_post && !$author_complete){
            YB_Lock_Notice::get_instance()->show($data);
          }elseif($author_complete){
            YB_Lock_Author::get_instance()->show($data);
          }
        }
      ?>
  </div>
</div>

<?php if(!$lock_post){ ?>
<script>

jQuery.noConflict();

(function( $ ) {

  $(document).ready(function() {

    var wp_ajax_data = {
			'action': 'refresh_post_lock',
			'task_id': <?php echo $task_id;?>
		};
    var ajax_call = function() {
      jQuery.post(yb.ajaxurl, wp_ajax_data, function(response) {
  			console.log('auto refresh lock timeout');
  		});
    };

    var interval = (1000 * 60 * 1); // where X is your every X minutes
    setInterval(ajax_call, interval);
  });

})( jQuery );

</script>
<?php } ?>
<?php get_footer(); ?>
