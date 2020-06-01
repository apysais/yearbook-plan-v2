<div class="bootstrap-iso">
  <div class="wrap">
    <div id="loader"></div>
    <h3><?php echo $plugin_page_title;?></h3>
    <div class="container-fluid">
      <div class="allteams-sendtask form-group">
      <div class="container-fluidx">
        <div class="row">
          <div class="col-sm-12 col-md-12">
            <form name="create-new-yearbook-plan" id="yearbook-plan-form" action="<?php echo $action_url;?>" method="post">
              <input type="hidden" name="_method" value="<?php echo $method;?>">
              <input type="hidden" name="yearbook_id" value="<?php echo $yearbook_id;?>" id="yearbook_id">
              <div class="row">
                <div class="col-sm-12 col-md-12"> <!-- right -->

                  <div class="form-row">
                    <div class="col">
                      <label for="yearbook-page-name"><?php echo $verbage_fields['yearbook_name'];?></label>
                      <input type="text" class="form-control form-control-sm yearbook-page-name" value="<?php echo $name;?>" name="yearbook-page-name" id="yearbook-page-name" >
                    </div>
                    <div class="col">
                      <?php if($is_admin && $status == 'draft') { ?>
                        <div class="form-group">
                          <label for="selectAccount">Owner</label>
                          <?php if($accounts) { ?>
                          <select name="school_admin" id="school_admin" class="form-control input-sm">
                          <?php foreach($accounts as $k => $v) { ?>
                          <option value="<?php echo $v->ID;?>" <?php echo ($school_admin_id == $v->ID) ? 'selected':''; ?>><?php echo $obj_account_meta->account_name(['user_id'=>$v->ID,'single'=>true]);?></option>
                          <?php } ?>
                          </select>
                          <?php } ?>
                        </div>
                      <?php }else{ ?>
                        <?php if($is_admin && $status == 'publish') { ?>
                          <div class="form-group">
                            <label for="selectAccount"><?php echo $verbage_fields['school_owner'];?></label>
                            <input type="text" name="school_name" class="form-control input-sm" id="school_name" value="<?php echo $school_name->display_name;?>" readonly>
                          </div>
                        <?php } ?>
                        <input type="hidden" name="school_admin" id="school_admin" value="<?php echo $school_admin_id;?>">
                      <?php } ?>
                      <div class="form-group">
                        <div class="form-group">
                          <label for="status"><?php echo $verbage_fields['yearbook_status'];?></label>
                          <select name="status" class="form-control form-control-sm status" id="status">
                          <option value="draft" <?php echo ($status == 'draft') ? 'selected':''; ?>><?php echo $verbage_fields['yearbook_status_draft'];?></option>
                          <option value="publish" <?php echo ($status == 'publish') ? 'selected':''; ?>><?php echo $verbage_fields['yearbook_status_publish'];?></option>
                          </select>
                        </div>
                        <input type="submit" name="publish" id="publish" class="btn btn-primary btn-sm" value="<?php echo $verbage_fields['publish_page'];?>">
                      </div>
                    </div>
                  </div>
                </div><!-- right -->

                <div class="col-sm-12 col-md-12"><!-- left -->
                  <table class="wp-list-table table widefat fixed striped pages">
                    <thead>
                      <tr>
                        <th scope="col" style="width:5%;">#</th>
                        <th scope="col"><?php echo $verbage_fields['column_page'];?></th>
                        <th scope="col"><?php echo $verbage_fields['column_article_name'];?></th>
                        <th scope="col"><?php echo $verbage_fields['column_author'];?></th>
                        <th scope="col"><?php echo $verbage_fields['column_word_count'];?></th>
                        <th scope="col"><?php echo $verbage_fields['column_photo_count'];?></th>
                        <th scope="col"><?php echo $verbage_fields['column_due_date'];?></th>
                        <!--<th scope="col">Page Side</th>-->
                        <th scope="col"><?php echo $verbage_fields['column_status'];?></th>
                      </tr>
                    </thead>
                    <tbody class="content-placeholder sortable">

                    </tbody>
                  </table>
                  <div class="container yb-add-container">
                    <div class="block-action"></div>
                  </div>
                  <p></p>
                  <button type="button" class="btn btn-success btn-sm add-block"><?php echo $verbage_fields['add_blocks'];?></button>
                  <p></p>
                </div><!-- left -->
              </div>
            </form>
          </div><!-- col -->
        </div><!-- row -->
      </div><!-- container -->
      </div><!-- allteams-sendtask -->
    </div><!-- container-fluid -->
  </div><!-- wrap -->
</div><!-- bootstrap-iso -->
</script>
<script id="add-yearbook-plan" type="text/x-handlebars-template">

    {{#each tasks.blocks.page as |v_block k_block|}}
      {{#each v_block as |child_block key_block|}}
        {{#if child_block.id}}
          <tr class="edit-tr-block-{{child_block.id}} ui-state-default">
            <input type="hidden" name="task[blocks][block_id][]" value="{{child_block.id}}">
            <td>{{k_block}}</td>
            <td>
              {{child_block.meta.block_size}}
              <div class="row-actions row-action-{{child_block.id}}">
                <span class="edit"><a href="#" class="edit-block " data-block-id="{{child_block.id}}" aria-label="">Edit</a></span> |
                <span class="remove-block"><a href="{{child_block.delete_url}}" class="remove-block" data-block-id={{child_block.id}}>Remove </a></span> |
                <span class="view-block"><a href="{{child_block.edit_post}}&yearbook_id=<?php echo $yearbook_id;?>" class="remove-block" data-block-id={{child_block.id}}><?php echo $verbage_fields['view_blocks'];?> </a></span> |
              </div>
            </td>
            <td>{{child_block.post_title}}</td>
            <td>{{child_block.user_info.data.display_name}}</td>
            <td>{{child_block.word_count}}</td>
            <td>{{child_block.photo_count}}</td>
            <td>
              {{#ifCond child_block.meta.is_finished '==' '1'}}
                Done
              {{else}}
                {{child_block.due_date_standing}} | {{child_block.due_date_format}}
              {{/ifCond}}
            </td>
            <!--<td>
              <div class="page-visual-container" style="">
                  <div class="bg-page" style="">
                    <div class="bg-page-current" style="width:100%;{{#ifCond child_block.loop_modulo '==' '1'}}background:#8dc63f;height:{{child_block.block_size_visual}}%;{{/ifCond}}{{#ifCond @index '==' '0'  }}margin-top:0px;{{else}}margin-top:{{child_block.previous_block_size}}%;{{/ifCond}}"></div>
                  </div>
                  <div class="bg-pagetwo" style="width:50%;{{#ifCond child_block.loop_modulo '==' '0'}}background:#8dc63f;height:{{child_block.block_size_visual}}%;{{/ifCond}}{{#ifCond @index '==' '0'  }}margin-top:0px;{{else}}margin-top:{{child_block.previous_block_size}}%;{{/ifCond}}"></div>
              </div>
            </td>-->
            <td>
              <p class="status-ajax-msg-{{child_block.id}} yb-status"></p>
              <select class="form-control form-control-sm status-project" name="block_status" data-block-id="{{child_block.id}}">
                <option value="0" {{#ifCond child_block.status '==' 'On Going'}}selected{{/ifCond}}>On Going</option>
                <option value="1" {{#ifCond child_block.status '==' 'Author Complete'}}selected{{/ifCond}}>Author Complete</option>
                <option value="2" {{#ifCond child_block.status '==' 'Proof Read'}}selected{{/ifCond}}>Proof Read</option>
                <option value="3" {{#ifCond child_block.status '==' 'Ready for production'}}selected{{/ifCond}}>Ready for production</option>
              </select>
            </td>
          </tr>
          <tr class="edit-tr edit-tr-{{child_block.id}}">
            <td colspan="8">
              <div class="col-md-12">
                <div class="edit-div-{{child_block.id}}">
                  <div class="row row-blocks update-block-container">
                    <div class="form-group col-md-3">

                      <label for="NameInput"><?php echo $verbage_fields['block_size'];?></label>
                        <div class="page-size-con">
                          <div class="page-size-sel">
                              <?php echo $verbage_fields['block_size_fullpage'];?></div>
                          <div class="page-size-sel">
                          <select class="form-control form-control-sm block-size_fullpage" name="update_task[block][block_size_fullpage]">
                            <option value="0" {{#ifCond child_block.meta.block_size_fullpage '==' '0'}}selected{{/ifCond}}>0</option>
                            <option value="1" {{#ifCond child_block.meta.block_size_fullpage '==' '1'}}selected{{/ifCond}}>1</option>
                            <option value="2" {{#ifCond child_block.meta.block_size_fullpage '==' '2'}}selected{{/ifCond}}>2</option>
                            <option value="3" {{#ifCond child_block.meta.block_size_fullpage '==' '3'}}selected{{/ifCond}}>3</option>
                            <option value="4" {{#ifCond child_block.meta.block_size_fullpage '==' '4'}}selected{{/ifCond}}>4</option>
                            <option value="5" {{#ifCond child_block.meta.block_size_fullpage '==' '5'}}selected{{/ifCond}}>5</option>
                          </select>
                          </div>
                          <div class="page-size-sel">
                            <?php echo $verbage_fields['block_size_partpage'];?>
                          </div>
                          <div class="page-size-sel">
                          <select class="form-control form-control-sm block-size_partpage" name="update_task[block][block_size_partpage]">
                            <option value="0" {{#ifCond child_block.meta.block_size_partpage '==' '0'}}selected{{/ifCond}}>0</option>
                            <option value=".25" {{#ifCond child_block.meta.block_size_partpage '==' '.25'}}selected{{/ifCond}}>1/4</option>
                            <option value=".3" {{#ifCond child_block.meta.block_size_partpage '==' '.3'}}selected{{/ifCond}}>1/3</option>
                            <option value=".5" {{#ifCond child_block.meta.block_size_partpage '==' '.5'}}selected{{/ifCond}}>1/2</option>
                            <option value=".67" {{#ifCond child_block.meta.block_size_partpage '==' '.67'}}selected{{/ifCond}}>2/3</option>
                            <option value=".75" {{#ifCond child_block.meta.block_size_partpage '==' '.75'}}selected{{/ifCond}}>3/4</option>
                          </select>
                          </div>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                      <label for="NameInput"><?php echo $verbage_fields['block_title'];?></label>
                      <input type="text" class="form-control form-control-sm block-title" name="update_task[block][block_title]" autocomplete="off" value="{{child_block.post_title}}">
                    </div>
                    <div class="form-group col-md-2">
                      <label for="NameInput"><?php echo $verbage_fields['block_template'];?></label>
                      <select name="update_task[block][template]" class="form-control form-control-sm template_name">
                        <option value="standard" {{#ifCond child_block.meta.template '==' 'standard'}}selected{{/ifCond}}>Standard</option>
                        <option value="gallery" {{#ifCond child_block.meta.template '==' 'gallery'}}selected{{/ifCond}}>Gallery</option>
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <label for="NameInput"><?php echo $verbage_fields['assign_to'];?></label>
                      <select class="form-control form-control-sm assign_to" name="update_task[block][post_author]">
                        {{#each ../../assign_to as |assign k_assign|}}
                          <option value="{{assign.id}}" {{#ifCond assign.id '==' child_block.post_author}}selected{{/ifCond}}>{{assign.name}}</option>
                        {{/each}}
                      </select>
                    </div>
                    <div class="form-group col-md-2">
                      <label for="NameInput"><?php echo $verbage_fields['due_date'];?></label>
                      <input type="text" class="form-control form-control-sm due_date" name="update_task[block][due_date]" value="{{child_block.meta.due_date}}" autocomplete="off">
                    </div>
                    <div class="form-group col-md-12">
                      <div class="alert alert-primary update-block-ajax-msg" role="alert"></div>
                      <div class="btn-action">
                        {{#ifCond child_block.post_status '==' 'publish'}}
                          {{#ifCond child_block.meta.is_finished '==' '0'}}
                            <a href="#" data-post-id="{{child_block.id}}" class="btn-sm btn-primary task-notify ">Send Notification</a>
                          {{/ifCond}}
                        {{/ifCond}}
                      <a href="#" class="update-block btn-sm btn-primary new-block-btn" data-block-id="{{child_block.id}}">Update</a>
                      <a href="#" class="cancel-update-block btn-sm btn-primary new-block-btn">Cancel</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        {{/if}}
      {{/each}}
    {{/each}}


  <!--<button type="button" class="btn btn-primary btn-sm add-page" ><?php //echo $verbage_fields['add_page'];?></button>-->
</script>
<style>
.sortable .ui-state-highlight { height: 150px; line-height: 70px; }
.sortable .ui-state-default { background:transparent; }
.ui-sortable-handle-main{cursor: pointer;}
.edit-tr{display: none;}
.page-visual-container{background: #f1f1f1;width: 100%;height: 100px;border: 2px solid black;}
.page-visual-container .bg-page{
background: #f1f1f1;
width: 50%;
position: relative;
float: left;
height: 100%;
/* margin-top: 25%; */
border-right: 3px solid black;
}
.page-visual-container .bg-page-current{

}
.page-visual-container .bg-pagetwo{
width: 50%;
height: 100%;
position: relative;
float: right;
}
.xadd-block{position:relative;left:75px;}
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
             return (v1 && v2) ? options.f_parent_idn(this) : options.inverse(this);
         case '||':
             return (v1 || v2) ? options.fn(this) : options.inverse(this);
         default:
             return options.inverse(this);
     }
  });
  var current_post_id = 0;
  var block_index = 0;
  var index_fields = 0;
  var _data_assign_to = [];
  var data;
  var _current_section_id = 0;
  var _yearbook_id = jQuery('#yearbook_id').val();

  <?php if($show_controller) { ?>
          var show_controller = true;
  <?php }else{ ?>
          var show_controller = false;
  <?php } ?>


  var theTemplateScript = jQuery("#add-yearbook-plan").html();
  var theTemplate = Handlebars.compile(theTemplateScript);

  function repeat_fields() {
      return  `
      <li class="app-lists-items new-section">
          <div class="row">
            <div class="form-group col-md-6">
              <label for="NameInput"><?php echo $verbage_fields['section_name'];?></label>
              <input type="text" class="form-control form-control-sm section-name-input" name="new_task[section_name]" autocomplete="off">
            </div>
            <div class="form-group col-md-12 add-section-actions">
              <div class="alert alert-primary add-section-ajax-msg" role="alert"></div>
              <div class="add-sections-btn">
                <a href="#" class="save-add-section btn-sm btn-primary">Save</a>
                <a href="#" class="cancel-add-section btn-sm btn-primary">Cancel</a>
              </div>
            </div>
          </div>
          <div class="childrens">

          </div>
        <button type="button" class="btn btn-success btn-sm add-block"><?php echo $verbage_fields['add_blocks'];?></button>
    </li>`;
  }

  function block_template(_arg = []) {
    let article_title = ''
    if(typeof _arg.title != "undefined"){
      article_title = _arg.title;
    }
    let new_block_css_prefix = ''
    if(typeof _arg.css_prefix != "undefined"){
      new_block_css_prefix = _arg.css_prefix;
    }
    return `
      <div class="row row-blocks new-block`+new_block_css_prefix+`">
        <div class="form-group col-md-3">
          <label for="NameInput"><?php echo $verbage_fields['block_size'];?></label>
            <div class="page-size-con">
              <div class="page-size-sel">
                <?php echo $verbage_fields['block_size_fullpage'];?></div>
              <div class="page-size-sel">
                <select class="form-control form-control-sm block-size_fullpage" name="new_task[block][block_size_fullpage]">
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
              </div>
              <div class="page-size-sel">
                <?php echo $verbage_fields['block_size_partpage'];?>
              </div>
              <div class="page-size-sel">
                <select class="form-control form-control-sm block-size_partpage" name="new_task[block][block_size_partpage]">
                  <option value="0">0</option>
                  <option value=".25">1/4 Page</option>
                  <option value=".3">1/3 Page</option>
                  <option value=".5">1/2 Page</option>
                  <option value=".67">2/3 Page</option>
                  <option value=".75">3/4 Page</option>
                </select>
              </div>
            </div>
        </div>
        <div class="form-group col-md-3">
          <label for="NameInput"><?php echo $verbage_fields['block_title'];?></label>
          <input type="text" class="form-control form-control-sm article-name" name="new_task[block][block_title]" autocomplete="off" value="`+article_title+`">
        </div>
        <div class="form-group col-md-2">
          <label for="NameInput"><?php echo $verbage_fields['block_template'];?></label>
          <select name="new_task[block][template]" class="form-control form-control-sm template_name">
              <option value="standard">Standard</option>
              <option value="gallery">Gallery</option>
          </select>
        </div>
        <div class="form-group col-md-2">
          <label for="NameInput"><?php echo $verbage_fields['assign_to'];?></label>
          <select class="form-control form-control-sm assign_to" name="new_task[block][post_author]">
          </select>
        </div>
        <div class="form-group col-md-2">
          <label for="NameInput"><?php echo $verbage_fields['due_date'];?></label>
          <input type="text" class="form-control form-control-sm due_date" name="new_task[block][due_date]" autocomplete="off">
        </div>
        <div class="form-group col-md-12">
          <div class="alert alert-primary add-block-ajax-msg" role="alert"></div>
          <a href="#" class="save_block btn-sm btn-primary new-block-btn">Save</a>
          <a href="#" class="cancel-save-block btn-sm btn-primary new-block-btn">Cancel</a>
        </div>
      </div>
    `;
  }

  function update_block_ajax_msg(msg = '', show = false){
    var _div = jQuery('.update-block-ajax-msg');

    _div.hide();
    _div.html('');
    if(show){
      _div.show();
      _div.html(msg);
    }
  }

  function sort_block() {
    var _children = jQuery(".sortable");
    _children.sortable({
      placeholder: "ui-state-highlight",
      update: function( event, ui ) {
        console.log('update start');
        toggle_loader(true);
        /*submit_form_data().done(function(res){
          var _parent_id = jQuery('#yearbook_id').val();
          get_data(_parent_id);
        });*/
        var form = jQuery('#yearbook-plan-form').serialize();
        var _parent_id = jQuery('#yearbook_id').val();

        var data = {
          'action': 'yb_sort_blocks',
          'form': form
        };

        var xmlRequest = jQuery.ajax({
          url: ajaxurl,
          method: "POST",
          data: data
        });

        xmlRequest.done(function(res){
          console.log(res);
          console.log('update stop');
          get_data(_parent_id);
        });

      }
    });
    _children.disableSelection();
  }

  function toggle_add_block(_show = true) {
    var add_block = jQuery('.add-block');
    if(_show) {
      add_block.show();
    }else{
      add_block.hide();
    }
  }

  function toggle_move_button(_show = true) {
    var _move_btn = jQuery('.dashicons-move');
    if(_show) {
      _move_btn.show();
    }else{
      _move_btn.hide();
    }
  }

  function toggle_btn(_show = true) {
    var _btn = jQuery(document).find('.btn');
    if(_show) {
      _btn.show();
    }else{
      _btn.hide();
    }
  }

  function toggle_update_button(_show = true) {
    var _btn = jQuery('#publish');
    if(_show) {
      _btn.show();
    }else{
      _btn.hide();
    }
  }

  function toggle_add_section(_show = true) {
    var add_section = jQuery('.add-page');
    if(_show) {
      add_section.show();
    }else{
      add_section.hide();
    }
  }

  function get_contributors(school_admin_id) {
    var ajaxGetUsers = jQuery.ajax({
      url: rest_object.yearbook_api_url + 'get-school-admin-contributor/' + school_admin_id,
      method: "GET",
      async: false
    });

    ajaxGetUsers.done(function(data){
      _data_assign_to = data;
    });
  }

  function init_contributors() {
    var sel_school_admin_id = jQuery('#school_admin');
    school_admin_id = sel_school_admin_id.val();
    get_contributors(school_admin_id);
    jQuery(document).on('change', '#school_admin', function(){
      school_admin_id = jQuery(this).val();
      jQuery('#app-lists')
        .find('.assign_to')
        .empty();
      get_contributors(school_admin_id);
      jQuery.each(_data_assign_to, function (i, item) {
          jQuery('#app-lists')
            .find('.assign_to')
            .append(new Option(item.name, item.id));
      });
    });
  }

  function submit_form_data() {

    var form = jQuery('#yearbook-plan-form').serialize();

    var data = {
      'action': 'yb_create_yearbook',
      'form': form      // We pass php values differently!
    };

    var xmlRequest = jQuery.ajax({
      url: ajaxurl,
      method: "POST",
      data: data
    });
    //console.log(data);
    return xmlRequest;
  }

  function get_data(_id) {

    toggle_loader(true);
    toggle_btn(false);

    var ajax_data = {
      'action': 'yb_get_yearbook',
      'yearbook_id' : _id
    };

    var xmlRequest = jQuery.ajax({
      url: ajaxurl,
      method: "POST",
      data: ajax_data
    });

    xmlRequest.done(function(res){
      //jQuery('.content-placeholder').html('');

      var data = {
          tasks: res,
          assign_to: _data_assign_to
      };
      console.log(data);
      /*console.log(data.tasks.blocks);
      console.log(data.tasks.blocks.page);
      */
      jQuery('#yearbook-page-name').val(data.tasks.parent.title);
      jQuery('#yearbook_id').val(data.tasks.parent.id);

      var theCompiledHtml = theTemplate(data);
      // Add the compiled html to the page
      jQuery('.content-placeholder').html(theCompiledHtml);

      btn_publish(true);
      sort_block();
      //sort_sections();
      toggle_loader();
      toggle_btn();
      //toggle_move_button(true);
      toggle_update_button(true);

      //jQuery('.update-section-ajax-msg').hide();
      jQuery('.update-block-ajax-msg').hide();

      initDatePicker('.content-placeholder');
      //autocomplete_section();

    });
  }

  function initDatePicker($sel) {
    jQuery($sel).find('.due_date').datepicker({
      dateFormat: 'yy-mm-dd'
    });
  }

  function autocomplete_section() {
    var _autocomplete_sel = jQuery('.section-name-input');
    var school_admin_id = jQuery('#school_admin').val();

    _autocomplete_sel.autocomplete({
      minLength: 0,
      source: rest_object.yearbook_api_url + 'get-sections/' + school_admin_id,
      focus: function( event, ui ) {
        return false;
      },
      select: function( event, ui ) {
        var section_id = jQuery(this).val( ui.item.value );
        return false;
      }
    });
  }

  function toggle_section_ajax_msg(section_id = 0, msg = '', show = false) {
    var _msg = jQuery('.ajax-msg');
    var _msg_html = jQuery('.ajax-msg-alert-'+section_id);

    _msg.hide();

    if(show){
      _msg.show();
      _msg_html.show();
      _msg_html.html(msg);
    }
  }

  function btn_publish(show = false) {
    var btn_publish = jQuery('#publish');
    if(show) {
      btn_publish.show();
    }else{
      btn_publish.hide();
    }

  }

  function toggle_loader(show = false) {
    var _loader = jQuery('#loader');
    var bg = jQuery('.allteams-sendtask');

    if(show) {
        bg.css('opacity','0.5');
        _loader.show();
    }else{
      bg.css('opacity','1');
      _loader.hide();
    }

  }

  var AddTask = function() {
    return {
      init: function(){

        toggle_loader();

        init_contributors();

        data = {
            assign_to: _data_assign_to
        };

        var theCompiledHtml = theTemplate(data);
        // Add the compiled html to the page
        jQuery('.content-placeholder').html(theCompiledHtml);

        jQuery(document).on('click', '.edit-block', function(e){
          e.preventDefault();
          var _edit_tr = jQuery('.edit-tr');
          var _this = jQuery(this);
          var _edit_current_block = '.edit-tr-' + _this.data('block-id');
          var _edit_block_div = '.edit-div-' + _this.data('block-id');
          _edit_tr.hide();
          update_block_ajax_msg('');
          //console.log(_this);
          //console.log(_edit_block_div);
          jQuery(_edit_current_block).show();
        });

        jQuery(document).on('click', '.add-block', function(){
            //jQuery('#app-lists').append(block_template());
            jQuery('.block-action').append(block_template());
            jQuery('.block-action').show();
            jQuery('.add-block-ajax-msg').hide();

            initDatePicker('.block-action');

            jQuery.each(_data_assign_to, function (i, item) {
                jQuery('.block-action').find('.assign_to').append(new Option(item.name, item.id));
            });

            //autocomplete_section();
            btn_publish(false);
            toggle_btn(false);
            toggle_move_button(false);
            toggle_update_button(false);
        });

        jQuery(document).on('click', '.save_block', function(e){
          e.preventDefault();
          jQuery('.add-block-ajax-msg').show();
          jQuery('.add-block-ajax-msg').html('Adding Block Please Wait');
          jQuery('.new-block-btn').hide();

          submit_form_data().done(function(res){
              var data = {
                  assign_to: _data_assign_to,
                  tasks: res,
              };
              console.log(res);
              console.log(data);
              var yearbook_id = data.tasks.parent.id;

              jQuery('#yearbook-page-name').val(data.tasks.parent.title);
              jQuery('#yearbook_id').val(data.tasks.parent.id);

              var theCompiledHtml = theTemplate(data);
              // Add the compiled html to the page
              //jQuery('.content-placeholder').html(theCompiledHtml);
              jQuery('.block-action').hide();
              get_data(yearbook_id);

              jQuery('.add-block-ajax-msg').hide();
              jQuery('.add-block-ajax-msg').html('');
             jQuery('.new-block').remove();
          });
        });//.save block

        jQuery(document).on('click', '.update-block', function(e){
          e.preventDefault();

          console.log('update-block');
          var _block_id = jQuery(this).data('block-id');
          console.log(_block_id);
          var _parent_block = jQuery('.edit-div-'+_block_id);
          var _post_status = jQuery('#status').val();

          var _block_size_full_page = _parent_block.find('.block-size_fullpage').val();
          var _block_size_part_page = _parent_block.find('.block-size_partpage').val();
          var _block_size = _block_size_full_page + '' + _block_size_part_page ;
          var _block_title = _parent_block.find('.block-title').val();
          var _template_name = _parent_block.find('.template_name').val();
          var _assign_to = _parent_block.find('.assign_to').val();
          var _due_date = _parent_block.find('.due_date').val();

          var _parent_id = jQuery('#yearbook_id').val();
          update_block_ajax_msg('Updating Block please Wait', _block_id, true);
          toggle_btn(false);
          jQuery('.btn-action').hide();
          var ajax_data = {
            'action': 'yb_update_yearbook',
            'yearbook_id' : _block_id,
            'block_size_fullpage' : _block_size_full_page,
            'block_size_partpage' : _block_size_part_page,
            'block_size' : _block_size,
            'block_title' : _block_title,
            'template_name' : _template_name,
            'assign_to' : _assign_to,
            'due_date' : _due_date,
            'post_status' : _post_status,
            'parent_id' : _parent_id,
          };
          console.log(ajax_data);
          var xmlRequest = jQuery.ajax({
            url: ajaxurl,
            method: "POST",
            data: ajax_data
          });

          xmlRequest.done(function(res){
            jQuery('.content-placeholder').html('');
            get_data(_parent_id);
            jQuery('.btn-action').show();
          });

        });

        jQuery(document).on('click', '.cancel-update-block', function(e){
          e.preventDefault();
          var _edit_tr = jQuery('.edit-tr');
          _edit_tr.hide();
          toggle_btn(true);
          btn_publish(true);
          toggle_move_button(true);
          toggle_update_button(true);
        });

        jQuery(document).on('click', '.task-notify', function(e){
          e.preventDefault();

          var task_id = jQuery(this).data('post-id');

          jQuery('.btn-action').hide();

          update_block_ajax_msg('Sending Notification', task_id, true);

          var data = {
        		'action': 'task_notify',
        		'post_id': task_id     // We pass php values differently!
        	};

          var ajaxSendNotificationTask = jQuery.ajax({
            url: ajaxurl,
            data: data,
            method: "POST",
          });

          ajaxSendNotificationTask.done(function(data){
            jQuery('.update-block-ajax-msg').html('Sent Notification');
            setTimeout(function(){
              jQuery('.update-block-ajax-msg').hide();
              jQuery('.btn-action').show();
            }, 3000);

          });
        });

        jQuery(document).on('click', '.cancel-save-block', function(e){
          e.preventDefault();
          jQuery('.new-block').remove();
          toggle_btn(true);
          btn_publish(true);
          toggle_move_button(true);
          toggle_update_button(true);
        });

        jQuery(document).on('change', '.status-project', function(){
          var _this = jQuery(this);
          var _block_id = _this.data('block-id');
          var _ajax_msg = jQuery('.status-ajax-msg-' + _block_id);
          //console.log(_block_id);
          var ajax_data = {
            'action': 'yb_change_status',
            'block_id' : _block_id,
            'status' : _this.val()
          };

          var xmlRequest = jQuery.ajax({
            url: ajaxurl,
            method: "POST",
            data: ajax_data
          });
          _ajax_msg.html('Updating Status please wait.');
          xmlRequest.done(function(res){
            //console.log(res);
            //console.log('update stop');
            //get_data(_parent_id);
            _ajax_msg.html('Update successfully.');
            setTimeout(function(){
              _ajax_msg.html('');
            }, 3000);
          });

        })

        function init_default_page()
        {
          var default_page = [
            'Front Cover',
            'Inside Front Cover',
            'Inside Back Cover',
            'Back Cover',
          ];
          jQuery.each(default_page, function(i,item){
            var _arg = {
              'title' : default_page[i],
              'css_prefix' : i,
            }
            jQuery('.block-action').append(block_template(_arg));
          });
          jQuery('.add-block-ajax-msg').hide();
          initDatePicker('.block-action');
          jQuery.each(_data_assign_to, function (i, item) {
              jQuery('.block-action').find('.assign_to').append(new Option(item.name, item.id));
          });
          jQuery('.block-action .row-blocks .btn-primary').hide();
        }
        if(show_controller) {
          var yearbook_id = jQuery('#yearbook_id');
          get_data(yearbook_id.val());
        }else{
          //init_default_page();
        }
        toggle_update_button(false);
      }//init: function()
    };
  }();
  jQuery(function () {
    AddTask.init();
  });
</script>
