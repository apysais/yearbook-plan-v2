<div class="container-fluid">
  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if ( ! empty( $user_query->get_results() ) ) { ?>
              <?php $indx = 1; ?>
              <?php foreach ( $user_query->get_results() as $user ) { ?>
                      <?php $school_name = get_user_meta($user->ID, 'account_name', true); ?>
                        <tr>
                          <th scope="row"><?php echo $indx;?></th>
                          <td>
                            <a href="<?php echo yb_admin_url_school_account('&_method=edit&id='.$user->ID.'');?>">
                              <?php echo $school_name;?>
                            </a>
                          </td>
                          <td>
                            <a href="<?php echo wp_nonce_url(yb_admin_url_school_account('&_method=verify-delete&id='.$user->ID.''), 'delete_school_account_'.$user->ID, 'delete_school_account' ) ;?>">Delete</a>
                          </td>
                        </tr>
                      <?php $indx++;?>
              <?php } ?>
      <?php } ?>
    </tbody>
  </table>
  <a class="btn btn-primary btn-sm" href="<?php echo admin_url('admin.php?page=SchoolAccount&_method=create');?>" role="button">Add New Account</a>
</div>
