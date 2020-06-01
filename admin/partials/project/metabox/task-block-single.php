<div class="bootstrap-iso">
  <div class="allteams-sendtask form-group">
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="current-page-parent"></div>
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

        var ajaxGetCurrentPageParent = jQuery.ajax({
          url: rest_object.yearbook_api_url + 'get-task-single/' + current_post_id,
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
        var theCompiledHtml = theTemplate(data);
        // Add the compiled html to the page
        jQuery('.current-page-parent').html(theCompiledHtml);
        jQuery('.current-page-parent').find('.due_date').datepicker({
          dateFormat: 'yy-mm-dd'
        });
      }
    };
  }();

  jQuery(function () {
    currentParentPage.init();
  });
</script>
