<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

  // fetching city data
    $weekData = $this->db->query("SELECT * FROM " . Table::weekTable . " WHERE status = 1")->result_array();


    // edit and delete action
    if(isset($_GET['action']))
    {
      // fetch city for edit the  city 
      if($_GET['action'] == 'edit')
      {
        $editId = $_GET['edit_id'];
        $editweekData = $this->db->query("SELECT * FROM " . Table::weekTable . " WHERE id='$editId' AND status = 1")->result_array();
      }

      // delete the city
      if($_GET['action'] == 'delete')
      {
        $deleteId = $_GET['delete_id'];
        $deleteweekData = $this->db->query("DELETE FROM " . Table::weekTable . " WHERE id='$deleteId'");
        if($deleteweekData)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'week Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'week Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:3 ".base_url()."master/weekMaster");
      }


    }


    // insert new city
    if(isset($_POST['submit']))
    {
      $weekName = $_POST['weekName'];
      $insertNewCity = $this->db->query("INSERT INTO " . Table::weekTable . " (weekName) VALUES ('$weekName')");
      if($insertNewCity)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New week Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'week Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:3");
    }

    // update exiting city
    if(isset($_POST['update']))
    {
      $weekName = $_POST['weekName'];
      $weekEditId = $_POST['updateweekId'];
      $updateweek = $this->db->query("UPDATE " . Table::weekTable . " SET weekName = '$weekName' WHERE id = '$weekEditId'");
      if($updateweek)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'week Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'week Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:3");
    }


    // print_r($cityData);


    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <?php 
              if(!empty($this->session->userdata('msg')))
              {?>

              <div class="alert alert-<?=$this->session->userdata('class')?> alert-dismissible fade show" role="alert">
                <strong>New Message!</strong> <?=$this->session->userdata('msg')?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
              $this->session->unset_userdata('class') ;
              $this->session->unset_userdata('msg') ;
              }
              ?>
              <h1 class="m-0"><?= $data['pageTitle'] ?> </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><?= $data['pageTitle'] ?> </li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- left column -->
            <?php //print_r($data['class']);
            ?>
            <div class="col-md-10 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add / Edit week</h3>
                  
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                    <?php 
                    if(isset($_GET['action']) && $_GET['action'] == 'edit')
                    {?>
                     <input type="hidden" name="updateweekId" value="<?=$editId?>">
                    <?php }
                    
                    ?>
                      <div class="row">
                        <div class="form-group col-md-3">
                          <input type="text" name="weekName" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editweekData[0]['weekName'];}?>" class="form-control" id="name" placeholder="Enter week name" required>
                        </div>
                        <div class="form-group col-md-3">
                          <button type="submit" name="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo 'update';}else{echo 'submit';}?>" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
              </div>

              <div class="row">

                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All week Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="weekDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>week Id</th>
                            <th>week Name</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($weekData)) {
                            $i = 0;
                            foreach ($weekData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $cn['id'];?></td>
                                <td><?= $cn['weekName'];?></td>
                                <td>
                                  <a href="?action=edit&edit_id=<?= $cn['id'];?>" class="btn btn-warning">Edit</a>
                                  <a href="?action=delete&delete_id=<?= $cn['id'];?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this?');">Delete</a>
                                </td>
                              </tr>
                          <?php  }
                          } ?>

                        </tbody>

                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
                </div>
              </div>



              <!--/.col (right) -->
            </div>

            <!-- /.container-fluid -->
          </div>
          <!-- /.content -->
        </div>
      </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php");?>
  <!-- ./wrapper -->
  <script>
    // var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';


    $("#weekDataTable").DataTable();
  </script>