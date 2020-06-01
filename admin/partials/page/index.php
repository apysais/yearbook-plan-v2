<div class="bootstrap-iso">
  <div class="wrap">
    <div class="container-fluid">
      <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
          <!--<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Dashboard</a>0-->
          <!--<a class="nav-item nav-link invinsible" id="nav-profile-tab" data-toggle="tab" href="#nav-settings" role="tab" aria-controls="nav-settings" aria-selected="false">Settings</a>-->
        </div>
      </nav>
      <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
          <div class="wrap">
            <?php YB_View::get_instance()->admin_partials('partials/page/dashboard.php', $data); ?>
          </div>
        </div>
        <!--<div class="tab-pane fade invinsible" id="nav-settings" role="tabpanel" aria-labelledby="nav-settings-tab">
          <div class="wrap">
            <?php// YB_View::get_instance()->admin_partials('partials/page/settings.php', []); ?>
          </div>
        </div>-->
      </div>
    </div>
  </div>
</div>
