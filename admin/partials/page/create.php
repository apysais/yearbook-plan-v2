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
                <div class="col-sm-12 col-md-12 yb-right-container"> <!-- right -->

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
                        </div>

                        <div class="col">
                          <div class="form-group">
                            <div class="form-group">
                              <label for="status"><?php echo $verbage_fields['yearbook_status'];?></label>
                              <select name="status" class="form-control form-control-sm status" id="status">
                              <option value="draft" <?php echo ($status == 'draft') ? 'selected':''; ?>><?php echo $verbage_fields['yearbook_status_draft'];?></option>
                              <option value="publish" <?php echo ($status == 'publish') ? 'selected':''; ?>><?php echo $verbage_fields['yearbook_status_publish'];?></option>
                              </select>
                            </div>
                            <input type="submit" name="publish" id="publish" class="btn btn-primary btn-sm" value="<?php echo $verbage_fields['publish_page'];?>">
                            <?php
                              if ( is_super_admin() ) {
                                yb_show_export_button($yearbook_id, $school_admin_id, $status);
                              }
                            ?>
                        </div>
                      </div>
                  </div>
                </div><!-- right -->

                <div class="col-sm-12 col-md-12 yb-left-container"><!-- left -->
                  <?php if( isset($_GET['_method']) && $_GET['_method'] !== 'add-new' ) : ?>
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="bulk-edit-container"></div>
                      <button class="btn btn-primary btn-primary bulk-edit-btn btn-sm" name="bulk-edit" >Bulk Edit</button>
                      <?php if ( is_super_admin() ) : ?>
                        <?php $bare_url = admin_url('?page=YearBook&_method=cleandb&id='.$yearbook_id.'&school_id='.$school_admin_id); ?>
                        <?php $nonce_clean_db = wp_nonce_url( $bare_url, 'cleandb-page_id-' . $yearbook_id . '-school-id-' . $school_admin_id, 'cleandb-nonce' ); ?>
                        <a href="<?php echo $nonce_clean_db; ?>" class="btn btn-danger cleanup-db btn-sm" name="clean-db" >Clean DB</a>
                      <?php endif; ?>
                    </div>
                  </div>
                  <?php endif; ?>
                  <table class="wp-list-table table widefat fixed striped pages">
                    <thead>
                      <tr>
                        <th scope="col" style="width:15%;"><input type="checkbox" id="checkAll"/></th>
                        <th scope="col">Article Position</th>
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
          {{#ifCond child_block.meta.is_cover '==' '1'}}
          <tr class="edit-tr-block edit-tr-block-{{child_block.id}} ui-is-covered" data-block-id="{{child_block.id}}">
          {{else}}
          <tr class="tr-article-block edit-tr-block edit-tr-block-{{child_block.id}} ui-state-default" data-block-id="{{child_block.id}}">
          {{/ifCond}}
            <input type="hidden" name="task[blocks][block_id][]" value="{{child_block.id}}">
            <input type="hidden" name="task[blocks][is_cover][]" value="{{child_block.meta.is_cover}}">
            <td>
              <input type="checkbox" class="bulk-edit-blocks" name="bulk-edit[]" value="{{child_block.id}}"> <span class="page-number-title">Page</span> : <span class="bock-page-number">{{child_block.page_number}}</span>
              {{#if child_block.can_be_edited}}
                <div class="row-actions row-action-{{child_block.id}}">
                  <span class="edit"><a href="#" class="edit-block " data-block-id="{{child_block.id}}" aria-label="">Edit</a></span>
                  {{#ifCond child_block.meta.is_cover '!=' '1'}}
                  | <span class="remove-block"><a href="{{child_block.delete_url}}" class="remove-block" data-block-id={{child_block.id}}>Remove </a></span>
                  {{/ifCond}}
                  | <span class="view-block"><a href="{{child_block.edit_post}}&yearbook_id=<?php echo $yearbook_id;?>" class="view-block" data-block-id={{child_block.id}}><?php echo $verbage_fields['view_blocks'];?> </a></span>
                </div>
              {{/if}}
            </td>
            <td><span class="page-article-position">{{child_block.menu_order}}</span></td>
            <td>
              <span class="page-article-block_size">{{child_block.meta.block_size}}</span>

            </td>
            <td><span class="page-article-title">{{child_block.post_title}}</span></td>
            <td><span class="page-article-author-name">{{child_block.authors_name}}</span></td>
            <td><span class="page-article-word-acount">{{child_block.word_count}}</span></td>
            <td><span class="page-article-photo-count">{{child_block.photo_count}}</span></td>
            <td>
              {{#ifCond child_block.meta.is_finished '==' '1'}}
                <span class="page-article-is-finished">Done</span>
              {{else}}
                <span class="page-article-due-date-standing">{{child_block.due_date_standing}}</span> | <span class="page-article-due-date-format">{{child_block.due_date_format}}</span>
              {{/ifCond}}
            </td>
            <!-- <td>
              <div class="page-visual-container" style="">
                  {{!child_block.page_visual}}
              </div>
            </td> -->
            <td>
              <p class="status-ajax-msg-{{child_block.id}} yb-status"></p>
              {{#if child_block.can_be_edited}}
              <select class="form-control form-control-sm status-project" name="block_status" data-block-id="{{child_block.id}}">
                <option value="0" {{#ifCond child_block.status '==' 'On Going'}}selected{{/ifCond}}>On Going</option>
                <option value="1" {{#ifCond child_block.status '==' 'Author Complete'}}selected{{/ifCond}}>Author Complete</option>
                <option value="2" {{#ifCond child_block.status '==' 'Proof Read'}}selected{{/ifCond}}>Proof Read</option>
                <option value="3" {{#ifCond child_block.status '==' 'Ready for Production'}}selected{{/ifCond}}>Ready for production</option>
                <option value="4" {{#ifCond child_block.status '==' 'In Production'}}selected{{/ifCond}}>In Production</option>
              </select>
              {{/if}}
            </td>
          </tr>
          <tr class="tr-article-block-edit edit-tr edit-tr-{{child_block.id}}">
            <td colspan="9" class="tr-article-block-edit-td">
              <div class="col-md-12 edit-div-col-container">
                <div class="edit-div-{{child_block.id}} edit-div-container">
                  <div class="row row-blocks update-block-container" data-block-id="{{child_block.id}}">
                    <!--<div class="form-groupx xcol-md-6">-->
                      <div class="form-group col-md-3 size-order-container">
                        <!-- <label for="NameInput"><?php //echo $verbage_fields['block_size'];?></label> -->
                        <div class="row edit-div-row">
                          <div class="col-md-12 edit-div-row-col">
                            <div class="form-row edit-div-form-row">
                              <div class="form-group col-md-6 size_fullpage-container">
                                <label for="block-size_fullpage" class="label-block-size_fullpage"><?php echo $verbage_fields['block_size_fullpage'];?></label>
                                <select class="form-control form-control-sm block-size_fullpage" name="update_task[block][block_size_fullpage]">
                                  <option value="0" {{#ifCond child_block.meta.block_size_fullpage '==' '0'}}selected{{/ifCond}}>0</option>
                                  <option value="1" {{#ifCond child_block.meta.block_size_fullpage '==' '1'}}selected{{/ifCond}}>1</option>
                                  <option value="2" {{#ifCond child_block.meta.block_size_fullpage '==' '2'}}selected{{/ifCond}}>2</option>
                                  <option value="3" {{#ifCond child_block.meta.block_size_fullpage '==' '3'}}selected{{/ifCond}}>3</option>
                                  <option value="4" {{#ifCond child_block.meta.block_size_fullpage '==' '4'}}selected{{/ifCond}}>4</option>
                                  <option value="5" {{#ifCond child_block.meta.block_size_fullpage '==' '5'}}selected{{/ifCond}}>5</option>
                                  <option value="-1" {{#ifCond child_block.meta.block_size_fullpage '==' '-1'}}selected{{/ifCond}}>Custom</option>
                                </select>
                              </div>
                              <div class="form-group col-md-6 size_partpage-container">
                                <label for="block-size_partpage" class="label-block-size_partpage"><?php echo $verbage_fields['block_size_partpage'];?></label>
                                <select class="form-control form-control-sm block-size_partpage" name="update_task[block][block_size_partpage]">
                                  <option value="0" {{#ifCond child_block.meta.block_size_partpage '==' '0'}}selected{{/ifCond}}>0</option>
                                  <option value=".25" {{#ifCond child_block.meta.block_size_partpage '==' '.25'}}selected{{/ifCond}}>1/4</option>
                                  <option value=".33" {{#ifCond child_block.meta.block_size_partpage '==' '.33'}}selected{{/ifCond}}>1/3</option>
                                  <option value=".50" {{#ifCond child_block.meta.block_size_partpage '==' '.50'}}selected{{/ifCond}}>1/2</option>
                                  <option value=".67" {{#ifCond child_block.meta.block_size_partpage '==' '.67'}}selected{{/ifCond}}>2/3</option>
                                  <option value=".75" {{#ifCond child_block.meta.block_size_partpage '==' '.75'}}selected{{/ifCond}}>3/4</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-md-6 custom-page-size-form-container">
                            <div class="custom-page-size-container" style="{{#ifCond child_block.meta.block_size_fullpage '!=' '-1'}}display:none;{{/ifCond}}">
                                <label for="custom-block-size" class="custom-block-size-label">Custom Size</label>
                                <input type="text" class="form-control form-control-sm custom-block-size" name="update_task[block][custom_block_size_fullpage]" autocomplete="off" value="{{child_block.meta.block_size}}">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="form-group col-md-6 menu-order-form-container">
                            <div class="menu-order-container">
                                {{#ifCond child_block.meta.is_cover '!=' '1'}}
                                  <label for="block-menu_order" class="block-menu_order-label">Article Position</label>
                                  <input class="form-control form-control-sm block-menu_order" name="update_task[block][menu_order]" type="text" value="{{child_block.menu_order}}">
                                {{/ifCond}}
                            </div>
                          </div>
                          <div class="form-group col-md-6 is-cover-container">
                            <?php if ( is_super_admin() ) : ?>
                              <label for="block-is_cover" class="is-cover-label">Is Cover?</label>
                              <input class="form-checkbox block-is_cover" name="update_task[block][is_cover]" type="checkbox" value="1" {{#ifCond child_block.meta.is_cover '==' '1'}}checked{{/ifCond}}>
                            <?php else: ?>
                                <input class="block-cover block-is_cover" name="update_task[block][is_cover]" type="hidden" value="{{child_block.meta.is_cover}}" >
                            <?php endif; ?>
                          </div>
                        </div>

                      </div>
                      <div class="form-group col-md-3 article-name-container">
                        <label for="NameInput" class="article-title-label"><?php echo $verbage_fields['block_title'];?></label>
                        <div class="row article-title-container">
                          <input type="text" class="form-control form-control-sm block-title" name="update_task[block][block_title]" autocomplete="off" value="{{child_block.post_title}}">
                        </div>
                      </div>
                    <!--</div>-->

                    <!--<div class="form-groupx xcol-md-6">-->
                      <div class="form-group col-md-3 assign_to-container">
                        <div>
                        <label for="NameInput" class="assign-to-label"><?php echo $verbage_fields['assign_to'];?></label></div>
                        <select class="selectpicker assign_to" id="assign_to-{{child_block.id}}" name="update_task[block][post_author][]" multiple data-selected-text-format="count > 2" data-live-search="true">
                          {{#each ../../assign_to as |assign k_assign|}}
                            <option value="{{assign.id}}" {{#ifIn assign.id child_block.authors}}selected{{/ifIn}}>{{assign.name}}</option>
                          {{/each}}
                        </select>
                        <input type="hidden" name="assign_to_input" value="" class="assign_to_input">
                      </div>
                      <div class="form-group col-md-2 due_date-container">
                        <label for="NameInput" class="due_date-label"><?php echo $verbage_fields['due_date'];?></label>
                        <input type="text" class="form-control form-control-sm due_date" name="update_task[block][due_date]" value="{{child_block.meta.due_date}}" autocomplete="off">
                      </div>
                    <!--</div>-->

                    <div class="form-group col-md-12">
                      <div class="alert alert-primary update-block-ajax-msg" role="alert"></div>
                      <div class="btn-action">
                        {{#ifCond child_block.is_finished '==' 0}}
                          <a href="#" data-post-id="{{child_block.id}}" class="btn-sm btn-primary task-notify ">Send Notification</a>
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

  Handlebars.registerHelper('ifIn', function(elem, list, options) {
    //console.log(list.indexOf(elem));
    if(list != null && list.indexOf(elem) > -1) {
      return options.fn(this);
    }
    return options.inverse(this);
  });

  var current_post_id = 0;
  var block_index = 0;
  var index_fields = 0;
  var _data_assign_to = [];
  var data;
  var _current_section_id = 0;
  var _yearbook_id = jQuery('#yearbook_id').val();
  var _is_admin = <?php echo $is_admin ? 1:0; ?>;
  <?php if($show_controller) { ?>
          var show_controller = true;
  <?php }else{ ?>
          var show_controller = false;
  <?php } ?>


  var theTemplateScript = jQuery("#add-yearbook-plan").html();
  var theTemplate = Handlebars.compile(theTemplateScript);

  function block_template(_arg = []) {
  	var article_title = ''
  	if(typeof _arg.title != "undefined"){
  		article_title = _arg.title;
  	}

  	var new_block_css_prefix = ''
  	if(typeof _arg.css_prefix != "undefined"){
  		new_block_css_prefix = _arg.css_prefix;
  	}

    var default_full_page = '0'
  	if(typeof _arg.default_full_page != "undefined"){
  		default_full_page = _arg.default_full_page;
  	}

    var block_size_full_page = 'new_task[block][0][block_size_fullpage]';
    var block_size_partpage = 'new_task[block][0][block_size_partpage]';
    var block_title = 'new_task[block][0][block_title]';
    var block_template = 'new_task[block][0][template]';
    var block_due_date = 'new_task[block][0][due_date]';
    var block_assign_to = 'new_task[block][0][post_author][]';
    var block_menu_order = 'new_task[block][0][menu_order]';
    var block_is_cover = 'new_task[block][0][is_cover]';
    var template_is_cover = false;
    var block_custom_page_size = 'new_task[block][0][custom_block_size_fullpage]';
    if(typeof _arg.is_default_page != "undefined"){
  		block_size_full_page = 'new_task[block]['+_arg.index+'][block_size_fullpage]';
  		block_size_partpage = 'new_task[block]['+_arg.index+'][block_size_partpage]';
  		block_menu_order = 'new_task[block]['+_arg.index+'][menu_order]';
      block_title = 'new_task[block]['+_arg.index+'][block_title]';
      block_template = 'new_task[block]['+_arg.index+'][template]';
      block_assign_to = 'new_task[block]['+_arg.index+'][post_author][]';
      block_due_date = 'new_task[block]['+_arg.index+'][due_date]';
      block_is_cover = 'new_task[block]['+_arg.index+'][is_cover]';
      template_is_cover = true;
      block_custom_page_size = 'new_task[block]['+_arg.index+'][custom_block_size_fullpage]';
    }

  	return `
  		<div class="row row-blocks new-block bulk-new-block-`+new_block_css_prefix+`">
        <div class="form-group col-md-3">

            <div class="row">
              <div class="col-md-12">
                <div class="form-row">
                  <div class="form-group col-md-6 size_fullpage-container">
                    <label for="block-size_fullpage" class="size_fullpage-label"><?php echo $verbage_fields['block_size_fullpage'];?></label>
                    <select class="form-control form-control-sm block-size_fullpage" name="`+block_size_full_page+`">
                      <option value="0">0</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="-1">Custom</option>
                    </select>
                  </div>
                  <div class="form-group col-md-6 size_partpage-container">
                    <label for="block-size_partpage" class="size_partpage-label"><?php echo $verbage_fields['block_size_partpage'];?></label>
                    <select class="form-control form-control-sm block-size_partpage" name="`+block_size_partpage+`">
                      <option value="0">0</option>
                      <option value=".25">1/4 Page</option>
                      <option value=".33">1/3 Page</option>
                      <option value=".50">1/2 Page</option>
                      <option value=".67">2/3 Page</option>
                      <option value=".75">3/4 Page</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="form-group col-md-6 custom-page-size-form-container">
                <div class="custom-page-size-container" style="display:none;">
                    <label for="custom-block-size" class="custom-block-size-label">Custom Size</label>
                    <input type="text" class="form-control form-control-sm custom-block-size" name="`+block_custom_page_size+`" autocomplete="off" value="">
                </div>
              </div>
            </div>

            <div class="row">

              <div class="form-group col-md-6 menu-order-form-container">
                <div class="menu-order-container">
                    <input class="form-control form-control-sm block-menu_order" name="`+block_menu_order+`" type="hidden" value="">
                </div>
              </div>

              <?php if ( isset($_GET['_method']) && $_GET['_method'] == 'add-new' ) : ?>
              <div class="form-group col-md-6 is-cover-container">
                <?php if ( is_super_admin() ) : ?>
                  <label for="block-is_cover" class="is_cover-label">Is Cover?</label>
                  <input class="form-checkbox block-is_cover" name="`+block_is_cover+`" type="checkbox" value="1" checked>
                <?php else: ?>
                    <input class="block-cover block-is_cover" name="`+block_is_cover+`" type="hidden" value="1" >
                <?php endif; ?>
              </div>
              <?php endif; ?>

            </div>

        </div>
        <div class="form-group col-md-3 article-name-container">
          <label for="NameInput" class="block_title-label"><?php echo $verbage_fields['block_title'];?></label>
          <input type="text" class="form-control form-control-sm article-name" name="`+block_title+`" autocomplete="off" value="`+article_title+`">
        </div>

        <div class="form-group col-md-3 assign_to-container">
          <label for="NameInput" class="assign_to-label"><?php echo $verbage_fields['assign_to'];?></label>
          <select id="assign_to_add_new" class="assign_to selectpicker" name="`+block_assign_to+`" multiple data-selected-text-format="count > 2" data-live-search="true">
          </select>
        </div>
        <div class="form-group col-md-2 due_date-container">
          <label for="NameInput" class="due_date-label"><?php echo $verbage_fields['due_date'];?></label>
          <input type="text" class="form-control form-control-sm due_date" name="`+block_due_date+`" autocomplete="off">
        </div>


  			<div class="form-group col-md-12 add-block-buttons">

  			</div>
  		</div>
  	`;
  }

  function block_template_btn()
  {
    return `
      <div class="alert alert-primary add-block-ajax-msg" role="alert"></div>
      <a href="#" class="save_block btn-sm btn-primary new-block-btn">Save</a>
      <a href="#" class="cancel-save-block btn-sm btn-primary new-block-btn">Cancel</a>
    `;
  }

</script>
