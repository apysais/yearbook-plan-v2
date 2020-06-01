<div class="bootstrap-iso">
  <div class="allteams-sendtask">
    <h3><?php _e( 'Welcome to your custom dashboard!' ); ?></h3>
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <?php if( $get_pages ) { ?>
                  <?php foreach($get_pages as $k => $v) { ?>
                          <h3><?php echo $v['post_title'];?></h3>
                          <?php if( isset($v['childrens']) && count($v['childrens']) > 0 ) { ?>
                                  <table id="" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Page</th>
                                            <th>Article Name</th>
                                            <th>Section</th>
                                            <th>Contributor</th>
                                            <th>Due Date</th>
                                            <th># of pages</th>
                                            <th>Word Count</th>
                                            <th>Photo Count</th>
                                            <th>Submitted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $child_indx=0;?>
                                        <?php foreach($v['childrens'] as $c_k => $c_v) { ?>
                                                <?php $media = get_attached_media( 'image', $c_v['id'] ); ?>
                                                <tr>
                                                    <td><?php echo $c_v['meta']['page_number'][0];?></td>
                                                    <td><?php echo $c_v['post_title'];?></td>
                                                    <td><?php echo isset($c_v['meta']['section'][0]) ? $c_v['meta']['section'][0]:$c_v['parent_meta']['section'][0];?></td>
                                                    <td><?php echo $c_v['user_info']->display_name;?></td>
                                                    <td><?php echo $c_v['meta']['due_date'][0];?></td>
                                                    <td>$320,800</td>
                                                    <td><?php echo str_word_count(wp_strip_all_tags($c_v['post_content']));?></td>
                                                    <td><?php echo count($media);?></td>
                                                    <td><?php echo ($c_v['meta']['submitted'][0] == 0) ? 'No':'Yes';?></td>
                                                </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                      <tr>
                                          <th>Page</th>
                                          <th>Article Name</th>
                                          <th>Section</th>
                                          <th>Contributor</th>
                                          <th>Due Date</th>
                                          <th># of pages</th>
                                          <th>Word Count</th>
                                          <th>Photo Count</th>
                                          <th>Submitted</th>
                                      </tr>
                                    </tfoot>
                                  </table>
                          <?php }else{ ?>
                                  <?php $child_indx = 0; ?>
                          <?php } ?>
                  <?php } ?>
          <?php } ?>

        </div>
      </div>
    </div>
  <div><!-- .custom-welcome-panel-content -->
</div>
<script>
jQuery(function () {
  jQuery('table.display').DataTable();
});
</script>
