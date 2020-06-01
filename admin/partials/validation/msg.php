<?php if(is_array($msg) && count($msg) >= 1) { ?>
  <div class="request-validation">
    <?php foreach($msg as $k => $v) { ?>
      <div class="alert alert-danger" role="alert">
        <?php echo $v;?>
      </div>
    <?php } ?>
  </div>
<?php } ?>
