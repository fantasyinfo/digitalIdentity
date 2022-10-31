
<link rel="stylesheet" href="<?= base_url() .$data['adminPanelUrl']?>fullcalendar/fullcalendar.min.css" />





<style>
body {
    font-family: Arial;
}
h1#demo-title {
    margin: 30px 0px 80px 0px;
    text-align: center;
}

#event-action-response {
    background-color: #5ce4c6;
    border: #57d4b8 1px solid;
    padding: 10px 20px;
    border-radius: 3px;
    margin-bottom: 15px;
    color: #333;
    display: none;
}

.fc-day-grid-event .fc-content {
    background: #586e75;
    color: #FFF;
}

.fc-event, .fc-event-dot {
    background-color: #586e75;
}

.fc-event {
    border: 1px solid #485b61;
}
</style>


<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
<?php $this->load->view('adminPanel/pages/navbar.php');?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php $this->load->view("adminPanel/pages/sidebar.php");?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
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
      <?php 

function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
{
    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    $dateArr = array();

    do
    {
        if(date("w", $startDate) != $weekdayNumber)
        {
            $startDate += (24 * 3600); // add 1 day
        }
    } while(date("w", $startDate) != $weekdayNumber);


    while($startDate <= $endDate)
    {
        $dateArr[] = date('Y-m-d', $startDate);
        $startDate += (7 * 24 * 3600); // add 7 days
    }

    return($dateArr);
}





function markAllSundayHoliday($db)
{
  $year   = date("Y");

  $dateArr = getDateForSpecificDayBetweenDates($year.'-01-01', $year.'-12-31', 0);
  
  for($i=0; $total = count($dateArr), $i < $total; $i++)
  {
    // first check if already mark sunday then continue;
    $already = $db->query("SELECT * FROM ".Table::holidayCalendarTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND title = 'Sunday' AND event_date = '$dateArr[$i]' AND session_table_id = '{$_SESSION['currentSession']}'")->result_array();

    if(!empty($already))
    {
      continue;
    }
    $db->query("INSERT INTO ".Table::holidayCalendarTable." (schoolUniqueCode,title,event_date,session_table_id) VALUES ('{$_SESSION['schoolUniqueCode']}','Sunday','$dateArr[$i]','{$_SESSION['currentSession']}')");
  }
}



if(isset($_GET['markSunday']))
{
  markAllSundayHoliday($this->db);
}

// hide button 
$alreadyMarkSunday = FALSE;
$checkIsAlreadyMarkSunday = $this->db->query("SELECT count(1) as c FROM ".Table::holidayCalendarTable." WHERE schoolUniqueCode = '{$_SESSION['schoolUniqueCode']}' AND title = 'Sunday' AND session_table_id = '{$_SESSION['currentSession']}'")->result_array()[0]['c'];

if($checkIsAlreadyMarkSunday > 51)
{
  $alreadyMarkSunday = TRUE;
}

$this->load->model('CrudModel');



              if(!empty($this->session->userdata('msg')))
              {?>

              <div class="alert alert-<?=$this->session->userdata('class')?> alert-dismissible fade show" role="alert">
                <?=$this->session->userdata('msg');
                  if($this->session->userdata('class') == 'success')
                  {
                    HelperClass::swalSuccess($this->session->userdata('msg'));
                  }else if($this->session->userdata('class') == 'danger')
                  {
                    HelperClass::swalError($this->session->userdata('msg'));
                  }
                ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
              $this->session->unset_userdata('class') ;
              $this->session->unset_userdata('msg') ;
              }
              ?>
     
        <div class="row">
       
        <?php  if(!$alreadyMarkSunday) {
          echo '<a href="?markSunday=true" class="btn btn-success">Mark All Sundays Holiday of Current Year</a>';
          }
          ?>
      
        <div id="event-action-response"></div>
        <div id="calendar"></div>

            </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->

  <!-- /.control-sidebar -->
</div>




  <?php $this->load->view("adminPanel/pages/footer-copyright.php");?>
</div>
<?php $this->load->view("adminPanel/pages/footer.php");?>
<!-- ./wrapper -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js" ></script>


<script src="<?= base_url() .$data['adminPanelUrl']?>fullcalendar/lib/moment.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>fullcalendar/fullcalendar.min.js"></script>

<script>
  var addHolidayEvent = '<?= base_url() . 'ajax/addHolidayEvent'?>';
  var editHolidayEvent = '<?= base_url() . 'ajax/editHolidayEvent'?>';
  var getHolidayEvent = '<?= base_url() . 'ajax/getHolidayEvent'?>';
  
$(document).ready(function() {
    var calendar = $('#calendar').fullCalendar({
        editable:true,
        events: getHolidayEvent,
        selectable:true,
        selectHelper:true,
        select: function(start,allDay)
        {
             var Event = prompt("Add Event");
             if(Event)
             {
                  var Date = $.fullCalendar.formatDate(start, "Y-MM-DD");
                  $("#event-action-response").hide();
                  $.ajax({
                       url:addHolidayEvent,
                       type:"POST",
                       data:{title:Event, start:Date},
                       success:function()
                       {
                        calendar.fullCalendar('refetchEvents');
                        $("#event-action-response").html("Event added Successfully");
                        $("#event-action-response").show();
                       }
                  })
             }
        },
        eventDrop: function(event, delta, revertFunc) {
            if (!confirm("Are you sure about to move this event?")) {
                 revertFunc();
            } else {
                var editedDate = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                $("#event-action-response").hide();
                $.ajax({
                    url:editHolidayEvent,
                    type:"POST",
                    data:{event_id:event.id, start:editedDate},
                    success:function(resource)
                    {
                     calendar.fullCalendar('refetchEvents');
                     $("#event-action-response").html("Event moved Successfully");
                     $("#event-action-response").show();
                    }
                })
            }
        },        
    });
});  



</script>
