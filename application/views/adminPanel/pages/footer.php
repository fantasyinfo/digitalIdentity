<!-- REQUIRED SCRIPTS -->

<style>
  .float{
	position:fixed;
	width:60px;
	height:60px;
	top:450px;
	right:5px;
	background-color:#25d366;
	color:#FFF;
	border-radius:50px;
	text-align:center;
  font-size:30px;
	box-shadow: 2px 2px 3px #999;
  z-index:100;
}

.my-float{
	margin-top:16px;
}
</style>

<a href="https://api.whatsapp.com/send?phone=916397520221&text=Hi,I need help in digitalfied digital identity software." class="float" target="_blank">
<i class="fa-brands fa-whatsapp"></i>
</a>






<!-- jQuery -->
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jquery-validation -->
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/jquery-validation/additional-methods.min.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= base_url() .$data['adminPanelUrl']?>dist/js/pages/dashboard3.js"></script>

<!-- Select2 -->
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/select2/js/select2.full.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE -->
<script src="<?= base_url() .$data['adminPanelUrl']?>dist/js/adminlte.js"></script>
<!-- Custom Logic -->
<script src="<?= base_url() .$data['adminPanelUrl']?>dist/js/custom.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<!-- <script src="https://www.gstatic.com/firebasejs/5.5.9/firebase.js"></script>

<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-app.js"></script>


<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-database.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-messaging.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-functions.js"></script>


<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.8.0/firebase-analytics.js"></script>


<script>
      // Your web app's Firebase configuration
    // var firebaseConfig = {
    //   apiKey: "AIzaSyAEmk2JRhQLJZsaxwfDSgJyMIESY39Ratk",
    //   authDomain: "dvmproject-4fc0f.firebaseapp.com",
    //   projectId: "dvmproject-4fc0f",
    //   storageBucket: "dvmproject-4fc0f.appspot.com",
    //   messagingSenderId: "265193356184",
    //   appId: "1:265193356184:web:71c988549b9d8e97cb89ed",
    //   measurementId: "G-CDFKTLV9MH"
    // };
    // Initialize Firebase
    // firebase.initializeApp(firebaseConfig);
    // const messaging = firebase.messaging();
    //   messaging
    // .requestPermission()
    // .then(function () {

    //   return messaging.getToken()
    // })
    // .then(function(token) {
 
    // })
    // .catch(function (err) {
    //  console.log("Unable to get permission to notify.", err);
    // });

    // messaging.onMessage(function(payload) {
    //     console.log(payload);
    //     var notify;
    //     notify = new Notification(payload.notification.title,{
    //         body: payload.notification.body,
    //         icon: payload.notification.icon,
    //         tag: "Dummy"
    //     });
     
    // });

    //var database = firebase.database().ref().child("/users/");
      
    // database.on('value', function(snapshot) {
    //     renderUI(snapshot.val());
    // });

   
    // database.on('child_added', function(data) {
  
    //     if(Notification.permission!=='default'){
    //         var notify;
            
    //         notify= new Notification('CodeWife - '+data.val().username,{
    //             'body': data.val().message,
    //             'icon': 'bell.png',
    //             'tag': data.getKey()
    //         });
    //         notify.onclick = function(){
    //             alert(this.tag);
    //         }
    //     }else{
    //         alert('Please allow the notification first');
    //     }
    // });

    // self.addEventListener('notificationclick', function(event) {       
    //     event.notification.close();
    // });

</script>
    -->
<script>
  $('.datepicker').datepicker();

</script> 

</body>
</html>
