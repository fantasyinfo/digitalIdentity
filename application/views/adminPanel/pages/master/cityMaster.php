<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');
    $this->load->model('CrudModel');

    // fetching city data
    $cityData = $this->db->query("SELECT c.*, s.stateName FROM " . Table::cityTable . " c 
    LEFT JOIN " . Table::stateTable . " s ON s.id = c.stateId AND c.schoolUniqueCode = s.schoolUniqueCode
    WHERE c.schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' ORDER BY c.id DESC")->result_array();

    $stateData = $this->db->query("SELECT * FROM " . Table::stateTable . " WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND status = '1' ORDER BY id DESC")->result_array();
    // edit and delete action
    if (isset($_GET['action'])) {

      if ($_GET['action'] == 'edit') {
        $editId = $_GET['edit_id'];
        $editCityData = $this->db->query("SELECT * FROM " . Table::cityTable . " WHERE id='$editId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();
      }


      if ($_GET['action'] == 'delete') {
        $deleteId = $_GET['delete_id'];
        $deleteCityData = $this->db->query("DELETE FROM " . Table::cityTable . " WHERE id='$deleteId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");
        if ($deleteCityData) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'City Deleted Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'City Not Deleted Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "master/cityMaster");
      }

      if ($_GET['action'] == 'status') {
        $status = $_GET['status'];
        $updateId = $_GET['edit_id'];
        $updateStatus = $this->db->query("UPDATE " . Table::cityTable . " SET status = '$status' WHERE id = '$updateId'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

        if ($updateStatus) {
          $msgArr = [
            'class' => 'success',
            'msg' => 'City Status Updated Successfully',
          ];
          $this->session->set_userdata($msgArr);
        } else {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'City Status Not Updated Due to this Error. ' . $this->db->last_query(),
          ];
          $this->session->set_userdata($msgArr);
        }
        header("Refresh:1 " . base_url() . "master/cityMaster");
      }
    }


    // insert new city
    if (isset($_POST['submit'])) {
      $cityName = $_POST['cityName'];
      $stateId = $_POST['stateId'];


      $alreadyCity = $this->db->query("SELECT * FROM " . Table::cityTable . " WHERE cityName = '$cityName'  AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'")->result_array();

      if (!empty($alreadyCity)) {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'This City is already inserted, Please Edit That',
        ];
        $this->session->set_userdata($msgArr);
        header('Location: cityMaster');
        exit(0);
      }

      $insertNewCity = $this->db->query("INSERT INTO " . Table::cityTable . " (schoolUniqueCode,cityName,stateId) VALUES ('{$_SESSION['schoolUniqueCode']}','$cityName','$stateId')");
      if ($insertNewCity) {

        $msgArr = [
          'class' => 'success',
          'msg' => 'New City Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'City Not Added Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "master/cityMaster");
    }

    // update exiting city
    if (isset($_POST['update'])) {
      $cityName = $_POST['cityName'];
      $cityEditId = $_POST['updateCityId'];
      $stateId = $_POST['stateId'];
      $updateCity = $this->db->query("UPDATE " . Table::cityTable . " SET cityName = '$cityName' , stateId = '$stateId' WHERE id = '$cityEditId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

      // echo $this->db->last_query(); die();
      if ($updateCity) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'City Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'City Not Updated Due to this Error. ' . $this->db->last_query(),
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "master/cityMaster");
    }


    // print_r($cityData);


    ?>



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
     
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <?php
          if (!empty($this->session->userdata('msg'))) {
            if ($this->session->userdata('class') == 'success') {
              HelperClass::swalSuccess($this->session->userdata('msg'));
            } else if ($this->session->userdata('class') == 'danger') {
              HelperClass::swalError($this->session->userdata('msg'));
            }


          ?>

            <div class="alert alert-<?= $this->session->userdata('class') ?> alert-dismissible fade show" role="alert">
              <strong>New Message!</strong> <?= $this->session->userdata('msg');
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
          <div class="card">
            <div class="card-header">City Master</div>
            <div class="card-body">
              <div class="row">
                <!-- left column -->
                <?php //print_r($data['class']);
                ?>
                <div class="col-md-4 mx-auto">

                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Add / Edit City</h3>
                    </div>
                    <div class="card-body">
                      <div class="col-md-12 mx-auto">
                        <form method="post" action="">
                          <?php
                          if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
                            <input type="hidden" name="updateCityId" value="<?= $editId ?>">
                          <?php }

                          ?>
                          <div class="form-group">
                            <label>Select State </label>
                            <select name="stateId" id="stateId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                              <option disabled>States</option>
                              <?php
                              $selected = '';
                              if (isset($stateData)) {
                                foreach ($stateData as $state) {
                                  if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                    if ($editCityData[0]['stateId'] == $state['id']) {
                                      $selected = 'selected';
                                    } else {
                                      $selected = '';
                                    }
                                  }


                              ?>
                                  <option <?= $selected; ?> value="<?= $state['id'] ?>"><?= $state['stateName'] ?></option>
                              <?php }
                              }

                              ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <input type="text" name="cityName" value="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                                        echo $editCityData[0]['cityName'];
                                                                      } ?>" class="form-control" id="name" placeholder="Enter city name" required>
                          </div>
                          <div class="form-group">
                            <button type="submit" name="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                          echo 'update';
                                                        } else {
                                                          echo 'submit';
                                                        } ?>" class="btn mybtnColor btn-block">Save</button>
                          </div>

                        </form>
                      </div>

                    </div>
                  </div>
                </div>





                <div class="col-md-8 ">
                  <div class="card">
                    <div class="card-header">
                      <h3 class="card-title">Showing All City Data</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <div class="table-responsive">
                        <table id="cityDataTable" class="table bg-white mb-0 align-middle">
                          <thead class="bg-white">
                            <tr>
                              <th>Id</th>
                              <!-- <th>City Id</th> -->
                              <th>City Name</th>
                              <th>State Name</th>
                              <th>Status</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php if (isset($cityData)) {
                              $i = 0;
                              foreach ($cityData as $cn) { ?>
                                <tr>
                                  <td><?= ++$i; ?></td>
                                  <!-- <td><?= $cn['id']; ?></td> -->
                                  <td><?= $cn['cityName']; ?></td>
                                  <td><?= $cn['stateName']; ?></td>
                                  <td>
                                    <a href="?action=status&edit_id=<?= $cn['id']; ?>&status=<?php echo ($cn['status'] == '1') ? '2' : '1'; ?>" class="badge badge-<?php echo ($cn['status'] == '1') ? 'success' : 'danger'; ?>" data-toggle="tooltip" data-placement="top" title="Status">
                                      <?php echo ($cn['status'] == '1') ? 'Active' : 'Inactive'; ?>
                                  </td>
                                  <td>
                                    <a href="?action=edit&edit_id=<?= $cn['id']; ?>" class="text-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa-solid fa-pencil"></i></a>

                                    &nbsp; &nbsp;<a href="?action=delete&delete_id=<?= $cn['id']; ?>" class="text-danger" onclick="return confirm('Are you sure want to delete this?');" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa-solid fa-trash"></i></a>
                                  </td>
                                </tr>
                            <?php  }
                            } ?>

                          </tbody>

                        </table>
                      </div>
                    </div>
                    <!-- /.card-body -->
                  </div>
                </div>




                <!--/.col (right) -->
              </div>

              <!-- /.container-fluid -->
            </div>
          </div>
        </div>


      </div>
    </div>
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->

  <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>
  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->
  <script>
    // var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';


    $("#cityDataTable").DataTable();
  </script>