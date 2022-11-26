<?php

if (isset($_POST['search'])) {
    $classId = $_POST['class_id'];
    $sectionId = $_POST['section_id'];
    $studentId = $_POST['student_id'];

    if ($classId == 'Please Select Class' || $sectionId == 'Please Select Section' || $studentId == 'Please Select Student') {
        echo '<script>alert(\' Please Select Class, Section & Student \')</script>';
    }


    $history = $this->db->query("SELECT st.*, s.name, s.date_of_admission, s.admission_no, s.father_name, c.className, CONCAT(session.session_start_year, ' - ' , session.session_end_year) as years, tc.date_of_issue, tc.reason_for_leaving FROM " . Table::studentHistoryTable . " st 
    JOIN " . Table::studentTable . " s ON s.id = st.student_id
    JOIN " . Table::classTable . " c ON c.id = st.class_id
    JOIN " . Table::sectionTable . " sec ON sec.id = st.section_id
    JOIN " . Table::schoolSessionTable . " session ON session.id = st.session_table_id
    LEFT JOIN " . Table::studentTC . " tc ON tc.student_id = st.id
    WHERE st.student_id = '$studentId' 
    AND st.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' 
    AND s.id = '$studentId' AND s.class_id = '$classId' AND s.section_id = '$sectionId'")->result_array();
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



                        <div class="col-md-12">
                            <div class="card boarder-top-3">
                                <div class="card-header">Student Details </div>
                                <div class="card-body">

                                <?php if(isset($history)){ ?>
                                    <div class="row alert alert-dark my-2 ">
                                        <div class="col-md-3"><b>Name: <?= $history[0]['name']; ?> </b></div>
                                        <div class="col-md-3"><b>Father's Name: <?= $history[0]['father_name']; ?></b></div>
                                        <div class="col-md-3"><b>Admission No: <?= $history[0]['admission_no']; ?></b></div>
                                    </div>
                              <?php  } ?>


                                    <div class="table-responsive">
                                        
                                        <form method="POST">
                                        <table class="table mb-0 align-middle bg-white" >
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($history)) {
                                                    foreach ($history as $cd) { 
                                                        
                                                        
                                                        
                                                        ?>

                                                        <tr>
                                                            <td><?php 
                                                            echo '<input type="text" name="classIds[]" value="'.$cd['className'].'" disabled>'; ?></td>
                                                            <td><?php

                                                                $datte = $cd['date_of_admission'];

                                                                if (!empty($datte)) {
                                                                    echo '<input type="text" name="admissionDate" value="'.date('d-m-Y', strtotime($datte)).'" disabled>' ;
                                                                } 
                                                               
                                                                ?></td>
                                                            <td><?php
                                                                if (!empty($cd['permotion_date'])) {
                                                                    echo '<input type="text" name="permostionDate[]" value="'.date('d-m-Y', strtotime(@$cd['permotion_date'])).'" disabled> ';
                                                                } else {
                                                                    echo " --- ";
                                                                }
                                                                ?></td>
                                                            <td><?php
                                                                if (!empty($cd['date_of_issue'])) {
                                                                    echo '<input type="text" name="removalDate[]" value="'.date('d-m-Y', strtotime(@$cd['date_of_issue'])).'" disabled>';
                                                                } else {
                                                                    echo " --- ";
                                                                }
                                                                ?></td>
                                                            <td><?php
                                                            if(!empty($cd['reason_for_leaving'])){
                                                                echo $cd['reason_for_leaving'];
                                                            }else{
                                                                echo " --- ";
                                                            }  ?></td>
                                                            <td><?php
                                                            echo '<input type="text" name="sessionYears[]" value="'.$cd['years'].'" disabled>'; ?></td>
                                                            <td><?php
                                                            echo '<input type="text" class="form-control" name="conduct[]" value="Good">'; ?></td>
                                                            <td><?php
                                                            echo '<input type="text"  class="form-control" name="work[]" value="Passed">'; ?></td>
                                                           
                                                        </tr>

                                                <?php  }
                                                } ?>
                                            </tbody>
                                        </table>
                                        <div class="col-md-12 mt-3">

                                        
                                        <button type="submit" name="submitSR" value="Submit" class="btn mybtnColor btn-block">Generate SR </button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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