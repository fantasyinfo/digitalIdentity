
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $data['pageTitle']?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <!-- <link rel="stylesheet" href="<?= base_url() .$data['adminPanelUrl']?>plugins/fontawesome-free/css/all.min.css"> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">


  <link rel="stylesheet" href="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <link rel="stylesheet" href="<?= base_url() .$data['adminPanelUrl']?>plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?= base_url() .$data['adminPanelUrl']?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url() .$data['adminPanelUrl']?>dist/css/adminlte.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
   <style>
    /* Chrome, Safari, Edge, Opera */
      input::-webkit-outer-spin-button,
      input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }

      /* Firefox */
      input[type=number] {
        -moz-appearance: textfield;
      }


      .border-top-3
      {
        border-top:3px solid #343a40;
      }

      .mybtnColor{
        background-color: #343a40;
        color:white;
      }
      .mybtnColor:hover{
        color:white;
        background-color: #002000;
      }
      .bg-light-dark{
        background-color: #f2f2f2;
        color:#000;
      }
      .margin-top-30{
        margin-top:30px;
      }

      .bg-blue-my{
        background-color: #D4F5FC;
      }

      .bg-red-my{
        background-color: #FF78AB;
      }

      .font-size-22{
        font-size:22px;
      }


      .bg-light-primary {
        background-color: rgba(0,123,255,.5);
      }
      .bg-light-warning {
        background-color: rgba(255,193,7,.5);
      }
      .bg-light-danger {
        background-color: rgba(220,53,69,.5);
      }
      .bg-light-info {
        background-color: rgba(23,162,84,.5);
      }
      .bg-light-success {
        background-color: rgba(40,167,69,.5);
      }




      /* .txt-light-primary {
        color: rgba(0,123,255,.5);
      }
      .txt-light-warning {
        color: rgba(255,193,7,.5);
      }
      .txt-light-danger {
        color: rgba(220,53,69,.5);
      }
      .txt-light-info {
        color: rgba(23,162,84,.5);
      }
      .txt-light-success {
        color: rgba(40,167,69,.5);
      }


      .txt-light-primary:hover {
        color: rgb(0,123,255);
      }
      .txt-light-warning:hover {
        color: rgb(255,193,7);
      }
      .txt-light-danger:hover {
        color: rgb(220,53,69);
      }
      .txt-light-info:hover {
        color: rgb(23,162,84);
      }
      .txt-light-success:hover {
        color: rgb(40,167,69);
      } */





   </style>

<script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">
</head>