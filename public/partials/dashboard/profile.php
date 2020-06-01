<?php
get_header();
$container   = get_theme_mod( 'understrap_container_type' );
?>
<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<main class="container" id="main">
				<h3>Update Profile</h3>
        <form id="profile_update" name="profile_update" method="post" action="#">
          <div class="form-group">
            <label for="exampleInputName">Name</label>
            <input type="text" name="full_name" class="form-control" id="exampleInputName" value="<?php echo $current_user->display_name;?>" placeholder="Enter Name">
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" name="email" value="<?php echo $current_user->user_email;?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
          </div>
					<?php wp_nonce_field( 'update_profile_'.$current_user->ID, 'update-profile' ); ?>
          <button type="submit" class="btn btn-primary">Update</button>
        </form>

			</main><!-- #main -->

	</div><!-- .row -->

</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
