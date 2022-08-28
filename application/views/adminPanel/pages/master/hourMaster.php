<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

  // fetching city data
    $timeTableData = $this->db->query("SELECT * FROM " . Table::timeTableHoursTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status != '4'")->result_array();


    // edit and delete action
    if(isset($_GET['action']))
    {
      // fetch city for edit the  city 
      if($_GET['action'] == 'edit')
      {
        $editId = $_GET['edit_id'];
        $editHourData = $this->db->query("SELECT * FROM " . Table::timeTableHoursTable . " WHERE id='$editId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
      }

      // delete the city
      if($_GET['action'] == 'delete')
      {
        $deleteId = $_GET['delete_id'];
        $deleteHourData = $this->db->query("UPDATE " . Table::timeTableHoursTable . " SET status = '4' WHERE id='$deleteId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
        if($deleteHourData)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Hour Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Hour Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:3 ".base_url()."master/hourMaster");
      }

      if($_GET['action'] == 'status')
      {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::timeTableHoursTable . " SET status = '$status'  WHERE id = '$updateId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if($updateStatus)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Hour Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Hour Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:3 ".base_url()."master/hourMaster");
      }


    }


    // insert new city
    if(isset($_POST['submit']))
    {
      $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
      $start_time = $_POST['start_time'];
      $end_time = $_POST['end_time'];

      $alreadyTimeTable = $this->db->query("SELECT * FROM " . Table::timeTableHoursTable . " WHERE start_time = '$start_time' AND end_time = '$end_time' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();

      if(!empty($alreadyTimeTable))
      {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'This Hour is already inserted, Please Edit That',
          ];
          $this->session->set_userdata($msgArr);
          header('Location: hourMaster');
          exit(0);
      }




      $insertNewTime = $this->db->query("INSERT INTO " . Table::timeTableHoursTable . " (schoolUniqueCode, start_time, end_time) VALUES ('$schoolUniqueCode','$start_time','$end_time')");
      if($insertNewTime)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Time Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Time Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:3 ".base_url()."master/hourMaster");
    }

    // update exiting city
    if(isset($_POST['update']))
    {
      $start_time = $_POST['start_time'];
      $end_time = $_POST['end_time'];
      $updateHourId = $_POST['updateHourId'];
      $updateHour = $this->db->query("UPDATE " . Table::timeTableHoursTable . " SET start_time = '$start_time', end_time = '$end_time' WHERE id = '$updateHourId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
      if($updateHour)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Hour Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Hour Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:3 ".base_url()."master/hourMaster");
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
                  <h3 class="card-title">Add / Edit Hours</h3>
                  
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                    <?php 
                    if(isset($_GET['action']) && $_GET['action'] == 'edit')
                    {?>
                     <input type="hidden" name="updateHourId" value="<?=$editId?>">
                    <?php }
                    
                    ?>
                      <div class="row">
                        <div class="form-group col-md-3">
                          <input type="time" name="start_time" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editHourData[0]['start_time'];}?>" class="form-control" id="name"  required>
                        </div>
                        <div class="form-group col-md-3">
                          <input type="time" name="end_time" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editHourData[0]['end_time'];}?>" class="form-control" id="name"  required>
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
                      <h3 class="card-title">Showing All Hours Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="hourDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <!-- <th>Hours Id</th> -->
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($timeTableData)) {
                            $i = 0;
                            foreach ($timeTableData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <!-- <td><?= $cn['id'];?></td> -->
                                <td><?= $cn['start_time'];?></td>
                                <td><?= $cn['end_time'];?></td>
                                <td>
                                <a href="?action=status&edit_id=<?= $cn['id'];?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1';?>"
                                    class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger';?>">
                                    <?php  echo ($cn['status'] == '1')? 'Active' : 'Inactive';?>
                                </td>
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

    $("#hourDataTable").DataTable();
  </script>