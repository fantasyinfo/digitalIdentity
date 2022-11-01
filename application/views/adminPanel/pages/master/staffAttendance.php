<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php $this->load->view("adminPanel/pages/sidebar.php");

        $this->load->model('CrudModel');

        $departmentList = $this->db->query("SELECT departmentName, id FROM " . Table::departmentTable . " WHERE status = '1'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


        $c = 0;
        if (isset($_POST['submit'])) {

            // if (date('D') == 'Sun') {
            //     $msgArr = [
            //         'class' => 'danger',
            //         'msg' => 'Today is Sunday. Please Mark Attendance in Between Monday to Saturday.',
            //     ];
            //     $this->session->set_userdata($msgArr);

            //     header("Refresh:1 " . base_url() . "teacher/attendance");
            //     exit();
            // }

             $today = date_create()->format('Y-m-d');





            // check if today is holiday

            $holiday = $this->db->query("SELECT title FROM " . Table::holidayCalendarTable . " WHERE event_date = '$today' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array();

            if (!empty($holiday)) {
                $msgArr = [
                    'class' => 'danger',
                    'msg' => "Today is $holiday[0]['title'] Holiday. Try on School Working Days",
                ];
                $this->session->set_userdata($msgArr);
                header("Refresh:1 " . base_url() . "master/staffAttendance");
                exit();
            }



            // is today already attendance mark
            $alreadyAttendance = $this->db->query("SELECT * FROM " . Table::staffattendanceTable . " WHERE status = '1'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND att_date = '$today' AND departmentName = '{$_POST['departmentName']}'")->result_array();

            if (!empty($alreadyAttendance)) {
                $msgArr = [
                    'class' => 'danger',
                    'msg' => $_POST['departmentName'].' Department Attendance Already Mark Today',
                ];
                $this->session->set_userdata($msgArr);

                header("Refresh:1 " . base_url() . "master/staffAttendance");
                exit();
            }


            $totalT = count($_POST['tId']);
            for ($j = 0; $j < $totalT; $j++) {
                $insertArr = [
                    "schoolUniqueCode" => $_SESSION['schoolUniqueCode'],
                    "employee_id" => $_POST['tId'][$j],
                    "employeeName" => $_POST['empName'][$j],
                    "departmentName" => $_POST['departmentName'],
                    "login_user_id" => $_SESSION['id'],
                    "login_user_type" => $_SESSION['user_type'],
                    "attendenceStatus" => $_POST['attendance'][$j],
                    "dateTime" => date_create()->format('Y-m-d h:i:s'),
                    "att_date" => date_create()->format('Y-m-d'),
                    "session_table_id" => $_SESSION['currentSession']

                ];
                $insertId = $this->CrudModel->insert(Table::staffattendanceTable, $insertArr);
                $c++;
            }

            if ($c == $totalT) {


                $msgArr = [
                    'class' => 'success',
                    'msg' => $_POST['departmentName'].' Department Attendance Updated Successfully',
                ];
                $this->session->set_userdata($msgArr);
            } else {
                $msgArr = [
                    'class' => 'danger',
                    'msg' => $_POST['departmentName'].' Department Attendance Not Updated Due to this Error. ' . $this->db->last_query(),
                ];
                $this->session->set_userdata($msgArr);

                header("Refresh:1 " . base_url() . "master/staffAttendance");
            }
        }



        ?>

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
                        <div class="col-md-12 mx-auto">
                            <!-- Tabs navs -->
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <!-- <button class="nav-link active" id="0" type="button" role="tab">Select Department</button> -->
                                    <?php
                                    if (isset($departmentList)) {
                                        foreach ($departmentList as $d) { ?>
                                            <button class="nav-link" id="mainid-<?= $d['id']; ?>" data-bs-toggle="tab" data-bs-target="#mainid-<?= $d['id']; ?>" type="button" role="tab" aria-controls="nav-home" aria-selected="true" onclick="showDetails('<?= $d['id']; ?>')"><?= $d['departmentName']; ?></button>
                                    <?php }
                                    } ?>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">

                            </div>

                            <div class="row mt-3">
                                <div class="col-md-8 mx-auto text-align-jusify">
                                    <?php 
                                    // print_r($this->CrudModel->totalEmployeesWorkingDaysAndHolidaysCurrentMonth()); 
                                    // print_r($this->CrudModel->getTotalAttendanceOfEmployeeCurrentMonth(1)); 
                                    ?>
                                </div>
                            </div>
                            <!-- Tabs content -->
                        </div>
                        <!--/.col (left) -->
                        <!-- right column -->
                    </div>
                    <!--/.col (right) -->
                </div>

                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->

        <!-- /.control-sidebar -->

        <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
    </div>
    <?php $this->load->view("adminPanel/pages/footer.php"); ?>

    <script>
        function showDetails(x) {
            // console.log(x);
            $("#nav-tabContent").html("");
            $.ajax({
                url: '<?= base_url() . 'ajax/showEmployeesViaDepartmentId'; ?>',
                method: 'post',
                processData: 'false',
                data: {
                    departmentId: x
                },
                success: function(response) {

                    response = $.parseJSON(response);
                    console.log(response);
                    if (response[0].msg != '') {
                        $("#nav-tabContent").html(`<div class="tab-pane fade show active alert alert-danger mt-2">${response[0].msg}</div>`);
                    } else {
                        let html = `<form method="post">
                            <div class="row">
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <tbody>`;
                            
                        for (let i = 0; i < response.length; i++) {
                            
                            html += `<input type="hidden" name="tId[]" value="${response[i].id}">
                            <input type="hidden" name="empName[]" value="${response[i].employeeName}">
                            <input type="hidden" name="departmentName" value="${response[i].departmentName}">
                            <tr>
                                <td>
                                ${response[i].empId} - ${response[i].employeeName} - ${response[i].departmentName} - ${response[i].designationName}
                                </td>
                                <td><input id="a${i}" value="1" type="radio" class="btn-check ml-5 " name="attendance[${i}]" required autocomplete="off">
                                        <label class="ml-5 " for="a${i}">
                                            Absent
                                        </label>
                                        <input id="p${i}" value="2" type="radio" class="btn-check ml-5 " name="attendance[${i}]" required autocomplete="off">
                                        <label class="ml-5 " for="p${i}">
                                            Present
                                        </label>
                                        <input id="h${i}" value="3" type="radio" class="btn-check ml-5 " name="attendance[${i}]" required autocomplete="off">
                                        <label class="ml-5" for="h${i}">
                                            Half Day
                                        </label>
                                        <input id="l${i}" value="4" type="radio" class="btn-check ml-5 " name="attendance[${i}]" required autocomplete="off">
                                        <label class="ml-5 " for="l${i}">
                                            Leave
                                        </label> </td>
                            </tr>`;

                        }
                        html += `<tr>
                        <td>#</td>
                        <td><button type="submit" name="submit" class="btn btn-primary btn-block btn-lg">Submit</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        </form>`;
                        $("#nav-tabContent").html(`<div class="tab-pane fade show active" >${html}</div>`);
                    }

                },
                error: function(error) {
                    console.log(error);
                }

            });


            // $("#d-"+x).addClass('active');
        }
    </script>