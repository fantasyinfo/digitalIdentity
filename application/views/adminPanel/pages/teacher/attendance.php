<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php $this->load->view("adminPanel/pages/sidebar.php");

        $this->load->model('CrudModel');

        $teachersList = $this->db->query("SELECT name, user_id, id FROM " . Table::teacherTable . " WHERE status = '1'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();


        $c = 0;
        if (isset($_POST['submit'])) {

            if( date('D') == 'Sun') { 
                $msgArr = [
                    'class' => 'danger',
                    'msg' => 'Today is Sunday. Please Mark Attendance in Between Monday to Saturday.' ,
                ];
                $this->session->set_userdata($msgArr);

               header("Refresh:1 " . base_url() . "teacher/attendance");
               exit();
              }

            $today = date_create()->format('Y-m-d');





              // check if today is holiday

                $holiday = $this->db->query("SELECT title FROM " . Table::holidayCalendarTable . " WHERE event_date = '$today' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1")->result_array();

                if (!empty($holiday)) {
                    $msgArr = [
                        'class' => 'danger',
                        'msg' => "Today is $holiday[0]['title'] Holiday. Try on School Working Days" ,
                    ];
                    $this->session->set_userdata($msgArr);
                   header("Refresh:1 " . base_url() . "teacher/attendance");
                   exit();
                    
                }



                // is today already attendance mark
            $alreadyAttendance = $this->db->query("SELECT * FROM " . Table::attendenceTeachersTable . " WHERE status = '1'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND att_date = '$today'")->result_array();

            if(!empty($alreadyAttendance))
            {
                $msgArr = [
                    'class' => 'danger',
                    'msg' => 'Teacher\'s Attendance Already Mark Today',
                ];
                $this->session->set_userdata($msgArr);

               header("Refresh:1 " . base_url() . "teacher/attendance");
               exit();
            }


            $totalT = count($_POST['tId']);
            for ($j = 0; $j < $totalT; $j++) {
                $insertArr = [
                    "schoolUniqueCode" => $_SESSION['schoolUniqueCode'],
                    "tec_id" => $_POST['tId'][$j],
                    "login_user_id" => $_SESSION['id'],
                    "login_user_type" => $_SESSION['user_type'],
                    "attendenceStatus" => $_POST['attendance'][$j],
                    "dateTime" => date_create()->format('Y-m-d h:i:s'),
                    "att_date" => date_create()->format('Y-m-d'),
                    "session_table_id" => $_SESSION['currentSession']

                ];
                $insertId = $this->CrudModel->insert(Table::attendenceTeachersTable, $insertArr);
                $c++;
            }

            if ($c == $totalT) {


                $msgArr = [
                    'class' => 'success',
                    'msg' => 'Teacher\'s Attendance Updated Successfully',
                ];
                $this->session->set_userdata($msgArr);
            } else {
                $msgArr = [
                    'class' => 'danger',
                    'msg' => 'Teacher\'s Attendance Not Updated Due to this Error. ' . $this->db->last_query(),
                ];
                $this->session->set_userdata($msgArr);

               header("Refresh:1 " . base_url() . "teacher/attendance");
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
                            <!-- jquery validation -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Add Correct Details</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="post">

                                    <div class="row">
                                        <div class="card-body">


                                            <div class="col-md-8">
                                                <table class="table">
                                                    <tbody>

                                                        <?php
                                                        $i = 0;
                                                        if (!empty($teachersList)) {
                                                            foreach ($teachersList as $t) { ?>

                                                                <input type="hidden" name="tId[]" value="<?= $t['id']; ?>">
                                                                <tr>
                                                                    <td>
                                                                        <?= $t['name'] . "  " . $t['user_id']; ?>
                                                                    </td>
                                                                    <td>

                                                                        <div class="form-check">

                                                                            <input class="form-check-input ml-3" value="1" type="radio" name="attendance[<?= $i ?>]" required>
                                                                            <label class="form-check-label ml-5" for="flexRadioDefault1">
                                                                                Present
                                                                            </label>

                                                                            <input class="form-check-input ml-3" value="0" type="radio" name="attendance[<?= $i ?>]" required>
                                                                            <label class="form-check-label ml-5" for="flexRadioDefault1">
                                                                                Absent
                                                                            </label>
                                                                        </div>

                                                                    </td>
                                                                </tr>
                                                        <?php $i++;
                                                            }
                                                        }



                                                        ?>


                                                        <tr>
                                                            <td>#</td>
                                                            <td><button type="submit" name="submit" class="btn btn-primary btn-block btn-lg">Submit</button></td>
                                                        </tr>
                                                        <!-- <tr>
                              <td>3</td>
                              <td>John</td>
                          </tr> -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                </form>
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
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->

        <!-- /.control-sidebar -->

        <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
    </div>
    <?php $this->load->view("adminPanel/pages/footer.php"); ?>
    <!-- ./wrapper -->
    <script>
        var ajaxUrl = '<?= base_url() . 'ajax/showCityViaStateId' ?>';

        // load default city
        showCity();

        function showCity() {

            $('#cityData option').remove();
            let stateId = $("#stateIdd").val();
            $.ajax({
                url: ajaxUrl,
                method: 'post',
                processData: 'false',
                data: {
                    stateId: stateId,
                },
                success: function(response) {
                    response = $.parseJSON(response);
                    $('#cityData').append(response);
                },
                error: function(error) {
                    console.log(error);
                }

            });
        }
    </script>
    