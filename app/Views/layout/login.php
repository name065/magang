<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Sistem Informasi Pelayanan Publik Satu Pintu Dinas Kominfo Kabupaten Bangkalan">
    <meta name="author" content="Dinas Kominfo Kabupaten Bangkalan">

    <title id="title_page">Peluit</title>

    <link href="<?= base_url('assets/image/favicon/logo_bangkalan.png') ?>" rel="icon">
    <link href="<?= base_url('assets/image/favicon/logo_bangkalan.png') ?>" rel="apple-touch-icon">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="<?= base_url('stisla/dist/assets/modules/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('stisla/dist/assets/modules/fontawesome/css/all.min.css') ?>">

    <!-- CSS Libraries -->
    <link rel="stylesheet"
        href="<?= base_url('stisla/dist/assets/modules/bootstrap-social/bootstrap-social.css') ?>">

    <!-- Template CSS -->
    <link rel="stylesheet" href="<?= base_url('stisla/dist/assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('stisla/dist/assets/css/components.css') ?>">

    <!-- CSS ASSETS -->
    <link href="<?= base_url('assets/login_background.css') ?>" rel="stylesheet">

    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.js"
        integrity="sha512-6DC1eE3AWg1bgitkoaRM1lhY98PxbMIbhgYCGV107aZlyzzvaWCW1nJW2vDuYQm06hXrW0As6OGKcIaAVWnHJw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Select2.Js -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Sweeet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ReCaptcha -->
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>

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

<body style="background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Poppins', sans-serif;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="login-card shadow-lg bg-white overflow-hidden" style="border-radius: 30px; display: flex; flex-wrap: wrap; min-height: 600px;">
                    <!-- Left Column: Blue Banner -->
                    <div class="login-banner p-5 d-none d-lg-flex flex-column justify-content-between text-white" 
                        style="flex: 1; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); position: relative;">
                        
                        <div style="position: relative; z-index: 2;">
                            <div class="d-flex align-items-center mb-5">
                               
                                <h3 class="mb-0 font-weight-bold" style="letter-spacing: 2px; font-size: 1.2rem;">PELUIT</h3>
                            </div>

                            <h1 class="display-4 font-weight-bold mb-4" style="line-height: 1.2;">Selamat Datang Kembali.</h1>
                            <p class="lead mb-0" style="opacity: 0.9; max-width: 400px; font-weight: 300;">
                                Portal terpadu untuk kemudahan akses layanan publik digital di lingkungan Pemerintah Kabupaten Bangkalan.
                            </p>
                        </div>

                        <div style="position: relative; z-index: 2;">
                            <div class="d-flex align-items-center mb-4 bg-white-transparent p-3 rounded" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(5px);">
                                <div class="bg-white rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-shield-alt text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold small">Akses Aman & Terenkripsi</h6>
                                    <p class="mb-0 x-small" style="font-size: 0.7rem; opacity: 0.8;">Data Anda dilindungi dengan standar keamanan tingkat tinggi.</p>
                                </div>
                            </div>
                            <p class="mb-0 small" style="opacity: 0.7;">&copy; 2026 Dinas Komunikasi dan Informatika Kabupaten Bangkalan</p>
                        </div>

                        <!-- Decorative element -->
                        <div style="position: absolute; bottom: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
                    </div>

                    <!-- Right Column: Form Area -->
                    <div class="login-form-area p-5 d-flex flex-column justify-content-center bg-white" style="flex: 1.2;">
                        <div class="row w-100 m-0">
                            <div class="col-12 col-md-10 offset-md-1">
                                <?= $this->renderSection('content') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- Modal -->
        <div class="modal fade bd-example-modal-lg" id="exampleModalLong" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Infografis Id Chat</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img class="img-fluid" src="<?= base_url('assets/image/id_chat.png') ?>" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="<?= base_url('stisla/dist/assets/modules/jquery.min.js') ?>"></script>
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