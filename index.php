<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';

?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Home | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name; ?>" name="author" />
    <?php include 'main-css.php'; ?>


    <style>
        .chart-container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .chart-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .chart-title {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #5b73e8, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .chart-subtitle {
            font-size: 1.1rem;
            color: #6c757d;
            font-weight: 500;
        }

        .chart-wrapper {
            position: relative;
            height: 500px;
            margin: 30px 100px;
            background: linear-gradient(145deg, #f8f9ff, #e8ecff);
            border-radius: 15px;
            padding: 40px;
            box-shadow: inset 0 2px 10px rgba(91, 115, 232, 0.1);
        }

        .bar-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            height: 100%;
            position: relative;
        }

        .bar-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            margin: 0 8px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .bar-wrapper:hover {
            transform: translateY(-5px);
        }

        .bar {
            width: 100%;
            max-width: 50px;
            background: linear-gradient(180deg, #5b73e8, #667eea);
            border-radius: 8px 8px 4px 4px;
            position: relative;
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(91, 115, 232, 0.3);
            transform-origin: bottom;
            animation: barGrow 1.5s ease-out forwards;
            animation-delay: calc(var(--index) * 0.1s);
            height: 0;
        }

        @keyframes barGrow {
            from {
                height: 0;
                transform: scaleY(0);
            }

            to {
                height: var(--height);
                transform: scaleY(1);
            }
        }

        .bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #fff, #f0f2ff);
            border-radius: 8px 8px 0 0;
            opacity: 0.8;
        }

        .bar:hover {
            background: linear-gradient(180deg, #6c82f0, #7589f2);
            box-shadow: 0 6px 25px rgba(91, 115, 232, 0.4);
            transform: scale(1.05);
        }

        .bar-value {
            position: absolute;
            top: -35px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(91, 115, 232, 0.9);
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            opacity: 0;
            transition: opacity 0.3s ease;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .bar-wrapper:hover .bar-value {
            opacity: 1;
        }

        .bar-label {
            margin-top: 15px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chart-grid {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 60px;
            pointer-events: none;
        }

        .grid-line {
            position: absolute;
            left: 0;
            right: 0;
            height: 1px;
            background: rgba(108, 117, 125, 0.15);
        }

        .grid-label {
            position: absolute;
            left: -50px;
            transform: translateY(-50%);
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .chart-wrapper {
                padding: 20px;
                height: 400px;
            }

            .chart-title {
                font-size: 2rem;
            }

            .bar {
                max-width: 35px;
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 4px 15px rgba(91, 115, 232, 0.3);
            }

            50% {
                box-shadow: 0 6px 25px rgba(91, 115, 232, 0.5);
            }

            100% {
                box-shadow: 0 4px 15px rgba(91, 115, 232, 0.3);
            }
        }
    </style>

</head>

<body data-layout="horizontal" data-topbar="colored">

    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php include 'navigation.php' ?>



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Dashboard</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
                    <?php
                    $ITEM_MASTER = new ItemMaster(NULL);
                    $MESSAGE = new Message(null);

                    $reorderItems = $ITEM_MASTER->checkReorderLevel();

                    if (!empty($reorderItems)) {
                        $customMessages = [];

                        foreach ($reorderItems as $item) {
                            $customMessages[] = "Reorder Alert: <strong>{$item['code']}</strong> - {$item['name']} is below reorder level.";
                        }

                        $MESSAGE->showCustomMessages($customMessages, 'danger');
                    }

                    ?>

                    <div class="row">
                        <!-- Total Tire Sales -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="float-end mt-2">
                                        <div id="tire-sales-chart"></div>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 mt-1">LKR <span data-plugin="counterup">128,450</span></h4>
                                        <p class="text-muted mb-0">Total Tire Sales</p>
                                    </div>
                                    <p class="text-muted mt-3 mb-0">
                                        <span class="text-success me-1">
                                            <i class="mdi mdi-arrow-up-bold me-1"></i>5.2%
                                        </span> this month
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Availability -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="float-end mt-2">
                                        <div id="stock-chart"></div>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 mt-1"><span data-plugin="counterup">2,450</span></h4>
                                        <p class="text-muted mb-0">Tires in Stock</p>
                                    </div>
                                    <p class="text-muted mt-3 mb-0">
                                        <span class="text-danger me-1">
                                            <i class="mdi mdi-arrow-down-bold me-1"></i>3.1%
                                        </span> from last week
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Suppliers -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="float-end mt-2">
                                        <div id="suppliers-chart"></div>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 mt-1"><span data-plugin="counterup">18</span></h4>
                                        <p class="text-muted mb-0">Active Suppliers</p>
                                    </div>
                                    <p class="text-muted mt-3 mb-0">
                                        <span class="text-success me-1">
                                            <i class="mdi mdi-arrow-up-bold me-1"></i>2 New
                                        </span> this month
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Revenue Growth -->
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="float-end mt-2">
                                        <div id="revenue-growth-chart"></div>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 mt-1">+ <span data-plugin="counterup">15.75</span>%</h4>
                                        <p class="text-muted mb-0">Revenue Growth</p>
                                    </div>
                                    <p class="text-muted mt-3 mb-0">
                                        <span class="text-success me-1">
                                            <i class="mdi mdi-arrow-up-bold me-1"></i>12.4%
                                        </span> vs last quarter
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Bar Chart -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Sales Overview</h4>
                                    <p class="card-title-desc">Monthly sales performance</p>
                                </div>
                                <div class="card-body">
                                    <div class="chart-wrapper">
                                        <div class="chart-grid" id="chart-grid"></div>
                                        <div class="bar-container" id="bar-container">
                                            <!-- Bars will be generated by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Bar Chart -->
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->


            <?php include 'footer.php' ?>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="ajax/js/common.js"></script>

    <!-- ApexCharts -->
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

    <!-- include main js  -->
    <?php include 'main-js.php' ?>

    <!-- Dashboard init -->
    <script src="assets/js/pages/dashboard.init.js"></script>

</body>

</html>