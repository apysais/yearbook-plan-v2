<?php if($articles){ ?>
  <div class="articles-lists">
    <div class="row">
      <div class="col-md-8 col-sm-12">
        <ul class="list-group list-group-flush">
          <?php foreach($articles as $k => $v){ ?>
              <li class="list-group-item">
                <?php echo $v->post_title;?>
                <input type="hidden" class="bulk-edit-blocks" name="bulk-edit-ids[]" value="<?php echo $v->ID;?>">
              </li>
          <?php } ?>
        </ul>
      </div>
      <div class="col-md-4 col-sm-12">
        <div class="bulk-edit-tools">
          <div class="form-group">
            <label for="dueDateFormControl">Due Date</label>
            <input type="text" class="form-control form-control-sm due_date" id="dueDateFormControl" name="due_date" autocomplete="off">
          </div>
          <div class="form-group">
            <label for="blockStatus">Status</label>
            <select class="block_status form-control form-control-sm" id="blockStatus" name="block_status">
              <option value="0">On Going</option>
              <option value="1">Author Complete</option>
              <option value="2">Proof Read</option>
              <option value="3">Ready for production</option>
              <option value="4">In Production</option>
            </select>
          </div>
          <div class="form-group">
            <label for="assignToFormControl">Assign To</label>
            <select name="assign_to[]" class="assign_to form-control" id="assignToFormControl" size="5" multiple>
              <?php foreach($authors as $k => $v){ ?>
                <option value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
              <?php } ?>
            </select>
          </div>
          <?php if ( is_super_admin() ) : ?>
            <div class="form-group">
              <label for="isCoverFormControl">Is Cover</label>
              <input class="form-checkbox block-is_cover" name="is_cover[]" type="checkbox" value="1">
            </div>
          <?php endif; ?>
        </div>
        <div class="button-group-container">
          <button type="button" class="btn btn-secondary btn-sm cancel-bulk-edit">Cancel</button>
          <button type="button" class="btn btn-primary btn-sm update-bulk-edit">Update</button>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
