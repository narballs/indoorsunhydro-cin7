<!-- jQuery -->
<script src="/newsletter_panel/admin/pluginss/jquery/jquery.min.js"></script>
<script src="/newsletter_panel/admin/pluginss/jquery-ui/jquery-ui.min.js"></script>
<script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
<!-- Bootstrap -->
<script src="/newsletter_panel/admin/pluginss/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/newsletter_panel/admin/pluginss/chart.js/Chart.min.js"></script>
<script src="/newsletter_panel/admin/pluginss/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="/newsletter_panel/admin/pluginss/jqvmap/jquery.vmap.min.js"></script>
<script src="/newsletter_panel/admin/pluginss/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="/newsletter_panel/admin/pluginss/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="/newsletter_panel/admin/pluginss/moment/moment.min.js"></script>
<script src="/newsletter_panel/admin/pluginss/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="/newsletter_panel/admin/pluginss/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="/newsletter_panel/admin/pluginss/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="/newsletter_panel/admin/pluginss/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/newsletter_panel/admin/distt/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="/newsletter_panel/admin/distt/js/demo.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="/newsletter_panel/admin/pluginss/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="/newsletter_panel/admin/pluginss/raphael/raphael.min.js"></script>
<script src="/newsletter_panel/admin/pluginss/jquery-mapael/jquery.mapael.min.js"></script>
<script src="/newsletter_panel/admin/pluginss/jquery-mapael/maps/usa_states.min.js"></script>
<!-- PAGE SCRIPTS -->
<script src="{{ asset('newsletter_panel/admin/distt/js/bootstrap-tagsinput.min.js') }}"></script>
{{-- <script src="/newsletter_panel/admin/distt/js/pages/dashboard2.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>




<script>
  $(document).ready(function() {
    $('#subscibers_table').DataTable({
        dom: '<"top"lf>rt<"bottom"ip><"clear">',
        ordering: false,
        searching: false,
        info: false,
        lengthChange: true,
        pageLength: 10,
      });
  });
</script>

</body>
</html>