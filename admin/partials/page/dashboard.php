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
      <?php if($root_pages) { ?>
              <?php $indx = 1; ?>
              <?php foreach($root_pages as $k=>$v) { ?>
                      <tr>
                        <th scope="row"><?php echo $indx;?></th>
                        <td>
                          <a href="<?php echo yb_admin_url_yearbookpage('&_method=showYearbook&id='.$v->ID.'&school_id='.$v->post_author.'');?>">
                            <?php echo $v->post_title;?>
                          </a>
                          - <?php echo $v->post_status;?>
                          <?php if($is_admin){ ?>
                              <?php $user_data = get_userdata($v->post_author); ?>
                            - <?php echo $user_data->display_name;?>
                          <?php } ?>
                        </td>
                        <td>
                          <a href="<?php echo wp_nonce_url(yb_admin_url_yearbookpage('&_method=verify-delete&id='.$v->ID.''), 'delete_yearbook_'.$v->ID, 'delete_yearbook_nonce' ) ;?>">Delete</a>
                        </td>
                        </tr>
                      <?php $indx++;?>
              <?php } ?>
      <?php } ?>
    </tbody>
  </table>
  <a class="btn btn-primary btn-sm" href="<?php echo admin_url('admin.php?page=YearBook&_method=add-new');?>" role="button">Add New Yearbook Plan</a>
</div>
