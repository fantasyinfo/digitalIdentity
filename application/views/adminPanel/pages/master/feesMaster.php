<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

  // fetching city data
    $feesData = $this->db->query("SELECT * FROM " . Table::feesTable . " ORDER BY id DESC")->result_array();

    // classes
    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status = '1' ORDER BY id DESC")->result_array();

    // edit and delete action
    if(isset($_GET['action']))
    {
      // fetch city for edit the  city 
      if($_GET['action'] == 'edit')
      {
        $editId = $_GET['edit_id'];
        $editCityData = $this->db->query("SELECT * FROM " . Table::feesTable . " WHERE id='$editId'")->result_array();
      }

      // delete the city
      if($_GET['action'] == 'delete')
      {
        $deleteId = $_GET['delete_id'];
        $deleteCityData = $this->db->query("DELETE FROM " . Table::feesTable . " WHERE id='$deleteId'");
        if($deleteCityData)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Fees Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Fees Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:3 ".base_url()."master/feesMaster");
      }

      if($_GET['action'] == 'status')
      {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::feesTable . " SET status = '$status' WHERE id = '$updateId'");

        if($updateStatus)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Fees Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Fees Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:3 ".base_url()."master/feesMaster");
      }

    }


    // insert new city
    if(isset($_POST['submit']))
    {
      $classId = $_POST['classId'];
      $feesAmt = $_POST['fees_amt'];

      $alreadyFees = $this->db->query("SELECT * FROM " . Table::feesTable . " WHERE class_id = '$classId'")->result_array();

      if(!empty($alreadyFees))
      {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'This Class Fees is already inserted, Please Edit That',
          ];
          $this->session->set_userdata($msgArr);
          header('Location: feesMaster');
          exit(0);
      }

      $insertNewFees = $this->db->query("INSERT INTO " . Table::feesTable . " (class_id,fees_amt) VALUES ('$classId','$feesAmt')");
      if($insertNewFees)
      {
      
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Fees Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Fees Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:3 ".base_url()."master/feesMaster");
    }

    // update exiting city
    if(isset($_POST['update']))
    {
      $classId = $_POST['classId'];
      $feesAmt = $_POST['fees_amt'];
      $cityEditId = $_POST['updateCityId'];
      $updateFees = $this->db->query("UPDATE " . Table::feesTable . " SET class_id = '$classId',fees_amt = '$feesAmt' WHERE id = '$cityEditId'");
      if($updateFees)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Fees Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Fees Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:3 ".base_url()."master/feesMaster");
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
                  <h3 class="card-title">Add / Edit Fees</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                    <?php 
                    if(isset($_GET['action']) && $_GET['action'] == 'edit')
                    {?>
                     <input type="hidden" name="updateCityId" value="<?=$editId?>">
                    <?php }
                    
                    ?>
                      <div class="row">
                      <div class="form-group col-md-6">
                        <label>Select Class </label>
                        <select name="classId" class="form-control  select2 select2-danger" required  data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 
                          if(isset($classData))
                          {
                            $selectedClass = '';
                            foreach($classData as $class)
                            { 
                              if(isset($editTeachersSubjectData) && $editTeachersSubjectData[0]['teacher_id'] == $class['id'])
                              {
                                $selectedClass = 'selected';
                              }
                              ?>
                              <option <?= $selectedClass?> value="<?=$class['id']?>"><?=$class['className']?></option>
                           <?php }
                          }
                          
                          ?>
                          
                        </select>
                        </div>
                        <div class="form-group col-md-6">
                        <label>Please Enter Fees Amount in Indian Rupees </label>
                          <input type="number" name="fees_amt" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['fees_amt'];}?>" class="form-control" id="name" placeholder="Enter Fees Amount" required>
                        </div>
                        <div class="form-group col-md-12">
                          <button type="submit" name="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo 'update';}else{echo 'submit';}?>" class="btn btn-primary btn-lg btn-block">Submit</button>
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
                      <h3 class="card-title">Showing All Class Fees</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="cityDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Fees Id</th>
                            <th>Class Name</th>
                            <th>Fees Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($feesData)) {
                            $i = 0;
                            foreach ($feesData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $cn['id'];?></td>
                                <td><?php $cf = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE id='{$cn['class_id']}' AND status = '1'")->result_array();
                                echo $cf[0]['className'];?>
                                </td>
                                <td>₹ <?= number_format($cn['fees_amt'],2);?>/- </td>
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
    // var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';


    $("#cityDataTable").DataTable();
  </script>