<style>
  .big-checkbox {width: 1.5rem; height: 1.5rem;top:0.5rem}
</style>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');
    $this->load->model('CrudModel');

  // fetching city data
    $examNameSem = $this->db->query("SELECT * FROM " . Table::semExamNameTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND  status != '4' ORDER BY id DESC")->result_array();






    if(isset($_POST['submit']))
    {

      $sem_exam_name = $_POST['sem_exam_name'];
      $exam_year = $_POST['exam_year'];
      $start_date = $_POST['start_date'];
      $end_date = $_POST['end_date'];
      $schoolUniqueCode = $_SESSION['schoolUniqueCode'];

      $addSemester = $this->db->query("INSERT INTO " . Table::semExamNameTable . " (schoolUniqueCode,sem_exam_name,exam_year,start_date,end_date, session_table_id) VALUES ('$schoolUniqueCode','$sem_exam_name','$exam_year','$start_date', '$end_date','{$_SESSION['currentSession']}')");
      if($addSemester)
      {
      
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Semester Exam Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Semester Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."semester/semesterMaster");

    }





    // edit and delete action
    if(isset($_GET['action']))
    {
   
      if($_GET['action'] == 'edit')
      {
        $editId = $_GET['edit_id'];
        $editExamSem = $this->db->query("SELECT * FROM " . Table::semExamNameTable . " WHERE id='$editId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
      }


      if($_GET['action'] == 'delete')
      {
        $deleteId = $_GET['delete_id'];
        $deletesectionData = $this->db->query("UPDATE " . Table::semExamNameTable . " SET status = '4' WHERE id='$deleteId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
        if($deletesectionData)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Semester Exam Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Semester Exam Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."semester/semesterMaster");
      }

      if($_GET['action'] == 'status')
      {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::semExamNameTable . " SET status = '$status' WHERE id = '$updateId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if($updateStatus)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Semester Exam Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Semester Exam Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."semester/semesterMaster");
      }
    }




    // update exiting section
    if(isset($_POST['update']))
    {
      $sem_exam_name = $_POST['sem_exam_name'];
      $exam_year = $_POST['exam_year'];
      $start_date = $_POST['start_date'];
      $end_date = $_POST['end_date'];
      $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
      $updateSemExamId = $_POST['updateSemExamId'];

      $updateSemExam = $this->db->query("UPDATE " . Table::semExamNameTable . " SET sem_exam_name = '$sem_exam_name',exam_year = '$exam_year', start_date = '$start_date', end_date = '$end_date' WHERE id = '$updateSemExamId'   AND schoolUniqueCode = '$schoolUniqueCode'");

      if($updateSemExam)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Semester Exam Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Semester Exam Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."semester/semesterMaster");
    }




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
              { 
                if($this->session->userdata('class') == 'success')
                 {
                   HelperClass::swalSuccess($this->session->userdata('msg'));
                 }else if($this->session->userdata('class') == 'danger')
                 {
                   HelperClass::swalError($this->session->userdata('msg'));
                 }
                
                ?>

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
            <div class="col-md-12 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add New Semester Exam</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                    <?php 
                    if(isset($_GET['action']) && $_GET['action'] == 'edit')
                    {?>
                     <input type="hidden" name="updateSemExamId" value="<?=$editId?>">
                    <?php }
                    
                    ?>
                      <div class="row">

                        <div class="form-group col-md-6 mx-auto mb-2">
                          <label>Semester Exam Name</label>
                          <input type="text" name="sem_exam_name"  value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editExamSem[0]['sem_exam_name'];}?>" class="form-control" id="name" placeholder="eg. Half Yearly, Final Exam" required>
                        </div>
                        <div class="form-group col-md-6 mx-auto mb-2">
                        <label>Exam Year</label>
                       
                        <select  id="sId" class="form-control  select2 select2-danger" name="exam_year"  data-dropdown-css-class="select2-danger" style="width: 100%;" required>
                        <option></option>
                            <?php 
                            $selected = '';
                            foreach(HelperClass::sessionYears as $sd => $val)
                              {  
                                
                                if(isset($_GET['action']) && $_GET['action'] == 'edit')
                                {
                                  if($editExamSem[0]['exam_year'] == $val)
                                  {
                                    $selected = 'selected';
                                  }else
                                  {
                                    $selected = '';
                                  }
                                }
                                
                                
                                ?>
                            <option <?=$selected?> value="<?=$val?>"><?=$val?></option>
                          <?php }  ?>   
                        </select>
                        </div>
                        <div class="form-group col-md-6 mx-auto mb-2">
                        <label>Exam Start Date</label>
                          <input type="date"  name="start_date" class="form-control" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editExamSem[0]['start_date'];}?>"  required>
                        </div>
                        <div class="form-group col-md-6 mx-auto mb-2">
                        <label>Exam End Date</label>
                          <input type="date"  name="end_date" class="form-control" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editExamSem[0]['end_date'];}?>" required>
                        </div>
                        <div class="form-group col-md-12 my-3">
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
                      <h3 class="card-title">Showing All Semester Exams</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="cityDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Sem Exam Id</th>
                            <th>Sem Exam Name</th>
                            <th>Exam Start Date</th>
                            <th>Exam End Date</th>
                            <th>Exam Year</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($examNameSem)) {
                            $i = 0;
                            foreach ($examNameSem as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $cn['id'];?></td>
                                <td><?= $cn['sem_exam_name'];?></td>
                                <td><?= $cn['start_date'];?></td>
                                <td><?= $cn['end_date'];?></td>
                                <td><?= $cn['exam_year'];?></td>
                                <td>
                                <a href="?action=status&edit_id=<?= $cn['id'];?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1';?>"
                                    class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger';?>">
                                    <?php  echo ($cn['status'] == '1')? 'Active' : 'Inactive';?>
                                </td>
                                <td><?= date('d-m-Y H:i:A', strtotime($cn['created_at']));?></td>
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