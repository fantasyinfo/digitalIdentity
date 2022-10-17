<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');

  // fetching city data
    $feesData = $this->db->query("SELECT * FROM " . Table::feesTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status != '4' ORDER BY id DESC")->result_array();

    // classes
    $classData = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE status = '1' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ORDER BY id DESC")->result_array();

    // edit and delete action
    if(isset($_GET['action']))
    {
      // fetch city for edit the  city 
      if($_GET['action'] == 'edit')
      {
        $editId = $_GET['edit_id'];
        $editCityData = $this->db->query("SELECT * FROM " . Table::feesTable . " WHERE id='$editId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
      }

      // delete the city
      if($_GET['action'] == 'delete')
      {
        $deleteId = $_GET['delete_id'];
        $deleteCityData = $this->db->query("DELETE FROM " . Table::feesTable . " WHERE id='$deleteId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ");
        if($deleteCityData)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Fees Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Fees Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/feesMaster");
      }

      if($_GET['action'] == 'status')
      {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::feesTable . " SET status = '$status' WHERE id = '$updateId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if($updateStatus)
        {
          $msgArr = [
            'class' => 'success',
            'msg' => 'Fees Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Fees Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 ".base_url()."master/feesMaster");
      }

    }


    // insert new city
    if(isset($_POST['submit']))
    {
      $classId = $_POST['classId'];
      $feesAmt = $_POST['fees_amt'];
      $tution_fees_amt = ($_POST['tution_fees_amt']) ? $_POST['tution_fees_amt'] : 0.0;
      $reg_fees = ($_POST['reg_fees'])? $_POST['reg_fees'] : 0.0;
      $adm_fees = ($_POST['adm_fees'])? $_POST['adm_fees'] : 0.0;
      $id_card_fees = ($_POST['id_card_fees'])? $_POST['id_card_fees'] : 0.0;
      $development_fees = ($_POST['development_fees'])? $_POST['development_fees'] : 0.0;
      $annual_function_fees = ($_POST['annual_function_fees'])? $_POST['annual_function_fees'] : 0.0;
      $book_and_stationary_fees = ($_POST['book_and_stationary_fees'])? $_POST['book_and_stationary_fees'] : 0.0;
      $uniform_fees = ($_POST['uniform_fees'])? $_POST['uniform_fees'] : 0.0;
      $worksheet_examination_fees = ($_POST['worksheet_examination_fees'])? $_POST['worksheet_examination_fees'] : 0.0;
      $extra_curricular_fees = ($_POST['extra_curricular_fees'])? $_POST['extra_curricular_fees'] : 0.0;
      $smart_class_fees = ($_POST['smart_class_fees'])? $_POST['smart_class_fees'] : 0.0;
      $transport_fees = ($_POST['transport_fees'])? $_POST['transport_fees'] : 0.0;


      $alreadyFees = $this->db->query("SELECT * FROM " . Table::feesTable . " WHERE class_id = '$classId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

      if(!empty($alreadyFees))
      {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'This Class Fees is already inserted, Please Edit That',
          ];
          $this->session->set_userdata($msgArr);
          header('Location: feesMaster');
          exit(0);
      }

      $insertNewFees = $this->db->query("INSERT INTO " . Table::feesTable . " (schoolUniqueCode,class_id,fees_amt,tution_fees_amt,reg_fees,adm_fees,id_card_fees,development_fees,annual_function_fees,book_and_stationary_fees,uniform_fees,worksheet_examination_fees,extra_curricular_fees,smart_class_fees,transport_fees) VALUES ('{$_SESSION['schoolUniqueCode']}','$classId','$feesAmt','$tution_fees_amt','$reg_fees','$adm_fees','$id_card_fees','$development_fees','$annual_function_fees','$book_and_stationary_fees','$uniform_fees','$worksheet_examination_fees','$extra_curricular_fees','$smart_class_fees','$transport_fees')");
      if($insertNewFees)
      {
      
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Fees Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Fees Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."master/feesMaster");
    }

    // update exiting city
    if(isset($_POST['update']))
    {
      $classId = $_POST['classId'];
      $feesAmt = $_POST['fees_amt'];
      $tution_fees_amt = ($_POST['tution_fees_amt']) ? $_POST['tution_fees_amt'] : 0.0;
      $reg_fees = ($_POST['reg_fees'])? $_POST['reg_fees'] : 0.0;
      $adm_fees = ($_POST['adm_fees'])? $_POST['adm_fees'] : 0.0;
      $id_card_fees = ($_POST['id_card_fees'])? $_POST['id_card_fees'] : 0.0;
      $development_fees = ($_POST['development_fees'])? $_POST['development_fees'] : 0.0;
      $annual_function_fees = ($_POST['annual_function_fees'])? $_POST['annual_function_fees'] : 0.0;
      $book_and_stationary_fees = ($_POST['book_and_stationary_fees'])? $_POST['book_and_stationary_fees'] : 0.0;
      $uniform_fees = ($_POST['uniform_fees'])? $_POST['uniform_fees'] : 0.0;
      $worksheet_examination_fees = ($_POST['worksheet_examination_fees'])? $_POST['worksheet_examination_fees'] : 0.0;
      $extra_curricular_fees = ($_POST['extra_curricular_fees'])? $_POST['extra_curricular_fees'] : 0.0;
      $smart_class_fees = ($_POST['smart_class_fees'])? $_POST['smart_class_fees'] : 0.0;
      $transport_fees = ($_POST['transport_fees'])? $_POST['transport_fees'] : 0.0;
      $cityEditId = $_POST['updateCityId'];

      $updateFees = $this->db->query("UPDATE " . Table::feesTable . " SET class_id = '$classId',fees_amt = '$feesAmt' ,tution_fees_amt = '$tution_fees_amt',reg_fees = '$reg_fees',adm_fees = '$adm_fees',id_card_fees = '$id_card_fees',development_fees = '$development_fees',
      annual_function_fees = '$annual_function_fees', book_and_stationary_fees = '$book_and_stationary_fees',uniform_fees = '$uniform_fees',
      worksheet_examination_fees = '$worksheet_examination_fees', extra_curricular_fees = '$extra_curricular_fees', smart_class_fees = '$smart_class_fees',transport_fees = '$transport_fees'
      WHERE id = '$cityEditId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
      if($updateFees)
      {
        $msgArr = [
          'class' => 'success',
          'msg' => 'Fees Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      }else
      {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Fees Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 ".base_url()."master/feesMaster");
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
              if(!empty($this->session->userdata('msg')))
              {
                
                if($this->session->userdata('class') == 'success')
                 {
                   HelperClass::swalSuccess($this->session->userdata('msg'));
                 }else if($this->session->userdata('class') == 'danger')
                 {
                   HelperClass::swalError($this->session->userdata('msg'));
                 }
                
                ?>

              <div class="alert alert-<?=$this->session->userdata('class')?> alert-dismissible fade show" role="alert">
                <strong>New Message!</strong> <?=$this->session->userdata('msg')?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
              $this->session->unset_userdata('class') ;
              $this->session->unset_userdata('msg') ;
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
            <div class="col-md-12 mx-auto">
              <!-- jquery validation -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Add / Edit Fees</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->


                <div class="row">
                  <div class="card-body">
                    <form method="post" action="">
                    <?php 
                    if(isset($_GET['action']) && $_GET['action'] == 'edit')
                    {?>
                     <input type="hidden" name="updateCityId" value="<?=$editId?>">
                    <?php }
                    
                    ?>
                      <div class="row">
                        <table class="table  col-md-8 ">
                          <thead>
                              <th>
                               Options
                              </th>
                              <th>
                                Values
                              </th>

                          </thead>
                          <tbody>
                           
                            <tr>
                              <td>
                              <label>Select Class </label>
                              </td>
                              <td>
                              <select name="classId" class="form-control  select2 select2-danger" required  data-dropdown-css-class="select2-danger" style="width: 100%;">
                                <?php 
                                if(isset($classData))
                                {
                                  $selectedClass = '';
                                  foreach($classData as $class)
                                  { 
                                    if(isset($editTeachersSubjectData) && $editTeachersSubjectData[0]['teacher_id'] == $class['id'])
                                    {
                                      $selectedClass = 'selected';
                                    }
                                    ?>
                                    <option <?= $selectedClass?> value="<?=$class['id']?>"><?=$class['className']?></option>
                                <?php }
                                }
                                
                                ?>
                                
                              </select>
                              </td>
                            </tr>
                            <tr>
                              <td>
                              <label>Please Enter Fees Amount <b> Per Month </b> </label>
                              </td>
                              <td>
                              <input type="number" name="fees_amt" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['fees_amt'];}?>" class="form-control" id="name" placeholder="Enter Fees Amount Per Month" required>
                              </td>
                            </tr>
                            <tr>
                              <td>
                              <label>Please Enter Tution Fees Amount <b> Per Month </b> </label>
                              </td>
                              <td>
                              <input type="number" name="tution_fees_amt" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['tution_fees_amt'];}?>" class="form-control" id="name" placeholder="Enter Fees Amount Per Month" required>
                              </td>
                            </tr>
                            <tr>
                              <td><label>Please Enter Transport Fees Amount <b> Per Month </b></label> </td>
                              <td><input type="number"  class="form-control" name="transport_fees" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['transport_fees'];}?>" placeholder="Enter Fees Amount Per Month"></td>
                            </tr>
                            <tr>
                              <td><label>Registration Fees</label></td>
                              <td><input type="number"  class="form-control" name="reg_fees" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['reg_fees'];}?>" placeholder="Enter Fees Amount"></td>
                            </tr>
                            <tr>
                              <td><label>Admission Fees</label></td>
                              <td><input type="number"  class="form-control" name="adm_fees" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['adm_fees'];}?>" placeholder="Enter Fees Amount"></td>
                            </tr>
                            <tr>
                              <td><label>ID Card Fee</label></td>
                              <td><input type="number" class="form-control" name="id_card_fees" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['id_card_fees'];}?>" placeholder="Enter Fees Amount"></td>
                            </tr>
                            <tr>
                              <td><label>Development Fees</label></td>
                              <td><input type="number"  class="form-control" name="development_fees" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['development_fees'];}?>" placeholder="Enter Fees Amount"></td>
                            </tr>
                            <tr>
                              <td><label>Annual Function Fees</label></td>
                              <td><input type="number"  class="form-control" name="annual_function_fees" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['annual_function_fees'];}?>" placeholder="Enter Fees Amount"></td>
                            </tr>
                            <tr>
                              <td><label>Books & Stationary Fees</label></td>
                              <td><input type="number"  class="form-control" name="book_and_stationary_fees" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['book_and_stationary_fees'];}?>" placeholder="Enter Fees Amount"></td>
                            </tr>
                            <tr>
                              <td><label>Uniform Charges</label></td>
                              <td><input type="number"  class="form-control" name="uniform_fees" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['uniform_fees'];}?>" placeholder="Enter Fees Amount"></td>
                            </tr>
                            <tr>
                              <td><label>Worksheet & Board Examination Fees</label></td>
                              <td><input type="number"  class="form-control" name="worksheet_examination_fees" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['worksheet_examination_fees'];}?>" placeholder="Enter Fees Amount"></td>
                            </tr>
                            <tr>
                              <td><label>Extra Curricular Activities Fees</label></td>
                              <td><input type="number"  class="form-control" name="extra_curricular_fees" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['extra_curricular_fees'];}?>" placeholder="Enter Fees Amount"></td>
                            </tr>
                            <tr>
                              <td><label>Smart Class Activity Fees</label></td>
                              <td><input type="number"  class="form-control" name="smart_class_fees" value="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo $editCityData[0]['smart_class_fees'];}?>" placeholder="Enter Fees Amount"></td>
                            </tr>
                         
                          
                          </tbody>
                        </table>
                      <div class="form-group col-md-6">
                        <label></label>
                     
                        </div>
                        <div class="form-group col-md-6">
                        <label></label>
                         
                        </div>
                        <div class="form-group col-md-12">
                          <button type="submit" name="<?php if(isset($_GET['action']) && $_GET['action'] == 'edit'){ echo 'update';}else{echo 'submit';}?>" class="btn btn-primary btn-lg btn-block">Submit</button>
                        </div>
                      </div>
                    </form>
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
                      <h3 class="card-title">Showing All Class Fees</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="cityDataTable" class="table table-bordered table-striped table-responsive">
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>Fees Id</th>
                            <th>Class</th>
                            <th>Fees Per Month</th>
                            <th>Tution Fees Per Month</th>
                            <th>Transport Fees Per Month</th>
                            <th>Registration Fees</th>
                            <th>Admission Fees</th>
                            <th>ID Card Fees</th>
                            <th>Development Fees</th>
                            <th>Annual Function Fees</th>
                            <th>Books & Stationary Fees</th>
                            <th>Uniform Charges Fees</th>
                            <th>Worksheet & Board Fees</th>
                            <th>Extra Curricular Fees</th>
                            <th>Smart Class Fees</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (isset($feesData)) {
                            $i = 0;
                            foreach ($feesData as $cn) { ?>
                              <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $cn['id'];?></td>
                                <td><?php $cf = $this->db->query("SELECT * FROM " . Table::classTable . " WHERE id='{$cn['class_id']}' AND status = '1'")->result_array();
                                echo $cf[0]['className'];?>
                                </td>
                                <td>₹ <?= number_format($cn['fees_amt'],2);?>/- </td>
                                <td>₹ <?= number_format($cn['tution_fees_amt'],2);?>/- </td>
                                <td>₹ <?= number_format($cn['transport_fees'],2);?>/- </td>
                                <td>₹ <?= number_format($cn['reg_fees'],2);?>/- </td>
                                <td>₹ <?= number_format($cn['adm_fees'],2);?>/- </td>
                                <td>₹ <?= number_format($cn['id_card_fees'],2);?>/- </td>
                                <td>₹ <?= number_format($cn['development_fees'],2);?>/- </td>
                                <td>₹ <?= number_format($cn['annual_function_fees'],2);?>/- </td>
                                <td>₹ <?= number_format($cn['book_and_stationary_fees'],2);?>/- </td>
                                <td>₹ <?= number_format($cn['uniform_fees'],2);?>/- </td>
                                <td>₹ <?= number_format($cn['worksheet_examination_fees'],2);?>/- </td>
                                <td>₹ <?= number_format($cn['extra_curricular_fees'],2);?>/- </td>
                                <td>₹ <?= number_format($cn['smart_class_fees'],2);?>/- </td>
                                <td>
                                <a href="?action=status&edit_id=<?= $cn['id'];?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1';?>"
                                    class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger';?>">
                                    <?php  echo ($cn['status'] == '1')? 'Active' : 'Inactive';?>
                                </td>
                                <td>
                                  <a href="?action=edit&edit_id=<?= $cn['id'];?>" class="btn btn-warning">Edit</a>
                                  <a href="?action=delete&delete_id=<?= $cn['id'];?>" class="btn btn-danger" onclick="return confirm('Are you sure want to delete this?');">Delete</a>
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
  <?php $this->load->view("adminPanel/pages/footer.php");?>
  <!-- ./wrapper -->
  <script>
    // var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';


    $("#cityDataTable").DataTable();
  </script>