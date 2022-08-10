<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jquery-validation -->
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/jquery-validation/additional-methods.min.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/chart.js/Chart.min.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?= base_url() .$data['adminPanelUrl']?>dist/js/pages/dashboard3.js"></script>

<!-- Select2 -->
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/select2/js/select2.full.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url() .$data['adminPanelUrl']?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE -->
<script src="<?= base_url() .$data['adminPanelUrl']?>dist/js/adminlte.js"></script>
<!-- Custom Logic -->
<script src="<?= base_url() .$data['adminPanelUrl']?>dist/js/custom.js"></script>
<script>
  console.log(ajaxUrl);
 
    var dataTableAjax = $("#listDatatable").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
      dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        serverSide: true,
        searching: true,
        ajax : {
          method: 'post',
          url: ajaxUrl,
          data : '',
          error: function ()
          {
            console.log('something went wrong.');
          }
        }
    });


  dataTableAjax.draw();
</script>
</body>
</html>
