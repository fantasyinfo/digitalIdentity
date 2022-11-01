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
                                    <h3 class="card-title">Select Employee Details</h3>

                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label>Select Department </label>
                                            <select name="departmentId" id="departmentId" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="showDesignation()">
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
                                                        <option <?= $selected; ?> value="<?= $department['id'] ?>"><?= $department['departmentName'] ?></option>
                                                <?php }
                                                }

                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Select Designation</label>
                                            <select id="designationId" name="designationId" class="form-control  select2 select2-danger" required data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <option>Please Select Designation</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4 pt-4">
                                            <button type="submit" id="showEmployees" class="btn btn-primary btn-block ">Filter</button>
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

        <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
    </div>
    <?php $this->load->view("adminPanel/pages/footer.php"); ?>
    <!-- ./wrapper -->
    <script>

        var ajaxUrlForEmployeeList = '<?= base_url() . 'ajax/showEmployeesViaDepartmentIdAndDesignationId'?>';
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


            if (departmentId != 'Please Select Department' && designationId != 'Please Select Designation' && departmentId != null && designationId != null) {
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
                        },
                        error: function() {
                            console.log('something went wrong.');
                        }
                    }
                });
            }
        })
    </script>