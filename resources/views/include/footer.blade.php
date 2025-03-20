<footer class="main-footer" align="center">
    <div class="float-right d-none d-sm-block">
    </div>
    <p>{{ config('app.name', 'Laravel') }} &copy; <?php echo date('Y'); ?> - <?php echo date('Y') + 1; ?> All Rights Reserved.</p>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="{{ asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Bootstrap Switch -->
<script src="{{ asset('assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<!-- BS-Stepper -->
<script src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<!-- dropzonejs -->
<script src="{{ asset('assets/plugins/dropzone/min/dropzone.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- CodeMirror -->
<script src="{{ asset('assets/plugins/codemirror/codemirror.js') }}"></script>
<script src="{{ asset('assets/plugins/codemirror/mode/css/css.js') }}"></script>
<script src="{{ asset('assets/plugins/codemirror/mode/xml/xml.js') }}"></script>
<script src="{{ asset('assets/plugins/codemirror/mode/htmlmixed/htmlmixed.js') }}"></script>
<!-- jquery-validation -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script src="{{ asset('/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

<div class="overlay"></div>




@error('pin_mask')
    <script type=" text/javascript">
    $(function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        Toast.fire({
            type: 'error',
            title: '{{ $message }}'
        });
    })
</script>
@enderror
<script>
    $(function() {

        $('#example1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });


        //$("body").addClass("loading");


        $("input[data-bootstrap-switch]").each(function() {
            $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });
        //Initialize Select2 Elements
        $('.select2').select2();


        $('#summernote').summernote()

        $('#reservationtime').daterangepicker({
            timePicker: true,
            timePickerSeconds: true, // Enable seconds in the time picker
            timePickerIncrement: 30,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss'
                //  format: 'YYYY-MM-DD hh:mm:ss A' // Alternative format with seconds
            }
        })


        var start = moment().subtract(0, 'days');
        var end = moment();
        cb(start, end);

        function cb(start, end) {
            $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#reportrange').val(start.format('YYYY-MM-DD') + '&' + end.format('YYYY-MM-DD'));
        }

        $('#daterange-btn').daterangepicker({
            startDate: start,
            endDate: end,
            showCustomRangeLabel: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')]
            }
        }, cb);


        var tml_start = moment().startOf('month');
        var tml_end = moment().endOf('month');
        tml_cb(tml_start, tml_end);

        function tml_cb(start, end) {
            $('#tml_daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#tml_daterange_input').val(start.format('YYYY-MM-DD') + '&' + end.format('YYYY-MM-DD'));
        }
        $('#tml_daterange').daterangepicker({
            startDate: tml_start,
            endDate: tml_end,
            showCustomRangeLabel: true,
            ranges: {
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')]
            }
        }, tml_cb);



        //Date picker
        $('#reservationdate').datetimepicker({
            format: 'L'
        });

        //Date and time picker
        $('#reservationdatetime').datetimepicker({
            icons: {
                time: 'far fa-clock'
            }
        });

        //Date range picker
        $('#reservation').daterangepicker()
        //Date range picker with time picker
        //$('#reservationtime').daterangepicker({
        //    timePicker: true,
        //    timePickerIncrement: 30,
        //    locale: {
        //        format: 'MM/DD/YYYY hh:mm A'
        //    }
        // })

        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });

        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type') }}";
            switch (type) {
                case 'info':
                    toastr.info("{{ Session::get('message') }}");
                    break;
                case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    break;
                case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;
                case 'error':
                    toastr.error("{{ Session::get('message') }}");
                    break;
            }
        @endif
    });

    function changePass() {
        $.ajax({
            type: 'get',
            success: function(response) {
                // Add response in Modal body
                // console.log(response.html);
                if (response.status == 'success') {
                    $('.modal-body').html(response.html);

                    $("#empModal").modal({
                        show: true
                    });
                } else {
                    toastr.error('Something Went Wrong!')
                }

            }
        });
    }

    $(document).on({
        ajaxStart: function() {
            $("body").addClass("loading");
        },
        ajaxStop: function() {
            $("body").removeClass("loading");
        }
    });
</script>
<script>
    $(document).ready(function() {
        toastr.options.timeOut = 10000;
        @if (Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @elseif (Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @elseif (Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif
    });
</script>
<script>
    $(function() {
        $('.select2').select2()
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
        $('.select2bs4Edit').select2({
            theme: 'bootstrap4'
        })
        $('.select2bs4Create').select2({
            theme: 'bootstrap4'
        })
    });
</script>

<div class="overlay"></div>
<script>
    $(window).on('load', function() {
        $("body").removeClass("loading");
        console.log("Window Loaded");
    });
    $(document).on({
        ajaxStart: function() {
            $("body").addClass("loading");
        },
        ajaxStop: function() {
            $("body").removeClass("loading");
        }
    });
</script>

</body>

</html>
