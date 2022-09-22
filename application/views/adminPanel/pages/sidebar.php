<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />



<?php
$dir = base_url().HelperClass::uploadImgDir;
@$schoolD = $this->db->query("SELECT * FROM ".Table::schoolMasterTable." WHERE unique_id = '{$_SESSION['schoolUniqueCode']}' ORDER BY id DESC LIMIT 1 ")->result_array();?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src="<?= @$dir.@$schoolD[0]['image'] ?>" alt="School Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"><?= @$schoolD[0]['school_name'] ?></span>
  </a>

  <?php



$checkPermission = (HelperClass::checkIfItsACEOAccount()) ? true : false;

$currentPage = $_SERVER['PATH_INFO'];



  $this->load->library('session');

  $parentMenu = $this->db->query("SELECT * FROM " . Table::adminPanelMenuTable . " WHERE is_parent = 1 AND status = '1'")->result_array();

  $childMenu = $this->db->query("SELECT * FROM " . Table::adminPanelMenuTable . " WHERE is_child = 1 AND status = '1'")->result_array();

  $exitingPermission = $this->db->query("SELECT permissions FROM " . Table::panelMenuPermissionTable . " WHERE user_id = '{$_SESSION['id']}' AND user_type = '{$_SESSION['user_type']}' AND status = '1'")->result_array();

  @$exitingPermission = json_decode( @$exitingPermission[0]['permissions'],TRUE);
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


            // select child menu and check if there is any parent menu located with it

            if(isset($exitingPermission) && !empty($exitingPermission))
            {
              $permissionIds = implode("','",$exitingPermission);

             $checkIfCurrentParentIdHasNoChildPermission =  $this->db->query($sqlTP = "SELECT count(1) as countTP FROM " . Table::adminPanelMenuTable . " WHERE is_child = '1' AND parent_id ='{$pM['id']}' AND id NOT IN ('$permissionIds') AND status = '1'")->result_array();


             $totalChildOFThisParent =  $this->db->query($sqlTC = "SELECT count(1) as countTC FROM " . Table::adminPanelMenuTable . " WHERE is_child = '1' AND parent_id ='{$pM['id']}' AND status = '1'")->result_array();

            //  echo $sqlTC;
             if(!empty($checkIfCurrentParentIdHasNoChildPermission) && !empty($totalChildOFThisParent))
             {
              if($totalChildOFThisParent[0]['countTC'] - $checkIfCurrentParentIdHasNoChildPermission[0]['countTP'] == '0')
              {
                continue;
              }
             
             }
            }
           

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
                    
                    if($checkPermission)
                    {
                      $display = 'block';
                    }else if (isset($exitingPermission))
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