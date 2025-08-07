<script src="assets/libs/sweetalert/sweetalert-dev.js"></script>
<script src="assets/js/jquery.preloader.min.js"></script>

<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Required datatable js -->
<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>

<!-- Responsive examples -->
<script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
<!-- Datatable init js -->
<script src="assets/js/pages/datatables.init.js"></script>
<script>
    $(function () {

        // Initialize the datepicker
        $(".date-picker").datepicker({
            dateFormat: 'yy-mm-dd' // or 'dd-mm-yy' as per your format
        });

        // Set today's date as default value
        var today = $.datepicker.formatDate('yy-mm-dd', new Date());
        $(".date-picker").val(today);
    });


    $(function () {

        // Initialize the datepicker
        $(".date-picker-date").datepicker({
            dateFormat: 'yy-mm-dd' // or 'dd-mm-yy' as per your format
        });

    });


    ///data table loard
    $('#dagTable').DataTable();
    $('#maindagTable').DataTable();
$('.datatable').DataTable();
</script>