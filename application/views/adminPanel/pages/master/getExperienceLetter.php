<?php
if(isset($_POST['submit']))
{
    $empId = $_POST['empId'];
    $employeeName = $_POST['employeeName'];
    $departmentId = $_POST['departmentId'];
    $designationId = $_POST['designationId'];
    $content = trim($_POST['content']);
    $session_table_id = $_SESSION['currentSession'];
    $schoolUniqueCode = $_SESSION['schoolUniqueCode'];
    $issueDate = date('Y-m-d');
   
    // first check if salary slip is already generated for this month

    $already = $this->db->query("SELECT empId FROM ".Table::experienceLetterTable." WHERE empId = '$empId' AND schoolUniqueCode = '$schoolUniqueCode' AND status = '1'")->result_array();

    if(!empty($already))
    {
        $msgArr = [
            'class' => 'danger',
            'msg' => 'Experience Letter is Already Generated For This Employee.',
        ];
        $this->session->set_userdata($msgArr);

        header("Refresh:0 " . base_url() . "master/getExperienceLetter");
        die();
    }

    $insertSalaryArr = [
        'schoolUniqueCode' => $schoolUniqueCode,
        'empId' => $empId,
        'employeeName' => $employeeName,
        'issueDate' => $issueDate,
        'content' =>$content,
        'departmentId' => $departmentId,
        'designationId' => $designationId,
        'session_table_id' => $_SESSION['currentSession']

    ];


    $c = $this->CrudModel->insert(Table::experienceLetterTable,$insertSalaryArr);


    $filterToken = "token=".HelperClass::generateRandomToken()."-m-".rand(1111,4444)."-y-".rand(1111,4444)."-i-".$empId ."-iId-".$c;

    $insertArr = [
    'schoolUniqueCode' => $schoolUniqueCode,
    'token' => $filterToken,
    'for_what' => 'Experience Letter',
    'insertId' => $c
    ];

    $d = $this->CrudModel->insert(Table::tokenFilterTable,$insertArr);

    if(!empty($d))
    {
        // change status of employee to getAway
        $u = $this->db->query("UPDATE ".Table::salaryTable." SET status = '3' WHERE id = '$empId' AND schoolUniqueCode = '$schoolUniqueCode' ");

        if($u)
        {
            header("Location: " . base_url('experienceLetter?tec_id=') . $filterToken);
        }
    }


}


?>



<style>
#showEmpTable {
    display: none;
}

#detailsShow{
    display: none;
}

.ck-editor__editable[role="textbox"] {
                /* editing area */
                min-height: 200px;
            }
            .ck-content .image {
                /* block images */
                max-width: 80%;
                margin: 20px auto;
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

                   
                       
                     
                        <!-- /.card -->
                    </div>
                    <!--/.col (left) -->
                    <!-- right column -->
                    <div class="row" id="detailsShow">
                            <div class="col-md-12 card card-body">
                                <div class="row" id="formBox">
                                   
                                   
                                </div>
                         
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
     
        $.ajax({
                url: '<?= base_url() . 'ajax/showEmployeesDetailsViaDepAndDesId'; ?>',
                method: 'post',
                processData: 'false',
                data: {
                    departmentId: departmentId,
                    designationId:designationId,
                    employeeId: employeeId
                },
                success: function(response) {
                    response = $.parseJSON(response);
                    console.log(response);
                    let html = ` <div class="col-md-12"><form method="POST">
                                    <input type="hidden" name="empId" value="${response.id}">
                                    <input type="hidden" name="employeeName" value="${response.employeeName}">
                                    <input type="hidden" name="departmentId" value="${response.departmentId}">
                                    <input type="hidden" name="designationId" value="${response.designationId}">
                                    <textarea id="editor" name="content" class="form-control" rows="20"><p style="text-align:justify;font-size:26px;margin-top:20px;margin-bottom:20px;line-height:40px;">This letter certifies that <b>${response.employeeName}</b> was an employee in the role of <b>${response.designationName}</b> from <b>${response.departmentName}</b> Department with <b>${response.school_name}</b> from <b>${response.doj}</b> up to <b>${response.todayDate}</b> was a great employee in our school. We were very proud of him/her. For further inquiry and verification, feel free to contact our school. We are sure that their passion and dedication will help them excel in whatever they choose to do next in their life. They have shown a high level of commitment throughout their time with our company.We wish {response.employeeName} all the best for their future.</p></textarea>
                                    <input type="submit" class="mt-2 btn btn-primary btn-block" name="submit" value="Generate Experience Letter">
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


