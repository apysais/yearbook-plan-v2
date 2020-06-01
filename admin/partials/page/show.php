<div class="bootstrap-iso">
  <div class="wrap">
    <h3><?php echo $plugin_page_title;?></h3>
    <div class="container-fluid">
      <div class="allteams-sendtask form-group">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-12 col-md-12">
              <form name="create-new-yearbook-plan" action="<?php echo $action_url;?>" method="post">
                <input type="hidden" name="_method" value="<?php echo $method;?>">
                <input type="hidden" name="post_id" value="<?php echo $post_id;?>">
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="InputYearbookName"><?php echo $verbage_fields['yearbook_name'];?></label>
                    <input type="text" value="<?php echo $root_title;?>" class="form-control form-control-sm" name="yearbook-page-name" id="InputYearbookName" >
                  </div>
                  <div class="form-group col-md-4">
                    <label for="selectAccount">Assign to School</label>
                    <input type="text" readonly value="<?php echo $school_admin_name; ?>" class="form-control form-control-sm">
                  </div>
                  <div class="form-group col-md-2">
                    <span style="margin-bottom: 45px;display: inline-block;"></span>
                    <input type="submit" name="publish" id="publish" class="btn btn-primary btn-sm" value="<?php echo $verbage_fields['update'];?>">
                  </div>
                </div>
                <div class="list-yearbook"></div>
                <button type="button" class="btn btn-primary btn-sm add-page" ><?php echo $verbage_fields['add_page'];?></button>
                <p></p>
                <input type="submit" name="publish" id="publish" class="btn btn-primary" value="<?php echo $verbage_fields['update'];?>">
              </form>
            </div>
          </div><!-- row -->
        </div><!-- container -->
      </div><!-- allteams-sendtask -->
    </div><!-- container-fluid -->
  </div> <!-- wrap -->
</div><!-- bootstrap-iso -->
</script>
<script id="list-task-template" type="text/x-handlebars-template">
  <ul class="list-group" id="app-lists">
    {{#each tasks as |task k|}}
      <li class="app-lists-items index-items-{{@index}}">
        <span class="btn-primary btn-sm ui-sortable-handle-main"><?php echo $verbage_fields['drag_page'];?></span>
        <div class="row">
          <div class="form-group col-md-1 page-number">
            <label for="NameInput"><?php echo $verbage_fields['page_number'];?></label>
            <input type="text" value="{{task.meta.page_number}}" class="page-number-input page-number-parent form-control form-control-sm form-control-plaintext" readonly name="tasks[{{task.id}}][page_number]" autocomplete="off">
          </div>
          <div class="form-group col-md-2">
          <label for="NameInput"><?php echo $verbage_fields['section_name'];?></label>
          <input type="text" value="{{task.meta.section_name}}" class="form-control form-control-sm" name="tasks[{{task.id}}][section_name]" autocomplete="off">
          </div>
          <div class="form-group col-md-2">
          <label for="NameInput"><?php echo $verbage_fields['template_name'];?></label>
          <select name="tasks[{{task.id}}][template_name]" class="form-control form-control-sm template_name">
              <option value="cover" {{#ifCond task.meta.template_name '==' 'cover'}}selected{{/ifCond}}>Cover</option>
              <option value="standard" {{#ifCond task.meta.template_name '==' 'standard'}}selected{{/ifCond}}>Standard</option>
              <option value="gallery" {{#ifCond task.meta.template_name '==' 'gallery'}}selected{{/ifCond}}>Gallery</option>
          </select>
          </div>
          <div class="form-group col-md-4">
            <span style="margin-bottom: 45px;display: inline-block;"></span>
            <a class="btn btn-primary btn-sm btn-collapse btn-collapse-index-{{task.id}}" data-toggle="collapse" href="#collapseItem-{{task.id}}" role="button" aria-expanded="false" aria-controls="collapseItem-{{task.id}}" data-collapse-index="{{task.id}}">
              <?php echo $verbage_fields['collapse_hide_blocks'];?>
            </a>
            <button type="button" class="btn btn-danger btn-sm remove_button" data-db-id="{{task.id}}"><?php echo $verbage_fields['remove_page'];?></button>
          </div>
      </div>
      <div class="collapse show" id="collapseItem-{{task.id}}">
        <!--children -->
        <div class="childrens">
        {{#each task.children as |v k|}}
          <div class="row row-blocks parent-block-index-{{v.id}} list-group-item-{{v.due_date_standing_label}}">
            <div class="form-group col-md-12 childrens-action">
              <span class="btn-sm btn-primary ui-sortable-handle-main"><?php echo $verbage_fields['drag_blocks'];?></span>
              <a href="#" class="btn-sm btn-danger remove_block" data-db-id="{{v.id}}" data-index="{{v.id}}" data-index-item="{{v.id}}-{{@index}}"><?php echo $verbage_fields['remove_blocks'];?></a>
              <a href="{{v.edit_post}}" class="btn-sm btn-primary"><?php echo $verbage_fields['view_blocks'];?></a>
              <a href="#" data-post-id="{{v.id}}" class="btn-sm btn-primary task-notify">Send Notification</a>
              <span class="due-date-standing badge badge-info">{{v.due_date_standing}} | {{v.due_date_format}}</span>
              <span class="badge badge-secondary"><?php echo $verbage_fields['word_count'];?> : {{v.word_count}}</span>
              <span class="badge badge-secondary"><?php echo $verbage_fields['photo_count'];?> : {{v.photo_count}}</span>
              <!--<input type="text" readonly value="{{v.login_less_url}}">-->
            </div>
            <div class="form-group col-md-1 page-number">
              <label for="NameInput"><?php echo $verbage_fields['page_number'];?></label>
              <input type="text" value="{{v.meta.page_number}}" class="page-number-input form-control form-control-sm form-control-plaintext"  name="tasks[{{task.id}}][block][{{@index}}][page_number]" readonly autocomplete="off">
            </div>
            <div class="form-group col-md-2">
              <label for="NameInput"><?php echo $verbage_fields['block_title'];?></label>
              <input type="text" value="{{v.meta.block_title}}" class="form-control form-control-sm" name="tasks[{{task.id}}][block][{{@index}}][block_title]" autocomplete="off">
            </div>
            <div class="form-group col-md-2">
              <label for="NameInput"><?php echo $verbage_fields['block_template'];?></label>
              <select name="tasks[{{task.id}}][block][{{@index}}][template]" class="form-control form-control-sm template_name">
                  <option value="cover" {{#ifCond v.meta.template_name '==' 'cover'}}selected{{/ifCond}}>Cover</option>
                  <option value="standard" {{#ifCond v.meta.template_name '==' 'standard'}}selected{{/ifCond}}>Standard</option>
                  <option value="gallery" {{#ifCond v.meta.template_name '==' 'gallery'}}selected{{/ifCond}}>Gallery</option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="NameInput"><?php echo $verbage_fields['assign_to'];?></label>
              <select class="form-control form-control-sm assign_to" name="tasks[{{task.id}}][block][{{@index}}][post_author]">
                {{#each ../../assign_to as |assign k_assign|}}
                  <option value="{{assign.id}}" {{#ifCond assign.id '==' v.post_author}}selected{{/ifCond}}>{{assign.name}}</option>
                {{/each}}
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="NameInput"><?php echo $verbage_fields['due_date'];?></label>
              <input type="text" value="{{v.meta.due_date}}" class="form-control form-control-sm due_date" name="tasks[{{task.id}}][block][{{@index}}][due_date]" autocomplete="off">
            </div>
            <div class="form-group col-md-2">
              <label for="NameInput"><?php echo $verbage_fields['block_size'];?></label>
              <select class="form-control form-control-sm" name="tasks[{{task.id}}][block][{{@index}}][block_size]">
                <option value="1_4" {{#ifCond v.meta.block_size '==' '1_4'}}selected{{/ifCond}}>1/4 Page</option>
                <option value="1_3" {{#ifCond v.meta.block_size '==' '1_3'}}selected{{/ifCond}}>1/3 Page</option>
                <option value="1_2" {{#ifCond v.meta.block_size '==' '1_2'}}selected{{/ifCond}}>1/2 Page</option>
                <option value="1" {{#ifCond v.meta.block_size '==' '1'}}selected{{/ifCond}}>1 Page</option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label for="NameInput"><?php echo $verbage_fields['submit'];?></label>
              <select class="form-control form-control-sm" name="tasks[{{task.id}}][block][{{@index}}][submitted]">
                <option value="0" {{#ifCond v.meta.submitted '==' 0}}selected{{/ifCond}}>No</option>
                <option value="1" {{#ifCond v.meta.submitted '==' 1}}selected{{/ifCond}}>Yes</option>
              </select>
            </div>
            <input type="hidden" class="posts-id" name="tasks[{{task.id}}][block][{{@index}}][id]" value="{{v.id}}">
          </div><!-- row -->
        {{/each}}
        </div>
        <!--children -->
        <button type="button" class="btn btn-success btn-sm add-block" data-task-id="{{task.id}}"><?php echo $verbage_fields['add_blocks'];?></button>
      </div>
      <p></p>
      <hr/>
      <input type="hidden" class="menu_order-{{task.id}} menu_order" name="tasks[{{task.id}}][menu_order]" value="{{task.menu_order}}">
      <input type="hidden" class="posts-id" name="tasks[{{task.id}}][id]" value="{{task.id}}">
    </li>
    {{/each}}
  </ul>
</script>
<style>
  #app-lists .ui-state-highlight { height: 150px; line-height: 70px; }
  .ui-sortable-handle-main{cursor: pointer;}
  .children-pages{margin-left: 20px;}
</style>
<script type = "text/javascript">
  Handlebars.registerHelper('ifCond', function (v1, operator, v2, options) {
     switch (operator) {
         case '==':
             return (v1 == v2) ? options.fn(this) : options.inverse(this);
         case '===':
             return (v1 === v2) ? options.fn(this) : options.inverse(this);
         case '!=':
             return (v1 != v2) ? options.fn(this) : options.inverse(this);
         case '!==':
             return (v1 !== v2) ? options.fn(this) : options.inverse(this);
         case '<':
             return (v1 < v2) ? options.fn(this) : options.inverse(this);
         case '<=':
             return (v1 <= v2) ? options.fn(this) : options.inverse(this);
         case '>':
             return (v1 > v2) ? options.fn(this) : options.inverse(this);
         case '>=':
             return (v1 >= v2) ? options.fn(this) : options.inverse(this);
         case '&&':
             return (v1 && v2) ? options.fn(this) : options.inverse(this);
         case '||':
             return (v1 || v2) ? options.fn(this) : options.inverse(this);
         default:
             return options.inverse(this);
     }
  });
  if (typeof hook_task_data != 'undefined') {
   task_data = hook_task_data;
  }
  var current_post_id = <?php echo $post_id;?>;
  var block_index = 0;
  var index_fields = <?php echo count($children);?>;
  var _data_assign_to = [];

  var school_admin_id = <?php echo $school_admin_id?>;

  var _block_template = function(block_index, index_fields) {
    return `
      <div class="row row-blocks parent-block-index-`+index_fields+` block-item-`+block_index+` row-item-index-`+ index_fields +`-`+ block_index +`">
          <div class="form-group col-md-12 childrens-action">
          <span class="ui-sortable-handle-main btn-sm btn-primary">Drag to re-order</span>
          <a href="#" class="btn-sm btn-danger remove_block" data-index="`+ block_index +`" data-index-item="`+ index_fields +`-`+ block_index +`"><?php echo $verbage_fields['remove_page'];?></a>
          </div>
          <div class="form-group col-md-1 page-number">
            <label for="NameInput"><?php echo $verbage_fields['page_number'];?></label>
            <input type="text" class="page-number-input form-control form-control-sm form-control-plaintext" name="tasks[`+ index_fields +`][block][`+block_index+`][page_number]" readonly autocomplete="off">
          </div>
          <div class="form-group col-md-2">
            <label for="NameInput"><?php echo $verbage_fields['block_title'];?></label>
            <input type="text" class="form-control form-control-sm" name="tasks[`+ index_fields +`][block][`+block_index+`][block_title]" autocomplete="off">
          </div>
          <div class="form-group col-md-2">
            <label for="NameInput"><?php echo $verbage_fields['block_template'];?></label>
            <select name="tasks[`+ index_fields +`][block][`+block_index+`][template]" class="form-control form-control-sm template_name">
                <option value="cover">Cover</option>
                <option value="standard">Standard</option>
                <option value="gallery">Gallery</option>
            </select>
          </div>
          <div class="form-group col-md-2">
            <label for="NameInput"><?php echo $verbage_fields['assign_to'];?></label>
            <select class="form-control form-control-sm assign_to" name="tasks[`+ index_fields +`][block][`+block_index+`][post_author]">
            </select>
          </div>
          <div class="form-group col-md-2">
            <label for="NameInput"><?php echo $verbage_fields['due_date'];?></label>
            <input type="text" class="form-control form-control-sm due_date" name="tasks[`+ index_fields +`][block][`+block_index+`][due_date]" autocomplete="off">
          </div>
          <div class="form-group col-md-2">
            <label for="NameInput"><?php echo $verbage_fields['block_size'];?></label>
            <select class="form-control form-control-sm" name="tasks[`+ index_fields +`][block][`+block_index+`][block_size]">
              <option value="1_4">1/4 Page</option>
              <option value="1_3">1/3 Page</option>
              <option value="1_2">1/2 Page</option>
              <option value="1">1 Page</option>
            </select>
          </div>
          <div class="form-group col-md-1">
            <label for="NameInput"><?php echo $verbage_fields['submit'];?></label>
            <select class="form-control form-control-sm" name="tasks[`+ index_fields +`][block][`+block_index+`][submitted]">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>

          </div>
          <hr>
      </div>
    `;
  }
  var repeat_fields = function(index_fields) {
      return  `
      <li class="app-lists-items index-items-`+ index_fields +`">
        <span class="btn-sm btn-primary ui-sortable-handle-main"><?php echo $verbage_fields['sort'];?></span>
        <div class="row">
          <div class="form-group col-md-1 page-number">
            <label for="NameInput"><?php echo $verbage_fields['page_number'];?></label>
            <input type="text" class="page-number-input page-number-parent form-control form-control-sm form-control-plaintext" name="tasks[`+ index_fields +`][page_number]" readonly autocomplete="off">
          </div>
          <div class="form-group col-md-2">
          <label for="NameInput"><?php echo $verbage_fields['section_name'];?></label>
          <input type="text" class="form-control form-control-sm" name="tasks[`+ index_fields +`][section_name]" autocomplete="off">
          </div>
          <div class="form-group col-md-2">
          <label for="NameInput"><?php echo $verbage_fields['template_name'];?></label>
          <select name="tasks[`+ index_fields +`][template_name]" class="form-control form-control-sm template_name">
              <option value="cover">Cover</option>
              <option value="standard">Standard</option>
              <option value="gallery">Gallery</option>
          </select>
          </div>
          <div class="form-group col-md-4">
            <span style="margin-bottom: 45px;display: inline-block;"></span>
            <a class="btn btn-primary btn-sm btn-collapse btn-collapse-index-`+index_fields+`" data-toggle="collapse" href="#collapseItem-`+index_fields+`" role="button" aria-expanded="false" aria-controls="collapseItem-`+index_fields+`" data-collapse-index="`+index_fields+`">
              <?php echo $verbage_fields['collapse_hide_blocks'];?>
            </a>
            <button type="button" class="btn btn-danger btn-sm remove_button"><?php echo $verbage_fields['remove_page'];?></button>
          </div>
      </div>
      <div class="collapse show" id="collapseItem-`+index_fields+`">
        <div class="childrens">
          <div class="row row-blocks">
            <div class="form-group col-md-12">
              <span class="btn-sm btn-primary ui-sortable-handle-main"><?php echo $verbage_fields['sort'];?></span>
              <a href="#" class="btn-sm btn-danger remove_block" data-index="`+ block_index +`" data-index-item="`+ index_fields +`-`+ block_index +`"><?php echo $verbage_fields['remove_blocks'];?></a>
            </div>
            <div class="form-group col-md-1 page-number">
              <label for="NameInput"><?php echo $verbage_fields['page_number'];?></label>
              <input type="text" class="page-number-input form-control form-control-sm col-sm-4 form-control-plaintext" name="tasks[`+ index_fields +`][block][`+block_index+`][page_number]" autocomplete="off" readonly>
            </div>
            <div class="form-group col-md-2">
              <label for="NameInput"><?php echo $verbage_fields['block_title'];?></label>
              <input type="text" class="form-control form-control-sm" name="tasks[`+ index_fields +`][block][`+block_index+`][block_title]" autocomplete="off">
            </div>
            <div class="form-group col-md-2">
              <label for="NameInput"><?php echo $verbage_fields['block_template'];?></label>
              <select name="tasks[`+ index_fields +`][block][`+block_index+`][template]" class="form-control form-control-sm template_name">
                  <option value="cover">Cover</option>
                  <option value="standard">Standard</option>
                  <option value="gallery">Gallery</option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="NameInput"><?php echo $verbage_fields['assign_to'];?></label>
              <select class="form-control form-control-sm assign_to" name="tasks[`+ index_fields +`][block][`+block_index+`][post_author]">
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="NameInput"><?php echo $verbage_fields['due_date'];?></label>
              <input type="text" class="form-control form-control-sm due_date" name="tasks[`+ index_fields +`][block][`+block_index+`][due_date]" autocomplete="off">
            </div>
            <div class="form-group col-md-2">
              <label for="NameInput"><?php echo $verbage_fields['block_size'];?></label>
              <select class="form-control form-control-sm" name="tasks[`+ index_fields +`][block][`+block_index+`][block_size]">
                <option value="1_4">1/4 Page</option>
                <option value="1_3">1/3 Page</option>
                <option value="1_2">1/2 Page</option>
                <option value="1">1 Page</option>
              </select>
            </div>
            <div class="form-group col-md-1">
              <label for="NameInput"><?php echo $verbage_fields['submit'];?></label>
              <select class="form-control form-control-sm" name="tasks[`+ index_fields +`][block][`+block_index+`][submitted]">
                <option value="0">No</option>
                <option value="1">Yes</option>
              </select>

            </div>
          </div><!-- row -->
        </div><!-- childrens -->
        <button type="button" class="btn btn-success btn-sm add-block" ><?php echo $verbage_fields['add_blocks'];?></button>
      </div>
      <p></p>
      <hr/>
    </li>`;
  }
  function re_index(){
    jQuery( "#app-lists .app-lists-items" ).each(function( index ) {
      var _index = index + 1;
      jQuery(this).find('.page-number-parent').val(_index);
    });
  }

  function sort_re_index_blocks(){
    jQuery( "#app-lists .app-lists-items" ).each(function( index ) {
      var blocks = jQuery(this).find('.childrens .row-blocks');
      var page_number_parent = jQuery(this).find('.page-number-parent');
      jQuery(blocks).each(function(index){
        jQuery(this).find('.page-number-input').val(page_number_parent.val() + '.' + (index+1));
      });
    });
  }
  var listTask = function() {
    return {
      init: function() {
        var theTemplateScript = jQuery("#list-task-template").html();
        var theTemplate = Handlebars.compile(theTemplateScript);
        var _data_assign_to = [];
        var _data_tasks = [];

        var initDatePicker = function($sel) {
          jQuery($sel).find('.due_date').datepicker({
            dateFormat: 'yy-mm-dd'
          });
        }

        var ajaxGetUsers = jQuery.ajax({
          url: rest_object.yearbook_api_url + 'get-school-admin-contributor/' + school_admin_id,
          method: "GET",
          async: false
        });

        ajaxGetUsers.done(function(data){
          _data_assign_to = data;
        });

        var ajaxGetTasks = jQuery.ajax({
          url: rest_object.yearbook_api_url + 'get-yearbookplan/' + current_post_id,
          method: "GET",
          async: false
        });
        ajaxGetTasks.done(function(data){
          _data_tasks = data;
        });
        //console.log(_data_tasks);
        var data = {
            tasks: _data_tasks,
            assign_to: _data_assign_to
        };
        var theCompiledHtml = theTemplate(data);
        // Add the compiled html to the page
        jQuery('.list-yearbook').html(theCompiledHtml);
        //call after ajax
        jQuery("body").on("click", ".due_date", function(){
          jQuery(this).datepicker({dateFormat: 'yy-mm-dd'});
          jQuery(this).datepicker("show");
        });
        jQuery('.list-group').sortable({
          placeholder: "ui-state-highlight",
          handle: '.ui-sortable-handle-main',
          stop: function(e, ui){
            var _sort_index = ui.item;
            re_index();
            sort_re_index_blocks();
          }
        });
        jQuery('.childrens').sortable({
          items: '> .row-blocks',
          handle: '.ui-sortable-handle-main',
          stop: function(e, ui){
            var _sort_index = ui.item;
            re_index();
            sort_re_index_blocks();
          }
        });
        jQuery('.list-group').on('click', '.add-block', function(){
          block_index++;
          var _this_index;
          var block_index_count;
          if(this.hasAttribute('data-task-id')) {
            var task_id = jQuery(this).attr('data-task-id');
            _this_index = task_id;
            var count_blocks = jQuery(this).parent().find('.childrens .row-blocks');
            block_index_count = count_blocks.length + 1;
          }else{
            block_index_count = block_index;
            _this_index = index_fields;
          }
          jQuery(this).parent().find('.childrens').append(_block_template(block_index_count, _this_index));
          jQuery.each(data.assign_to, function (i, item) {
              jQuery('.block-item-' + block_index_count + ' .assign_to').append(new Option(item.name, item.id));
          });
          initDatePicker('.block-item-' + block_index_count);
          jQuery('.childrens').sortable({
            items: '> .row-blocks',
            handle: '.ui-sortable-handle-main',
            stop: function(e, ui){
              var _sort_index = ui.item;
              re_index();
              sort_re_index_blocks();
            }
          });
          re_index();
          sort_re_index_blocks();
        });
        jQuery('.list-group').on('click', '.remove_block', function(e){
          e.preventDefault();
          if(this.hasAttribute('data-db-id')) {
            var block_post_id = jQuery(this).attr('data-db-id');
            // Send the data using post
            var data = {
              'action': 'remove_page',
              'post_id': block_post_id     // We pass php values differently!
            };
            var removed_page = jQuery.post( ajaxurl , data );
            // Put the results in a div
            removed_page.done(function( data ) {
              jQuery('.parent-block-index-'+block_post_id).parents('li').remove();
            });
            jQuery('.parent-block-index-'+block_post_id).remove();
          }else{
            var block_post_id = jQuery(this).attr('data-index-item');
            jQuery('.row-item-index-'+block_post_id).remove();
          }
          re_index();
          sort_re_index_blocks();
        });
        jQuery('.add-page').on('click',function(){
          block_index = 0;
          index_fields++;
          jQuery('.list-group').append(repeat_fields(index_fields));
          initDatePicker('.list-group');
          jQuery.each(data.assign_to, function (i, item) {
            jQuery('.list-group').find('.assign_to').append(new Option(item.name, item.id));
          });
          re_index();
          sort_re_index_blocks();
        });
        //jQuery('.remove_button').on('click', function(e){
        jQuery("body").on('click', '.remove_button', function(e){
          e.preventDefault();
          if(this.hasAttribute('data-db-id')) {
            var block_post_id = jQuery(this).attr('data-db-id');
            // Send the data using post
            var data = {
              'action': 'remove_page',
              'post_id': block_post_id     // We pass php values differently!
            };
            var removed_page = jQuery.post( ajaxurl , data );
            // Put the results in a div
            removed_page.done(function( data ) {
              jQuery(this).parents('li').remove();
            });
          }
          //index_fields--;
          jQuery(this).parents('li').remove(); //Remove field html
        });
        jQuery("body").on('click', '.btn-collapse', function(){
          var collapsed = jQuery(this);
          if(collapsed.hasClass('collapsed')){
            collapsed.text('<?php echo $verbage_fields['collapse_hide_blocks'];?>');
          }else{
            collapsed.text('<?php echo $verbage_fields['collapse_show_blocks'];?>');
          }
        });
      },
      notify:function(){
        var notify = jQuery('.task-notify');
        jQuery(document).on('click', '.task-notify', function(e){
          e.preventDefault();
          var task_id = jQuery(this).data('post-id');
          console.log(task_id);
          var data = {
        		'action': 'task_notify',
        		'post_id': task_id,
        	};
          var ajaxSendNotificationTask = jQuery.ajax({
            url: ajaxurl,
            data: data,
            method: "POST",
          });
          ajaxSendNotificationTask.done(function(data){
            console.log(data);
          });
        });
      }
    };
  }();

  jQuery(function () {
    listTask.init();
    listTask.notify();
  });
</script>
