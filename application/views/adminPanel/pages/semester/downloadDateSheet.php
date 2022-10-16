<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <title>DateSheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800;900&display=swap');
    
    body{
        font-family: 'Poppins', sans-serif;
        background-color:#f8f8f8;
    }
    p{
        font-size: 18px;
    }


    tr,td,th
    {
        border: 1px solid black;
    }
    /* .bg-diff{
        background: #fff;
        border: 1px solid #8d41d3;
        height: 700px;
        width: 1000px;
    } */

    .voilet{
        background: red;
        /* background-image: url("https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjYjLksfaOsS3oLuwYkUfbl0NtjLBqppMzwiwCBcERfvkuy9hWNIV9j081gvcPe5w_8TE8YSLyaF3zZAr3eu8UcLyIGY_ADf9hC6kJgIUUg-E42K-NMa093h50RgCInkbZ3yVLKsjVXarUti_N2xlhlQOwqJefvWf2O8KhZQAyt8lOCp_Taol0IN_VTWA/s320/pila%20bg.png"); */
    }
    @page {
    size: auto;
    margin: 0;
}

@print {
    @page :footer {
        display: none
    }
 
    @page :header {
        display: none
    }
}


@media print {
    @page {
        margin-top:2px;
        margin-bottom: 2px;
    }
    body {
        padding: 20px;
       
    }
    p{
        font-size: 14px;
    }

    .bg-diff{
        background: #fff;
        border: 1px solid #8d41d3;
    }

    .voilet{
        background-color: #8d41d3;
        color: #fff;
    }
}
</style>
    
  </head>
  <body>
  
<?php 

// print_r($_GET);
$condition = '';
if(!empty($_GET['classId']) && !empty($_GET['sectionId']) && !empty($_GET['secExamNameId']))
{
    $condition .= " AND sen.id = '{$_GET['secExamNameId']}' ";
    $condition .= " AND c.id = '{$_GET['classId']}' ";
    $condition .= " AND sec.id = '{$_GET['sectionId']}' ";

    $d = $this->db->query("SELECT se.id as semId, se.exam_date,se.exam_day,se.exam_start_time,se.exam_end_time, se.min_marks, se.max_marks, se.status,sen.id as semNameId, sen.sem_exam_name, sen.exam_year,c.className,sec.sectionName,sub.subjectName
    FROM " .Table::secExamTable." se 
    LEFT JOIN ".Table::semExamNameTable." sen ON sen.id =  se.sem_exam_id
    LEFT JOIN ".Table::classTable." c ON c.id =  se.class_id
    LEFT JOIN ".Table::sectionTable." sec ON sec.id =  se.section_id
    LEFT JOIN ".Table::subjectTable." sub ON sub.id =  se.subject_id
    WHERE se.status != 4 $condition ")->result_array();


    if(!empty($d))
    {
        $data = $d;

        $schoolName = $this->db->query("SELECT school_name FROM ".Table::schoolMasterTable ." WHERE status = '2' AND unique_id = '{$_SESSION['schoolUniqueCode']}'")->result_array()[0]['school_name'];
    }

}else
{
    // redirect to home
}




?>
    <div class="container mt-5" >
        <div class="row" id="printableArea">
            <div class="col-md-8 mx-auto text-center">
                <p class="h2"><?=  $schoolName; ?></p>
            </div>
            <div class="col-md-12  text-center">
                <div class="row my-2">
                    <div class="col-md-8 mx-auto">
                        <p>Date Sheet For <?=  $data[0]['sem_exam_name'] . " " . $data[0]['exam_year']; ?> </p>
                        <p>Class <?=  $data[0]['className'] . " " . $data[0]['sectionName']; ?></p>
                    </div>
                </div>
            </div>
   
            <div class="col-md-10 my-2 mx-auto ">
                <table class="table table-striped text-center" border="1">
                    <thead>
                      <tr class="bg-primary text-white">
                        <th scope="col">Date</th>
                        <th scope="col">Day</th>
                        <th scope="col">Subject Name</th>
                       
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach($data as $dd)
                        { ?>
                         <tr>
                            <td><?= date('d-m-Y', strtotime($dd['exam_date']));?></td>
                            <td><?=$dd['exam_day'];?></td>
                            <td scope="row"><?=$dd['subjectName'];?></td>
                         </tr>

                      <?php  }
                        
                        
                        ?>
                    </tbody>
                  </table>
            </div>

        </div>
    
    </div>


    
    <div class="row mt-5" id="hideMe">
        <div class="col-md-12 mx-auto text-center">
            <!-- <button  class="btn btn-primary" onclick="printDiv('printableArea')">Download</button> -->
            <a href="javascript:getScreen()"  class="btn btn-primary">Capture</a>
            <!-- <button class="btn btn-success">Share</button> -->
        </div>
    </div>


    <a href="" id="dd"></a>

<!-- 
    <script>
        function printDiv(divName) {
         var printContents = document.getElementById(divName).innerHTML;
         var originalContents = document.body.innerHTML;
    
         document.body.innerHTML = printContents;
    
         window.print();
    
         document.body.innerHTML = originalContents;
    }
    </script> -->



<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
 function screenshot(){
         html2canvas(document.getElementById('container')).then(function(canvas) {
            document.body.appendChild(canvas);
         });
      }

function getScreen()
{
    $("#hideMe").hide();
   let screenshotName = '<?=  $data[0]['sem_exam_name'] . " " . $data[0]['exam_year'] . " DateSheet For Class  ". $data[0]['className'] . " " . $data[0]['sectionName']; ?>';
    // html2canvas(document.body,{
    //     dpi:192,
    //     onrendered: function(canvas)
    //     {
    //         console.log(canvas);
    //     }
    // });
    html2canvas(document.body).then(function(canvas) {
    document.body.appendChild(canvas);
    $("#dd").attr('href',canvas.toDataURL("image/png"));
    $("#dd").attr('download',screenshotName);
    $("#dd")[0].click();
    $("#hideMe").show();
});
}
</script> 
  </body>
</html>