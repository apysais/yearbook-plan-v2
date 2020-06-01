<div class="bootstrap-iso">
  <div class="wrap">
    <div class="container-fluid">
      <form name="delete-yearbook-section" action="<?php echo $action_url;?>" method="post">
        <h3>Delete YearBook Section : <?php echo $section->name;?></h3>
        <input type="hidden" name="_method" value="<?php echo $method;?>">
        <?php if( count($posts)>1 ) {?>
                <h5>There are <?php echo count($posts);?> Blocks in this section</h5>
                <p>Assign Them to any sections: </p>
                <?php if($terms) { ?>
                  <select name="sections">
                    <?php foreach($terms as $k => $v) { ?>
                            <?php if($v->term_id != $term_id){ ?>
                                  <option value="<?php echo $v->term_id;?>">
                                    <?php echo $v->name;?>
                                  </option>
                            <?php } ?>
                    <?php } ?>
                  </select>
                <?php } ?>
                <p></p>
        <?php } ?>
        <?php wp_nonce_field( 'delete_this_section_' . $term_id.$school_id.$yb_id, 'delete_this_section' ); ?>
        <button type="submit" class="btn btn-primary">Confirm Deletion</button>
        <input type="hidden" name="section_id" value="<?php echo $term_id;?>">
        <input type="hidden" name="school_admin_id" value="<?php echo $school_id;?>">
        <input type="hidden" name="yb_id" value="<?php echo $yb_id;?>">
      </form>
    </div>
  </div>
</div>
