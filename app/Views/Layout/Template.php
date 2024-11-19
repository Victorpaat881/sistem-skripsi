<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <title><?= $title; ?></title>

    <!-- DON'T CHANGE ANY POSITION OF THIS FILES BELOW OR IT WILL NOT WORK -->

    <!-- DataTables Core -->
    <script src="/assets/Jquery/jquery-3.7.0.min.js"></script>

    <link href="/assets/DataTables/DataTables-1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <script src="/assets/DataTables/DataTables-1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="/assets/DataTables/DataTables-1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> -->
    <!-- <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet"> -->

    <link rel="stylesheet" href="/assets/bootstrap-5.3.0/css/bootstrap.min.css">
    <script src="/assets/bootstrap-5.3.0/js/bootstrap.bundle.min.js"></script>

    <!-- For ApexCharts -->
    <!-- <link rel="stylesheet" href="/assets/apexcharts-bundle/dist/apexcharts.css"> -->
    <link rel="stylesheet" href="/assets/apexcharts/dist/apexcharts.css">
    <script src="/assets/apexcharts/dist/apexcharts.js"></script>

    <!-- For Dark/Light Mode -->
    <link href="/assets/css/dark-light-mode.css" rel="stylesheet">

    <!-- For FontAwesome -->
    <link href="/assets/fontawesome-free-6.4.0-web/css/fontawesome.css" rel="stylesheet">
    <link href="/assets/fontawesome-free-6.4.0-web/css/brands.css" rel="stylesheet">
    <link href="/assets/fontawesome-free-6.4.0-web/css/solid.css" rel="stylesheet">

    <!-- For DataTables extensions -->
    <script src="/assets/Moment/moment.min.js"></script>
    <script src="/assets/Moment/moment-with-locales.js"></script>
    <script src="/assets/Moment/datetime.js"></script>

    <!-- Fixed Header DataTables -->
    <!-- <link href="/assets/DataTables/FixedHeader-3.4.0/css/fixedHeader.dataTables.min.css" rel="stylesheet" />
    <script src="/assets/DataTables/FixedHeader-3.4.0/js/dataTables.fixedHeader.min.js"></script> -->

    <!-- Responsive DataTables Plugin -->
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- Select DataTables Plugin -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/select/1.4.0/css/select.dataTables.min.css">
    <script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script> -->

    <!-- DataTables Button -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <!-- For SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <link href="/assets/css/app.css" rel="stylesheet">
    <!-- <link href="/assets/css/siqtheme.css" rel="stylesheet"> -->

    <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/favicon.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/img/favicon.png">

    <style>
        @media screen and (max-width: 768px) {
            .cari {
                width: 100%;
            }
        }

        @media screen and (min-width: 768px) {
            .cari {
                width: 70%;

            }
        }

        @media screen and (min-width: 1200px) {
            .cari {
                width: 80%;
            }
        }

        .table .thead-light th,
        .table td,
        .table {
            border: 1px solid #555555;
        }

        .table .thead-light th {
            background-color: #cbcbcb;
            font-size: small;
        }

        .modal-dark {
            color: white;
            background-color: #2c2f31 !important;
        }

        .table .tbody-dark td {
            color: white;
            background-color: #1e2225;
        }

        .table .tfoot-dark th {
            background-color: #1e2225;
        }

        @media screen and (max-width: 768px) {
            .button {
                display: flex;
                flex-wrap: wrap;
            }
        }

        @media screen and (min-width: 768px) {
            .button {
                display: flex;
            }
        }

        @media screen and (max-width: 768px) {
            .btn-tess {
                padding: 0.10rem 0.25rem;
                flex: 0 0 33.3333333333%;
                max-width: 33.3333333333%;
            }
        }
    </style>
</head>

<body class="theme-default" style="overflow: auto;">
    <div class="grid-wrapper sidebar-bg bg1" id="menuss">

        <!-- BOF HEADER -->
        <div class="header">
            <div class="header-bar">
                <div class="brand">
                    <img src="/Logo-puskesmas-lansot.png" alt="logo-puskesmas-lansot" class="logo" width="170" height="70">
                </div>
                <div class="btn-toggle">
                    <a href="#" class="slide-sidebar-btn" style="display: none;"><i class="ti-menu"></i></a>
                </div>
            </div>
        </div>
        <!-- EOF HEADER -->
        <!-- BOF ASIDE-LEFT -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-content">
                <!-- sidebar-menu  -->
                <div class="sidebar-menu">
                    <ul>
                        <li class="active" style="display: inline-block">
                            <a href="#" class="toggle-sidebar-btn"><i class="ti-arrow-circle-left" onclick="RemoveClass()"></i></a>
                        </li>
                        <li class="header-menu" style="vertical-align: top;padding-top:12px;">
                            <span>Categories</span>
                        </li>
                        <li class="active">
                            <a href="/">
                                <i class="ti-write"></i>
                                <span class="menu-text">Stok Obat</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="/gudang/lplpo">
                                <i class="ti-agenda"></i>
                                <span class="menu-text">LPLPO</span>
                            </a>
                        </li>
                        <li class="active">
                            <a href="/gudang/Perbandingan">
                                <i class="ti-bar-chart"></i>
                                <span class="menu-text">Perbandingan Hasil Prediksi</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- sidebar-menu  -->
            </div>
        </div>
        <!-- EOF ASIDE-LEFT -->

        <?= $this->renderSection('isi'); ?>

        <!-- BOF FOOTER -->
        <div class="footer">
            <p class="text-center mt-3" style="color: white;">Copyright © 2019-2020 <?= date('Y'); ?> siQtheme by 3M Square. All rights reserved.</p>
            <!-- <p class="text-center mt-3" style="color: white;">Environment: <?= ENVIRONMENT ?></p> -->
        </div>
        <!-- EOF FOOTER -->

        <div id="overlay"></div>

    </div> <!-- END WRAPPER -->

    <!-- <script src="/assets/scripts/siqtheme.js"></script> -->

    <!-- <script src="/assets/scripts/pages/tb_datatables.js"></script> -->

    <!-- <script src="/assets/scripts/app.js"></script> -->

    <script src="/assets/scripts/sidebar.js"></script>
</body>

</html>