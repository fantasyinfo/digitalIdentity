<style>
    #srDetails {
        display: none;
    }
</style>

<?php

if (isset($_POST['search'])) {
    $classId = $_POST['class_id'];
    $sectionId = $_POST['section_id'];
    $studentId = $_POST['student_id'];

    if ($classId == 'Please Select Class' || $sectionId == 'Please Select Section' || $studentId == 'Please Select Student') {
        echo '<script>alert(\' Please Select Class, Section & Student \')</script>';
    }


    $studentDetails = $this->db->query("SELECT s.*, c.className FROM " . Table::studentTable . " s
    JOIN " . Table::classTable . " c ON c.id = s.class_id
    JOIN " . Table::sectionTable . " sec ON sec.id = s.section_id
    WHERE s.id = '$studentId' 
    AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'
    AND s.class_id = '$classId' AND s.section_id = '$sectionId'")->result_array();



    if (!empty($studentDetails)) {


        $alreadySRCheck =  $this->db->query("SELECT * FROM " . Table::srRegisterHistory . " WHERE student_id = '$studentId' AND status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array();



?>
        <style>
            #srDetails {
                display: block;
            }

            #filter_frm {
                display: none;
            }
        </style>
<?php  }
}


if (isset($_POST['submitSR'])) {

    // first check if already a sr created for this student then update them

    $alreadySR =  $this->db->query("SELECT * FROM " . Table::srRegisterHistory . " WHERE student_id = '{$_POST['student_id']}' AND status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array();

    if (empty($alreadySR)) {


        // HelperClass::prePrintR($_POST); 
        $classIdsArr = count($_POST['classIds']);

        $sendObj = [];
        for ($i = 0; $i < $classIdsArr; $i++) {
            $subArr = [];
            $subArr['classId'] = @$_POST['classIds'][$i];
            $subArr['doa'] = @$_POST['doa'][$i];
            $subArr['dop'] = @$_POST['dop'][$i];
            $subArr['dor'] = @$_POST['dor'][$i];
            $subArr['causeOfRemoval'] = @$_POST['causeOfRemoval'][$i];
            $subArr['sessionYears'] = @$_POST['sessionYears'][$i];
            $subArr['conduct'] = @$_POST['conduct'][$i];
            $subArr['work'] = @$_POST['work'][$i];
            array_push($sendObj, $subArr);
        }

        $this->load->model('CrudModel');
        $insertArr = [
            'schoolUniqueCode' => $_SESSION['schoolUniqueCode'],
            'student_id' => $_POST['student_id'],
            'srData' => json_encode($sendObj),
            'currentClass' => $_POST['currentClass']
        ];
        $insert = $this->CrudModel->insert(Table::srRegisterHistory, $insertArr);

        if ($insert) {
            $msgArr = [
                'class' => 'success',
                'msg' => 'SR Saved Successfully',
            ];
            $this->session->set_userdata($msgArr);
        } else {
            $msgArr = [
                'class' => 'danger',
                'msg' => 'SR Not Saved due to this Error. ' . $this->db->last_query(),
            ];
            $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "student/srRegisterHistory");
    } else {
        // update the SR
        $classIdsArr = count($_POST['classIds']);

        $sendObj = [];
        for ($i = 0; $i < $classIdsArr; $i++) {
            $subArr = [];
            $subArr['classId'] = @$_POST['classIds'][$i];
            $subArr['doa'] = @$_POST['doa'][$i];
            $subArr['dop'] = @$_POST['dop'][$i];
            $subArr['dor'] = @$_POST['dor'][$i];
            $subArr['causeOfRemoval'] = @$_POST['causeOfRemoval'][$i];
            $subArr['sessionYears'] = @$_POST['sessionYears'][$i];
            $subArr['conduct'] = @$_POST['conduct'][$i];
            $subArr['work'] = @$_POST['work'][$i];
            array_push($sendObj, $subArr);
        }

        $this->load->model('CrudModel');
        $updateArr = [
            'srData' => json_encode($sendObj),
            'currentClass' => $_POST['currentClass']
        ];
        $insert = $this->CrudModel->update(Table::srRegisterHistory, $updateArr, $alreadySR[0]['id']);

        if ($insert) {
            $msgArr = [
                'class' => 'success',
                'msg' => 'SR Saved Successfully',
            ];
            $this->session->set_userdata($msgArr);
        } else {
            $msgArr = [
                'class' => 'danger',
                'msg' => 'SR Not Saved due to this Error. ' . $this->db->last_query(),
            ];
            $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "student/srRegisterHistory");
    }
}

// echo $this->db->last_query();



?>

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
                            <!-- <p style="color:red;">Please Generate TC First Then Generate Charater Certificate.</p> -->
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
                        <?php

                        $classData = $this->CrudModel->allClass(Table::classTable, $_SESSION['schoolUniqueCode']);
                        $sectionData = $this->CrudModel->allSection(Table::sectionTable, $_SESSION['schoolUniqueCode']);

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
                        <!-- left column -->
                        <?php //print_r($data['class']);
                        ?>
                        <div class="col-md-12 mx-auto">
                            <!-- jquery validation -->
                            <div class="card border-top-3">
                                <div class="card-header">
                                    <h3 class="card-title">Select Student Details</h3>

                                </div>

                                <div class="card-body" id="filter_frm">

                                    <form method="POST">
                                        <div class="row">
                                            <div class="form-group col-md-3">
                                                <label>Select Class </label>
                                                <select id="classId" name="class_id" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                                                    <option>Please Select Class</option>
                                                    <?php
                                                    if (isset($classData)) {
                                                        foreach ($classData as $cd) {  ?>
                                                            <option value="<?= $cd['id'] ?>"><?= $cd['className'] ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Select Section </label>
                                                <select id="sectionId" name="section_id" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;" onchange="showStudents()">
                                                    <option>Please Select Section</option>
                                                    <?php
                                                    if (isset($sectionData)) {
                                                        foreach ($sectionData as $sd) {  ?>
                                                            <option value="<?= $sd['id'] ?>"><?= $sd['sectionName'] ?></option>
                                                    <?php }
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>Select Students </label>
                                                <select name="student_id" id="studentId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                                                    <option>Please Select Student</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3 margin-top-30">
                                                <input type="submit" name="search" value="Search" class="btn mybtnColor btn-block ">
                                            </div>
                                        </div>
                                </div>
                                </form>

                                <!-- /.card-header -->
                                <!-- form start -->

                            </div>
                        </div>

                        <div id="srDetails">
                            <form method="POST">

                                <div class="col-md-12">
                                    <div class="card boarder-top-3">
                                        <div class="card-body">

                                            <?php if (isset($studentDetails)) { ?>
                                                <div class="row">
                                                    <a href="<?= base_url() .  'scholarRegisterCertificate?stu_id=' . $studentDetails[0]['id'] ?>" target="_blank" class="btn btn-success">Download SR</a>
                                                    <div class="table-responsive">
                                                        <table class="table mb-0 align-middle bg-white">
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        Name
                                                                    </td>
                                                                    <td>
                                                                        <b> <?= @$studentDetails[0]['name']; ?></b>
                                                                    </td>
                                                                    <td>
                                                                        Class
                                                                    </td>
                                                                    <td>
                                                                        <b> <?= @$studentDetails[0]['className']; ?></b>
                                                                    </td>
                                                                    <td>
                                                                        Aadhar No
                                                                    </td>
                                                                    <td>
                                                                        <b> <?= @$studentDetails[0]['aadhar_no']; ?></b>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        Father's Name
                                                                    </td>
                                                                    <td>
                                                                        <b> <?= @$studentDetails[0]['father_name']; ?></b>
                                                                    </td>
                                                                    <td>
                                                                        Mother' Name
                                                                    </td>
                                                                    <td>
                                                                        <b> <?= @$studentDetails[0]['mother_name']; ?></b>
                                                                    </td>
                                                                    <td>
                                                                        Occupation
                                                                    </td>
                                                                    <td>
                                                                        <b> <?= @$studentDetails[0]['occupation']; ?></b>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        Caste - Religion
                                                                    </td>
                                                                    <td>
                                                                        <b> <?= @HelperClass::casteCategory[@$studentDetails[0]['cast_category']]; ?></b>
                                                                    </td>
                                                                    <td>
                                                                        D.O.B
                                                                    </td>
                                                                    <td>
                                                                        <b><?= @$studentDetails[0]['dob']; ?></b>
                                                                    </td>
                                                                    <td>
                                                                        Last School Name
                                                                    </td>
                                                                    <td>
                                                                        <b><?= @$studentDetails[0]['last_schoool_name']; ?></b>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        Admission No
                                                                    </td>
                                                                    <td>
                                                                        <b> <?= @$studentDetails[0]['admission_no']; ?></b>
                                                                    </td>
                                                                    <td>
                                                                        SR No
                                                                    </td>
                                                                    <td>
                                                                        <b> <?= @$studentDetails[0]['sr_number']; ?></b>
                                                                    </td>
                                                                    <td>
                                                                        Address
                                                                    </td>
                                                                    <td>
                                                                        <?= @$studentDetails[0]['address']; ?></b>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <input type="hidden" name="student_id" value="<?= $studentDetails[0]['id']; ?>">
                                                </div>
                                            <?php  } ?>


                                            <div class="table-responsive">


                                                <table class="table mb-0 align-middle bg-white table-borderless">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th>Class</th>
                                                            <th>Date of Admission</th>
                                                            <th>Date of Promotion</th>
                                                            <th>Date of Removal</th>
                                                            <th>Cause of Removal</th>
                                                            <th>Session Year</th>
                                                            <th>Conduct</th>
                                                            <th>Work</th>
                                                            <th>Current Class</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php

                                                        $srDetails = [];
                                                        if (!empty($alreadySRCheck)) {
                                                            $srDetails = json_decode($alreadySRCheck[0]['srData'], true);
                                                        }
                                                        //print_r($srDetails);
                                                        $i = 0;
                                                        foreach (HelperClass::srClass as $classes => $values) { ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="classIds[]" value="<?= $classes; ?>">
                                                                    <input type="text" disabled value="<?= $values; ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="date" class="form-control" name="doa[]" value="<?= !empty($srDetails[$i]['doa']) ? $srDetails[$i]['doa'] : '' ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="date" class="form-control" name="dop[]" value="<?= !empty($srDetails[$i]['dop']) ? $srDetails[$i]['dop'] : '' ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="date" class="form-control" name="dor[]" value="<?= !empty($srDetails[$i]['dor']) ? $srDetails[$i]['dor'] : '' ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="causeOfRemoval[]" value="<?= !empty($srDetails[$i]['causeOfRemoval']) ? $srDetails[$i]['causeOfRemoval'] : '' ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="sessionYears[]" placeholder="2022-2023" value="<?= !empty($srDetails[$i]['sessionYears']) ? $srDetails[$i]['sessionYears'] : '' ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="conduct[]" placeholder="Good" value="<?= !empty($srDetails[$i]['conduct']) ? $srDetails[$i]['conduct'] : '' ?>">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="work[]" placeholder="Passed" value="<?= !empty($srDetails[$i]['work']) ? $srDetails[$i]['work'] : '' ?>">
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $checked = '';
                                                                    if (@$alreadySRCheck[0]['currentClass'] == $classes) {
                                                                        $checked = 'checked';
                                                                    } else {
                                                                        $checked = '';
                                                                    } ?>
                                                                    <input <?= $checked ?> type="checkbox" class="form-control" name="currentClass" value="<?= $classes; ?>">
                                                                </td>
                                                            </tr> <?php $i++;
                                                                }  ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">


                                    <button type="submit" name="submitSR" value="Submit" class="btn mybtnColor btn-block">Save SR </button>
                                </div>
                            </form>
                        </div>





                    </div>
                    <!--/.col (left) -->
                    <!-- right column -->
                </div>
                <!--/.col (right) -->
            </div>

            <!-- /.container-fluid -->
        </div>


        <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
    </div>
    <?php $this->load->view("adminPanel/pages/footer.php"); ?>

    <script>
        function showStudents() {
            var classId = $("#classId").val();
            var sectionId = $("#sectionId").val();
            if (classId != '' && sectionId != '') {
                console.log(classId + ' and ' + sectionId);
                $.ajax({
                    url: '<?= base_url() . 'ajax/showStudentViaClassAndSectionIdSR'; ?>',
                    method: 'post',
                    processData: 'false',
                    data: {
                        classId: classId,
                        sectionId: sectionId
                    },
                    success: function(response) {
                        //console.log(response);
                        response = $.parseJSON(response);
                        $('#studentId').append(response);
                    },
                    error: function(error) {
                        console.log(error);
                    }

                });
            }
        }
    </script>