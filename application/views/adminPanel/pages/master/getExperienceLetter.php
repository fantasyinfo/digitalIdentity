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
                                                data-dropdown-css-class="select2-danger" style="width: 100%;" onchange="showEmployeesData()">
                                                <option>Please Select Designation</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Select Employee</label>
                                            <select id="employeeId" name="employeeId"
                                                class="form-control  select2 select2-danger" required
                                                data-dropdown-css-class="select2-danger" style="width: 100%;">
                                                <option>Please Select Employee</option>
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

                     <div class="container">
                        <div class="row">
                            <div class="col-md-12 card card-body">
                                <form method="POST">
                         <textarea name="bodyC" class="form-control" rows="20">This letter certifies that Mr. Ms. ABC was an employee in the role of engineer with LearningContainer from 1 Apr 2012 up to 1 June 2020.Mr. Ms. ABC was a great employee in our company. We were very proud of him/her. For further inquiry and verification, feel free to contact our office. I have given all of our contact numbers and email ids so you can contact us in any way you are comfortable with.</textarea>
                         <input type="submit" class="mt-2 btn btn-primary btn-block" name="submit" value="Generate Experience Letter">
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
    function showEmployeesData() {
        $('#employeeId').html("");
        var departmentId = $("#departmentId").val();
        var designationId = $("#designationId").val();
        if (departmentId != '' && designationId != '') {
            console.log(departmentId);
            $.ajax({
                url: '<?= base_url() . 'ajax/showEmployeesViaDepAndDesId'; ?>',
                method: 'post',
                processData: 'false',
                data: {
                    departmentId: departmentId,
                    designationId:designationId
                },
                success: function(response) {
                    console.log(response);
                    response = $.parseJSON(response);
                    $('#employeeId').append(response);
                },
                error: function(error) {
                    console.log(error);
                }

            });
        }
    }



    $("#showEmployees").click(function(e) {
       
        e.preventDefault();
        let departmentId = $("#departmentId").val();
        let designationId = $("#designationId").val();
        let employeeId = $("#employeeId").val();
     

        
    });

    </script>
