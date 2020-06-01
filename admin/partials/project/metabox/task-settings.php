<div class="bootstrap-iso">
  <?php //echo $post_yearbook_id; ?>
  <?php $yearbook_id = $post_yearbook_id; ?>
  <p><a href="<?php echo admin_url('admin.php?page=YearBook&_method=showYearbook&id='.$yearbook_id.'');?>">Back To YearBook List</a></p>
  <p>Word Count: <?php echo $word_count;?></p>
  <p>Photo Count: <?php echo $photo_count;?></p>
  <p>Done?</p>
  <select name="done">
    <option value="0" <?php echo ($is_finished == 0) ? 'selected':'';?>>No</option>
    <option value="3" <?php echo ($is_finished == 3) ? 'selected':'';?>>Ready for Production</option>
    <option value="2" <?php echo ($is_finished == 2) ? 'selected':'';?>>Proof Read</option>
  </select>
  <input type="hidden" name="yearbook_id" value="<?php echo $yearbook_id;?>">
</div>
