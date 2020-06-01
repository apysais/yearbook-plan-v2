<form id="page_content" name="page_content" method="post" action="#" enctype="multipart/form-data">
<div class="row">
  <div class="col-sm-12 col-md-8">
    <p><a href="<?php echo site_url(YB_PUBLIC_TASK_LIST);?>">Back to List</a></p>
    <h4><?php echo $post['title']; ?></h4>
    <p>Due Date : <span class="badge badge-<?php echo $standing;?>"><?php echo $post['due_date_standing']; ?> | <?php echo $post['due_date_format']; ?></span></p>
    <p>Article Length : <?php echo  $post['block_size']; ?> page(s)</span></p>
    <p>Current Word Count : <?php echo str_word_count(wp_strip_all_tags($post['post_content']));?> words</span></p>
    <!--<p>Photo Count : <span class="img-count"><?php //echo count($images); ?></p>-->

      <div class="card">
        <div class="card-body">
          <h3>Instructions</h3>
          <ul>
            <li>Write an article on the title given above.</li>
            <li>Complete this by the deadline date shown.</li>
            <li>Ensure word and photo count is appropriate for the length of the given article. One page is 800 words. For every photo added - reduced by 75 words. Example : one page = 4 photos and 500 words.</li>
            <li>Type text in the box below or copy and paste into box below.</li>
            <li>To upload photos press the "Choose Files" button, navigate to where the photo/photos are, select the photo/photos and press open.</li>
            <li>To remove a photo - press 'Remove'.</li>
            <li>To save as a draft press ‘save’ You can come back to this article at a later date.</li>
            <li>Once you have finished, select ‘Yes’ from the drop down.</li>
            <li>Once you have been allocated more than one article, the article name will appear in the list 'Upcoming' To access the article - press the name of the article you would like to complete.</li>
          </ul>
          <!--<input id="submit_my_page_content" class="btn btn-large btn-primary" name="submit_my_page_content" type="submit" value="Update" />-->
        </div>
      </div>

      <div class="editor">
        <?php wp_editor($post['post_content'], 'pagecontent', ['media_buttons'=>false]); ?>
      </div>
      <?php wp_nonce_field( 'add_page_content_'.$post['id'], 'page-content' ); ?>

      <div class="card">
        <div class="card-body">
          <input id="submit_my_page_content" class="btn btn-large btn-primary" name="submit_my_page_content" type="submit" value="Save" />
        </div>
      </div>
      <input type="hidden" name="post_id" id="post_id" value="<?php echo $post['id'];?>" />

  </div>
  <div class="col-sm-12 col-md-4">
    <div class="card mb-3">
      <div class="card-header">Options</div>
      <div class="card-body">
        <div class="options">
          <p class="card-title">Finished? if this is done please select "Yes"</p>
          <select name="submitted">
            <option value="0" <?php echo ($post['submitted']==0) ? 'selected':'';?>>No</option>
            <option value="1" <?php echo ($post['submitted']==1) ? 'selected':'';?>>Yes</option>
          </select>
          <p></p>
        </div>
      </div>
    </div>
    <div class="card mb-3">
      <div class="card-header">
        Add Image
      </div>
      <div class="card-body">
        <div class="upload-image">
          <input type="file" name="files[]" id="files" class="user_picked_files" multiple>
          <span class="upload-image-ajax-status"></span>
          <div class="blueimp-img-list"></div>
        </div>
        <div class="list-images">
          <div class="card-columns">
            <div id="list-images-data">
              <?php if(count($images) > 0) { ?>
                      <?php foreach($images as $k => $v) { ?>
                              <div class="card image-<?php echo $v->ID;?>">
                                <?php echo wp_get_attachment_image( $v->ID, "thumbnail", "", array( "class" => "img-responsive card-img newsfeed-fit" ) );  ?>
                                <span class="badge badge-danger remove-img-db" data-id="<?php echo $v->ID;?>">Remove</span>
                              </div>
                      <?php } ?>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
      // $partial_template = YB_View::get_instance()->public_part_partials('partials/dashboard/list-partials.php');
      // YB_View::get_instance()->display($partial_template, $data);
    ?>
  </div>

</div>
</form>
