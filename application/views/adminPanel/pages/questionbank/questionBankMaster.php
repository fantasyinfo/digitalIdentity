<style>
  #mcq {
    display: none;
  }
</style>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->

    <?php $this->load->view('adminPanel/pages/navbar.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php $this->load->view("adminPanel/pages/sidebar.php");

    $this->load->library('session');
    $this->load->model('CrudModel');

    // fetching fees type data
    $booksData = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::booksTable . " WHERE status != '4' ORDER BY id DESC");





    // edit and delete action
    if (isset($_GET['action'])) {

      if ($_GET['action'] == 'edit') {
        $editId = $this->CrudModel->sanitizeInput($_GET['edit_id']);

        $editFeeTypeData =   $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::chaptersTable . " WHERE id='$editId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' LIMIT 1");
      }


      // if ($_GET['action'] == 'delete') {

      //   $deleteId = $this->CrudModel->sanitizeInput($_GET['delete_id']);

      //   $deleteFeeTypeData = $this->CrudModel->runQueryIUD("DELETE FROM " . Table::newfeestypesTable . " WHERE id='$deleteId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

      //   if ($deleteFeeTypeData) {
      //     $msgArr = [
      //       'class' => 'success',
      //       'msg' => 'Fees Type Deleted Successfully',
      //     ];
      //     $this->session->set_userdata($msgArr);
      //   } else {
      //     $msgArr = [
      //       'class' => 'danger',
      //       'msg' => 'Fees Type Deleted Try Again.'
      //     ];
      //     $this->session->set_userdata($msgArr);
      //   }
      //   header("Refresh:1 " . base_url() . "feesManagement/feeTypeMaster");
      // }

      // if ($_GET['action'] == 'status') {

      //   $status = $this->CrudModel->sanitizeInput($_GET['status']);
      //   $updateId = $this->CrudModel->sanitizeInput($_GET['edit_id']);

      //   $updateStatus = $this->CrudModel->runQueryIUD("UPDATE " . Table::newfeestypesTable . " SET status = '$status' WHERE id = '$updateId' AND schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}'");

      //   if ($updateStatus) {
      //     $msgArr = [
      //       'class' => 'success',
      //       'msg' => 'Fee Type Status Updated Successfully',
      //     ];
      //     $this->session->set_userdata($msgArr);
      //   } else {
      //     $msgArr = [
      //       'class' => 'danger',
      //       'msg' => 'Fee Type Status Not Updated Try Again.',
      //     ];
      //     $this->session->set_userdata($msgArr);
      //   }
      //   header("Refresh:1 " . base_url() . "feesManagement/feeTypeMaster");
      // }
    }


    // insert new city
    if (isset($_POST['submit'])) {


   
      $schoolUniqueCode = $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']);
      $book_id = $this->CrudModel->sanitizeInput($_POST['book_id']);
      $chapter_id = $this->CrudModel->sanitizeInput($_POST['chapter_id']);
      $question_type = $this->CrudModel->sanitizeInput($_POST['question_type']);
      $question = htmlentities($_POST['question'], ENT_QUOTES);
      $option_1 = $this->CrudModel->sanitizeInput($_POST['option_1']);
      $option_2 = $this->CrudModel->sanitizeInput($_POST['option_2']);
      $option_3 = $this->CrudModel->sanitizeInput($_POST['option_3']);
      $option_4 = $this->CrudModel->sanitizeInput($_POST['option_4']);

      $this->load->model('CrudModel');
      $imagesJson = [];

      if(isset($_FILES['images'])){

        $totalImages = count($_FILES['images']);
        
        for($i=0; $i < $totalImages; $i++){

          if(!empty($_FILES['image']['name']))
          {
            $subArr = $this->CrudModel->uploadImgWithName($_FILES['images'][$i],'Question',HelperClass::schoolLogoImagePath);
            array_push($imagesJson,$subArr); 
          }

        }

        $imagesJson = json_encode($imagesJson);
      }

  


      $alreadyQuestionAdded = $this->CrudModel->dbSqlQuery("SELECT * FROM " . Table::questionsBankTable . " WHERE book_id = '$book_id' AND question = '$question' LIMIT 1");

      if (!empty($alreadyQuestionAdded)) {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'The Question is already added.',
        ];
        $this->session->set_userdata($msgArr);
        header("Refresh:0 " . base_url() . "questionBank/questionBankMaster");
        exit(0);
      }
      // date_create()->format('Y-m-d')
      $insertArr = [
        "schoolUniqueCode" => $schoolUniqueCode,
        "book_id" => $book_id,
        "chapter_id" => $chapter_id,
        "question_type" => $question_type,
        "question" => htmlentities($_POST['question'], ENT_QUOTES),
        "option_1" => $option_1,
        "option_2" => $option_2,
        "option_3" => $option_3,
        "option_4" => $option_4,
        "image" =>$imagesJson
      ];


      $insertId = $this->CrudModel->insert(Table::questionsBankTable, $insertArr);

      if ($insertId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Question Added Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Question Not Added, Try Again.',
        ];
        $this->session->set_userdata($msgArr);
      }
     // header("Refresh:1 " . base_url() . "questionBank/questionBankMaster");
    }

    // update exiting city
    if (isset($_POST['update'])) {

      $schoolUniqueCode = $this->CrudModel->sanitizeInput($_SESSION['schoolUniqueCode']);
      $book_id = $this->CrudModel->sanitizeInput($_POST['book_id']);
      $chapter_name = $this->CrudModel->sanitizeInput($_POST['chapter_name']);

      $updateId = $this->CrudModel->sanitizeInput($_POST['updateStateId']);

      $updateArr = [
        "book_id" => $book_id,
        "chapter_name" => $chapter_name
      ];

      $updateId = $this->CrudModel->update(Table::chaptersTable, $updateArr, $updateId);

      if ($updateId) {
        $msgArr = [
          'class' => 'success',
          'msg' => 'New Chapter Updated Successfully',
        ];
        $this->session->set_userdata($msgArr);
      } else {
        $msgArr = [
          'class' => 'danger',
          'msg' => 'Chapter Not Updated, Try Again.',
        ];
        $this->session->set_userdata($msgArr);
      }
      header("Refresh:1 " . base_url() . "questionBank/chapterMaster");
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
              if (!empty($this->session->userdata('msg'))) {
                if ($this->session->userdata('class') == 'success') {
                  HelperClass::swalSuccess($this->session->userdata('msg'));
                } else if ($this->session->userdata('class') == 'danger') {
                  HelperClass::swalError($this->session->userdata('msg'));
                }

              ?>

                <div class="alert alert-<?= $this->session->userdata('class') ?> alert-dismissible fade show" role="alert">
                  <strong>New Message!</strong> <?= $this->session->userdata('msg') ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
              <?php
                $this->session->unset_userdata('class');
                $this->session->unset_userdata('msg');
              }
              ?>
              <!-- <h1 class="m-0"><?= $data['pageTitle'] ?> </h1> -->
            </div><!-- /.col -->
            <!-- <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><?= $data['pageTitle'] ?> </li>
              </ol> 
            </div> -->
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
              <div class="card border-top-3">
                <div class="card-header">
                  <h4><?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                        echo 'Edit Question';
                      } else {
                        echo 'Add Question';
                      } ?></h4>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <form method="post" action="" enctype="multipart/form-data">
                    <?php
                    if (isset($_GET['action']) && $_GET['action'] == 'edit') { ?>
                      <input type="hidden" name="updateStateId" value="<?= $editId ?>">
                    <?php }

                    ?>
                    <div class="row">
                      <div class="form-group col-md-12">
                        <label>Select Book <span style="color:red;">*</span></label>
                        <select name="book_id" id="bookId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;" onchange="showChapters()">
                          <option>Select Book Name</option>
                          <?php
                          $selected = '';
                          foreach ($booksData as $b) {
                            if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                              if ($editFeeTypeData[0]['book_id'] == $b['id']) {
                                $selected = 'selected';
                              } else {
                                $selected = '';
                              }
                            }


                          ?>
                            <option <?= $selected; ?> value="<?= $b['id'] ?>"><?= $b['book_name'] . " - " . $b['class_name'] . " Class " . $b['class_name'] ?></option>
                          <?php }


                          ?>
                        </select>
                      </div>
                      <div class="form-group col-md-12">
                        <label>Select Chapter Name <span style="color:red;">*</span></label>
                        <select name="chapter_id" id="chapter_id" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                          <option>Select Chapter Name</option>
                        </select>
                      </div>

                      <div class="form-group col-md-12">
                        <label>Select Question Type <span style="color:red;">*</span></label>
                        <select name="question_type" id="question_type" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;" onchange="showOptions()">
                          <option>Select Question Type</option>
                          <?php
                          foreach (HelperClass::questionTypes as $key => $qt) { ?>
                            <option value="<?= $key ?>"><?= $qt ?></option>


                          <?php  } ?>
                        </select>
                      </div>

                      <div class="col-md-12" id="mcq">


                        <div class="row">
                          <div class="form-group col-md-3">
                            <label>Option 1</label>
                            <input type="text" name="option_1" class="form-control">
                          </div>
                          <div class="form-group col-md-3">
                            <label>Option 2</label>
                            <input type="text" name="option_2" class="form-control">
                          </div>
                          <div class="form-group col-md-3">
                            <label>Option 3</label>
                            <input type="text" name="option_3" class="form-control">
                          </div>
                          <div class="form-group col-md-3">
                            <label>Option 4</label>
                            <input type="text" name="option_4" class="form-control">
                          </div>
                        </div>

                      </div>

                      <div class="form-group col-md-12">
                        <label>Type Question <span style="color:red;">*</span></label>
                        <textarea id="question_editor" name="question" class="form-control" rows="3"></textarea>
                      </div>

                      <div class="form-group col-md-12">
                        <label>Add Images ( Optional )</label>
                        <input type="file" name="images[]" multiple class="form-control">
                      </div>





                      <div class="form-group col-md-12">
                        <button type="submit" name="<?php if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                                                      echo 'update';
                                                    } else {
                                                      echo 'submit';
                                                    } ?>" class="btn btn-lg float-right mybtnColor">Save</button>
                      </div>
                    </div>
                  </form>
                </div>

              </div>

            </div>



          </div>



          <!--/.col (right) -->
        </div>

        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- </div>
      </div>
    </div> -->
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->

    <?php $this->load->view("adminPanel/pages/footer-copyright.php"); ?>
  </div>

  <?php $this->load->view("adminPanel/pages/footer.php"); ?>
  <!-- ./wrapper -->


  <!-- <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script> -->
  <script src="https://cdn.tiny.cloud/1/x4wtc2rfmmywdggb15d7twcr949xau8f04axph7pssyfw1x3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

  <script>
    tinymce.init({
      selector: '#question_editor',
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });

    // CKEDITOR.ClassicEditor.create(document.getElementById("question_editor"), {

    //   toolbar: {
    //     items: [
    //       'exportPDF', 'exportWord', '|',
    //       'findAndReplace', 'selectAll', '|',
    //       'heading', '|',
    //       'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
    //       'bulletedList', 'numberedList', 'todoList', '|',
    //       'outdent', 'indent', '|',
    //       'undo', 'redo',
    //       '-',
    //       'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
    //       'alignment', '|',
    //       'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
    //       'specialCharacters', 'horizontalLine', 'pageBreak', '|',
    //       'textPartLanguage', '|',
    //       'sourceEditing'
    //     ],
    //     shouldNotGroupWhenFull: true
    //   },

    //   list: {
    //     properties: {
    //       styles: true,
    //       startIndex: true,
    //       reversed: true
    //     }
    //   },

    //   heading: {
    //     options: [{
    //         model: 'paragraph',
    //         title: 'Paragraph',
    //         class: 'ck-heading_paragraph'
    //       },
    //       {
    //         model: 'heading1',
    //         view: 'h1',
    //         title: 'Heading 1',
    //         class: 'ck-heading_heading1'
    //       },
    //       {
    //         model: 'heading2',
    //         view: 'h2',
    //         title: 'Heading 2',
    //         class: 'ck-heading_heading2'
    //       },
    //       {
    //         model: 'heading3',
    //         view: 'h3',
    //         title: 'Heading 3',
    //         class: 'ck-heading_heading3'
    //       },
    //       {
    //         model: 'heading4',
    //         view: 'h4',
    //         title: 'Heading 4',
    //         class: 'ck-heading_heading4'
    //       },
    //       {
    //         model: 'heading5',
    //         view: 'h5',
    //         title: 'Heading 5',
    //         class: 'ck-heading_heading5'
    //       },
    //       {
    //         model: 'heading6',
    //         view: 'h6',
    //         title: 'Heading 6',
    //         class: 'ck-heading_heading6'
    //       }
    //     ]
    //   },

    //   placeholder: 'Welcome to CKEditor 5!',

    //   fontFamily: {
    //     options: [
    //       'default',
    //       'Arial, Helvetica, sans-serif',
    //       'Courier New, Courier, monospace',
    //       'Georgia, serif',
    //       'Lucida Sans Unicode, Lucida Grande, sans-serif',
    //       'Tahoma, Geneva, sans-serif',
    //       'Times New Roman, Times, serif',
    //       'Trebuchet MS, Helvetica, sans-serif',
    //       'Verdana, Geneva, sans-serif'
    //     ],
    //     supportAllValues: true
    //   },

    //   fontSize: {
    //     options: [10, 12, 14, 'default', 18, 20, 22],
    //     supportAllValues: true
    //   },

    //   htmlSupport: {
    //     allow: [{
    //       name: /.*/,
    //       attributes: true,
    //       classes: true,
    //       styles: true
    //     }]
    //   },

    //   htmlEmbed: {
    //     showPreviews: true
    //   },

    //   link: {
    //     decorators: {
    //       addTargetToExternalLinks: true,
    //       defaultProtocol: 'https://',
    //       toggleDownloadable: {
    //         mode: 'manual',
    //         label: 'Downloadable',
    //         attributes: {
    //           download: 'file'
    //         }
    //       }
    //     }
    //   },

    //   mention: {
    //     feeds: [{
    //       marker: '@',
    //       feed: [
    //         '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
    //         '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
    //         '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
    //         '@sugar', '@sweet', '@topping', '@wafer'
    //       ],
    //       minimumCharacters: 1
    //     }]
    //   },

    //   removePlugins: [

    //     'CKBox',
    //     'CKFinder',
    //     'EasyImage',

    //     'RealTimeCollaborativeComments',
    //     'RealTimeCollaborativeTrackChanges',
    //     'RealTimeCollaborativeRevisionHistory',
    //     'PresenceList',
    //     'Comments',
    //     'TrackChanges',
    //     'TrackChangesData',
    //     'RevisionHistory',
    //     'Pagination',
    //     'WProofreader',

    //     'MathType'
    //   ]
    // });
  </script>






  <script>
    // var ajaxUrl = '<?= base_url() . 'ajax/listStudentsAjax' ?>';


    $("#stateDataTable").DataTable();



    function showChapters() {

      $.ajax({
        url: '<?= base_url() . 'ajax/bookIdtoChapters'; ?>',
        method: 'post',
        processData: 'false',
        data: {
          bookId: $("#bookId").val()
        },
        success: function(response) {
          //console.log(response);
          response = $.parseJSON(response);
          $('#chapter_id').append(response);
        },
        error: function(error) {
          console.log(error);
        }

      });
    }


    function showOptions() {
      if ($("#question_type").val() == '6') {
        $("#mcq").show();
      } else {
        $("#mcq").hide();
      }
    }
  </script>