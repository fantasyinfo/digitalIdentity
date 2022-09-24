<style>
  #map {
  height: 700px;
  width: 100%;
  border: 1px solid red;
}

/* #content
{
  height: 120px;
} */
</style>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
<?php $this->load->view('adminPanel/pages/navbar.php');?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php $this->load->view("adminPanel/pages/sidebar.php");?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <?php 

    ?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?=$data['pageTitle']?> </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?=$data['pageTitle']?> </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
    
      <div id="map">
      </div>
</div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
</div>
  <?php $this->load->view("adminPanel/pages/footer-copyright.php");
  
  
  
  
  ?>
</div>
<!-- ./wrapper -->
<script>
  var ajaxUrlForStudentList = '<?= base_url() . 'ajax/getLatLng'?>';
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDONXc92OEi-rHqbVBTz2ktXmHb-PCHRFA&callback=loadMap&v=weekly" defer ></script>

    <script>
let llFromResponse = [];
 //     const image =
              // "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png";
      function loadMap()
      {

        $.ajax({
          url: ajaxUrlForStudentList,
          method: 'POST',
          success:function(response)
          {
            llFromResponse = $.parseJSON(response);
            console.log(llFromResponse);

         



            let lat = llFromResponse[0]['lat'];
            let lng = llFromResponse[0]['lng'];
            
      
            // console.log(lat + lng);
            const myLatLng = { lat: lat, lng: lng };

            const map = new google.maps.Map(
              document.getElementById("map"),
              {
                zoom: 12,
                center: myLatLng,
              }
            );

             


              

              // adding marker
              for(let i=0; i < llFromResponse.length; i++)
              {
                let latA = llFromResponse[i]['lat'];
                let lngA = llFromResponse[i]['lng'];
                let myLatLngArr = { lat: latA, lng: lngA };
                  let marker =  new google.maps.Marker({
                    position: myLatLngArr,
                    map,
                    title: llFromResponse[i]['name'],
                    // icon: image
                  });

                  marker.addListener("click", () => {
                  infowindow.open({
                    anchor: marker,
                    map,
                    shouldFocus: false,
                  });
                });


                  const contentString =
                  '<div id="content">' +
                  '<div id="siteNotice">' +
                  "</div>" +
                  '<h1 id="firstHeading" class="firstHeading">'+llFromResponse[i]['name']+'</h1>' +
                  '<div id="bodyContent">' +
                  "<p style='font-size:16px; line-height:1.5;'> Driver Name: <b>"+llFromResponse[i]['name']+" </b></br>" +
                  " Vechile Type: <b>" + llFromResponse[i]['vechicle_type'] + "</b> </br>" +
                  " Vechile Number: <b>" + llFromResponse[i]['vechicle_no'] + "</b> </br>" +
                  " Total Seats: <b>" + llFromResponse[i]['total_seats'] + "</b> </br>" +
                  "</p>" +
                  "</div>" +
                  "</div>";
                const infowindow = new google.maps.InfoWindow({
                  content: contentString,
                });

              }

              


          }
        })

      
      }

    </script>