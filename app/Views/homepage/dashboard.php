<?= $this->extend('layout/home_page') ?>
<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<style>
.hover-up {
    transition: all 0.3s ease;
}
.hover-up:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.hero-gradient {
    background: linear-gradient(135deg, rgba(63,81,181,0.95) 0%, rgba(63,81,181,0.7) 100%), url('<?= base_url('assets/image/login_hero.jpg') ?>');
    background-size: cover;
    background-position: center;
}
.swiper-slide {
    height: auto;
    padding-bottom: 20px;
}
.swiper-button-next,
.swiper-button-prev {
    color: #3f51b5;
    background: #fff;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.swiper-button-next::after,
.swiper-button-prev::after {
    font-size: 14px;
    font-weight: bold;
}

/* Reduce distance to footer and header */
.main-content {
    padding-top: 60px !important;
    padding-bottom: 0 !important;
}
.section {
    margin-bottom: 0 !important;
}
</style>

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <!-- Hero -->
            <div class="hero text-white mb-2 hero-gradient shadow-lg" 
                style="border-radius: 20px; padding: 60px 50px; position: relative; overflow: hidden;">
                <div class="hero-inner" style="position: relative; z-index: 1;">
                    <h1 class="mb-3 display-4 font-weight-bold" style="line-height: 1.2;">PELUIT</h1>
                    <h2 class="mb-4 font-weight-light" style="opacity: 0.9;">Pelayanan Publik Satu Pintu</h2>
                    <p class="lead mb-4" style="font-weight: 400; opacity: 0.9; max-width: 600px;">
                        Sistem Informasi Pelayanan Publik Dinas Komunikasi dan Informatika Kabupaten Bangkalan. 
                        Akses layanan pemerintah daerah dengan mudah, cepat, dan transparan.
                    </p>
                    <div class="d-flex flex-wrap">
                        <a href="<?= base_url('sslogin') ?>" class="btn btn-light btn-lg font-weight-bold mr-3 mb-2 shadow hover-up" style="border-radius: 50px; padding: 12px 35px; color: #3f51b5;">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk Sekarang
                        </a> 
                    </div>
                </div>
                <!-- Decorative Circle -->
                <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                <div style="position: absolute; bottom: -30px; left: -30px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
            </div>

            <!-- Stats Grid -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                    <div class="card card-statistic-1 shadow-sm h-100 hover-up border-0" style="border-radius: 15px;">
                        <div class="card-icon bg-primary-light text-primary" style="background-color: #e8eaf6; border-radius: 12px;">
                            <i class="fas fa-ticket-alt" style="font-size: 1.6rem; color: #3f51b5;"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4 class="text-muted" id="orders-tittle">Jumlah Tiket </h4>
                            </div>
                            <div class="card-body font-weight-bold text-dark" id="orders-semua" style="font-size: 1.5rem;">
                                0
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                    <div class="card card-statistic-1 shadow-sm h-100 hover-up border-0" style="border-radius: 15px;">
                        <div class="card-icon bg-success-light text-success" style="background-color: #e8f5e9; border-radius: 12px;">
                            <i class="fas fa-check-circle" style="font-size: 1.6rem; color: #2e7d32;"></i>

                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4 class="text-muted">Tiket Selesai</h4>
                            </div>
                            <div class="card-body font-weight-bold text-dark" id="orders-selesai" style="font-size: 1.5rem;">
                                0
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                    <div class="card card-statistic-1 shadow-sm h-100 hover-up border-0" style="border-radius: 15px;">
                        <div class="card-icon bg-warning-light text-warning" style="background-color: #fff3e0; border-radius: 12px;">
                            <i class="fas fa-building" style="font-size: 1.6rem; color: #ff9800;"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4 class="text-muted">Total OPD</h4>
                            </div>
                            <div class="card-body font-weight-bold text-dark" id="orders-jenis-siswa" style="font-size: 1.5rem;">
                                0
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                    <div class="card card-statistic-1 shadow-sm h-100 hover-up border-0" style="border-radius: 15px;">
                        <div class="card-icon bg-danger-light text-danger" style="background-color: #ffebee; border-radius: 12px;">
                            <i class="fas fa-users" style="font-size: 1.6rem; color: #ff1744;"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4 class="text-muted">Total Pengguna</h4>
                            </div>
                            <div class="card-body font-weight-bold text-dark" id="orders-jenis-mahasiswa" style="font-size: 1.5rem;">
                                0
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid: Services & Calendar -->
            <div class="row">
                <!-- Services Column -->
                <div class="col-lg-12 col-md-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="font-weight-bold text-dark" style="font-size: 1.4rem; border-left: 5px solid #3f51b5; padding-left: 15px;">Kategori Pelayanan</h4>
                         <a href="javascript:void(0)" onclick="toggleServices()" class="btn btn-outline-primary btn-sm px-3 rounded-pill" id="btn-lihat-semua">Lihat Semua <i class="fas fa-chevron-down ml-1"></i></a>
                    </div>
                    
                    <!-- Swiper Container -->
                    <div class="swiper mySwiper" id="services-swiper">
                        <div class="swiper-wrapper">
                            <?php foreach($list_pelayanan as $layanan): ?>
                                <?php $urlPengajuan = base_url('pengajuan'); ?>
                                <?php if($layanan['active'] == 1): ?>
                                <div class="swiper-slide">
                                    <a href="<?= $urlPengajuan . '?pelayanan=' . $layanan['id_pelayanan'] ?>" class="card shadow-sm h-100 hover-up border-0 mx-2 my-2 text-decoration-none" style="border-radius: 15px; cursor: pointer;">
                                        <div class="card-body p-4 d-flex flex-column text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-3">
                                                <div class="bg-light d-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px; border-radius: 50%;">
                                                    <img src="<?= base_url('assets/image/logo_app/' . $layanan['file_foto']) ?>" alt="Icon" style="width: 40px; height: 40px; object-fit: contain;">
                                                </div>
                                            </div>
                                            <h5 class="card-title text-dark font-weight-bold mb-2" style="font-size: 1rem;"><?= $layanan['nama_pelayanan'] ?></h5>
                                            <p class="card-text text-muted small flex-grow-1 mb-0" style="line-height: 1.5; font-size: 0.85rem;">
                                                <?= substr(strip_tags($layanan['deskripsi']), 0, 70) ?>...
                                            </p>
                                        </div>
                                    </a>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <!-- ✅ Panah TARUH DI SINI -->
                        
                    </div> 

                    <!-- Full Grid View (Hidden by default) -->
                    <div class="row d-none" id="services-grid">
                        <?php foreach($list_pelayanan as $layanan): ?>
                            <?php if($layanan['active'] == 1): ?>
                            <div class="col-lg-6 col-md-6 col-12 mb-4">
                                <a href="<?= $urlPengajuan . '?pelayanan=' . $layanan['id_pelayanan'] ?>" class="card shadow-sm h-100 hover-up border-0 text-decoration-none" style="border-radius: 15px; cursor: pointer;">
                                       <div class="card-body p-4 d-flex flex-column">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="bg-light mr-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px; border-radius: 15px;">
                                                <img src="<?= base_url('assets/image/logo_app/' . $layanan['file_foto']) ?>" alt="Icon" style="width: 35px; height: 35px; object-fit: contain;">
                                            </div>
                                            <h5 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1.1rem;"><?= $layanan['nama_pelayanan'] ?></h5>
                                        </div>
                                        <p class="card-text text-muted mb-0 flex-grow-1" style="line-height: 1.6; font-size: 0.95rem;">
                                            <?= substr(strip_tags($layanan['deskripsi']), 0, 100) ?>...
                                        </p>
                                    </div>
                                </a>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Calendar Column -->
                <div class="col-lg-12 col-md-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="font-weight-bold text-dark" style="font-size: 1.4rem; border-left: 5px solid #ff9800; padding-left: 15px;">Kalender</h4>
                    </div>
                    
                    <div class="card shadow hover-up border-0" style="border-radius: 15px;">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                             <input type="month" onchange="change_calendar()" class="form-control rounded-pill bg-light border-0 px-3" id="tgl_now">
                        </div>
                        <div class="card-body p-3">
                            <div id="calendar" style="font-size: 0.85rem;"></div>
                        </div>
                        <div class="card-footer bg-white border-0 pt-0 pb-4 px-4">
                            <h6 class="text-muted font-weight-bold mb-3 small text-uppercase spacing-1">Agenda Hari Ini</h6>
                            <div id="agenda-list">
                                <!-- Dynamic Content -->
                                <div class="text-center py-3 text-muted small">Memuat agenda...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Tiket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row align-items-center">
                    <label class="col-md-12 text-md-left text-left">Nama Pelayanan</label>
                    <div class="col-lg-12 col-md-12">
                        <input type="text" id="modal_acara" class="form-control" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <label>Tanggal Pengajuan</label>
                    <input type="datetime-local" class="form-control" id="modal_tgl_akhir" disabled>
                </div>
                <div class="form-group">
                    <label>Instansi Pemohon</label>
                    <input type="text" id="modal_aula" class="form-control" disabled>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
//     var swiper = new Swiper(".mySwiper", {
//     slidesPerView: 3,
//     spaceBetween: 20,
//     loop: false,
//     navigation: {
//         nextEl: ".swiper-button-next",
//         prevEl: ".swiper-button-prev",
//     },
//     breakpoints: {
//         0: {
//             slidesPerView: 1,
//         },
//         768: {
//             slidesPerView: 2,
//         },
//         992: {
//             slidesPerView: 3,
//         }
//     }
// });
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 3,
        spaceBetween: 20,
        loop: true,                 // biar muter terus
        autoplay: {
            delay: 2500,              // kecepatan geser (ms)
            disableOnInteraction: false
        },
        navigation: false,          // matikan next/prev
        pagination: false,          // kalau kamu nggak mau titik pagination
        breakpoints: {
            0: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            992: { slidesPerView: 3 }
        }
    });
    swiper.on('touchStart', () => { swiper.allowClick = true; });

function toggleServices() {
    var swiperDiv = document.getElementById("services-swiper");
    var gridDiv = document.getElementById("services-grid");
    var btn = document.getElementById("btn-lihat-semua");

    if (swiperDiv.classList.contains("d-none")) {
        swiperDiv.classList.remove("d-none");
        gridDiv.classList.add("d-none");
        btn.innerHTML = 'Lihat Semua <i class="fas fa-chevron-down ml-1"></i>';
    } else {
        swiperDiv.classList.add("d-none");
        gridDiv.classList.remove("d-none");
        btn.innerHTML = 'Sembunyikan <i class="fas fa-chevron-up ml-1"></i>';
    }
}

function change_tiket_count() {
    $.ajax({
        url: "<?= base_url('statistik/tiket-pelayanan') ?>",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            document.getElementById("orders-semua").innerHTML = data["count_semua"];
            document.getElementById("orders-selesai").innerHTML = data["count_selesai"];
        },
    });
}

function jenis_tiket_count() {
    $.ajax({
        url: "<?= base_url('statistik/tiket-data-master') ?>",
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            document.getElementById("orders-jenis-siswa").innerHTML = data["count_siswa"];
            document.getElementById("orders-jenis-mahasiswa").innerHTML = data["count_mahasiswa"];
            document.getElementById("orders-tittle").innerHTML = "Jumlah Pelayanan Tahun " + data[
                "tahun"];
        },
    });
}

// KALENDER
document.addEventListener('DOMContentLoaded', function() {
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();
    document.getElementById("tgl_now").value = yyyy + "-" + mm;
    change_calendar();
    change_tiket_count();
    jenis_tiket_count();
});

function change_calendar() {
    var formData = new FormData();
    formData.append('tgl', $("#tgl_now").val());

    $.ajax({
        url: "<?= base_url('statistik/tiket-data-calendar') ?>",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        dataType: "JSON",
        success: function(data) {
            // Render Calendar
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: false,
                initialDate: document.getElementById("tgl_now").value,
                events: data,
                eventClick: function(info) {
                    // pakai start kalau end kosong (biar tidak 1970)
                    const start = info.event.start;
                    const end = info.event.end ? info.event.end : start;

                    // format yang valid untuk input type="datetime-local"
                    const fmtDateTimeLocal = (d) => moment(d).format('YYYY-MM-DDTHH:mm');

                    // ✅ Mapping yang lebih masuk akal sesuai label modal kamu:
                    // Nama Pelayanan (modal_acara) -> pakai title (atau description kalau kamu mau)
                    document.getElementById("modal_acara").value = info.event.title || '';

                    // Tanggal Pengajuan (modal_tgl_akhir) -> pakai start (atau extendedProps.tgl_pengajuan kalau ada)
                    const tglPengajuan = info.event.extendedProps?.tgl_pengajuan
                        ? moment(info.event.extendedProps.tgl_pengajuan).toDate()
                        : start;

                    document.getElementById("modal_tgl_akhir").value = fmtDateTimeLocal(tglPengajuan);

                    // Instansi Pemohon (modal_aula) -> ambil dari extendedProps kalau backend mengirim
                    document.getElementById("modal_aula").value =
                        info.event.extendedProps?.instansi_pemohon
                        || info.event.extendedProps?.aula
                        || info.event.extendedProps?.description
                        || '';

                    $('#calendarModal').modal('show');
                },
                // eventDidMount: function(info) {
                //     document.getElementById("modal_acara").value = info.event.extendedProps["description"];
                // },
            });
            calendar.render();

            // Render "Agenda Hari Ini" from the Data
            renderAgenda(data);
        },
    });
}


function renderAgenda(events) {
    const listContainer = document.getElementById('agenda-list');
    listContainer.innerHTML = ''; // Clear loading/static text

    // Filter events for TODAY
    const today = new Date();
    const todayStr = today.toISOString().split('T')[0]; // YYYY-MM-DD

    // Or do filtering based on the 'start' property of events which is "YYYY-MM-DD HH:mm:ss"
    const todaysEvents = events.filter(event => {
        return event.start.startsWith(todayStr); // Simple string check
    });

    if (todaysEvents.length === 0) {
        listContainer.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="far fa-calendar-times mb-2" style="font-size: 1.5rem; opacity: 0.5;"></i>
                <p class="small mb-0">Tidak ada agenda hari ini.</p>
            </div>
        `;
        return;
    }

    // Helper for Month Name
    const monthNames = ["JAN", "FEB", "MAR", "APR", "MEI", "JUN", "JUL", "AGT", "SEP", "OKT", "NOV", "DES"];

    todaysEvents.forEach(event => {
        // Parse date for display
        const dateObj = new Date(event.start);
        const day = String(dateObj.getDate()).padStart(2, '0');
        const month = monthNames[dateObj.getMonth()];
        const time = dateObj.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        const html = `
            <div class="d-flex align-items-center mb-3 p-3 rounded hover-up-sm transition-all" style="background-color: #f8f9fa; border-left: 4px solid ${event.color || '#3f51b5'};">
                 <div class="mr-3 text-center" style="min-width: 40px;">
                    <span class="d-block font-weight-bold text-dark" style="font-size: 1.1rem; line-height: 1;">${day}</span>
                    <span class="d-block x-small font-weight-bold text-muted" style="font-size: 0.7rem;">${month}</span>
                </div>
                <div style="border-left: 1px solid #e0e0e0; height: 35px; margin-right: 15px;"></div>
                <div style="overflow: hidden;">
                    <h6 class="mb-1 text-dark font-weight-bold text-truncate" style="font-size: 0.9rem;" title="${event.title}">${event.description}</h6>
                    <div class="d-flex align-items-center text-muted small">
                        <i class="far fa-clock mr-1" style="font-size: 0.8rem;"></i> ${time} WIB
                         <span class="mx-2">•</span>
                        <span class="text-truncate" style="max-width: 100px;">${event.title}</span>
                    </div>
                </div>
            </div>
        `;
        listContainer.innerHTML += html;
    });
}
</script>
<?= $this->endSection() ?>