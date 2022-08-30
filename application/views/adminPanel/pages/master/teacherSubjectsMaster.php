<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

  // fetching city data
    $teachersSubjetsData = $this->db->query("SELECT DISTINCT(tst.teacher_id), tst.status, tst.id,tst.subject_ids, CONCAT(tt.name, ' - ' ,tt.user_id) teacherName,tt.id as teacherId FROM " . Table::teacherSubjectsTable . " tst 
    LEFT JOIN ".Table::teacherTable." tt ON tst.teacher_id = tt.id AND tt.schoolUniqueCode = ".$_SESSION['schoolUniqueCode']." 
    WHERE tst.status != '4'  AND tst.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
     ORDER BY tst.id DESC")->result_array();

    $teachersData = $this->db->query("SELECT id,CONCAT(name, ' - ',user_id) as userName FROM " . Table::teacherTable . " WHERE status = 1 AND schoolUniqueCode = ".$_SESSION['schoolUniqueCode']." ORDER BY id DESC")->result_array();


    $subjectsData = $this->db->query("SELECT id, subjectName FROM " . Table::subjectTable . " WHERE status = 1 ORDER BY id DESC")->result_array();

    // edit and delete action
    if(isset($_GET['action']))
    {
      // fetch city for edit the  city 
      if($_GET['action'] == 'edit')
      {
        $editId = $_GET['edit_id'];
        $editTeachersSubjectData = $this->db->query("SELECT DISTINCT(tst.teacher_id), tst.id,tst.subject_ids, CONCAT(tt.name, ' - ' ,tt.user_id) teacherName,tt.id as teacherId FROM " . Table::teacherSubjectsTable . " tst 
        LEFT JOIN ".Table::teacherTable." tt ON tst.teacher_id = tt.id AND tt.schoolUniqueCode = ".$_SESSION['schoolUniqueCode']." 
        WHERE tst.status = 1 AND tst.id = '$editId' AND tst.status != '4'  AND tst.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
        ORDER BY tst.id DESC LIMIT 1")->result_array();

        
      }

      // delete the city
      if($_GET['action'] == 'delete')
      {
        $deleteId = $_GET['delete_id'];
        $deleteCityData = $this->db->query("UPDATE " . Table::teacherSubjectsTable . " SET status = '4' WHERE id='$deleteId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
        if($deleteCityData)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Teachers Subjects Data Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Teachers Subjects Data Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:3 ".base_url()."master/teacherSubjectsMaster");
      }

      if($_GET['action'] == 'status')
      {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::teacherSubjectsTable . " SET status = '$status' WHERE id = '$updateId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if($updateStatus)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Teachers Subjects Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Teachers Subjects Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:3 ".base_url()."master/teacherSubjectsMaster");
      }

    }


    // insert new city
    if(isset($_POST['submit']))
    {
      // HelperClass::prePrintR($_POST);

      $teacherId = $_POST['teacherId'];
      $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
      $alreadyTeacherSubjects = $this->db->query("SELECT * FROM " . Table::teacherSubjectsTable . " WHERE teacher_id = '$teacherId' AND schoolUniqueCode = '$schoolUniqueCode'")->result_array();

      if(!empty($alreadyTeacherSubjects))
      {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'This Teacher\'s Subject is already inserted, Please Edit That',
          ];
          $this->session->set_userdata($msgArr);
          header('Location: teacherSubjectsMaster');
          exit(0);
      }


      $subjectIds = json_encode($_POST['subjectIds']);

      $insertTeacherSubjects = $this->db->query("INSERT INTO " . Table::teacherSubjectsTable . " (schoolUniqueCode,teacher_id,subject_ids) VALUES ('$schoolUniqueCode','$teacherId','$subjectIds')");
      if($insertTeacherSubjects)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Subjects Updated for Teacher Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Subejcts Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:3 ".base_url()."master/teacherSubjectsMaster");
    }

    // update exiting city
    if(isset($_POST['update']))
    {
      $teacherId = $_POST['teacherId'];
      $subjectIds = json_encode($_POST['subjectIds']);
      $teacherSubjectsEditId = $_POST['updateCityId'];
      $updateCity = $this->db->query("UPDATE " . Table::teacherSubjectsTable . " SET subject_ids = '$subjectIds' WHERE id = '$teacherSubjectsEditId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
      if($teacherSubjectsEditId)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Subjects Updated for Teacher Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Subejcts Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:3 ".base_url()."master/teacherSubjectsMaster");
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
            <div class="col-md-10 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add / Edit Teachers Subjects</h3>
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
                      <div class="form-group col-md-3">
                        <label>Select Teacher </label>
                        <select name="teacherId" class="form-control  select2 select2-danger" required  data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 
                          if(isset($teachersData))
                          {
                            $selectedTeacher = '';
                            foreach($teachersData as $techers)
                            { 
                              if(isset($editTeachersSubjectData) && $editTeachersSubjectData[0]['teacher_id'] == $techers['id'])
                              {
                                $selectedTeacher = 'selected';
                              }
                              ?>

                              
                              <option <?= $selectedTeacher?> value="<?=$techers['id']?>"><?=$techers['userName']?></option>
                           <?php }
                          }
                          
                          ?>
                          
                        </select>
                        </div>
                        <div class="form-group col-md-3">
                        <label>Select Subjects Multiple </label>
                        <select name="subjectIds[]" class="form-control  select2 select2-danger" multiple required  data-dropdown-css-class="select2-danger" style="width: 100%;">
                          <?php 
                          if(isset($subjectsData))
                          {
                            $selectedSubjects = '';
                            foreach($subjectsData as $subjects)
                            { 
                              if(isset($editTeachersSubjectData))
                              {
                                $subIds = json_decode($editTeachersSubjectData[0]['subject_ids'],TRUE);
                                foreach($subIds as $si)
                                {
                                  if($si == $subjects['id'])
                                  {
                                    $selectedSubjects = 'selected';
                                    break;
                                  }else
                                  {
                                    $selectedSubjects = '';
                                  }
                                }
                              }

                              ?>
                              <option <?= $selectedSubjects?> value="<?=$subjects['id']?>"><?=$subjects['subjectName']?></option>
                           <?php }
                          }
                          
                          ?>
                          
                        </select>
                        </div>
                        <div class="form-group col-md-3">
                          <button type="submit" name="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo 'update';}else{echo 'submit';}?>" class="btn btn-primary mt-4">Submit</button>
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
                      <h3 class="card-title">Showing All Teachers Subjects Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="cityDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Teacher Name - User Id</th>
                            <th>Subjects Names</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($teachersSubjetsData)) {
                            $j = 0;
                            
                            foreach ($teachersSubjetsData as $cn) { ?>
                              <tr>
                                <td><?= ++$j;?></td>
                                <td><?= $cn['teacherName'];?></td>
                                <td><?php
                                
                                
                                  if(isset($cn['subject_ids']))
                                  {
                                   $subjectIdsArr =  json_decode($cn['subject_ids'],TRUE);
                                  }
                                  if(isset($subjectIdsArr))
                                  {
                                    $i = 0;
                                    foreach($subjectIdsArr as $subArr)
                                    {
                                        if(isset($subjectsData))
                                        {
                                         
                                          foreach($subjectsData as $sub)
                                          {
                                            
                                            if($sub['id'] == $subArr)
                                            {
                                              echo "<span class='btn btn-".HelperClass::colorClassType[HelperClass::uniqueI()]."' >".$sub['subjectName'] . " </span> " ; 
                                              
                                            }
                                           
                                          }
                                        }
                                       $i++;
                                    }
                                  }
                                  
                                 //$cn['cityName'];
                                 ?></td>
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