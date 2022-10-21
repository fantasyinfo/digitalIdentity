<?php
$this->load->library('session');
$this->load->model('CrudModel');

if (isset($_POST['class_id']) && isset($_POST['section_id']) && isset($_POST['studentId'])) {

    // first check if already tc generated for this student
    $tcDetails = $this->db->query("SELECT * FROM ".Table::studentTC." WHERE student_id = '{$_POST['studentId']}' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array();

    if(empty($tcDetails))
    {
        $student = $this->db->query("SELECT s.*, c.className,sc.sectionName FROM " . Table::studentTable . " s 
        JOIN " . Table::classTable . " c ON c.id = s.class_id
        JOIN " . Table::sectionTable . " sc ON sc.id = s.section_id
        WHERE s.class_id = '{$_POST['class_id']}' AND s.section_id = '{$_POST['section_id']}' AND s.id = '{$_POST['studentId']}' AND s.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array()[0];

        if (empty($student)) {
            $msgArr = [
				'class' => 'danger',
				'msg' => 'Student Details Not Found.' . $this->db->last_query(),
			  ];
			 $this->session->set_userdata($msgArr);
			redirect(base_url('student/generateTC'));
        }
    }else
    {
        $msgArr = [
            'class' => 'danger',
            'msg' => 'This Student Transfer Certificate Already Generated.',
          ];
         $this->session->set_userdata($msgArr);
         $insert = $tcDetails[0]['id'];
         $user_id = $tcDetails[0]['user_id'];
         header("Location: " . base_url() . "student/tc?tc_id=" . $insert . "&user_id=" . $user_id);
    }

    

    
} else if (isset($_POST['submit'])) {


    $insertArr = [
        'schoolUniqueCode' => $_POST['schoolUniqueCode'],
        'student_id' => $_POST['student_id'],
        'book_register_no' => $_POST['book_register_no'],
        's_i_s_r_no' => $_POST['s_i_s_r_no'],
        'admission_no' => $_POST['admission_no'],
        'student_name' => $_POST['student_name'],
        'father_name' => $_POST['father_name'],
        'mother_name' => $_POST['mother_name'],
        'gender' => $_POST['gender'],
        'category' => $_POST['category'],
        'date_of_birth' => $_POST['date_of_birth'],
        'date_of_admission' => $_POST['date_of_admission'],
        'nationality' => $_POST['nationality'],
        'shedule_tribe' => $_POST['shedule_tribe'],
        'last_class_studies' => $_POST['last_class_studies'],
        'ncc_cadet' => $_POST['ncc_cadet'],
        'game_played' => $_POST['game_played'],
        'board_exam_last_taken' => $_POST['board_exam_last_taken'],
        'subjects_studies' => $_POST['subjects_studies'],
        'qualify_for_permotion' => $_POST['qualify_for_permotion'],
        'fees_due' => $_POST['fees_due'],
        'total_working_days' => $_POST['total_working_days'],
        'total_present_days' => $_POST['total_present_days'],
        'date_of_application' => $_POST['date_of_application'],
        'date_of_issue' => $_POST['date_of_issue'],
        'reason_for_leaving' => $_POST['reason_for_leaving'],
        'remark' => $_POST['remark'],
        'session_table_id' => $_POST['session_table_id'],
        'general_conduct' =>$_POST['general_conduct'],
        'failed_in_class' => $_POST['failed_in_class'],
        'user_id' => $_POST['user_id']
    ];


    $insert = $this->CrudModel->insert(Table::studentTC, $insertArr);

    if ($insert) {
        $msgArr = [
            'class' => 'success',
            'msg' => 'Student Transfer Certificate Generated Successfully. Please Click on Download Button for Download.',
        ];
        $this->session->set_userdata($msgArr);
        header("Location: " . base_url() . "student/tc?tc_id=" . $insert . "&user_id=" . $_POST['user_id']);
        ?>
        <style>#form_w {display:none;}</style>
        <?php
    }
} else {

    $msgArr = [
        'class' => 'danger',
        'msg' => 'Please Select Class, Section & Student First.',
    ];
    $this->session->set_userdata($msgArr);

    header("Refresh:0 " . base_url() . "student/generateTC");
}

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
                        <div class="col-md-12 mx-auto" id="form_w">
                            <!-- jquery validation -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Add Correct Details</h3>

                                </div>
                                <div id="tc_details">
                                    <form method="post">
                                        <input type="hidden" name="schoolUniqueCode" value="<?= $_SESSION['schoolUniqueCode'] ?>">
                                        <input type="hidden" name="session_table_id" value="<?= $_SESSION['currentSession'] ?>">
                                        <input type="hidden" name="student_id" value="<?= $student['id']; ?>">
                                        <input type="hidden" name="user_id" value="<?= $student['user_id']; ?>">
                                        <div class="container">
                                            <table class="table table-borderless">
                                                <tr><img src="" class="img-responsive"></tr>
                                                <tr>
                                                    <th scope="col">
                                                        <h4>Book No : <u><b><input type="text" name="book_register_no" width="30px" class="form-control" value="<?= $student['sr_number']; ?>" required></b></u></h4>
                                                    </th>
                                                    <th scope="col">
                                                        <h4>SI.No: <u><b><input type="text" name="s_i_s_r_no" width="30px" class="form-control" value="<?= $student['sr_number']; ?>" required></b></u></h4>
                                                    </th>
                                                    <th scope="col">
                                                        <h4>Admission No: <u><b><input type="text" name="admission_no" width="30px" class="form-control" value="<?= $student['admission_no']; ?>" required></b></u></h4>
                                                    </th>
                                                    <th scope="col">
                                                        <h4>TC No: <u><b>will be generated digitaly</b></u></h4>
                                                    </th>
                                                </tr>

                                            </table>


                                            <table class="table table-bordered table-striped" width="100%">


                                                <tr>
                                                    <td>Full Name Of Pupil: </td>
                                                    <td><input type="text" name="student_name" width="30px" class="form-control" value="<?= $student['name']; ?>" required></td>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Father's/Guardian's Name:</td>
                                                    <td><input type="text" name="father_name" width="30px" class="form-control" value="<?= $student['father_name']; ?>" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Mother's Name: </td>
                                                    <td><input type="text" name="mother_name" width="30px" class="form-control" value="<?= $student['mother_name']; ?>" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Gender : </td>
                                                    <td><input type="text" name="gender" width="30px" class="form-control" value="<?= ($student['gender'] == '1') ? "Male" : "Female"; ?>" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Category :</td>
                                                    <td><input type="text" name="category" width="30px" class="form-control" value="<?= $student['cast_category']; ?>" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Date Of Birth : </td>
                                                    <td><input type="date" name="date_of_birth" width="30px" class="form-control" value="<?= $student['dob']; ?>" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Date Of Admission : </td>
                                                    <td><input type="date" name="date_of_admission" width="30px" class="form-control" value="<?= $student['date_of_admission']; ?>" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Nationality : </td>
                                                    <td><input type="text" name="nationality" width="30px" class="form-control" value="Indian" required></td>

                                                </tr>
                                                <tr>
                                                    <td>Whether Candidate Belongs To Scheduled Caste Or Schedule Tribe : </td>
                                                    <td><input type="text" name="shedule_tribe" width="30px" class="form-control" value="No" required> </td>
                                                </tr>
                                                <tr>
                                                    <td>Standard In Which The Pupil Was Studying At The Time Of Leaving The School : </td>
                                                    <td><input type="text" name="last_class_studies" width="30px" class="form-control" value="<?= $student['className'] . " - " . $student['sectionName']; ?>" required></td>
                                                </tr>

                                                <tr>
                                                    <td>Weather Ncc Cadet / Boy Scout / Girl Guide Details Be Given :</td>
                                                    <td><input type="text" name="ncc_cadet" width="30px" class="form-control" value="NO" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Games Played For Extra Curricular Activities In Which The People Usually Took Part :</td>
                                                    <td><input type="text" name="game_played" width="30px" class="form-control" value="Cricket" required></td>
                                                </tr>

                                                <tr>
                                                    <td>School Board Annual Examination Last Taken With Result :</td>
                                                    <td><input type="text" name="board_exam_last_taken" width="30px" class="form-control" value="CBSE Board" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Subject Studied :</td>
                                                    <td><input type="text" name="subjects_studies" width="30px" class="form-control" value="Hindi,English,Maths,Science" placeholder="please enter comma seprated values eg. Hindi,English,Maths" required></td>
                                                </tr>

                                                <tr>
                                                    <td>Whether Student is Failed in Class :</td>
                                                    <td><input type="text" name="failed_in_class" width="30px" class="form-control" value="NO" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Whether Qualified For Promotion To The Higher Class If Yes, Specify :</td>
                                                    <td><input type="text" name="qualify_for_permotion" width="30px" class="form-control" value="Yes" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Whether The Pupil Has Paid All The Fees Due To The School :</td>
                                                    <td><input type="text" name="fees_due" width="30px" class="form-control" value="Yes" required></td>
                                                </tr>
                                                <tr>
                                                    <td>General Conduct / Behaviour :</td>
                                                    <td><input type="text" name="general_conduct" width="30px" class="form-control" value="Good" required></td>
                                                </tr>

                                                <tr>
                                                    <td>Total Number Of Working Days Upto The Date Of Leaving :</td>
                                                    <td><input type="text" name="total_working_days" width="30px" class="form-control" value="196" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Total Number Of School Days Attended :</td>
                                                    <td><input type="text" name="total_present_days" width="30px" class="form-control" value="140" required></td>
                                                </tr>

                                                <tr>
                                                    <td>Date Of Application For Certificate : </td>
                                                    <td><input type="text" name="date_of_application" width="30px" class="form-control" value="<?= date('Y-m-d') ?>" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Date Of Issue Of Certificate :</td>
                                                    <td><input type="text" name="date_of_issue" width="30px" class="form-control" value="<?= date('Y-m-d') ?>" required></td>
                                                </tr>

                                                <tr>
                                                    <td>Reason For Leaving School :</td>
                                                    <td><input type="text" name="reason_for_leaving" width="30px" class="form-control" value="Transfer of Father" required></td>
                                                </tr>
                                                <tr>
                                                    <td>Other Remakrks :</td>
                                                    <td><input type="text" name="remark" width="30px" class="form-control" value="NA" required></td>
                                                </tr>
                                                <tr>
                                                    <td>#</td>
                                                    <td><button type="submit" name="submit" class="btn btn-success btn-block"> Generate Transfer Certificate </button></td>
                                                </tr>
                                            </table>
                                        </div>








                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                    <!-- right column -->
                </div>
                <!--/.col (right) -->
            </div>

            <!-- /.container-fluid -->
        </div>

        <!-- /.content -->

        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->

        <!-- /.control-sidebar -->

        <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
    </div>
    <?php $this->load->view("adminPanel/pages/footer.php"); ?>