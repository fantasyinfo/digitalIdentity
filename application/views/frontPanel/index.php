<div class="container">
  <div class="content-wrapper">
    <?php




    $sd = $data['studentData'][0];
    //print_r($sd);
    $dir = base_url().HelperClass::uploadImgDir;
    $schoolD = $this->db->query("SELECT smt.* FROM ".Table::schoolMasterTable." smt 
    LEFT JOIN ".Table::studentTable."  s ON s.schoolUniqueCode = smt.unique_id 
    WHERE s.user_id = '{$sd['user_id']}' ORDER BY smt.id DESC LIMIT 1 ")->result_array();

    if(!$sd || empty( $sd) )
    {
      header('Location: '.HelperClass::brandUrl);
    }

    ?>
<style>
  .fa-solid, .fas {
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    background: orange;
    color: white;
    border-radius: 50%;
    padding: 8px;
 
}
</style>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="main-body">
          <div class="row gutters-sm">
            <div class="col-md-6 mb-3">
              <div class="card card-primary">
                <div class="card-header bg-dark rounded">
                  <h4 class="card-title text-white">Profile Image</h4>
                </div>
                <div class="card-body rounded">
                  <div class="d-flex flex-row align-items-center text-left">
                    <img src="<?= @$sd['image']; ?>" alt="Admin" class="rounded-circle" width="100" height="100">
                    <div class="mt-3">
                      <h3 style="font-weight:600;margin-left:20px;"><?= $sd['name']; ?></h3>
                      <h5 style="font-weight:300;margin-left:20px;" class="text-secondary mb-1 ml-3">@<?= $sd['user_id']; ?></h5>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card mt-3">
                <div class="card card-primary">
                  <div class="card-header bg-dark rounded">
                    <h4 class="card-title text-white">Student QR</h4>
                  </div>
                  <div class="card-body rounded">
                    <!-- <div class="d-flex align-items-center text-align-center"> -->
                    <?php
                    $string = HelperClass::fullPathQR.$sd['user_id'];
                    $google_chart_api_url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=".$string."";
                      echo "<img src='".$google_chart_api_url."' alt='".$string."' id='qrCode' class='img-fluid rounded mx-auto d-block' >";
                    ?>
                    <!-- </div> -->
                    <div class="row text-center">
                      <div class="col-md-6">
                        <button onclick="PrintImage('<?=$google_chart_api_url?>'); return false;" class="btn btn-lg btn-block btn-danger btn-rounded mb-1"><i class="fa-solid fa-print"></i> Print QR</button>
                      </div>
                      <div class="col-md-6">
                        <button href="<?=$google_chart_api_url?>" id='dwnBtn' class="btn btn-lg btn-dark btn-block btn-rounded mb-1"><i class="fa-solid fa-download"></i> Download QR</button>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card card-primary mb-3">
                <div class="card-header bg-dark rounded">
                  <h4 class="card-title text-white">Student Details</h4>
                </div>
                <div class="card-body rounded ml-3">
                <div class="row mb-2">
                      <p class="mb-0" class="text-secondary" style="font-weight:500;">School Name</p>
                    <h6 class="mt-2" style="font-weight:600;">  <img src="<?=@$dir.@$schoolD[0]['image']?>" class="rounded-circle" width="40" height="40"> <?= @$schoolD[0]['school_name'] ?></h6>
                  </div>
                <div class="row mb-2">
                      <p class="mb-0" class="text-secondary" style="font-weight:500;">Class</p>
                    <h6 class="mt-2" style="font-weight:600;">  <i class="fa-solid fa-graduation-cap"></i> <?= $sd['className'] . " - " . $sd['sectionName']; ?></h6>
                  </div>
                <div class="row mb-2">
                      <p class="mb-0" class="text-secondary" style="font-weight:500;">Roll No</p>
                    <h6 class="mt-2" style="font-weight:600;">  <i class="fa-solid fa-tag"></i> <?= @$sd['roll_no']; ?></h6>
                  </div>
                <div class="row mb-2">
                      <p class="mb-0" class="text-secondary" style="font-weight:500;">Gender</p>
                    <h6 class="mt-2" style="font-weight:600;">  <i class="fa-solid fa-mars-and-venus"></i> <?php
                      if ($sd['gender'] == 1) {
                        echo 'Male';
                      } elseif ($sd['gender'] == 2) {
                        echo 'Female';
                      } else {
                        echo 'Other';
                      }
                      ?></h6>
                  </div>
                <!-- <div class="row mb-2">
                      <p class="mb-0" class="text-secondary" style="font-weight:500;">Attendence</p>
                    <h6 class="mt-2" style="font-weight:600;">  <i class="fa-solid fa-calendar-check"></i> 78%</h6>
                  </div> -->
                <div class="row mb-2">
                      <p class="mb-0" class="text-secondary" style="font-weight:500;">Class Teacher</p>
                    <h6 class="mt-2" style="font-weight:600;">  <i class="fa-solid fa-user"></i> <?= $sd['className'] . " - " . $sd['sectionName']; ?></h6>
                  </div>
              
                <div class="row mb-2">
                      <p class="mb-0" class="text-secondary" style="font-weight:500;">Mother Name</p>
                    <h6 class="mt-2" style="font-weight:600;">  <i class="fa-solid fa-person-dress"></i> <?= $sd['mother_name']; ?></h6>
                  </div>
                <div class="row mb-2">
                      <p class="mb-0" class="text-secondary" style="font-weight:500;">Father Name</p>
                    <h6 class="mt-2" style="font-weight:600;">  <i class="fa-solid fa-person"></i>  <?= $sd['father_name']; ?></h6>
                  </div>
                  <div class="row mb-2">
                      <p class="mb-0" class="text-secondary" style="font-weight:500;">Phone Number</p>
                    <h6 class="mt-2" style="font-weight:600;">  <i class="fa-solid fa-phone"></i> <?= $sd['mobile']; ?></h6>
                  </div>
                <div class="row mb-2">
                      <p class="mb-0" class="text-secondary" style="font-weight:500;">Email Id</p>
                    <h6 class="mt-2" style="font-weight:600;"> <i class="fa-solid fa-envelope"></i> <?= $sd['email']; ?></h6>
                  </div>
                <div class="row mb-2">
                      <p class="mb-0" class="text-secondary" style="font-weight:500;">Address</p>
                    <h6 class="mt-2" style="font-weight:600;">  <i class="fa-solid fa-location-dot"></i>  <?= $sd['address'] . " - " . $sd['cityName'] . " - " . $sd['stateName'] . " - India"; ?></h6>
                  </div>
                
                </div>
              </div>

            </div>
          </div>

        </div>


        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js" integrity="sha512-csNcFYJniKjJxRWRV1R7fvnXrycHP6qDR21mgz1ZP55xY5d+aHLfo9/FcGDQLfn2IfngbAHd8LdfsagcCqgTcQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">

function ImagetoPrint(source) {
    return "<html><head><script>function step1(){\n" +
            "setTimeout('step2()', 10);}\n" +
            "function step2(){window.print();window.close()}\n" +
            "</scri" + "pt></head><body onload='step1()'>\n" +
            "<img src='" + source + "' /></body></html>";
}
function PrintImage(source) {
    Pagelink = "about:blank";
    var pwa = window.open(Pagelink, "_new");
    pwa.document.open();
    pwa.document.write(ImagetoPrint(source));
    pwa.document.close();
}


let btnDownload = document.getElementById('dwnBtn');
let img = document.getElementById('qrCode');
 
 
// Must use FileSaver.js 2.0.2 because 2.0.3 has issues.
btnDownload.addEventListener('click', () => {
    let imagePath = img.getAttribute('src');
    let fileName = getFileName(imagePath);
    saveAs(imagePath, fileName +".png");
});
 
function getFileName(str) {
    return str.substring(str.lastIndexOf('/') + 1)
}
</script>