<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>DRIVERS | ChartJS</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="index2.php" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>LT</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>DRIVERS</b>.io</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->


                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <?php require_once("aside.php") ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <h1 style="text-align: center;margin-bottom: 20px;">SETTINGS PAGE</h1>
            <div class="row margin">
                <div class="col-sm-3">
                </div>

                <div class="col-sm-6">
                    <input type="text" value="" class="slider form-control" data-slider-min="0" data-slider-max="1"
                           data-slider-step="0.1" data-slider-value="[0,1]" data-slider-orientation="horizontal"
                           data-slider-selection="before" data-slider-tooltip="show" data-slider-id="red">

                    <p>WEIGHT SETTINGS(Scale 0-1)</p>
                    <input type="text" value="" class="slider form-control" data-slider-min="0" data-slider-max="100"
                           data-slider-step="5" data-slider-value="[0.5,100]" data-slider-orientation="horizontal"
                           data-slider-selection="before" data-slider-tooltip="show" data-slider-id="blue">

                    <p>PROCE SETTINGS (Scale 1-100)</p>

                </div>

            </div>

            <div class="box box-warning ">
                <div class="box-header with-border">
                    <h3 class="box-title">Other Settings</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                class="fa fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="checkbox" id="exampleInputEmail1">
                            <label for="exampleInputEmail1" style="padding: 10px;">Type One Settings</label>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" id="exampleInputEmail1">
                            <label for="exampleInputEmail1" style="padding: 10px;">Type Two Settings</label>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" checked="true" id="exampleInputEmail1">
                            <label for="exampleInputEmail1" style="padding: 10px;">Type Three Settings</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="checkbox" checked="true" id="exampleInputEmail1">
                            <label for="exampleInputEmail1" style="padding: 10px;">Type One Settings</label>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" id="exampleInputEmail1">
                            <label for="exampleInputEmail1" style="padding: 10px;">Type Two Settings</label>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" checked="true" id="exampleInputEmail1">
                            <label for="exampleInputEmail1" style="padding: 10px;">Type Three Settings</label>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>


    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="plugins/chartjs/Chart.min.js"></script>
<!-- FastClick -->
<script src="plugins/chartjs/canvasjs.min.js"></script>

<script src="plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Ion Slider -->
<link rel="stylesheet" href="plugins/ionslider/ion.rangeSlider.css">
<!-- ion slider Nice -->
<link rel="stylesheet" href="plugins/ionslider/ion.rangeSlider.skinNice.css">
<!-- bootstrap slider -->
<link rel="stylesheet" href="plugins/bootstrap-slider/slider.css">

<!-- page script -->
<!-- Ion Slider -->
<script src="plugins/ionslider/ion.rangeSlider.min.js"></script>
<!-- Bootstrap slider -->
<script src="plugins/bootstrap-slider/bootstrap-slider.js"></script>
<script>
    $(function () {
        /* BOOTSTRAP SLIDER */
        $('.slider').slider();

        /* ION SLIDER */
        $("#range_1").ionRangeSlider({
            min: 0,
            max: 5000,
            from: 1000,
            to: 4000,
            type: 'double',
            step: 1,
            prefix: "$",
            prettify: false,
            hasGrid: true
        });
        $("#range_2").ionRangeSlider();

        $("#range_5").ionRangeSlider({
            min: 0,
            max: 10,
            type: 'single',
            step: 0.1,
            postfix: " mm",
            prettify: false,
            hasGrid: true
        });
        $("#range_6").ionRangeSlider({
            min: -50,
            max: 50,
            from: 0,
            type: 'single',
            step: 1,
            postfix: "°",
            prettify: false,
            hasGrid: true
        });

        $("#range_4").ionRangeSlider({
            type: "single",
            step: 100,
            postfix: " light years",
            from: 55000,
            hideMinMax: true,
            hideFromTo: false
        });
        $("#range_3").ionRangeSlider({
            type: "double",
            postfix: " miles",
            step: 10000,
            from: 25000000,
            to: 35000000,
            onChange: function (obj) {
                var t = "";
                for (var prop in obj) {
                    t += prop + ": " + obj[prop] + "\r\n";
                }
                $("#result").html(t);
            },
            onLoad: function (obj) {
                //
            }
        });
    });
</script>

</body>
</html>
