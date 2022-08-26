<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src="<?= $data['adminPanelUrl'] ?>dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Admin</span>
  </a>

  <?php

$currentPage = $_SERVER['PATH_INFO'];


  $this->load->library('session');

  $parentMenu = $this->db->query("SELECT * FROM " . Table::adminPanelMenuTable . " WHERE is_parent = 1 AND status = '1'")->result_array();

  $childMenu = $this->db->query("SELECT * FROM " . Table::adminPanelMenuTable . " WHERE is_child = 1 AND status = '1'")->result_array();

  $exitingPermission = $this->db->query("SELECT permissions FROM " . Table::panelMenuPermissionTable . " WHERE user_id = '{$_SESSION['id']}' AND user_type = '{$_SESSION['user_type']}' AND status = '1'")->result_array();

  $exitingPermission = json_decode( $exitingPermission[0]['permissions'],TRUE);
  ?>
  <!-- Sidebar -->
  <div class="sidebar">


    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <?php
        $active = '';
        if (isset($parentMenu)) {
          foreach ($parentMenu as $pM) { 
            ?>

            <li class="nav-item menu-open">
              <a href="#" class="nav-link active">
                <i class="nav-icon <?= $pM['icon']; ?>"></i>
                <p>
                  <?= $pM['name']; ?>
                  <i class="right fa-solid fa-arrow-down"></i>
                </p>
              </a>
              <?php
              if (isset($childMenu)) {
                foreach ($childMenu as $cM) {

                  if ($pM['id'] == $cM['parent_id']) {

                    if($currentPage == '/'.$cM['link'])
                    {
                      $active = 'active';
                    }else
                    {
                      $active = '';
                    }
                    
                    if(isset($exitingPermission))
                    {
                     
                      for($i=0;$i<count($exitingPermission);$i++)
                      {
                        if($exitingPermission[$i] == $cM['id'])
                        {
                          $display = 'block';
                           break;
                        }else
                        {
                          $display = 'none';
                        }
                      }
                    }

              ?>
                    <ul class="nav nav-treeview">
                      <li class="nav-item" style="display:<?=$display?>">
                        <a href="<?= base_url($cM['link']) ?>" class="nav-link <?= $active; ?>">
                          <i class="<?= $cM['icon']; ?> nav-icon"></i>
                          <p><?= $cM['name']; ?></p>
                        </a>
                      </li>
                    </ul>

              <?php
                  }
                }
              }

              ?>
            </li>
        <?php  }
        }

        ?>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>