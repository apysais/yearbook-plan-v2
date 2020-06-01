<div class="bootstrap-iso">
  <div class="list-images">
    <h3><span class="img-count"><?php echo count($images); ?></span> Images </h3>
    <div class="upload-image">
      <input id="upload_image_button" type="button" class="btn-primary btn btn-sm" value="Add Image" />
    </div>
    <div class="card-columns list-images-data">
      <?php if(count($images) > 0) { ?>
              <?php foreach($images as $k => $v) { ?>
                      <div class="card image-<?php echo $v->ID;?>">
                        <?php echo wp_get_attachment_image( $v->ID, "thumbnail", "", array( "class" => "img-responsive card-img" ) );  ?>
                        <a href="#" class="btn btn-danger btn-sm remove-img-db" data-id="<?php echo $v->ID;?>">Remove</a>
                      </div>
              <?php } ?>
      <?php } ?>
    </div>
  </div>
</div>
