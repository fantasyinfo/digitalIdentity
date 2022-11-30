<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <a href="https://www.digitalfied.com" target="_blank"><img src="https://www.digitalfied.com/digitalfied_logo.png" alt="Digitalfied" class="img-responsive" height="70px"></a>
    <!-- Right navbar links -->
<?php

$schoolUniqueCode = $_SESSION['schoolUniqueCode'];
$currentSession = $_SESSION['currentSession'];

$currentSession = $this->db->query("SELECT * FROM " . Table::schoolSessionTable . " WHERE schoolUniqueCode = '$schoolUniqueCode' AND id = '$currentSession' LIMIT 1")->result_array();

if(!empty($currentSession)){ 
  $startYear = $currentSession[0]['session_start_year'];
  $endYear = $currentSession[0]['session_end_year'];
  
  ?>

  <?= "<div class='col-md-4 ml-3 h4'> Current Session : $startYear  - $endYear </div>"; ?>
<?php } ?>
    <ul class="navbar-nav ml-auto">
  
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url() . 'logout'?>" role="button">
         Logout
        </a>
      </li>
    </ul>
  </nav>