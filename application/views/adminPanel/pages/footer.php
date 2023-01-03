<!-- REQUIRED SCRIPTS -->

<style>
  .float {
    position: fixed;
    width: 60px;
    height: 60px;
    bottom: 20px;
    right: 5px;
    background-color: #25d366;
    color: #FFF;
    border-radius: 50px;
    text-align: center;
    font-size: 30px;
    box-shadow: 2px 2px 3px #999;
    z-index: 100;
  }

  .my-float {
    margin-top: 16px;
  }
</style>

<a href="https://api.whatsapp.com/send?phone=916397520221&text=Hi,I need help in digitalfied digital identity software." class="float" target="_blank">
  <i class="fa-brands fa-whatsapp"></i>
</a>






<!-- jQuery -->
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jquery-validation -->
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/jquery-validation/additional-methods.min.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= base_url() . $data['adminPanelUrl'] ?>dist/js/pages/dashboard3.js"></script>

<!-- Select2 -->
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/select2/js/select2.full.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url() . $data['adminPanelUrl'] ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE -->
<script src="<?= base_url() . $data['adminPanelUrl'] ?>dist/js/adminlte.js"></script>
<!-- Custom Logic -->
<script src="<?= base_url() . $data['adminPanelUrl'] ?>dist/js/custom.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->

<script>
  $(function() {
    $(".datePicker").datepicker({
        changeYear: true,
        changeMonth: true,
        dateFormat: "yy-mm-dd",
        orientation: 'bottom auto',
        todayHighlight: true,
        autoclose: true,
      }

    );
  });


  $(function() {
    $('[data-toggle="tooltip"]').tooltip()
  })

  paceOptions = {
    // Disable the 'elements' source
    elements: false,

    // Only show the progress on regular and ajax-y page navigation, not every request
    restartOnRequestAfter: false
}
</script>

</body>

</html>