<style>
#showEmpTable {
    display: none;
}
</style>

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

                        $departmentData = $this->db->query("SELECT * FROM " . Table::departmentTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' ORDER BY id DESC")->result_array();

                        $monthData = $this->db->query("SELECT * FROM " . Table::monthTable . " WHERE status = '1' ")->result_array();

                        if (!empty($this->session->userdata('msg'))) { ?>

                        <div class="alert alert-<?= $this->session->userdata('class') ?> alert-dismissible fade show"
                            role="alert">
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
                                    <h3 class="card-title">Select Employee Details</h3>

                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Select Month </label>
                                            <select name="monthId" id="monthId"
                                                class="form-control  select2 select2-danger" required
                                                data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <option>Please Select Month</option>
                                                <?php
                                                $selected = '';
                                                if (isset($monthData)) {
                                                    foreach ($monthData as $month) {
                                                            if (date('m') == $month['id']) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            }
                                                        ?>
                                                <option <?= $selected; ?> value="<?= $month['id'] ?>">
                                                    <?= $month['monthName'] ?></option>
                                                <?php }
                                                }

                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Select Year </label>
                                            <select name="yearId" id="yearId"
                                                class="form-control  select2 select2-danger" required
                                                data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <option>Please Select Year</option>
                                                <?php
                                                $selected = '';
                                                $yearArr = ['2021','2022','2023','2024','2025'];
                                                
                                                if (isset($yearArr)) {
                                                    foreach ($yearArr as $year => $val) {
                                                            if (date('Y') ==  $val) {
                                                                $selectedY = 'selected';
                                                            } else {
                                                                $selectedY = '';
                                                            }
                                                        ?>
                                                <option <?= $selectedY; ?> value="<?= $val ?>"><?=  $val ?></option>
                                                <?php }
                                                }

                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Select Department </label>
                                            <select name="departmentId" id="departmentId"
                                                class="form-control  select2 select2-danger" required
                                                data-dropdown-css-class="select2-danger" style="width: 100%;"
                                                onchange="showDesignation()">
                                                <option>Please Select Department</option>
                                                <?php
                                                $selected = '';
                                                if (isset($departmentData)) {
                                                    foreach ($departmentData as $department) {
                                                        if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                            if ($editCityData[0]['departmentId'] == $department['id']) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            }
                                                        }


                                                ?>
                                                <option <?= $selected; ?> value="<?= $department['id'] ?>">
                                                    <?= $department['departmentName'] ?></option>
                                                <?php }
                                                }

                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Select Designation</label>
                                            <select id="designationId" name="designationId"
                                                class="form-control  select2 select2-danger" required
                                                data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <option>Please Select Designation</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3 pt-4">
                                            <button type="submit" id="showEmployees"
                                                class="btn btn-primary btn-block ">Filter</button>
                                        </div>
                                    </div>


                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->

                            </div>
                        </div>

                        <div class="col-md-12" id="showEmpTable">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Showing All Employees Salary Data</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="listDatatable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Employee Id</th>
                                                <th>Employee Name</th>
                                                <th>Department</th>
                                                <th>Designation</th>
                                                <th>Salary To Paid</th>
                                                <th>Total Deducations</th>
                                                <th>Total Allowances</th>
                                                <th>View Details Salary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>

                                    </table>
                                </div>
                                <!-- /.card-body -->
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


        <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog border border-success modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Employee Salary Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="employeeDetails">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-danger btn-lg btn-block"
                            data-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </div>
            </div>
        </div>













        <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
    </div>
    <?php $this->load->view("adminPanel/pages/footer.php"); ?>
    <!-- ./wrapper -->
    <script>
    var ajaxUrlForEmployeeList = '<?= base_url() . 'ajax/showEmployeesViaDepartmentIdAndDesignationId'?>';


    let monthArr = {
        1: 'January',
        2: 'February',
        3: 'March',
        4: 'April',
        5: 'May',
        6: 'June',
        7: 'July',
        8: 'August',
        9: 'September',
        10: 'October',
        11: 'November',
        12: 'December'
    };

    function showDesignation() {
        $('#designationId').html("");
        var departmentId = $("#departmentId").val();
        if (departmentId != '') {
            console.log(departmentId);
            $.ajax({
                url: '<?= base_url() . 'ajax/showDesignationsViaDepartmentId'; ?>',
                method: 'post',
                processData: 'false',
                data: {
                    departmentId: departmentId
                },
                success: function(response) {
                    console.log(response);
                    response = $.parseJSON(response);
                    $('#designationId').append(response);
                },
                error: function(error) {
                    console.log(error);
                }

            });
        }
    }



    $("#showEmployees").click(function(e) {
        $("#listDatatable").DataTable().destroy();
        e.preventDefault();
        let departmentId = $("#departmentId").val();
        let designationId = $("#designationId").val();
        let monthId = $("#monthId").val();
        let yearId = $("#yearId").val();

        console.log(monthId);

        if (departmentId != 'Please Select Department' && designationId != 'Please Select Designation' &&
            monthId != 'Please Select Month' && yearId != 'Please Select Year' && departmentId != null &&
            designationId != null && monthId != null && yearId != null) {
            $("#showEmpTable").show();
            $("#listDatatable").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                dom: 'lBfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ],
                lengthMenu: [10, 50, 100, 500, 1000, 2000, 5000, 10000, 50000, 100000],
                pageLength: 10,
                processing: true,
                serverSide: true,
                searching: false,
                paging: true,
                ajax: {
                    method: 'post',
                    url: ajaxUrlForEmployeeList,
                    data: {
                        departmentId: departmentId,
                        designationId: designationId,
                        monthId: monthId,
                        yearId: yearId
                    },
                    error: function() {
                        console.log('something went wrong.');
                    }
                }
            });
        }
    });



    function checkDetails(x) {
        console.log(x);
        let monthId = $("#monthId").val();
        let yearId = $("#yearId").val();
        $.ajax({
            url: '<?= base_url() . 'ajax/checkEmployeeSalaryById'; ?>',
            method: 'post',
            processData: 'false',
            data: {
                id: x,
                monthId: monthId,
                yearId: yearId
            },
            success: function(response) {
                // console.log(response);
                response = $.parseJSON(response);
                console.log(response);
                console.log(response.employeeDetails);
                let showDetailsHtml = `<table class="table table-striped">
                                    <tbody>
                                        <tr>
                                          <td colspan="2"><b>Salary Details on ${monthArr[monthId]} - ${yearId}</b> </td>
                                          <td colspan="2"> ${response.employeeDetails.empId} - ${response.employeeDetails.employeeName}</td>
                                        
                                        </tr>
                                       
                                        <tr>
                                          <td scope="row font-bold" class="font-bold"><b>Working Days</b></td>
                                          <td>${response.workingDays.totalWorkingDays} Days</td>
                                          <td scope="row font-bold" class="font-bold"><b>Total Holidays Including Sunday</b></td>
                                          <td>${response.workingDays.totalHolidaysIncludingSundays} Days</td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold"><b>Present Days</b></td>
                                          <td>${response.attendanceData.present}</td>
                                          <td scope="row font-bold" class="font-bold"><b>Absent Days</b></td>
                                          <td>${response.attendanceData.absent}</td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold"><b>Leaves Days</b></td>
                                          <td>${response.attendanceData.leaves}</td>
                                          <td scope="row font-bold" class="font-bold"><b>Half Days</b></td>
                                          <td>${response.attendanceData.helfDay}</td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold text-danger" ><b>Deducations</b></td>
                                          <td colspan="3">Professinal Tax : ₹ ${response.deducations.ptpm} , P.F.: ₹ ${response.deducations.pfpm}  , TDS : ₹ ${response.deducations.tds} , <b>Total: ₹ ${response.deducations.total} </b></td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold text-success"><b>Allowances</b></td>
                                          <td colspan="3">DA : ₹ ${response.allowances.da}, HRA : ₹ ${response.allowances.hra}, CA : ₹ ${response.allowances.ca}, MA : ₹ ${response.allowances.ma}, SA : ₹ ${response.allowances.sa}, <b>Total: ₹ ${response.allowances.total}</b></td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold text-success"><b>Basic Pay</b></td>
                                          <td colspan="3"><b>₹ ${response.basicPay}</b></td>
                                        </tr>
                                        <tr>
                                          <td scope="row font-bold" class="font-bold  text-success"><b>Total Salary</b></td>
                                          <td colspan="3"> <h2>₹ ${response.totalSalaryToPay}</h2></td>
                                        </tr>
                                    </tbody>
                                  </table>
                                  
                                  <form method="POST">
                                  <input type="hidden" name="bSalary" value="${response.employeeDetails.basicSalaryMonth}">
                                  <input type="hidden" name="da" value="${response.allowances.da}">
                                  <input type="hidden" name="ca" value="${response.allowances.ca}">
                                  <input type="hidden" name="hra" value="${response.allowances.hra}">
                                  <input type="hidden" name="ma" value="${response.allowances.ma}">
                                  <input type="hidden" name="sa" value="${response.allowances.sa}">
                                  <input type="hidden" name="tld" value="${response.leaves.leaveAmountToDeducat}">
                                  <input type="hidden" name="pt" value="${response.deducations.ptpm}">
                                  <input type="hidden" name="pf" value="${response.deducations.pfpm}">
                                  <input type="hidden" name="tds" value="${response.deducations.tds}">
                                  <input type="hidden" name="employeeName" value="${response.employeeDetails.employeeName}">
                                  <input type="hidden" name="doj" value="${response.employeeDetails.doj}">
                                  <input type="hidden" name="totalD" value="${response.deducations.total}">
                                  <input type="hidden" name="totalA" value="${response.allowances.total}">
                                  <input type="hidden" name="tToPay" value="${response.totalSalaryToPay}">
                                  <input type="hidden" name="tInWords" value="${response.totalSalaryToPay}">
                                  <input type="hidden" name="tSalary" value="${response.basicPay}">
                                  <input type="hidden" name="monthId" value="${monthId}">
                                  <input type="hidden" name="yearId" value="${yearId}">
                                  <input type="hidden" name="salaryId" value="${response.id}">
                                  <input type="submit" class="btn btn-success" name="submit" value="Generate Salary Slip">
                           
                                  </form>
                                  `;
                $("#employeeDetails").html(showDetailsHtml);
                $("#detailsModal").modal('show');
            },
            error: function(error) {
                console.log(error);
            }

        });
    }
    </script>

<?php 

if(isset($_POST['submit']))
{
    $monthId = $_POST['monthId'];
    $yearId = $_POST['yearId'];
    $salaryEmpId = $_POST['salaryId'];
    $randomToken = HelperClass::generateRandomToken();
    $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
    $todayDate = date('Y-m-d');
   
    // first check if salary slip is already generated for this month

    $already = $this->db->query("SELECT empId FROM ".Table::checkSalarySlipTable." WHERE empId = '$salaryEmpId' AND schoolUniqueCode = '$schoolUniqueCode' AND status = '1' AND generateDate = '$todayDate'")->result_array();

    if(!empty($already))
    {
        $msgArr = [
            'class' => 'danger',
            'msg' => 'Salary Slip is Already Generated For This Employee Today, Please Try After Today.',
        ];
        $this->session->set_userdata($msgArr);

        header("Refresh:1 " . base_url() . "master/checkSalary");
        die();
    }

    $insertSalaryArr = [
        'schoolUniqueCode' => $schoolUniqueCode,
        'empId' => $salaryEmpId,
        'month' => $monthId,
        'year' => $yearId,
        'bSalary' => $_POST['bSalary'],
        'da' => $_POST['da'],
        'ca' => $_POST['ca'],
        'hra' => $_POST['hra'],
        'ma' => $_POST['ma'],
        'sa' => $_POST['sa'],
        'totalLeaveDeducation' => $_POST['tld'],
        'pt' =>$_POST['pt'],
        'pf' => $_POST['pf'],
        'tds' =>$_POST['tds'],
        'empName' => $_POST['employeeName'],
        'doj' => $_POST['doj'],
        'totalSalary' => $_POST['tSalary'],
        'totalDeducation' => $_POST['totalD'],
        'totalAllowances' => $_POST['totalA'],
        'totalPaidSalary' => $_POST['tToPay'],
        'tpsInWords' => $this->CrudModel->numberToWordsCurrency($_POST['tInWords']),
        'generateDate' => $todayDate,
        'session_table_id' => $_SESSION['currentSession']

    ];


    $c = $this->CrudModel->insert(Table::checkSalarySlipTable,$insertSalaryArr);


    $filterToken = "token=".$randomToken."-m-".$monthId."-y-".$yearId."-i-".$salaryEmpId ."-iId-".$c;

    $insertArr = [
    'schoolUniqueCode' => $schoolUniqueCode,
    'token' => $filterToken,
    'for_what' => 'Salary Slip',
    'insertId' => $c
    ];

    $d = $this->CrudModel->insert(Table::tokenFilterTable,$insertArr);

    if($d){
        header("Location: " . base_url('salarySlip?tec_id=') . $filterToken);
    }

}

?>
<!-- <a href="<?=base_url('salarySlip?tec_id=') . rand(1111,9999) . '-' . rand(1111,9999) . '-' ; ?>${response.id}<?= '-random_token-' . rand(111111111,999999999) ;?>-${monthId}-${yearId}-salarySlip-98754-empICan-445" class="btn btn-block btn-lg btn-succs">Download Salary Slip</a> -->