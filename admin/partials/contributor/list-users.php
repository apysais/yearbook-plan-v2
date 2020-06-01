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
      <?php if ( $contributors ) { ?>
              <?php $indx = 1; ?>
              <?php foreach ( $contributors as $user ) { ?>
                      <?php //$school_name = get_user_meta($user->ID, 'account_name', true); ?>
                      <tr>
                        <th scope="row"><?php echo $indx;?></th>
                        <td>
                          <?php $url_edit = yb_contributor_url('&_method=edit&id='.$user->ID.'');?>
                          <?php if($is_admin) { ?>
                                  <?php $school_admin_id = YB_Contributor_Meta::get_instance()->parent_school_admin(['user_id' => $user->ID, 'single' => true]); ?>
                                  <?php $url_edit = yb_contributor_url('&_method=edit&id='.$user->ID.'&school_admin_id='.$school_admin_id.'');?>
                          <?php } ?>
                          <a href="<?php echo $url_edit;?>">
                            <?php echo $user->display_name;?>
                          </a>
                        </td>
                        <td>
                          <a href="<?php echo wp_nonce_url(yb_contributor_url('&_method=verify-delete&id='.$user->ID.''), 'delete_user_'.$user->ID, 'delete_user_nonce' ) ;?>">Delete</a>
                        </td>
                        </tr>
                      <?php $indx++;?>
              <?php } ?>
      <?php } ?>
    </tbody>
  </table>
  <a class="btn btn-primary btn-sm" href="<?php echo admin_url('admin.php?page=Contributors&_method=create');?>" role="button">Add New Contributor</a>
</div>
