<div class="bootstrap-iso">
  <div class="allteams-sendtask form-group">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="current-page-parent"></div>
          <h3><?php echo $title;?></h3>
          <div class="list-block"></div>
          <div class="content-block-placeholder"></div>
        </div>
      </div>
    </div>
  </div>
</div><!-- .bootstrap-iso -->
<style>
  .list-group .ui-state-highlight { height: 150px; line-height: 70px; }
  .ui-sortable-handle-main{cursor: pointer;}
  .current-page-parent{margin-bottom:10px; border-bottom: 3px solid}
  .list-block{margin-bottom:10px; border-bottom: 3px solid}
</style>
<script id="current-page-parent-template" type="text/x-handlebars-template">
  <div id="current-page-parent-container">
    <h3>Current Page Details</h3>
    {{#each tasks as |task k|}}
    <div class="row parent-block-index-{{task.ID}}">
        <div class="form-group col-md-2">
          <label for="NameInput">Page number</label>
          <input type="text" value="{{task.meta.page_number}}" class="form-control form-control-sm" name="current[meta][page_number]" autocomplete="off">
        </div>
        <div class="form-group col-md-4">
          <label for="NameInput">Due Date</label>
          <input type="text" value="{{task.meta.due_date}}" class="form-control form-control-sm due_date" name="current[meta][due_date]" autocomplete="off">
        </div>
        <div class="form-group col-md-6">
          <label for="NameInput">Section</label>
          <input type="text" value="{{task.meta.section}}" class="form-control form-control-sm " name="current[meta][section]" autocomplete="off">
        </div>
        <div class="form-group col-md-6">
          <label for="NameInput">Assign To</label>
          <select class="form-control form-control-sm assign_to" name="current[post_author]">
            {{#each ../assign_to as |assign k_assign|}}
              <option value="{{assign.id}}" {{#ifCond assign.id '==' task.post_author}}selected{{/ifCond}}>{{assign.name}}</option>
            {{/each}}
          </select>
        </div>
        <div class="form-group col-md-2">
          <label for="NameInput">Submitted</label>
          <select class="form-control form-control-sm" name="current[meta][submitted]">
            <option value="0" {{#ifCond task.meta.submitted '==' 0}}selected{{/ifCond}}>No</option>
            <option value="1" {{#ifCond task.meta.submitted '==' 1}}selected{{/ifCond}}>Yes</option>
          </select>
        </div>

        <input type="hidden" name="current[menu_order]" value="{{task.menu_order}}">
        <input type="hidden" name="current[id]" value="{{task.id}}">
    </div>
    {{/each}}
  </div>
</script>
<script id="add-yearbook-plan-blocks" type="text/x-handlebars-template">
  <ul id="app-lists-blocks"></ul>
  <button type="button" class="btn btn-primary btn-sm add-block" >Add Blocks</button>
</script>
<script id="list-task-block-template" type="text/x-handlebars-template">
<div class="accordion" id="accordionList">
  <ul class="list-group">
    {{#each tasks as |task k|}}
      <li class="current-data">
        <div class="row row-blocks parent-block-index-{{task.id}} block-item-{{@index}} row-item-index-{{task.id}}-{{@index}}">
            <div class="form-group col-md-12">
            <span class="ui-sortable-handle-main">[Drag to re-order]</span>
            </div>
            <div class="form-group col-md-2">
              <label for="NameInput">Page number</label>
              <input type="text" value="{{task.meta.page_number}}" class="form-control form-control-sm" name="tasks[{{task.id}}][block][meta][page_number][]" autocomplete="off">
            </div>
            <div class="form-group col-md-10">
              <label for="NameInput">Article Name</label>
              <input type="text" value="{{task.post_title}}" class="form-control form-control-sm"  name="tasks[{{task.id}}][block][post_title][]" autocomplete="off">
            </div>
            <div class="form-group col-md-6">
              <label for="NameInput">Due Date</label>
              <input type="text" value="{{task.meta.due_date}}" class="form-control form-control-sm due_date" name="tasks[{{task.id}}][block][meta][due_date][]" autocomplete="off">
            </div>
            <div class="form-group col-md-6">
              <label for="NameInput">Assign To</label>
              <select class="form-control form-control-sm assign_to" name="tasks[{{task.id}}][block][post_author][]">
                {{#each ../assign_to as |assign k_assign|}}
                  <option value="{{assign.id}}" {{#ifCond assign.id '==' task.post_author}}selected{{/ifCond}}>{{assign.name}}</option>
                {{/each}}
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="NameInput">Submitted</label>
              <select class="form-control form-control-sm" name="tasks[{{task.id}}][block][meta][submitted][]">
                <option value="0" {{#ifCond task.meta.submitted '==' 0}}selected{{/ifCond}}>No</option>
                <option value="1" {{#ifCond task.meta.submitted '==' 1}}selected{{/ifCond}}>Yes</option>
              </select>
              <a href="#" class="remove_block" data-db-id="{{task.id}}" data-index="{{task.id}}" data-index-item="{{task.id}}-{{@index}}">Remove</a>
            </div>
            <input type="hidden" class="menu_order-{{task.id}} menu_order" name="tasks[{{task.id}}][block][menu_order][]" value="{{task.menu_order}}">
            <input type="hidden" class="posts-id" name="tasks[{{task.id}}][block][id][]" value="{{task.id}}">
        </div>
      </li>
    {{/each}}
  </ul>
</div>
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
  var current_post_id = <?php echo $posts->ID;?>;

  var block_index = 0;
  var index_fields = 0;
  var _data_assign_to = [];
  var _block_template = function(block_index, index_fields) {
    return `
      <div class="row row-blocks parent-block-index-`+index_fields+` block-item-`+block_index+` row-item-index-`+ index_fields +`-`+ block_index +`">
          <div class="form-group col-md-12">
          <span class="ui-sortable-handle-main">[Drag to re-order]</span>
          </div>
          <div class="form-group col-md-2">
            <label for="NameInput">Page number</label>
            <input type="text" class="form-control form-control-sm" name="tasks[`+ index_fields +`][block][meta][page_number][]" autocomplete="off">
          </div>
          <div class="form-group col-md-10">
            <label for="NameInput">Article Name</label>
            <input type="text" class="form-control form-control-sm" value="" name="tasks[`+ index_fields +`][block][post_title][]" autocomplete="off">
          </div>
          <div class="form-group col-md-6">
            <label for="NameInput">Due Date</label>
            <input type="text" class="form-control form-control-sm due_date" name="tasks[`+ index_fields +`][block][meta][due_date][]" autocomplete="off">
          </div>
          <div class="form-group col-md-6">
            <label for="NameInput">Assign To</label>
            <select class="form-control form-control-sm assign_to" name="tasks[`+ index_fields +`][block][post_author][]">
            </select>
          </div>
          <div class="form-group col-md-2">
            <label for="NameInput">Submitted</label>
            <select class="form-control form-control-sm" name="tasks[`+ index_fields +`][block][meta][submitted][]">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
            <a href="#" class="remove_block" data-index="`+ block_index +`" data-index-item="`+ index_fields +`-`+ block_index +`">Remove</a>
          </div>
          <hr>
      </div>
    `;
  }

  var currentParentPage = function() {
    return {
      init: function() {
        var current_parent_page = [];
        var theTemplateScript = jQuery("#current-page-parent-template").html();
        var theTemplate = Handlebars.compile(theTemplateScript);
        var _data_assign_to = [];

        var ajaxGetUsers = jQuery.ajax({
          url: rest_object.api_url + 'users',
          method: "GET",
          async: false
        });

        ajaxGetUsers.done(function(data){
          _data_assign_to = data;
        });
        console.log(rest_object.yearbook_api_url + 'show-parent-page/' + current_post_id);
        var ajaxGetCurrentPageParent = jQuery.ajax({
          url: rest_object.yearbook_api_url + 'show-parent-page/' + current_post_id,
          method: "GET",
          async: false
        });
        ajaxGetCurrentPageParent.done(function(data){
          current_parent_page = data;
        });

        var data = {
          tasks: current_parent_page,
          assign_to: _data_assign_to
        };
        console.log('current');
        console.log(data);
        var theCompiledHtml = theTemplate(data);
        // Add the compiled html to the page
        jQuery('.current-page-parent').html(theCompiledHtml);
        jQuery('.current-page-parent').find('.due_date').datepicker({
          dateFormat: 'yy-mm-dd'
        });
      }
    };
  }();

  var listBlocks = function() {
    return {
      init: function() {
        var theTemplateScript = jQuery("#list-task-block-template").html();
        var theTemplate = Handlebars.compile(theTemplateScript);
        var _data_assign_to = [];
        var _data_tasks = [];

        var ajaxGetUsers = jQuery.ajax({
          url: rest_object.api_url + 'users',
          method: "GET",
          async: false
        });

        ajaxGetUsers.done(function(data){
          _data_assign_to = data;
        });

        var ajaxGetTasks = jQuery.ajax({
          url: rest_object.yearbook_api_url + 'get-task/' + current_post_id,
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
        //console.log(data);
        var theCompiledHtml = theTemplate(data);
        // Add the compiled html to the page
        jQuery('.list-block').html(theCompiledHtml);
        jQuery('.list-group').sortable({
          items: '> li',
          handle: '.ui-sortable-handle-main',
          placeholder: "ui-state-highlight",
          update: function( e, ui ) {
            jQuery('#accordionList .list-group li').each(function(index) {
              jQuery(this).find('.menu_order').val(index);
            })
          }
        });

        jQuery('.list-block').on('click', '.remove_block', function(e){
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
            }
        });
        jQuery('.due_date').datepicker({
          dateFormat: 'yy-mm-dd'
        });
      }
    };
  }();

  var AddBlocks = function() {
		return {
			init: function(){
        // Grab the template script
        var theTemplateScript = jQuery("#add-yearbook-plan-blocks").html();

        // Compile the template
        var theTemplate = Handlebars.compile(theTemplateScript);

        var initDatePicker = function($sel) {
          jQuery($sel).find('.due_date').datepicker({
            dateFormat: 'yy-mm-dd'
          });
        }

        var ajaxGetUsers = jQuery.ajax({
          url: rest_object.api_url + 'users',
          method: "GET",
          async: false
        });

        ajaxGetUsers.done(function(data){
          _data_assign_to = data;
        });

        var data = {
            tasks: [],
            assign_to: _data_assign_to
        };
        // Pass our data to the template
        var theCompiledHtml = theTemplate(data);
        // Add the compiled html to the page
        jQuery('.content-block-placeholder').html(theCompiledHtml);

        initDatePicker('.content-block-placeholder');

        jQuery('.add-block').on('click', function(){
          block_index++;
          jQuery('#app-lists-blocks').append('<li>'+_block_template(block_index, index_fields)+'</li>');
          jQuery.each(data.assign_to, function (i, item) {
              jQuery('.block-item-' + block_index + ' .assign_to').append(new Option(item.name, item.id));
          });
          initDatePicker('.block-item-' + block_index);
          jQuery('#collapseItem-'+index_fields+' .add-block-content').sortable({
            items: '> .row-blocks',
            handle: '.ui-sortable-handle-main',
            placeholder: "ui-state-highlight",
          });
        });

        jQuery('#app-lists-blocks').on('click', '.remove_block', function(e){
          e.preventDefault();
          console.log('remove block');
          var block_index = jQuery(this).attr('data-index-item');
          jQuery('.row-item-index-' + block_index).remove(); //Remove field html
        });

        jQuery('#app-lists-blocks').sortable({
          items: '> li',
          handle: '.ui-sortable-handle-main',
          placeholder: "ui-state-highlight",
        });
      }//init: function()
		};
	}();

  jQuery(function () {
    currentParentPage.init();
    listBlocks.init();
    AddBlocks.init();
  });
</script>
