<?php



$schoolD = $this->db->query("SELECT * FROM " . Table::schoolMasterTable . " WHERE unique_id = '{$_SESSION['schoolUniqueCode']}' ORDER BY id DESC LIMIT 1 ")->result_array();


$parentMenu = $this->db->query("SELECT * FROM " . Table::adminPanelMenuTable . " WHERE is_parent = 1 AND status = '1' ORDER BY position ASC")->result_array();

?>

<style>
  .myLink {
    text-decoration: none;
  }

  .myH3 {
    text-transform: uppercase;
    text-align: center;
    color: #fff;

  }

  .myBox {
    height: 200px;
    width: 200px;
    border-radius: 50px;
    margin: 10px;
    padding: 10px;
  }
</style>
<!-- <body style="background: linear-gradient(90deg, rgba(58,156,235,1) 0%, rgba(36,158,255,1) 0%, rgba(6,99,245,1) 100%);"> -->

<body style="background-image: url('https://img.freepik.com/free-photo/wooden-table-with-blurred-background_1134-14.jpg?w=1380&t=st=1670669012~exp=1670669612~hmac=3046ba853447dbf5e65b5318cdf02b46c41492a1703ef8cc4dfbd9d9fb83682c');background-repeat:no-repeat;background-size:cover;background-position: center;background-opacity:0.3">


  <div class="container mt-3">

    <?php if (!empty($schoolD)) { ?>
      <div class="row">
        <div class="col-md-12">
          <p class="text-center h5 text-white"><?= strtoupper($schoolD[0]['school_name']) ?></p>
        </div>
      </div>

    <?php } ?>


    <div class="row">

      <?php if (!empty($parentMenu)) {

        foreach ($parentMenu as $m) {

      ?>
          <div class="col-md-2 my-1 py-1">
            <form action="<?= base_url($m['link']) ?>" method="POST">
              <input type="hidden" name="id" value="<?= $m['id'] ?>" class="myLink">
              <div class="card shadow p-1">
                <input type="submit" value="" style="background-image: url('<?= base_url('assets/uploads/menuIcons/') . @$m['img']; ?>');background-repeat:no-repeat;height:200px;width:100%;background-size: contain;border:none;border-radius:25px;background-position: center center;">
                <button type="submit" class="btn btn-block mybtnColor"><?= $m['name'] ?></button>

              </div>

            </form>
          </div>

      <?php }
      } ?>





    </div>

  </div>









</body>
<?php $this->load->view("adminPanel/pages/footer.php"); ?>