<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <style>html { scroll-behavior: smooth; } body { font-family: 'Nunito', sans-serif !important; }</style>

    <title id="title_page">Peluit</title>

    <!-- Favicons -->
    <link href="<?= base_url('assets/image/favicon/logo_bangkalan.png') ?>" rel="icon">
    <link href="<?= base_url('assets/image/favicon/logo_bangkalan.png') ?>" rel="apple-touch-icon">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="<?= base_url('stisla/dist/assets/modules/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('stisla/dist/assets/modules/fontawesome/css/all.min.css') ?>">

    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.js"
        integrity="sha512-6DC1eE3AWg1bgitkoaRM1lhY98PxbMIbhgYCGV107aZlyzzvaWCW1nJW2vDuYQm06hXrW0As6OGKcIaAVWnHJw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Calendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

    <!-- Apexchart -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Select2 -->
    <link rel="stylesheet" href="<?= base_url('stisla/dist/assets/modules/select2/dist/css/select2.min.css') ?>">
    <script src="<?= base_url('stisla/dist/assets/modules/select2/dist/js/select2.full.min.js') ?>"></script>
    <script src="<?= base_url('stisla/dist/assets/modules/jquery-selectric/jquery.selectric.min.js') ?>"></script>

    <!-- CK EDITOR -->
    <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/super-build/ckeditor.js"></script>

    <!-- CSS Libraries -->

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= base_url('stisla/dist/assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('stisla/dist/assets/css/components.css') ?>">
    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-94034622-3');
    </script>
    <!-- /END GA -->
</head>

<body class="layout-3">
    <div id="app">
        <div class="main-wrapper container">
            <!-- <div class="navbar-bg"></div> -->
            <nav class="navbar navbar-expand-lg main-navbar bg-white shadow-sm" style="position: sticky; top: 0; z-index: 1000;">
                <a href="<?= base_url() ?>" class="navbar-brand sidebar-gone-hide text-primary font-weight-bold" style="font-size: 1.5rem; letter-spacing: 2px;">
                    PELUIT
                </a>
                <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars text-dark"></i></a>
                
                <div class="nav-collapse mr-auto">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a href="<?= base_url() ?>" class="nav-link text-dark" style="opacity: 0.7;"><i class="fas fa-th-large text-primary mr-1"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://www.lapor.go.id/" target="_blank" class="nav-link text-dark" style="opacity: 0.7;"><i class="fas fa-search mr-1;"></i> Span Lapor</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://sistawan.bangkalankab.go.id/" target="_blank" class="nav-link text-dark" style="opacity: 0.7;"><i class="fas fa-camera-retro mr-1"></i> Sistawan</a>
                        </li>
                        <li class="nav-item">
                            <a href="http://wsadmin.bangkalankab.go.id/" target="_blank" class="nav-link text-dark" style="opacity: 0.7;"><i class="fas fa-database mr-1"></i> E-Sambhung</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://bangga.bangkalankab.go.id/" target="_blank" class="nav-link text-dark" style="opacity: 0.7;"><i class="fas fa-flag mr-1"></i> Bangga</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('homepage/faq') ?>" class="nav-link text-dark" style="opacity: 0.7;"><i class="fas fa-question-circle mr-1"></i> FAQ</a>
                        </li>
                    </ul>
                </div>

                <ul class="navbar-nav navbar-right">
                    <li class="dropdown"><a href="<?= base_url() ?>" data-toggle="dropdown"
                            class="nav-link nav-link-lg nav-link-user">
                            <img alt="image" style="width:50px!important"
                                src="<?= base_url('assets/image/logo_opd/logo.jpg') ?>"
                                class="rounded-circle mr-1">
                            <img alt="image" style="width:40px!important"
                                src="<?= base_url('assets/image/logo_opd/logokominfo-1.png') ?>"
                                class="rounded-circle mr-1">
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <?= $this->renderSection('content') ?>

            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; 2023 <div class="bullet"></div> Design By <a
                        href="https://kominfo.bangkalankab.go.id/">Dinas
                        Komunikasi dan Informatika Kabupaten Bangkalan</a>
                </div>
                <div class="footer-right">

                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <!-- <script src="<?= base_url() ?>/public/stisla/dist/assets/modules/jquery.min.js"></script> -->
    <script src="<?= base_url('stisla/dist/assets/modules/popper.js') ?>"></script>
    <script src="<?= base_url('stisla/dist/assets/modules/tooltip.js') ?>"></script>
    <script src="<?= base_url('stisla/dist/assets/modules/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('stisla/dist/assets/modules/nicescroll/jquery.nicescroll.min.js') ?>"></script>
    <script src="<?= base_url('stisla/dist/assets/modules/moment.min.js') ?>"></script>
    <script src="<?= base_url('stisla/dist/assets/js/stisla.js') ?>"></script>

    <!-- JS Libraies -->

    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="<?= base_url('stisla/dist/assets/js/scripts.js') ?>"></script>
    <script src="<?= base_url('stisla/dist/assets/js/custom.js') ?>"></script>
</body>

</html>