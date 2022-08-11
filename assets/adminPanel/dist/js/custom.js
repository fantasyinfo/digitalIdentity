$(document).ready(function(){
    $(function () {
        $.validator.setDefaults({
          submitHandler: function (form) {
            //alert( "Form successful submitted!" );
            form.submit();
          }
        });
        $('#addStudentForm').validate({
          rules: {
            name: {
              required: true
            },
            class: {
              required: true
            },
            section: {
              required: true
            },
            email: {
              required: true,
              email: true,
            },
            mobile: {
              required: true,
              minlength: 10
            },
            dob: {
              required: true
            },
            address: {
              required: true
            },
            state: {
              required: true
            },
            city: {
              required: true
            },
            roll_no: {
              required: true
            },
            gender: {
              required: true
            },
          },
          messages: {
            email: {
              required: "Please enter a email address",
              email: "Please enter a valid email address"
            },
            mobile: {
              required: "Please provide a mobile number",
              minlength: "Your password must be at least 10 digit"
            },
            class: "Please select your class",
            section: "Please select your section",
            mother: "Please add your mother name",
            father: "Please add your father name",
            dob: "Please select your date of birth",
            address: "Please add your address",
            state: "Please select your state",
            city: "Please select your city",
            image: "Please add student image",
            roll_no: "Please add roll no",
          },
          errorElement: 'span',
          errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
          },
          highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
          },
          unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
          }
        });
      }); 
      // form validation for student ends here


 // select 2 drop down initilize
 $('.select2').select2();

 // datatable student list intilizing
 loadStudentDataTable();

 function loadStudentDataTable(sn = '',sc = '',sm = '',si = '',fd = '',td = '')
 {
    $("#listDatatable").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": true,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        dom: 'lBfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        lengthMenu: [50,100,500,1000,2000,5000,10000,50000,100000],
        processing: true,
        serverSide: true,
        // searching: true,
        ajax : {
          method: 'post',
          url: ajaxUrlForStudentList,
          data : {
            studentName: sn,
            studentClass: sc,
            studentMobile: sm,
            studentUserId: si,
            studentFromDate: fd,
            studentToDate: td,
          },
          error: function ()
          {
            console.log('something went wrong.');
          }
        }
    });
 }



  $("#search").click(function(e){
    e.preventDefault();
    $("#listDatatable").DataTable().destroy();
    loadStudentDataTable(
      $("#studentName").val(),
      $("#studentClass").val(),
      $("#studentMobile").val(),
      $("#studentUserId").val(),
      $("#fromDate").val(),
      $("#toDate").val(),
      );
  })


}); // document ready ends here



 