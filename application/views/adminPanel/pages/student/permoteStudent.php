<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
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
          <?php
          $this->load->model('CrudModel');
          $this->load->model('StudentModel');


          if (isset($_POST['search'])) {

            $studentsData = $this->db->query("SELECT s.*,cl.className,cl.id as classId,sc.sectionName, sc.id as sectionId FROM " . Table::studentTable . " s
            INNER JOIN " . Table::classTable . " cl ON cl.id = s.class_id
            INNER JOIN " . Table::sectionTable . " sc ON sc.id = s.section_id
            WHERE s.status = '1'  AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
            AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
            AND s.class_id = '{$_POST['classId']}' AND s.section_id = '{$_POST['sectionId']}' ")->result_array();

            $sessionDataToShow = $this->db->query("SELECT * FROM " . Table::schoolSessionTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND id='{$_SESSION['currentSession']}' AND status = '1' LIMIT 1")->result_array()[0];

          }










          if(isset($_POST['submitClass']))
          {
            //HelperClass::prePrintR($_POST);
           $ids =  explode(",",$_POST['student_id']);
           
           $total =  count($ids);
            for($i=0; $i < $total; $i++ )
            {
              $dd = $this->CrudModel->showStudentFeesViaIdClassAndSection($ids[$i],$_POST['currentClassId'], $_POST['currentSectionId'],$_SESSION['schoolUniqueCode'],$_SESSION['currentSession']);

              $totalDueThisSession = $dd['totalDueNow'];

              // also check old session dues and add to fees dues
              $oldSessinDues = $this->CrudModel->showStudentOldSessionFeesDetails($ids[$i]);

              if(!empty($oldSessinDues))
              {
                $totalDueThisSession = $oldSessinDues[0]['fees_due'];
              }

              $insertPermoteHistory = $this->db->query("INSERT INTO " . Table::studentHistoryTable . " (schoolUniqueCode,student_id,old_session_id,session_table_id,currentClassId,currentSessionId,class_id,section_id,fees_due) VALUES ('{$_SESSION['schoolUniqueCode']}','$ids[$i]','{$_SESSION['currentSession']}','{$_POST['session_table_id']}','{$_POST['currentClassId']}','{$_POST['currentSectionId']}','{$_POST['class_id']}','{$_POST['section_id']}','$totalDueThisSession')");

        
            if ($insertPermoteHistory) {
              $updateStudent = $this->db->query("UPDATE " . Table::studentTable . " SET class_id = '{$_POST['class_id']}', section_id = '{$_POST['section_id']}' WHERE id = '$ids[$i]' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

            }

            }
            $msgArr = [
              'class' => 'success',
              'msg' => 'Class Permoted Successfully',
            ];
            $this->session->set_userdata($msgArr);
          header("Refresh:1 " . base_url() . "student/permoteStudent");
          }


          if (isset($_POST['submit'])) {

            $dd = $this->CrudModel->showStudentFeesViaIdClassAndSection($_POST['student_id'],$_POST['currentClassId'], $_POST['currentSectionId'],$_SESSION['schoolUniqueCode'],$_SESSION['currentSession']);

            $totalDueThisSession = $dd['totalDueNow'];


            $insertPermoteHistory = $this->db->query("INSERT INTO " . Table::studentHistoryTable . " (schoolUniqueCode,student_id,old_session_id,session_table_id,currentClassId,currentSessionId,class_id,section_id,fees_due) VALUES ('{$_SESSION['schoolUniqueCode']}','{$_POST['student_id']}','{$_SESSION['currentSession']}','{$_POST['session_table_id']}','{$_POST['currentClassId']}','{$_POST['currentSectionId']}','{$_POST['class_id']}','{$_POST['section_id']}','$totalDueThisSession')");

            if ($insertPermoteHistory) {
              $updateStudent = $this->db->query("UPDATE " . Table::studentTable . " SET class_id = '{$_POST['class_id']}', section_id = '{$_POST['section_id']}' WHERE id = '{$_POST['student_id']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

              if ($updateStudent) {
                $msgArr = [
                  'class' => 'success',
                  'msg' => 'Student Permoted Successfully',
                ];
                $this->session->set_userdata($msgArr);
              } else {
                $msgArr = [
                  'class' => 'danger',
                  'msg' => 'Student Not Permoted Due to this Error. ' . $this->db->last_query(),
                ];
                $this->session->set_userdata($msgArr);
              }
              header("Refresh:1 " . base_url() . "student/permoteStudent");
            }
          }


          $classData = $this->CrudModel->allClass(Table::classTable, $_SESSION['schoolUniqueCode']);
          $sectionData = $this->CrudModel->allSection(Table::sectionTable, $_SESSION['schoolUniqueCode']);
          $sessionData = $this->db->query("SELECT * FROM " . Table::schoolSessionTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1'")->result_array();


          if (!empty($this->session->userdata('msg'))) { ?>

            <div class="alert alert-<?= $this->session->userdata('class') ?> alert-dismissible fade show" role="alert">
              <?= $this->session->userdata('msg');
              if ($this->session->userdata('class') == 'success') {
                HelperClass::swalSuccess($this->session->userdata('msg'));
              } else if ($this->session->userdata('class') == 'danger') {
                HelperClass::swalError($this->session->userdata('msg'));
              }
              ?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
          <?php
            $this->session->unset_userdata('class');
            $this->session->unset_userdata('msg');
          }
          ?>

          <div class="row">
            <div class="col-md-12">


              <div class="card border-top-3">
                <div class="card-header">
                  <h5>Search Filters</h5>
                </div>
                <form method="POST">
                  <div class="card-body">
                    <div class="row">
                      <div class="form-group col-md-4">
                        <label>Select Class </label>
                        <select id="studentClass" name="classId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                          <option>Please Select Class</option>
                          <?php
                          if (isset($classData)) {
                            foreach ($classData as $cd) {  ?>
                              <option value="<?= $cd['id'] ?>"><?= $cd['className'] ?></option>
                          <?php }
                          } ?>
                        </select>
                      </div>

                      <div class="form-group col-md-4">
                        <label>Select Section </label>
                        <select id="studentSection" name="sectionId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                          <option>Please Select Section</option>
                          <?php
                          if (isset($sectionData)) {
                            foreach ($sectionData as $sd) {  ?>
                              <option value="<?= $sd['id'] ?>"><?= $sd['sectionName'] ?></option>
                          <?php }
                          } ?>
                        </select>
                      </div>
                      <div class="form-group col-md-4 margin-top-30">
                        <button id="search" name="search" class="btn btn-block mybtnColor">Submit</button>
                        <!-- <button onclick="window.location.reload();" class="btn btn-warning">Clear</button> -->
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <!-- <div class="form-group col-md-2">
            
          </div> -->

            <div class="col-md-12">
              <div class="card border-top-3">
                <div class="card-header">
                  <h3 class="card-title">Showing All Students Data</h3>
                  <button type="submit" onclick="permoteClass()" class="btn btn-block mybtnColor col-md-3 float-right">Permote Class </button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
               
                  <div class="table-responsive">

                 
                  <table class="table align-middle mb-0 bg-white">
                    <thead class="bg-light">
                      <tr>
                        <th><input type="checkbox" id="checkAll"></th>
                        <th>Name</th>
                        <th>User Id</th>
                        <th>Mobile</th>
                        <th>Father Name</th>
                        <th>Current Class</th>
                        <th>Permote Student</th>
                      </tr>
                    </thead>
                    <tbody>
                   
                      <?php
 $a = 0; 
                      if (isset($studentsData)) {
                       
                        foreach ($studentsData as $cn) { ?>
                        <input type="hidden" id="classId" name="classId" value="<?= $cn['classId'] ?>">
                        <input type="hidden" id="sectionId" name="sectionId" value="<?= $cn['sectionId'] ?>">
                        <input type="hidden" id="className" name="className" value="<?= $cn['className'] ?>">
                        <input type="hidden" id="sectionName" name="sectionName" value="<?= $cn['sectionName'] ?>">
                        <input type="hidden" id="currentSessionName" name="currentSessionName" value="<?= $sessionDataToShow['session_start_year'] . '-' . $sessionDataToShow['session_end_year'] ?>">
                          <tr>
                            <td><input type="checkbox" class="feeTypeCheckbox" id="fees_id_<?=$a?>" value="<?= $cn['id'] ?>"></td>
                            <td><?= $cn['name']; ?></td>
                            <td><?= $cn['user_id']; ?></td>
                            <td><?= $cn['mobile']; ?></td>
                            <td><?= $cn['father_name']; ?></td>
                            <td><?= $cn['className'] . " ( " . $cn['sectionName'] . " )"; ?></td>
                            <td><button onclick="permoteStudent('<?= $cn['id'] ?>')" class="btn btn-block mybtnColor">Permote Student</button></td>
                          </tr>

                       

                      <?php $a++; }
                      }

                    


                      ?>
                    
                    </tbody>

                  </table>
                  <input type="hidden" id="aHighValue" value="<?=$a?>">
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
            </div>
          </div>

          <!-- /.container-fluid -->
        </div>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <!-- Control Sidebar -->



      <!-- bootstrap modal box -->
      <!-- Modal -->
      <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <form method="POST">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Current Class <span id="currentClassName"></span> And Session <span id="currentSessionName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="form-group col-md-12">
                    <label>Select Session </label>
                    <select id="sessionI" class="form-control  select2 select2-dark" required name="session_table_id" data-dropdown-css-class="select2-dark" style="width: 100%;">
                    <option>Please Select Next Session</option>
                      <?php
                      if (isset($sessionData)) {
                        foreach ($sessionData as $ssd) {  ?>
                          <option value="<?= $ssd['id'] ?>"><?= $ssd['session_start_year'] . " - " . $ssd['session_end_year']; ?></option>
                      <?php }
                      } ?>
                    </select>
                  </div>
                  <div class="form-group col-md-12">
                    <label>Select Class </label>
                    <select id="studentClassIV" class="form-control  select2 select2-dark" required name="class_id" data-dropdown-css-class="select2-dark" style="width: 100%;">
                    <option>Please Select Next Class</option>
                      <?php
                      if (isset($classData)) {
                        foreach ($classData as $cd) {  ?>
                          <option value="<?= $cd['id'] ?>"><?= $cd['className'] ?></option>
                      <?php }
                      } ?>
                    </select>
                  </div>
                  <div class="form-group col-md-12">
                    <label>Select Section </label>
                    <select id="studentSectionIV" class="form-control  select2 select2-dark" name="section_id" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                    <option>Please Select Next Section</option>
                      <?php
                      if (isset($sectionData)) {
                        foreach ($sectionData as $sd) {  ?>
                          <option value="<?= $sd['id'] ?>"><?= $sd['sectionName'] ?></option>
                      <?php }
                      } ?>
                    </select>
                  </div>
                </div>
                <input type="hidden" name="student_id" id="stu_id">
                <input type="hidden" name="currentClassId" id="currentClassId">
                <input type="hidden" name="currentSectionId" id="currentSectionId">

              </div>
              <div class="modal-footer">
              
                <button type="submit" name="submit" class="btn btn-block mybtnColor">Permote Student</button>
              </div>
            </div>
          </form>
        </div>
      </div>





      <!-- Modal -->
      <div class="modal fade" id="permoteClass" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <form method="POST">
            <div class="modal-content">
              <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Current Class <span id="currentClassNameC"></span> - <span id="currentSessionNameC"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="form-group col-md-12">
                    <label>Select Session </label>
                    <select id="session" class="form-control  select2 select2-dark" required name="session_table_id" data-dropdown-css-class="select2-dark" style="width: 100%;">
                      <option>Please Select Next Session</option>
                      <?php
                      if (isset($sessionData)) {
                        foreach ($sessionData as $ssd) {  ?>
                          <option value="<?= $ssd['id'] ?>"><?= $ssd['session_start_year'] . " - " . $ssd['session_end_year']; ?></option>
                      <?php }
                      } ?>
                    </select>
                  </div>
                  <div class="form-group col-md-12">
                    <label>Select Class </label>
                    <select id="studentClassI" class="form-control  select2 select2-dark" required name="class_id" data-dropdown-css-class="select2-dark" style="width: 100%;">
                      <option>Please Select Next Class</option>
                      <?php
                      if (isset($classData)) {
                        foreach ($classData as $cd) {  ?>
                          <option value="<?= $cd['id'] ?>"><?= $cd['className'] ?></option>
                      <?php }
                      } ?>
                    </select>
                  </div>
                  <div class="form-group col-md-12">
                    <label>Select Section </label>
                    <select id="studentSectionI" class="form-control  select2 select2-dark" name="section_id" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                      <option>Please Select Next Section</option>
                      <?php
                      if (isset($sectionData)) {
                        foreach ($sectionData as $sd) {  ?>
                          <option value="<?= $sd['id'] ?>"><?= $sd['sectionName'] ?></option>
                      <?php }
                      } ?>
                    </select>
                  </div>
                </div>
                <input type="hidden" name="student_id" id="stu_id_Class">
                <input type="hidden" name="currentClassId" id="currentClassIdC">
                <input type="hidden" name="currentSectionId" id="currentSectionIdC">

              </div>
              <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                <button type="submit" name="submitClass" class="btn btn-block mybtnColor">Permote Class</button>
              </div>
            </div>
          </form>
        </div>
      </div>








      <!-- /.control-sidebar -->
    </div>
    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>

  <script>
    function permoteStudent(x) {
      let classId = $("#classId").val();
      let sectionId = $("#sectionId").val();
      $("#stu_id").val(x);
      $("#currentClassName").html($("#className").val() + ' ( ' + $("#sectionName").val() + ' ) ');
      $("#currentSessionName").html($("#currentSessionName").val());
      $("#currentClassId").val(classId);
      $("#currentSectionId").val(sectionId);
      $("#exampleModalCenter").modal('show');
      console.log(x);

    }

    function permoteClass()
    {
      let h = $("#aHighValue").val();
      let classId = $("#classId").val();
      let sectionId = $("#sectionId").val();
      console.log(sectionId);
      console.log(h)
      let studentsIds = [];
      for(let b = 0; b < h; b++)
      {
        console.log($("#fees_id_"+b));
        if($("#fees_id_"+b)[0].checked == true)
        {
          studentsIds.push($("#fees_id_"+b).val());
         
        }
        
      }

      console.log(studentsIds);
      $("#currentClassNameC").html($("#className").val() + ' ( ' + $("#sectionName").val() + ' ) ');
      $("#currentSessionNameC").html($("#currentSessionName").val());
      $("#stu_id_Class").val(studentsIds);
      $("#currentClassIdC").val(classId);
      $("#currentSectionIdC").val(sectionId);
      $("#permoteClass").modal('show');
    }


    $("#checkAll").change(function() {
      if ($(this).prop('checked')) {
        console.log("Checked Box Selected");
        $(".feeTypeCheckbox").attr('checked', 'checked');
      } else {
        console.log("Checked Box deselect");
        $(".feeTypeCheckbox").attr('checked', false);
      }
    });
  </script>