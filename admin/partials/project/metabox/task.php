<div class="bootstrap-iso">
  <div class="allteams-sendtask form-group">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <h3><?php echo $title;?></h3>
          <div class="list-tasks"></div>
          <div class="content-placeholder"></div>
        </div>
      </div>
    </div>
  </div>
</div><!-- .bootstrap-iso -->
<script id="list-task-template" type="text/x-handlebars-template">
<div class="accordion" id="accordionList">
  <ul class="list-group">
    {{#each tasks as |task k|}}
      <li class="list-group-item list-group-item-primary" >
        <button type="button" class="btn btn-link btn-sm" data-toggle="collapse" data-target="#collapseItem{{task.id}}" aria-expanded="true" aria-controls="collapseItem{{task.id}}">{{task.post_title}}</button>
        <div id="collapseItem{{task.id}}" class="collapse {{#if @first}}show{{/if}}" aria-labelledby="headingOne" data-parent="#accordionList">
          <div class="card col-sm-12">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 col-sm-12">
                  <ul>
                    <li><p>Page Number : {{task.meta.page_number}}</p></li>
                    <li><p>Section : {{task.meta.section}}</p></li>
                  </ul>
                </div>
                <div class="col-md-6 col-sm-12">
                  <ul>
                    <li><p>Due Date : {{task.meta.due_date}}</p></li>
                    <li><p>Assign : {{task.user_info.data.display_name}}</p></li>
                    <li># of Pages : {{task.num_of_pages}}</li>
                  </ul>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <p>Blocks</p>
                  <div class="row">
                    {{#each task.children as |v k|}}
                    <div class="col-sm-6">
                      <div class="card bg-light mb-3">
                        <div class="card-header">{{v.post_title}}</div>
                        <div class="card-body">
                          <ul class="children-pages">
                            <li><p>Page Number: {{v.page_number}}</p></li>
                            <li><p>Due Date: {{v.meta.due_date}}</p></li>
                            <li><p>Assign: {{v.user_info.data.display_name}}</p></li>
                            <li><p>Word Count: {{v.word_count}}</p></li>
                            <li><p>Photo Count: {{v.photo_count}}</p></li>
                          </ul>
                          <a href="{{v.edit_post}}" class="btn btn-primary btn-sm">Edit</a>
                        </div>
                      </div>
                    </div>
                    {{/each}}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </li>
    {{/each}}
  </ul>
</div>
</script>
<script id="add-yearbook-plan" type="text/x-handlebars-template">
  <ul id="app-lists"></ul>
  <button type="button" class="btn btn-primary btn-sm add-page" >Add Page</button>
</script>
<style>
  #app-lists .ui-state-highlight { height: 150px; line-height: 70px; }
  .ui-sortable-handle-main{cursor: pointer;}
  .children-pages{margin-left: 20px;}
</style>
<!--Your new content will be displayed in here-->
<script type = "text/javascript">
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
  var repeat_fields = function(index_fields) {
      return  `
      <li class="app-lists-items index-items-`+ index_fields +`">
        <span class="ui-sortable-handle-main">[Drag to re-order]</span>
        <div class="row">
          <div class="form-group col-md-2">
            <label for="NameInput">Page number</label>
            <input type="text" class="form-control form-control-sm" name="tasks[`+ index_fields +`][meta][page_number]" autocomplete="off">
          </div>
          <div class="form-group col-md-10">
          <label for="NameInput">Article Name</label>
          <input type="text" class="form-control form-control-sm" name="tasks[`+ index_fields +`][post_title]" autocomplete="off">
          </div>
      </div>
      <p>
        <a class="btn btn-primary btn-sm btn-collapse btn-collapse-index-`+index_fields+`" data-toggle="collapse" href="#collapseItem-`+index_fields+`" role="button" aria-expanded="false" aria-controls="collapseItem-`+index_fields+`" data-collapse-index="`+index_fields+`">
          Hide / Show Data
        </a>
      </p>
      <div class="collapse show" id="collapseItem-`+index_fields+`">
        <div class="row">
          <div class="form-group col-md-6">
            <label for="NameInput">Section</label>
            <input type="text" class="form-control form-control-sm" name="tasks[`+ index_fields +`][meta][section]" autocomplete="off">
          </div>
          <div class="form-group col-md-6">
            <label for="NameInput">Template</label>
            <input type="text" class="form-control form-control-sm" name="tasks[`+ index_fields +`][meta][template]" autocomplete="off">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="NameInput">Assign To</label>
            <select class="form-control form-control-sm assign_to" name="tasks[`+ index_fields +`][post_author]">
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="NameInput">Due Date</label>
            <input type="text" class="form-control form-control-sm due_date" name="tasks[`+ index_fields +`][meta][due_date]" autocomplete="off">
          </div>
          <div class="form-group col-md-2">
            <label for="NameInput">Submitted</label>
            <select class="form-control form-control-sm" name="tasks[`+ index_fields +`][meta][submitted]">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
          </div>
        </div>
        <div class="add-block-content"></div>
        <button type="button" class="btn btn-primary btn-sm add-block" >Add Block</button>
      </div>
      <p></p>
      <p><button type="button" class="btn btn-primary btn-sm remove_button">Remove</button></p>
      <hr/>
    </li>`;
  }
  var listTask = function() {
    return {
      init: function() {
        var theTemplateScript = jQuery("#list-task-template").html();
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
        console.log(_data_tasks);
        var data = {
            tasks: _data_tasks,
            assign_to: _data_assign_to
        };
        console.log(data);
        var theCompiledHtml = theTemplate(data);
        // Add the compiled html to the page
        jQuery('.list-tasks').html(theCompiledHtml);
        jQuery('.list-tasks').on('click', '.remove_button', function(e){
            e.preventDefault();

            if(this.hasAttribute('data-db-id')) {
              console.log(jQuery(this).attr('data-db-id'));
              console.log('remove this to db');
            }
            //jQuery(this).parents('li').remove(); //Remove field html
        });
      }
    };
  }();

  var AddTask = function() {
		return {
			init: function(){
        // Grab the template script
        var theTemplateScript = jQuery("#add-yearbook-plan").html();

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
        jQuery('.content-placeholder').html(theCompiledHtml);

        initDatePicker('.content-placeholder');

        jQuery('#app-lists').on('click', '.add-block', function(){
          block_index++;
          jQuery(this).parent().find('.add-block-content').append(_block_template(block_index, index_fields));
          jQuery.each(data.assign_to, function (i, item) {
              jQuery('.block-item-' + block_index + ' .assign_to').append(new Option(item.name, item.id));
          });
          initDatePicker('.block-item-' + block_index);
          jQuery('#collapseItem-'+index_fields+' .add-block-content').sortable({
            items: '> .row-blocks',
            handle: '.ui-sortable-handle-main'
          });
        });

        jQuery('.add-page').on('click',function(){
          block_index = 0;
          index_fields++;
          jQuery('#app-lists').append(repeat_fields(index_fields));
          initDatePicker('#app-lists');
          jQuery.each(data.assign_to, function (i, item) {
              jQuery('#app-lists').find('.assign_to').append(new Option(item.name, item.id));
          });
        });
        //Once remove button is clicked
        jQuery('#app-lists').on('click', '.remove_button', function(e){
          e.preventDefault();
          if(this.hasAttribute('data-db-id')) {
            console.log(jQuery(this).attr('data-db-id'));
            console.log('remove this to db');
          }
          index_fields--;
          jQuery(this).parents('li').remove(); //Remove field html
        });

        jQuery('#app-lists').on('click', '.remove_block', function(e){
          e.preventDefault();
          console.log('remove block');
          var block_index = jQuery(this).attr('data-index-item');
          jQuery('.row-item-index-' + block_index).remove(); //Remove field html
        });

        jQuery('#app-lists').sortable({
          placeholder: "ui-state-highlight",
          handle: '.ui-sortable-handle-main'
        });

      }//init: function()
		};
	}();

  jQuery(function () {
    listTask.init();
    AddTask.init();
  });
</script>
