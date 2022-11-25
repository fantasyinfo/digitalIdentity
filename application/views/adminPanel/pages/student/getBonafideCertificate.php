<?php
if(isset($_POST['submit']))
{
    $student_id = $_POST['student_id'];
    $studentName = $_POST['studentName'];
    $class_id = $_POST['class_id'];
    $section_id = $_POST['section_id'];
    $content = trim($_POST['content']);
    $session_table_id = $_SESSION['currentSession'];
    $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
    $issueDate = date('Y-m-d');
   
    // first check if salary slip is already generated for this month

    $already = $this->db->query("SELECT id,student_id FROM ".Table::studentBonafideCertificate." WHERE student_id = '$student_id' AND schoolUniqueCode = '$schoolUniqueCode' AND status = '1'  LIMIT 1")->result_array()[0];

    if(!empty($already))
    {
        
        $alreadyC = $this->db->query("SELECT token,for_what FROM ".Table::tokenFilterTable." WHERE insertId = '{$already['id']}' AND schoolUniqueCode = '$schoolUniqueCode' AND status = '1' AND for_what = 'Bonafide Certificate' LIMIT 1 ")->result_array()[0];

        
        if($alreadyC)
        {
          header("Location: " . base_url('bonafideCertificate?tec_id=') . $alreadyC['token']);
        }else
        {
          $msgArr = [
            'class' => 'danger',
            'msg' => 'Bonafide Certificate is Already Generated For This Student',
        ];
        $this->session->set_userdata($msgArr);
          header("Refresh:0 " . base_url() . "master/getBonafideCertificate");
        }
        
        die();
    }

    $insertStudentArr = [
        'schoolUniqueCode' => $schoolUniqueCode,
        'student_id' => $student_id,
        'studentName' => $studentName,
        'issueDate' => $issueDate,
        'content' =>$content,
        'class_id' => $class_id,
        'section_id' => $section_id,
        'session_table_id' => $session_table_id

    ];


    $c = $this->CrudModel->insert(Table::studentBonafideCertificate,$insertStudentArr);


    $filterToken = "token=".HelperClass::generateRandomToken()."-m-".rand(1111,4444)."-y-".rand(1111,4444)."-i-".$empId ."-iId-".$c;

    $insertArr = [
    'schoolUniqueCode' => $schoolUniqueCode,
    'token' => $filterToken,
    'for_what' => 'Bonafide Certificate',
    'insertId' => $c
    ];

    $d = $this->CrudModel->insert(Table::tokenFilterTable,$insertArr);

    if(!empty($d))
    {
      header("Location: " . base_url('bonafideCertificate?tec_id=') . $filterToken);
        
    }


}


?>

<style>
  #detailsShow{
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
                      <select name="studentId" id="studentId" class="form-control  select2 select2-dark" required data-dropdown-css-class="select2-dark" style="width: 100%;">
                        <option>Please Select Student</option>
                      </select>
                    </div>
                    <div class="form-group col-md-3 margin-top-30">
                      <button id="getStudent" name="search" class="btn mybtnColor btn-block ">Search</button>
                    </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <!-- form start -->

              </div>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->
          <!-- right column -->
        </div>
        <div class="row" id="detailsShow ">
                            <div class="col-md-12 card card-body border-top-3">
                                <div class="row" id="formBox">
                                   
                                   
                                </div>
                         
                        </div>
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
  <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
  <script>
    
    

    function showStudents() {
      var classId = $("#classId").val();
      var sectionId = $("#sectionId").val();
      if (classId != '' && sectionId != '') {
        console.log(classId + ' and ' + sectionId);
        $.ajax({
          url: '<?= base_url() . 'ajax/showStudentViaClassAndSectionId'; ?>',
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


    $("#getStudent").click(function(e) {
       
       e.preventDefault();
       var classId = $("#classId").val();
      var sectionId = $("#sectionId").val();
      var studentId = $("#studentId").val();

    
       $.ajax({
               url: '<?= base_url() . 'ajax/showStudentViaClassAndSectionAndStudentIdForBonefide'; ?>',
               method: 'post',
               processData: 'false',
               data: {
                   classId: classId,
                   sectionId:sectionId,
                   studentId: studentId
               },
               success: function(response) {
                   response = $.parseJSON(response);
                   console.log(response);
                   let html = ` <div class="col-md-12 "><form method="POST">
                                   <input type="hidden" name="student_id" value="${response.id}">
                                   <input type="hidden" name="studentName" value="${response.name}">
                                   <input type="hidden" name="class_id" value="${response.class_id}">
                                   <input type="hidden" name="section_id" value="${response.section_id}">
                                   <textarea id="editor" name="content" class="form-control" rows="20"><p style="text-align:justify;font-size:26px;margin-top:20px;margin-bottom:20px;line-height:40px;">It is to be certified that Mr. / Km. <b>${response.name}</b> S/o, D/o Shri <b>${response.father_name} </b> from <b> ${response.address} - ${response.cityName} - ${response.stateName} - ${response.pincode} </b> is a bonafide student of <b>${response.school_name} ${response.sAddress}</b> He/she is studying in class <b>${response.className} ${response.sectionName}</b> in the session <b>${response.session_start_year} - ${response.session_end_year}</b> . We wish <b>${response.name}</b> all the best for their future.</p></textarea>
                                   <input type="submit" class="mt-2 btn mybtnColor btn-block" name="submit" value="Generate Bonafide Certificate">
                                   </form></div>`;
                       $("#formBox").html(html);
                       $("#detailsShow").show();
                       CKEDITOR.replace( 'editor' );
               },
               error: function(error) {
                   console.log(error);
               }

           });

       

       
   });

  

   CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
               // https://ckeditor.com/docs/ckeditor5/latest/features/toolbar/toolbar.html#extended-toolbar-configuration-format
               toolbar: {
                   items: [
                       'exportPDF','exportWord', '|',
                       'findAndReplace', 'selectAll', '|',
                       'heading', '|',
                       'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                       'bulletedList', 'numberedList', 'todoList', '|',
                       'outdent', 'indent', '|',
                       'undo', 'redo',
                       '-',
                       'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                       'alignment', '|',
                       'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                       'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                       'textPartLanguage', '|',
                       'sourceEditing'
                   ],
                   shouldNotGroupWhenFull: true
               },
               // Changing the language of the interface requires loading the language file using the <script> tag.
               // language: 'es',
               list: {
                   properties: {
                       styles: true,
                       startIndex: true,
                       reversed: true
                   }
               },
               // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
               heading: {
                   options: [
                       { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                       { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                       { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                       { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                       { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                       { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                       { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                   ]
               },
               // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
               placeholder: 'Welcome to CKEditor 5!',
               // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
               fontFamily: {
                   options: [
                       'default',
                       'Arial, Helvetica, sans-serif',
                       'Courier New, Courier, monospace',
                       'Georgia, serif',
                       'Lucida Sans Unicode, Lucida Grande, sans-serif',
                       'Tahoma, Geneva, sans-serif',
                       'Times New Roman, Times, serif',
                       'Trebuchet MS, Helvetica, sans-serif',
                       'Verdana, Geneva, sans-serif'
                   ],
                   supportAllValues: true
               },
               // https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
               fontSize: {
                   options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                   supportAllValues: true
               },
               // Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
               // https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
               htmlSupport: {
                   allow: [
                       {
                           name: /.*/,
                           attributes: true,
                           classes: true,
                           styles: true
                       }
                   ]
               },
               // Be careful with enabling previews
               // https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
               htmlEmbed: {
                   showPreviews: true
               },
               // https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
               link: {
                   decorators: {
                       addTargetToExternalLinks: true,
                       defaultProtocol: 'https://',
                       toggleDownloadable: {
                           mode: 'manual',
                           label: 'Downloadable',
                           attributes: {
                               download: 'file'
                           }
                       }
                   }
               },
               // https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
               mention: {
                   feeds: [
                       {
                           marker: '@',
                           feed: [
                               '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                               '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                               '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                               '@sugar', '@sweet', '@topping', '@wafer'
                           ],
                           minimumCharacters: 1
                       }
                   ]
               },
               // The "super-build" contains more premium features that require additional configuration, disable them below.
               // Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
               removePlugins: [
                   // These two are commercial, but you can try them out without registering to a trial.
                   // 'ExportPdf',
                   // 'ExportWord',
                   'CKBox',
                   'CKFinder',
                   'EasyImage',
                   // This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
                   // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
                   // Storing images as Base64 is usually a very bad idea.
                   // Replace it on production website with other solutions:
                   // https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
                   // 'Base64UploadAdapter',
                   'RealTimeCollaborativeComments',
                   'RealTimeCollaborativeTrackChanges',
                   'RealTimeCollaborativeRevisionHistory',
                   'PresenceList',
                   'Comments',
                   'TrackChanges',
                   'TrackChangesData',
                   'RevisionHistory',
                   'Pagination',
                   'WProofreader',
                   // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                   // from a local file system (file://) - load this site via HTTP server if you enable MathType
                   'MathType'
               ]
           });
   </script>

  </script>

  