<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

    // fetching city data
    $subjectData = $this->db->query("SELECT * FROM " . Table::subjectTable . " WHERE status = 1 ORDER BY id DESC")->result_array();

    // class data
    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status = 1")->result_array();

    // section data
    $sectionData = $this->db->query("SELECT * FROM " . Table::sectionTable . " WHERE status = 1")->result_array();

    // hours data
    $hoursTableData = $this->db->query("SELECT * FROM " . Table::timeTableHoursTable . " WHERE status = 1 AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

    // teachers data
    $teachersData = $this->db->query("SELECT id,CONCAT(name, ' - ',user_id) as userName FROM " . Table::teacherTable . " WHERE status = 1 AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ORDER BY id DESC")->result_array();

    // week data
    $weekData = $this->db->query("SELECT * FROM " . Table::weekTable . " WHERE status = 1")->result_array();

    // shedule data
     $sheduleData = $this->db->query("
     SELECT cST.shedule_json, cST.id, ct.className,st.sectionName,cST.status FROM " . Table::classSheduleTable . " cST 
     JOIN ".Table::classTable." ct ON ct.id = cST.class_id
     JOIN ".Table::sectionTable." st ON st.id = cST.section_id 
     WHERE cST.status != '4' AND cST.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
     ORDER BY cST.id DESC
     ")->result_array();


    // edit and delete action
    if (isset($_GET['action'])) {
      // fetch city for edit the  city 
      if ($_GET['action'] == 'edit') {
        $editId = $_GET['edit_id'];
        $editClassShedule = $this->db->query("SELECT * FROM " . Table::classSheduleTable . " 
        WHERE id = '$editId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ORDER BY id DESC LIMIT 1")->result_array();
        //HelperClass::prePrintR($editClassShedule);
      }

      // delete the city
      if ($_GET['action'] == 'delete') {
        $deleteId = $_GET['delete_id'];
        $deleteCityData = $this->db->query("UPDATE " . Table::classSheduleTable . " SET status = '4' WHERE id='$deleteId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
        if ($deleteCityData) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Class Time Table Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Class Time Table Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:3 " . base_url() . "master/timeTableSheduleMaster");
      }

      if($_GET['action'] == 'status')
      {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::classSheduleTable . " SET status = '$status' WHERE id = '$updateId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if($updateStatus)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Class Time Table Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Class Time Table Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:3 " . base_url() . "master/timeTableSheduleMaster");
      }
    }


    // insert new city
    if (isset($_POST['submit'])) {
     
    
        $jsonArr = [];
        if(isset($_POST['timeHour']))
        {
            $subArr = [];
            $subArr['class'] = $_POST['class'];
            $subArr['section'] = $_POST['section'];

            $alreadyTimeTable = $this->db->query("SELECT * FROM " . Table::classSheduleTable . " WHERE class_id = '{$_POST['class']}' AND section_id = '{$_POST['section']}'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

            if(!empty($alreadyTimeTable))
            {
                $msgArr = [
                  'class' => 'danger',
                  'msg' => 'This Class Time Table is already inserted, Please Edit That',
                ];
                $this->session->set_userdata($msgArr);
                header('Location: timeTableSheduleMaster');
                exit(0);
            }


            $subArr['timeTable'] = [];
            for($i=0;$i < count($_POST['timeHour']); $i++)
            {
              $timeTableArr = [];
              $timeTableArr['time'] = $_POST['timeHour'][$i];
              $timeTableArr['subject'] = $_POST['subjects'][$i];
              $timeTableArr['teacher'] = $_POST['teacherId'][$i];
              array_push($subArr['timeTable'],$timeTableArr);
            }
          $jsonArr[] = $subArr;
        }

        $obj = json_encode($jsonArr[0]['timeTable']);

        //HelperClass::prePrintR($obj);

        $cClassId = $jsonArr[0]['class'];
        $sSectionId = $jsonArr[0]['section'];
        $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
       $insertClassShedule = $this->db->query("INSERT INTO " . Table::classSheduleTable . " (schoolUniqueCode,class_id,section_id,shedule_json)
        VALUES ('$schoolUniqueCode','$cClassId','$sSectionId','$obj')");

      if ($insertClassShedule) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Time Table Inserted Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Time Table Not Inserted Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:3 " . base_url() . "master/timeTableSheduleMaster");
    }

    // update exiting city
    if (isset($_POST['update'])) {

      $updateSheduleId= $_POST['updateSheduleId'];
      $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
      $jsonArr = [];
      if(isset($_POST['timeHour']))
      {
          $subArr = [];
          $subArr['class'] = $_POST['class'];
          $subArr['section'] = $_POST['section'];
          $subArr['timeTable'] = [];
          for($i=0;$i < count($_POST['timeHour']); $i++)
          {
            $timeTableArr = [];
            $timeTableArr['time'] = $_POST['timeHour'][$i];
            $timeTableArr['subject'] = $_POST['subjects'][$i];
            $timeTableArr['teacher'] = $_POST['teacherId'][$i];
            array_push($subArr['timeTable'],$timeTableArr);
          }
        $jsonArr[] = $subArr;
      }

      $obj = json_encode($jsonArr[0]['timeTable']);

      //HelperClass::prePrintR($obj);

      $cClassId = $jsonArr[0]['class'];
      $sSectionId = $jsonArr[0]['section'];


      $teacherSubjectsEditId = $this->db->query("UPDATE " . Table::classSheduleTable . " SET class_id = '$cClassId',section_id = '$sSectionId', shedule_json = '$obj' WHERE id = '$updateSheduleId' AND schoolUniqueCode = '$schoolUniqueCode'");
      if ($teacherSubjectsEditId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Time Table Updated for This Class Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Time Table Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:3 " . base_url() . "master/timeTableSheduleMaster");
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
              if (!empty($this->session->userdata('msg'))) { ?>

                <div class="alert alert-<?= $this->session->userdata('class') ?> alert-dismissible fade show" role="alert">
                  <strong>New Message!</strong> <?= $this->session->userdata('msg') ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php
                $this->session->unset_userdata('class');
                $this->session->unset_userdata('msg');
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
                  <h3 class="card-title">Add / Edit Class Shedule</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                   
                      
                      <div class="row">
                        <div class="form-group col-md-12">
                          <label>Select Teacher </label>
                        
                        </div>

                        <div class="form-group col-md-12">

                          <div class="row">
                            <table class="table table-stripred table-border">
                              <thead>
                                <tr>
                                  <th>Class</th>
                                  <th>Section</th>
                                  <th>Hours</th>
                                  <th>Subject</th>
                                  <th>Teacher</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>


                                <?php

                               ?>
                                           <form method="post" action="">
                                           <?php
                                            if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
                                              <input type="hidden" name="updateSheduleId" value="<?= $editId ?>">
                                            <?php }

                                            ?>
                                           <!-- <input type="hidden" name="teacherId" id="teacherInput"> -->
                                    <tr>
                                      <td scope="row">
                                      <select name="class" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <option value="" selected>Class</option>
                                                <?php  foreach ($classData as $class) { 
                                                  $classSelected = '';
                                                    if(isset($editClassShedule))
                                                    {
                                                      if($editClassShedule[0]['class_id'] == $class['id'])
                                                      {
                                                        $classSelected = 'selected';
                                                      }else
                                                      {
                                                        $classSelected = '';
                                                      }
                                                    }
                                                  
                                                  
                                                  ?>
                                                  <option <?=$classSelected?> value="<?= $class['id'] ?>"><?= $class['className'] ?></option>
                                                <?php  } ?>
                                      </select>
                                      </td>
                                      <td>
                                      <select name="section" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                                          <option value="" selected>Section</option>
                                        <?php  
                                        $sectionSelected = '';
                                        foreach ($sectionData as $section) { 
                                          if(isset($editClassShedule))
                                          {
                                            if($editClassShedule[0]['section_id'] == $section['id'])
                                            {
                                              $sectionSelected = 'selected';
                                            }else
                                            {
                                              $sectionSelected = '';
                                            }
                                          }
                                          
                                          ?>
                                               <option <?=$sectionSelected?> value="<?= $section['id'] ?>"><?= $section['sectionName'] ?></option>
                                      <?php } ?>
                                      </select>
                                      </td>

                                      <td>
                                        <?php foreach ($hoursTableData as $hours) { ?>
                                            <input type="hidden" name="timeHour[]" class="form-check-input" value="<?= $hours['id'] ?>">
                                            <span class="h2"><?= $hours['start_time'] . " - " . $hours['end_time'] ?></span>
                                        </br></br>
                                        <?php } ?>
                                      </td>

                                      <td>
                                          <?php

                                              if(isset($editClassShedule))
                                              {
                                                $editJsonData = json_decode($editClassShedule[0]['shedule_json'],TRUE);
                                              }


                                          if (isset($subjectData)) {
                                            $subjectSelected = '';
                                            for ($i = 0; $i < count($hoursTableData); $i++)
                                             { 
                                              
                                              ?>
                                             <select name="subjects[]" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                                             <option value="" selected>Subjects</option>
                                             <?php  foreach ($subjectData as $sub) { 
                                              
                                                if(isset($editJsonData))
                                                {
                                                  if($editJsonData[$i]['subject'] == $sub['id'])
                                                  {
                                                    $subjectSelected = 'selected';
                                                  }else
                                                  {
                                                    $subjectSelected = '';
                                                  }
                                                }
                                                
                                              
                                              ?>
                                          <option <?=$subjectSelected?> value="<?= $sub['id'] ?>"><?= $sub['subjectName'] ?></option>
                                          <?php } ?>
                                              </select></br>
                                           <?php } } ?>
                                      </td>
                                      <td>
                                            <?php
                                            if (isset($teachersData)) {
                                              $teacherSelected = '';
                                              for ($i = 0; $i < count($hoursTableData); $i++)
                                              { ?>
                                                <select name="teacherId[]" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <option value="" selected>Teachers</option>
                                             <?php foreach ($teachersData as $techers) {
                                              
                                                    if(isset($editJsonData))
                                                    {
                                                      if($editJsonData[$i]['teacher'] == $techers['id'])
                                                      {
                                                        $teacherSelected = 'selected';
                                                      }else
                                                      {
                                                        $teacherSelected = '';
                                                      }
                                                    }
                                            ?>
                                                <option <?= $teacherSelected ?> value="<?= $techers['id'] ?>"><?= $techers['userName'] ?></option>
                                            <?php 
                                            } ?>
                                            </select></br>
                                        <?php } } ?>
                                         
                                      </td>
                                    
                                      <td>
                                      <button type="submit" name="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                        echo 'update';
                                      } else {
                                        echo 'submit';
                                      } ?>" class="btn btn-primary mt-4">Submit</button>
                                        </form>
                                      </td>
                                    </tr>
                                   
                                <?php

                                
                                ?>

                              </tbody>
                            </table>
                          </div>
                        </div>
                        <div class="form-group col-md-3">
                          <!-- <button type="submit" name="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                        echo 'update';
                                                      } else {
                                                        echo 'submit';
                                                      } ?>" class="btn btn-primary mt-4">Submit</button> -->
                        </div>
                      </div>
                    <!-- </form> -->
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
                      <h3 class="card-title">Showing All Class Shedule Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="timeTableDataTable" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Class Name</th>
                            <th>Section Names</th>
                            <th>Shedule</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($sheduleData)) {
                            $j = 0;

                            foreach ($sheduleData as $cn) { ?>
                              <tr>
                                <td><?= ++$j; ?></td>
                                <td><?= $cn['className']; ?></td>
                                <td><?= $cn['sectionName']; ?></td>
                                <td><?php

                                    if (isset($cn['shedule_json'])) {
                                      $sheduleJsonArr =  json_decode($cn['shedule_json'], TRUE);
                                    }

                                    if (isset($sheduleJsonArr)) {
                                    
                                      foreach ($sheduleJsonArr as $subArr) {
                                        $timeArr = $this->db->query("SELECT * FROM " . Table::timeTableHoursTable . " WHERE status = 1 AND id='{$subArr['time']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

                                        $subectArr = $this->db->query("SELECT * FROM " . Table::subjectTable . " WHERE status = 1 AND id='{$subArr['subject']}'")->result_array();

                                        $teacherArr = $this->db->query("SELECT * FROM " . Table::teacherTable . " WHERE status = 1 AND id='{$subArr['teacher']}'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

                                        for($a=0;$a<count($timeArr);$a++)
                                        {
                                           echo "<span class='btn btn-outline-primary'> Time : " . 
                                           $timeArr[$a]['start_time'] . " - " . $timeArr[$a]['end_time'] . "  </span> ";

                                           echo "<span class='btn btn-outline-info ml-2' > Subject : " . 
                                           $subectArr[$a]['subjectName'] . "  </span> ";

                                           echo "<span class='btn btn-outline-danger ml-2' > Teacher : " . 
                                           $teacherArr[$a]['name'] . " - " . $teacherArr[$a]['user_id']
                                           . "  </span> ";

                                           echo "<br><br>";
                                        }
                                        
                                      }
                                    }
                                    ?>
                                </td>
                                <td>
                                      <a href="?action=status&edit_id=<?= $cn['id'];?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1';?>"
                                          class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger';?>">
                                          <?php  echo ($cn['status'] == '1')? 'Active' : 'Inactive';?>
                                </td>
                                <td>
                                  
                                  <a href="?action=edit&edit_id=<?= $cn['id']; ?>" class="btn btn-warning">Edit</a>
                                  <a href="?action=delete&delete_id=<?= $cn['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this?');">Delete</a>
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
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    // var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';


    $("#timeTableDataTable").DataTable({
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
       dom: 'lBfrtip',
       buttons: [
           'copyHtml5',
           'excelHtml5',
           'csvHtml5',
           'pdfHtml5'
       ],
       lengthMenu: [10,50,100,500,1000,2000,5000,10000,50000,100000],
       pageLength: 10,
    });



    function changeId(x)
    {
      console.log(x.value);
      $("#teacherInput").val(x.value);
    }

  </script>