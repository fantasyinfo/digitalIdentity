<!-- <div class="container">
  <div class="content-wrapper"> -->


  

    <?php

$isTeacher = false;
$stTable = Table::studentTable;
if(isset($_GET['tecid']))
{
  $isTeacher = true;
  $stTable = Table::teacherTable;
}


    $sd = $data['studentData'][0];
    //print_r($sd);
    $dir = base_url().HelperClass::uploadImgDir;
    $schoolD = $this->db->query("SELECT smt.* FROM ".Table::schoolMasterTable." smt 
    LEFT JOIN ".$stTable."  s ON s.schoolUniqueCode = smt.unique_id 
    WHERE s.user_id = '{$sd['user_id']}' ORDER BY smt.id DESC LIMIT 1 ")->result_array();

    if(!$sd || empty( $sd) )
    {
      header('Location: '.HelperClass::brandUrl);
    }

    ?>
<!-- <style>
  .fa-solid, .fas {
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    background: orange;
    color: white;
    border-radius: 50%;
    padding: 8px;
 
}
</style> -->

    <!-- Main content -->
    <!-- <div class="content">
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
                  
                    <?php
                    $string = HelperClass::fullPathQR.$sd['user_id'];
                    $google_chart_api_url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=".$string."";
                      echo "<img src='".$google_chart_api_url."' alt='".$string."' id='qrCode' class='img-fluid rounded mx-auto d-block' >";
                    ?>
                  
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


      </div>

    </div> -->
  <!-- </div>
</div> -->




<div class="resumebox">
        <div class="container">
            <div class="row">
                <div class="col-sm-6" >
                    <div class="whitebox  namebox d-flex align-items-center">
                        <div class="imgboy">
                            <img src="<?= @$sd['image']; ?>" alt="" class="img-res" />
                        </div>
                        <div class="name_box">
                            <h1><?= $sd['name']; ?></h1>
                            <span>@<?= $sd['user_id']; ?></span>
                        </div>
                    </div>

                    <div class="whitebox qrbox " id="qr1">
                        <h2>QR Code</h2>
                        <span>
                        <?php
                          $string = HelperClass::fullPathQR.$sd['user_id'];
                          $google_chart_api_url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=".$string."";
                            echo "<img src='".$google_chart_api_url."' alt='".$string."' id='qrCode' class='img-res' >";
                          ?>
                            <h6><?= $sd['user_id']; ?></h6>
                        </span>
                        <div class="btnbox d-flex align-items-center justify-content-center">
                            <a href="#" class="btn_p" onclick="PrintImage('<?=$google_chart_api_url?>'); return false;">Print QR<span><img src="<?= $dir . 'profile/'?>Print.svg" alt=""
                                        class="img-res" /></span></a>
                            <a href="#" class="btn_s">Share<span><img src="<?= $dir . 'profile/'?>share.svg" alt=""
                                        class="img-res" /></span></a>
                            <a href="#" class="btn_d" onclick="PrintImage('<?=$google_chart_api_url?>'); return false;">Download<span><img src="<?= $dir . 'profile/'?>download.svg" alt=""
                                        class="img-res"  /></span></a>
                        </div>
                    </div>

                </div>

                <div class="col-sm-6" >
                    <div class="whitebox school_dts">
                        <div class="detailbox">
                            <ul>
                                <li>
                                    <label>School Name</label>
                                    <div class="box_dts d-flex align-items-center">
                                        <span>
                                          <img src="<?=@$dir.@$schoolD[0]['image']?>" alt="" class="img-res" />
                                        </span>
                                        <h4><?= @$schoolD[0]['school_name'] ?></h4>
                                    </div>
                                </li>
                                <?php if(!$isTeacher )
                                { ?>
                                <li>
                                    <label>Class</label>
                                    <div class="box_dts d-flex align-items-center">
                                        <span><img src="<?= $dir . 'profile/'?>dicon1.svg" alt="" class="img-res" /></span>
                                        <h4><?= $sd['className'] . " - " . $sd['sectionName']; ?></h4>
                                    </div>
                                </li>
                                <?php }else
                                { ?>
                                  <li>
                                  <label>Education</label>
                                  <div class="box_dts d-flex align-items-center">
                                      <span><img src="<?= $dir . 'profile/'?>dicon1.svg" alt="" class="img-res" /></span>
                                      <h4><?= $sd['education'] ; ?></h4>
                                  </div>
                                  </li>
                                  <li>
                                  <label>Experience</label>
                                  <div class="box_dts d-flex align-items-center">
                                      <span><img src="<?= $dir . 'profile/'?>dicon1.svg" alt="" class="img-res" /></span>
                                      <h4><?= @HelperClass::experience[$sd['experience']]; ?></h4>
                                  </div>
                                  </li>
                               <?php } ?>





                                <?php if(!$isTeacher )
                                { ?>
                                  <li>
                                    <label>Roll No</label>
                                    <div class="box_dts d-flex align-items-center">
                                        <span><img src="<?= $dir . 'profile/'?>dicon2.svg" alt="" class="img-res" /></span>
                                        <h4><?= @$sd['roll_no']; ?></h4>
                                    </div>
                                </li>
                                <?php } ?>
                                <?php if(!$isTeacher )
                                { ?>

                                <li>
                                    <label>Attendance</label>
                                    <div class="box_dts d-flex align-items-center">
                                        <span><img src="<?= $dir . 'profile/'?>dicon3.svg" alt="" class="img-res" /></span>
                                        <h4>78%</h4>
                                    </div>
                                </li>
                                <?php } ?>
                               

                                <?php if(!$isTeacher )
                                { ?>
                                <li>
                                    <label>Class Teacher</label>
                                    <div class="box_dts d-flex align-items-center">
                                        <span><img src="<?= $dir . 'profile/'?>dicon4.svg" alt="" class="img-res" /></span>
                                        <h4><?= $sd['className'] . " - " . $sd['sectionName']; ?></h4>
                                    </div>
                                </li>

                                <?php } ?>
                                

                                <li>
                                    <label>Phone Number</label>
                                    <div class="box_dts d-flex align-items-center">
                                        <span><img src="<?= $dir . 'profile/'?>dicon5.svg" alt="" class="img-res" /></span>
                                        <h4><?= $sd['mobile']; ?></h4>
                                    </div>
                                </li>

                                <li>
                                    <label>Email Id</label>
                                    <div class="box_dts d-flex align-items-center">
                                        <span><img src="<?= $dir . 'profile/'?>dicon8.svg" alt="" class="img-res" /></span>
                                        <h4><?= $sd['email']; ?></h4>
                                    </div>
                                </li>

                                <li>
                                    <label>Mother Name</label>
                                    <div class="box_dts d-flex align-items-center">
                                        <span><img src="<?= $dir . 'profile/'?>dicon6.svg" alt="" class="img-res" /></span>
                                        <h4><?= $sd['mother_name']; ?></h4>
                                    </div>
                                </li>

                                <li>
                                    <label>Father Name</label>
                                    <div class="box_dts d-flex align-items-center">
                                        <span><img src="<?= $dir . 'profile/'?>dicon4.svg" alt="" class="img-res" /></span>
                                        <h4><?= $sd['father_name']; ?></h4>
                                    </div>
                                </li>

                                <li>
                                    <label>Address</label>
                                    <div class="box_dts d-flex align-items-center">
                                        <span><img src="<?= $dir . 'profile/'?>dicon7.svg" alt="" class="img-res" /></span>
                                        <h4><?= $sd['address'] . " - " . $sd['cityName'] . " - " . $sd['stateName'] . " - India"; ?>
                                        </h4>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if($isTeacher)
                {
                  
                  $reviews = $this->db->query("SELECT * FROM ".Table::ratingAndReviewTable." WHERE user_id = '{$sd['id']}' AND user_type = '".HelperClass::userType['Teacher']."' AND schoolUniqueCode = '{$sd['schoolUniqueCode']}' AND status = '1'")->result_array();
                  
                  $totalStars = $this->db->query("SELECT SUM(stars) as totalStars FROM ".Table::ratingAndReviewTable." 
                  WHERE user_id = '{$sd['id']}' AND user_type = '".HelperClass::userType['Teacher']."' AND schoolUniqueCode = '{$sd['schoolUniqueCode']}' AND status = '1'  GROUP BY user_id ")->result_array();


                  $totalCountReview = count($reviews);
                  // HelperClass::prePrintR($reviews);
                  if(!empty($reviews))
                  {
                  ?>
                  <div class="col-sm-12 py-2">
                    <div class="whitebox reviewbox py-3 px-2">
                      
                        <!-- <span style="margin:20px;">Showing 1-5 out of <?= $totalCountReview; ?> reviews</span> -->
                        <table id="newtable1" class="table">
                          <thead>
                            <tr>
                              <th>
                              <!-- <div class="d-flex align-items-center justify-content-lg-between"> -->
                                <span>Total Reviews : <b><?= $totalCountReview; ?></b></span>
                                <!-- OverAll Rating <?=  ($totalStars[0]['totalStars'] / $totalCountReview) * 5 ?> -->
                               <!-- </div> -->
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                <?php  
                    foreach($reviews as $r)
                    { ?>
                      <tr>
                        <td>
                         <div class="pdvb">
                          <div class="rvdetails">
                          <div class="sttxt d-flex justify-content-between align-items-center">
                                <div class="star d-flex align-items-center">
                                  <?php 
                                  
                                  for($j=0; $j <$r['stars'];$j++ )
                                  { ?>
                                    <span><img src="<?= $dir . 'profile/'?>star.svg" alt="" class="img-res"/></span>
                                <?php }
                                  
                                  ?>
                                    <h5><?=$r['stars'];?>.0</h5>
                                </div>

                                <h5>&nbsp;<?= HelperClass::reviewType[$r['for_what']]; ?></h5>
                            </div>
                          </div>
                          <div class="details_rv">
                                  <h4><?=$r['review_title'];?></h4>
                                  <p><?=$r['review'];?></p>
                                  <div class="boxname_d d-flex align-items-center mt-4">
                                          <?php 
                                          
                                          if($r['login_user_type'] == HelperClass::userType['Parent'])
                                          {
                                            $tTable = Table::studentTable;
                                          }
                                          
                                        $ratingProviderUser =  $this->db->query("SELECT father_name, mother_name, user_id FROM ".$tTable." WHERE id = '{$r['login_user_id']}' AND schoolUniqueCode = '{$sd['schoolUniqueCode']}' AND status = '1' ")->result_array();
                                          
                                        if(!empty($ratingProviderUser))
                                        { ?>
                                          <h5><?=$ratingProviderUser[0]['father_name'][0]; ?></h5>
                                          <h6><b><?= $ratingProviderUser[0]['father_name'] . " & " . $ratingProviderUser[0]['mother_name'] ?> </b>&nbsp;
                                          <span style="color:dimgrey">@<?=$ratingProviderUser[0]['user_id']?></span></h6>
                                          <span style="color:dimgrey">&#729;&nbsp;<?= date('d-m-Y h:i:A', strtotime($r['created_at']));?></span>
                                        <?php }
                                          ?>
                                  </div>
                          </div>
                         </div>
                        </td>
                      </tr>
                   <?php }   ?>
          
             </tbody>
                      </table>
             </div>
 </div> 
 <?php } } ?>

                <div class="col-sm-12">
                <div class="whitebox qrbox " id="qr2">
                        <h2>QR Code</h2>
                        <span>
                        <?php
                          $string = HelperClass::fullPathQR.$sd['user_id'];
                          $google_chart_api_url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=".$string."";
                            echo "<img src='".$google_chart_api_url."' alt='".$string."' id='qrCode' class='img-res' >";
                          ?>
                            <h6><?= $sd['user_id']; ?></h6>
                        </span>
                        <div class="btnbox d-flex align-items-center justify-content-center">
                            <a href="#" class="btn_p" onclick="PrintImage('<?=$google_chart_api_url?>'); return false;">Print QR<span><img src="<?= $dir . 'profile/'?>Print.svg" alt=""
                                        class="img-res" /></span></a>
                            <a href="#" class="btn_s">Share<span><img src="<?= $dir . 'profile/'?>share.svg" alt=""
                                        class="img-res" /></span></a>
                            <a href="#" class="btn_d" onclick="PrintImage('<?=$google_chart_api_url?>'); return false;">Download<span><img src="<?= $dir . 'profile/'?>download.svg" alt=""
                                        class="img-res"  /></span></a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
</div>


<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.0/FileSaver.min.js" integrity="sha512-csNcFYJniKjJxRWRV1R7fvnXrycHP6qDR21mgz1ZP55xY5d+aHLfo9/FcGDQLfn2IfngbAHd8LdfsagcCqgTcQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

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

// function showModal()
// {
//   // $('#myModal').modal(options)
//   $("#reasonModal").modal('show');
// }


// function hideMe()
// {
//   $("#reasonModal").modal('hide');
// }


</script>

<script>
console.log('aaya');
$(document).ready(function () {
    $('#newtable1').DataTable({
      // searching: false
      pageLength : 5,
      lengthMenu: [5, 10, 20],
      ordering: false,
      language: {
            "lengthMenu": "Display _MENU_ records per page",
            "zeroRecords": "No Data Found",
            "info": "Showing _START_ Out Of _END_ Reviews From Total _TOTAL_ Reviews",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)"
        }
    });
    console.log('aaya');
});
</script>