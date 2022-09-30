<?php
// echo '<pre>';
// print_r($_SERVER);

$stTable = Table::studentTable;
$sd = $data['studentData'][0];

if(!$sd || empty( $sd) )
{
  header('Location: '.HelperClass::brandUrl);
}


if(isset($_GET['tecid']))
{
  $isTeacher = true;
  $stTable = Table::teacherTable;

  $dir = base_url().HelperClass::uploadImgDir;
  $schoolD = $this->db->query("SELECT smt.* FROM ".Table::schoolMasterTable." smt 
  LEFT JOIN ".$stTable."  s ON s.schoolUniqueCode = smt.unique_id 
  WHERE s.user_id = '{$sd['user_id']}' ORDER BY smt.id DESC LIMIT 1 ")->result_array();
}



$shareMsg = "Hey, Friends This is My Amazing Profile Via Digital Identity - Digitalfied. Have A Look.";
    ?>



<meta property="og:title" content="<?php echo $shareMsg; ?>" />

    <meta property="og:url" content="<?php echo base_url("?") . $_SERVER['QUERY_STRING']; ?>" />

    <meta property=”og:description” content="Hey, Friends This is my amazing profile via Digital Identity - Digitalfied, have a look." />

    <meta property="og:image" content="<?= @$sd['image']; ?>" />



    <meta property="og:type" content="article" />

    <meta name="twitter:card" content="Hey, Friends This is my amazing profile via Digital Identity - Digitalfied, have a look." />

    <meta name="twitter:title" content="<?php echo $shareMsg; ?>" />

    <meta name="twitter:url" content="<?php echo base_url("?") . $_SERVER['QUERY_STRING']; ?>" />

    <meta name="twitter:description" content="Hey, Friends This is My Amazing Profile Via Digital Identity - www.digitalfied.com, Have A Look." />


    <meta name="twitter:image" content="<?= @$sd['image']; ?>" />

    </head>
  <body>
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
                            <span>@<?= $sd['user_id']; ?></span> </br>
                            <a href="#" id="shareBtn" class="btn_s btn-success btn mt-2">Share<span><img src="<?= $dir . 'profile/'?>share.svg" alt=""
                                        class="img-res" /></span></a>
                        </div>
                    </div>

                    <div class="whitebox qrbox " id="qr1">
                        <h2>QR Code</h2>
                        <span>
                        <?php
                          $string = HelperClass::fullPathQRTec.$sd['user_id'];
                          $google_chart_api_url = "https://chart.googleapis.com/chart?chs=250x250&cht=qr&chl=".$string."";
                            echo "<img src='".$google_chart_api_url."' alt='".$string."' id='qrCode' class='img-res' >";
                          ?>
                            <h6><?= $sd['user_id']; ?></h6>
                        </span>
                        <div class="btnbox d-flex align-items-center justify-content-center">
                            <a href="#" class="btn_p" onclick="PrintImage('<?=$google_chart_api_url?>'); return false;">Print QR<span><img src="<?= $dir . 'profile/'?>Print.svg" alt=""
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
                                        <h4><?= @$schoolD[0]['school_name'] ?>  </h4> </br>
                                        <button id="schoolBox" href="#" class="btn btn-primary">Get Admission</button>
                                    </div>
                                </li>
                           
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
                             

                                <!-- <li>
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
                                </li> -->

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
                                        <h4><?=  $sd['cityName'] . " - " . $sd['stateName'] . " - India"; ?>
                                        </h4>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php 
                
                  
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
                                            
                                          }else
                                          {
 $tTable = Table::studentTable;
                                          }
                                         
                                          $qrTable = Table::qrcodeTable;
                                        $ratingProviderUser =  $this->db->query("SELECT u.father_name, u.mother_name, u.user_id, qr.qrcodeUrl
                                         FROM ".$tTable." u
                                        LEFT JOIN ".$qrTable." qr ON u.user_id = qr.uniqueValue
                                        WHERE u.id = '{$r['login_user_id']}' 
                                        AND u.schoolUniqueCode = '{$sd['schoolUniqueCode']}' 
                                        AND qr.schoolUniqueCode = '{$sd['schoolUniqueCode']}'
                                        AND u.status = '1' ")->result_array();
                                          
                                        if(!empty($ratingProviderUser))
                                        { ?>
                                         <h5><?=@$ratingProviderUser[0]['father_name'][0]; ?></h5>
                                         <a href="<?= @$ratingProviderUser[0]['qrcodeUrl'] ?>" target="_blank">
                                          <h6>
                                            <b><?= @$ratingProviderUser[0]['father_name'] . " & " . @$ratingProviderUser[0]['mother_name'] ?>
                                           </b>&nbsp;
                                          </a>
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
 <?php }  ?>

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
                            <!-- <a href="#" class="btn_s">Share<span><img src="<?= $dir . 'profile/'?>share.svg" alt=""
                                        class="img-res" /></span></a> -->
                            <a href="#" class="btn_d" onclick="PrintImage('<?=$google_chart_api_url?>'); return false;">Download<span><img src="<?= $dir . 'profile/'?>download.svg" alt=""
                                        class="img-res"  /></span></a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
</div>






<div class="modal fade" id="modalBox" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Admission On School</h5>
        <!-- <button type="button" class="close" data-dismiss="modal" id="closeMe"  aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body">
      <b>Contact Number: </b> <a href="tel:+91<?= $schoolD[0]['mobile'] ?>"><?= $schoolD[0]['mobile'] ?></a> </br>
        <b>Email : </b> <a href="mailto:<?= $schoolD[0]['email'] ?>" ><?= $schoolD[0]['email'] ?></a> </br>
        <b>Address : </b> <?= $schoolD[0]['address'] ?> </br>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="closeMe" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>





  <!-- Modal -->
  <div class="modal fade" id="socialModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog " role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Select Social Platform</h5>
        <!-- <button type="button" class="close" data-dismiss="modal" id="closeMe" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body">
        <!-- <div class="alert alert-warning"> -->
        <span>Share This Amazing Profile on Social Media</span>
        <p class="my-1">

            <a class="btn btn-primary" href="http://www.facebook.com/sharer.php?u=<?php echo base_url("?") . $_SERVER['QUERY_STRING']; ?>"
                target="_blank" rel='noreferrer' rel="noopener">FaceBook</a>


            <a class="btn btn-info"
                href="http://twitter.com/share?url=<?php echo base_url("?") . $_SERVER['QUERY_STRING']; ?>&text=<?= $shareMsg?>&hashtags=My Digital Id"
                target="_blank" rel='noreferrer' rel="noopener">Twitter</a>

            <a class="btn btn-success"
                href="https://api.whatsapp.com/send?text=<?php echo urlencode($shareMsg) . " " . base_url("?") . $_SERVER['QUERY_STRING']; ?>"
                target="_blank" rel='noreferrer' rel="noopener">WhatsApp</a>
        </p>

    <!-- </div> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="closeMeShare" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
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


$("#schoolBox").click(function(e){
  e.preventDefault();
  $("#modalBox").modal("show");
})

$("#closeMe").click(function(e){
  e.preventDefault();
  $("#modalBox").modal("hide");
})

$("#shareBtn").click(function(e){
  e.preventDefault();
  $("#socialModal").modal("show");
})

$("#closeMeShare").click(function(e){
  e.preventDefault();
  $("#socialModal").modal("hide");
})





</script>