<!-- REQUIRED SCRIPTS -->

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

<!-- firebase integration started -->

<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase.js"></script>
<!-- Firebase App is always required and must be first -->
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-app.js"></script>

<!-- Add additional services that you want to use -->
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-database.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-messaging.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-functions.js"></script>

<!-- firebase integration end -->

<!-- Comment out (or don't include) services that you don't want to use -->
<!-- <script src="https://www.gstatic.com/firebasejs/5.5.9/firebase-storage.js"></script> -->

<script src="https://www.gstatic.com/firebasejs/5.5.9/firebase.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.8.0/firebase-analytics.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
      // Your web app's Firebase configuration
    var firebaseConfig = {
      apiKey: "AIzaSyAEmk2JRhQLJZsaxwfDSgJyMIESY39Ratk",
      authDomain: "dvmproject-4fc0f.firebaseapp.com",
      projectId: "dvmproject-4fc0f",
      storageBucket: "dvmproject-4fc0f.appspot.com",
      messagingSenderId: "265193356184",
      appId: "1:265193356184:web:71c988549b9d8e97cb89ed",
      measurementId: "G-CDFKTLV9MH"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    //firebase.analytics();
    const messaging = firebase.messaging();
      messaging
    .requestPermission()
    .then(function () {
    //MsgElem.innerHTML = "Notification permission granted." 
      //console.log("Notification permission granted.");

        // get the token in the form of promise
      return messaging.getToken()
    })
    .then(function(token) {
    // print the token on the HTML page     
      //console.log(token);
      
      
      
    })
    .catch(function (err) {
     console.log("Unable to get permission to notify.", err);
    });

    messaging.onMessage(function(payload) {
        console.log(payload);
        var notify;
        notify = new Notification(payload.notification.title,{
            body: payload.notification.body,
            icon: payload.notification.icon,
            tag: "Dummy"
        });
       // console.log(payload.notification);
    });

        //firebase.initializeApp(config);
    var database = firebase.database().ref().child("/users/");
      
    database.on('value', function(snapshot) {
        renderUI(snapshot.val());
    });

    // On child added to db
    database.on('child_added', function(data) {
     // console.log("Comming");
        if(Notification.permission!=='default'){
            var notify;
            
            notify= new Notification('CodeWife - '+data.val().username,{
                'body': data.val().message,
                'icon': 'bell.png',
                'tag': data.getKey()
            });
            notify.onclick = function(){
                alert(this.tag);
            }
        }else{
            alert('Please allow the notification first');
        }
    });

    self.addEventListener('notificationclick', function(event) {       
        event.notification.close();
    });

</script>

<script>
  $('.datepicker').datepicker();

</script>

</body>
</html>
