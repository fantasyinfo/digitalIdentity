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
            // state: {
            //   required: true
            // },
            pincode: {
              required: true,
              minlength: 6,
              maxlength: 6
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
            // state: "Please select your state",
            pincode: {
              required: "Please enter pincode",
              minlength: "Your password must be at least 6 digit",
              maxlength: "Your password must be at least 6 digit"
            },
            image: "Please add student image",
            roll_no: "Please add roll no",
          },
          errorElement: 'span',
          errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
          },
          highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass( "is-valid" );
          },
          unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass( "is-valid" );
          }
        });
      }); 
      // form validation for student ends here

      $(function () {
        $.validator.setDefaults({
          submitHandler: function (form) {
            //alert( "Form successful submitted!" );
            form.submit();
          }
        });
        $('#addTeacherForm').validate({
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
            doj: {
              required: true
            },
            address: {
              required: true
            },
            // state: {
            //   required: true
            // },
            pincode: {
              required: true,
              minlength: 6,
              maxlength: 6
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
            doj: "Please select your date of joining in the school",
            address: "Please add your address",
            // state: "Please select your state",
            pincode: {
              required: "Please enter pincode",
              minlength: "Your password must be at least 6 digit",
              maxlength: "Your password must be at least 6 digit"
            },
            image: "Please add student image",
            roll_no: "Please add roll no",
          },
          errorElement: 'span',
          errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
          },
          highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid').removeClass( "is-valid" );
          },
          unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid').addClass( "is-valid" );
          }
        });
      }); 

 // select 2 drop down initilize
 $('.select2').select2();





}); // document ready ends here



 