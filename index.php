<!DOCTYPE html>
<html lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
  <title>DataTable</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.css">
  <!-- Customize Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte_cus.css">
  <!-- fontface -->
  <link rel="stylesheet" href="dist/css/fontface.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

  <style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400&display=swap');
    body{
        font-size:0.85rem;
        /*font-family: "Noto Sans Thai",sans-serif;*/
        font-family: 'Sarabun', sans-serif;
        font-style: normal;
        font-weight:500;
    }
  </style>

<!-- jQuery jQuery v3.6.0 -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<div class="card-body p-3">
  <div class="row">
    <div class="col-sm-12 p-0 m-0">

      <a id="some_button" class="btn btn-success">Refresh</a>

      <table id="example1" class="table table-bordered table-hover dataTable dtr-inline table-responsive-xl">
        <thead>
          <tr class="bg-light">
            <th class="sorting_disabled">No</th>
            <th>Col 1</th>
            <th>Col 2</th>
            <th>Col 3</th>
            <th>Col 4</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>

    </div>
  </div><!-- /.row -->

</div><!-- /.card-body -->

<script type="text/javascript"> 

  $('#example1').DataTable({
    "processing": true,
    "serverSide": true,
    "order": [0,'desc'], //ถ้าโหลดครั้งแรกจะให้เรียงตามคอลัมน์ไหนก็ใส่เลขคอลัมน์ 0,'desc'
    "aoColumnDefs": [
      { "bSortable": false, "aTargets": [4] }, //คอลัมน์ที่จะไม่ให้ฟังก์ชั่นเรียง
      { "bSearchable": false, "aTargets": [0, 3, 4] } //คอลัมน์ที่จะไม่ให้เสิร์ช
    ], 
    ajax: {
      beforeSend: function () {
        //จะให้ทำอะไรก่อนส่งค่าไปหรือไม่
      },
      url: 'table.php',
      type: 'POST',
      data : {"action":"get"},//"slt_search":slt_search
      async: false,
      cache: false,
      error: function (xhr, error, code) {
        console.log(xhr, code);
      },
    },
    "paging": true,
    "lengthChange": true, //ออฟชั่นแสดงผลต่อหน้า
    "pagingType": "simple_numbers",
    "pageLength": 10,
    "searching": true,
    "ordering": true,
    "info": true,
    "autoWidth": false,
    "responsive": true,
  });

    $('#some_button').click(function refreshData() {
      $('#example1').DataTable().ajax.reload();
    });

</script>