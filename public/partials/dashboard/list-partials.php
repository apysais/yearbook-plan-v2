<?php if(isset($tasks['Overdue'])){ ?>
  <div class="task-list task-overdue">
      <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Overdue</h6>
        <?php foreach($tasks['Overdue'] as $k => $v) { ?>
          <div class="media text-muted pt-3">
            <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
              <a href="<?php echo YB_Page_List::get_instance()->createNonceUrlEditTask($v['id'], $v['assign_to']);?>">
                <?php echo $v['title'];?>
              </a>
              <span class="float-right badge badge-danger">
                <?php echo date("F j, Y", strtotime($v['due_date']));?>
              </span>
            </p>
          </div>
        <?php } ?>
      </div>
  </div>
<?php } ?>
<?php if(isset($tasks['Today'])){ ?>
  <div class="task-list task-today">
      <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Today</h6>
        <?php foreach($tasks['Today'] as $k => $v) { ?>
          <div class="media text-muted pt-3">
            <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
              <a href="<?php echo YB_Page_List::get_instance()->createNonceUrlEditTask($v['id'], $v['assign_to']);?>">
                <?php echo $v['title'];?>
              </a>
            </p>
          </div>
        <?php } ?>
      </div>
  </div>
<?php } ?>
<?php if(isset($tasks['Upcoming'])){ ?>
  <div class="task-list task-overdue">
      <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Upcoming</h6>
        <?php foreach($tasks['Upcoming'] as $k => $v) { ?>
          <div class="media text-muted pt-3">
            <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
              <a href="<?php echo YB_Page_List::get_instance()->createNonceUrlEditTask($v['id'], $v['assign_to']);?>">
                <?php echo $v['title'];?>
              </a>
              <span class="float-right badge badge-info">
                <?php echo date("F j, Y", strtotime($v['due_date']));?>
              </span>
            </p>
          </div>
        <?php } ?>
      </div>
  </div>
<?php } ?>
<?php if(isset($tasks['done'])){ ?>
  <div class="task-list task-today">
      <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Done</h6>
        <?php foreach($tasks['done'] as $k => $v) { ?>
          <div class="media text-muted pt-3">
            <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
              <a href="<?php echo YB_Page_List::get_instance()->createNonceUrlEditTask($v['id'], $v['assign_to']);?>">
                <?php echo $v['title'];?>
              </a>
            </p>
          </div>
        <?php } ?>
      </div>
  </div>
<?php } ?>
<?php if(isset($tasks['submitted'])){ ?>
  <div class="task-list task-submitted">
      <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">Done</h6>
        <?php foreach($tasks['submitted'] as $k => $v) { ?>
          <div class="media text-muted pt-3">
            <p class="media-body pb-3 mb-0 lh-125 border-bottom border-gray">
              <a href="<?php echo YB_Page_List::get_instance()->createNonceUrlEditTask($v['id'], $v['assign_to']);?>">
                <?php echo $v['title'];?>
              </a>
            </p>
          </div>
        <?php } ?>
      </div>
  </div>
<?php } ?>
