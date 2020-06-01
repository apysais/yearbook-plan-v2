<?php if(count($images) > 0) { ?>
        <?php foreach($images as $k => $v) { ?>
                <div class="card image-<?php echo $v->ID;?>">
                  <?php echo wp_get_attachment_image( $v->ID, "thumbnail", "", array( "class" => "img-responsive card-img newsfeed-fit" ) );  ?>
                  <span class="badge badge-danger remove-img-db" data-id="<?php echo $v->ID;?>">Remove</span>
                </div>
        <?php } ?>
<?php } ?>
