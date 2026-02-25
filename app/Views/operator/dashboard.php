<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<style>
    /* ============================================
       OPERATOR DASHBOARD - MODERN INDIGO THEME
       ============================================ */

    /* Page Header */
    .dash-header {
        background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);
        margin: -28px -28px 28px -28px;
        padding: 24px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .dash-header-title {
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }

    /* === STAT CARDS === */
    .stat-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 14px rgba(0,0,0,0.06);
        padding: 24px;
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(0,0,0,0.09);
    }

    .stat-card-label {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.9px;
        margin-bottom: 10px;
        display: block;
    }

    .stat-card-number {
        font-size: 42px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 20px;
    }

    .stat-card-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .icon-indigo { background: #ede9fe; color: #6366f1; }
    .icon-blue   { background: #dbeafe; color: #3b82f6; }
    .icon-green  { background: #d1fae5; color: #10b981; }

    .stat-divider {
        height: 1px;
        background: #f1f5f9;
        margin-bottom: 16px;
    }

    .stat-breakdown {
        display: flex;
        justify-content: space-between;
    }

    .breakdown-item {
        text-align: center;
        flex: 1;
    }

    .breakdown-value {
        font-size: 16px;
        font-weight: 700;
        color: #334155;
        display: block;
        margin-bottom: 2px;
    }

    .breakdown-label {
        font-size: 10px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }

    .breakdown-sep {
        width: 1px;
        background: #f1f5f9;
        align-self: stretch;
    }

    /* === PROFILE CARD === */
    .profile-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 14px rgba(0,0,0,0.06);
        padding: 28px 20px 24px;
        text-align: center;
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .profile-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(0,0,0,0.09);
    }

    .profile-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ede9fe;
        margin-bottom: 14px;
    }

    .profile-name {
        font-size: 14.5px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 5px;
    }

    .profile-role-badge {
        display: inline-block;
        background: #ede9fe;
        color: #6366f1;
        font-size: 11px;
        font-weight: 700;
        border-radius: 20px;
        padding: 3px 12px;
        margin-bottom: 12px;
    }

    .profile-verified {
        font-size: 12px;
        color: #10b981;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    /* === TICKET FILTER BADGE === */
    .ticket-filter-badge {
        background: #d1fae5;
        color: #065f46;
        font-size: 10px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        border: none;
        cursor: pointer;
        letter-spacing: 0.5px;
    }

    /* === CHART CARD === */
    .chart-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 14px rgba(0,0,0,0.06);
        padding: 24px;
        margin-bottom: 24px;
    }

    .chart-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .chart-card-subtitle {
        font-size: 12px;
        color: #94a3b8;
        margin-bottom: 20px;
    }

    .chart-link {
        font-size: 12.5px;
        font-weight: 600;
        color: #6366f1;
        text-decoration: none;
    }

    .chart-link:hover { color: #4f46e5; text-decoration: none; }
</style>

<div class="main-content">

    <!-- Indigo Page Header -->
    <div class="dash-header">
        <h1 class="dash-header-title">Selamat Datang, <?= session()->get('nama') ?? 'Operator' ?> 👋</h1>
    </div>

    <!-- Row 1: Stats Cards -->
    <div class="row align-items-stretch">

        <!-- Card: Jumlah Pengguna -->
        <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="stat-card">
                <span class="stat-card-label">Jumlah Pengguna</span>
                <div class="stat-card-icon icon-indigo"><i class="fas fa-users"></i></div>
                <div class="stat-card-number" id="jumlah_total">0</div>
                <div class="stat-divider"></div>
                <div class="stat-breakdown">
                    <div class="breakdown-item">
                        <span class="breakdown-value" id="jumlah_pembimbing">0</span>
                        <span class="breakdown-label">Pembimbing</span>
                    </div>
                    <div class="breakdown-sep"></div>
                    <div class="breakdown-item">
                        <span class="breakdown-value" id="jumlah_operator">0</span>
                        <span class="breakdown-label">Operator</span>
                    </div>
                    <div class="breakdown-sep"></div>
                    <div class="breakdown-item">
                        <span class="breakdown-value" id="jumlah_magang">0</span>
                        <span class="breakdown-label">Magang</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Profile -->
        <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="profile-card">
                <img src="<?= base_url() ?>/public/assets/image/avatar/<?= $url ?>"
                    alt="avatar" class="profile-avatar">
                <div class="profile-name"><?= $nama ?></div>
                <div class="profile-role-badge"><?= $role ?></div>
                <div class="profile-verified">
                    <i class="fas fa-check-circle"></i> Identitas Terverifikasi
                </div>
            </div>
        </div>

        <!-- Card: Jumlah Tiket -->
        <div class="col-lg-4 col-md-6 col-12 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center mb-2">
                    <span class="stat-card-label mb-0 mr-2">Jumlah Tiket</span>
                    <div class="dropdown">
                        <a class="ticket-filter-badge dropdown-toggle" data-toggle="dropdown" href="#" id="orders-month">
                            HARI INI
                        </a>
                        <div class="dropdown-menu dropdown-menu-sm" style="border-radius: 10px; box-shadow: 0 8px 30px rgba(0,0,0,0.12); border: none;">
                            <div class="dropdown-header small font-weight-bold text-uppercase" style="font-size: 10px; color: #94a3b8; letter-spacing: 0.8px;">Pilih Filter</div>
                            <a href="javascript:void(0)" id="orders-month-0" onclick="change_tiket(0)" class="dropdown-item active" style="font-size: 13px;">Hari Ini</a>
                            <a href="javascript:void(0)" id="orders-month-1" onclick="change_tiket(1)" class="dropdown-item" style="font-size: 13px;">Bulan Ini</a>
                            <a href="javascript:void(0)" id="orders-month-2" onclick="change_tiket(2)" class="dropdown-item" style="font-size: 13px;">Tahun Ini</a>
                        </div>
                    </div>
                </div>
                <div class="stat-card-icon icon-blue"><i class="fas fa-ticket-alt"></i></div>
                <div class="stat-card-number" id="orders-semua">0</div>
                <div class="stat-divider"></div>
                <div class="stat-breakdown">
                    <div class="breakdown-item">
                        <span class="breakdown-value" id="orders-proses">0</span>
                        <span class="breakdown-label">Proses</span>
                    </div>
                    <div class="breakdown-sep"></div>
                    <div class="breakdown-item">
                        <span class="breakdown-value" id="orders-selesai" style="color:#10b981;">0</span>
                        <span class="breakdown-label">Selesai</span>
                    </div>
                    <div class="breakdown-sep"></div>
                    <div class="breakdown-item">
                        <span class="breakdown-value" id="orders-tolak" style="color:#ef4444;">0</span>
                        <span class="breakdown-label">Ditolak</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Charts -->
    <div class="row">

        <!-- Area Chart: Harian -->
        <div class="col-lg-8 col-12">
            <div class="chart-card">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <div class="chart-card-title">Grafik Pelayanan Harian</div>
                        <p class="chart-card-subtitle">Jumlah tiket masuk per hari bulan ini</p>
                    </div>
                </div>
                <div id="chart"></div>
            </div>
        </div>

        <!-- Stacked Bar: Tahunan -->
        <div class="col-lg-4 col-12">
            <div class="chart-card">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <div class="chart-card-title">Status Per Tahun</div>
                        <p class="chart-card-subtitle">Distribusi status tiket per tahun</p>
                    </div>
                    <a href="<?= base_url() ?>/<?= session()->get('role') ?>/tiket" class="chart-link">
                        Detail →
                    </a>
                </div>
                <div id="chart_stack"></div>
            </div>
        </div>
    </div>

</div>

<script>
$(document).ready(function() {
    // Set active sidebar
    $('#dashboard a').closest('li').addClass('active');

    change_tiket_count(0);
    tiket_count_user();
    tiket_count_harian();
    tiket_count_tahunan();
});

/* ---- TICKET FILTER ---- */
function change_tiket(id) {
    ['orders-month-0','orders-month-1','orders-month-2'].forEach(function(i){ $('#'+i).removeClass('active'); });
    var labels = ['HARI INI','BULAN INI','TAHUN INI'];
    document.getElementById('orders-month').innerHTML = labels[id];
    document.getElementById('orders-month-'+id).classList.add('active');
    change_tiket_count(id);
}

/* ---- AREA CHART: Harian ---- */
function chart(jumlah, tgl) {
    var options = {
        chart: { height: 260, type: 'area', toolbar: { show: false }, zoom: { enabled: false }, fontFamily: 'Inter, sans-serif' },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2.5 },
        colors: ['#6366f1'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.25, opacityTo: 0.02, stops: [0, 100] }
        },
        markers: { size: 3, colors: ['#fff'], strokeColors: '#6366f1', strokeWidth: 2, hover: { size: 6 } },
        series: [{ name: 'Jumlah Tiket', data: jumlah }],
        xaxis: {
            categories: tgl,
            labels: { style: { colors: '#94a3b8', fontSize: '11px', fontFamily: 'Inter' } },
            axisBorder: { show: false }, axisTicks: { show: false }
        },
        yaxis: { labels: { style: { colors: '#94a3b8', fontSize: '11px', fontFamily: 'Inter' } }, min: 0 },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4, yaxis: { lines: { show: true } }, xaxis: { lines: { show: false } } },
        tooltip: { theme: 'light' },
        noData: { text: 'Belum ada data pelayanan hari ini', style: { color: '#94a3b8', fontSize: '13px', fontFamily: 'Inter' } }
    };
    if (window.chartInst) window.chartInst.destroy();
    window.chartInst = new ApexCharts(document.querySelector("#chart"), options);
    window.chartInst.render();
}

/* ---- STACKED BAR: Tahunan ---- */
function get_stack_bar(tahun, proses, selesai, tolak) {
    var options = {
        series: [
            { name: 'Proses',  data: proses,  color: '#6366f1' },
            { name: 'Selesai', data: selesai, color: '#10b981' },
            { name: 'Ditolak', data: tolak,   color: '#ef4444' },
        ],
        chart: { type: 'bar', height: 260, stacked: true, toolbar: { show: false }, zoom: { enabled: false }, fontFamily: 'Inter, sans-serif' },
        plotOptions: { bar: { horizontal: false, columnWidth: '50%', borderRadius: 4, borderRadiusApplication: 'end', dataLabels: { total: { enabled: false } } } },
        dataLabels: { enabled: false },
        xaxis: {
            categories: tahun,
            labels: { style: { colors: '#94a3b8', fontSize: '11px', fontFamily: 'Inter' } },
            axisBorder: { show: false }, axisTicks: { show: false }
        },
        yaxis: { labels: { style: { colors: '#94a3b8', fontSize: '11px', fontFamily: 'Inter' } } },
        legend: { position: 'bottom', horizontalAlign: 'center', fontSize: '12px', fontFamily: 'Inter', markers: { radius: 6, width: 10, height: 10 }, itemMargin: { horizontal: 8, vertical: 6 } },
        fill: { opacity: 1 },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4, yaxis: { lines: { show: true } } }
    };
    if (window.chartStackInst) window.chartStackInst.destroy();
    window.chartStackInst = new ApexCharts(document.querySelector("#chart_stack"), options);
    window.chartStackInst.render();
}

/* ---- AJAX CALLS ---- */
function change_tiket_count(id) {
    var fd = new FormData();
    fd.append('id', id);
    $.ajax({
        url: "<?= base_url() ?>/operator/dashboard/get_orders",
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        cache: false,
        dataType: "JSON",
        success: function(data) {
            $('#orders-proses').text(data.count_proses);
            $('#orders-selesai').text(data.count_selesai);
            $('#orders-tolak').text(data.count_tolak);
            $('#orders-semua').text(data.count_semua);
        }
    });
}

function tiket_count_user() {
    $.ajax({
        url: "<?= base_url() ?>/operator/dashboard/get_user",
        type: "GET", dataType: "JSON",
        success: function(data) {
            $('#jumlah_pembimbing').text(data.count_pembimbing);
            $('#jumlah_operator').text(data.count_operator);
            $('#jumlah_magang').text(data.count_magang);
            $('#jumlah_total').text(data.count_all);
        }
    });
}

function tiket_count_harian() {
    $.ajax({
        url: "<?= base_url() ?>/operator/dashboard/get_orders_harian",
        type: "GET", dataType: "JSON",
        success: function(data) {
            var today = new Date();
            var daysInMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0).getDate();
            var tgl = [], jumlah = [];

            for (var i = 1; i <= daysInMonth; i++) {
                tgl.push(i.toString());
                var temp = 0;
                for (var j = 0; j < data.length; j++) {
                    if (new Date(data[j].tgl_input).getDate() == i) temp++;
                }
                jumlah.push(temp);
            }
            chart(jumlah, tgl);
        }
    });
}

function tiket_count_tahunan() {
    var fd = new FormData(); fd.append('tahun', new Date().getFullYear());
    $.ajax({
        url: "<?= base_url() ?>/operator/dashboard/get_orders_tahunan",
        type: "POST", data: fd, processData: false, contentType: false,
        cache: false, dataType: "JSON",
        success: function(data) {
            get_stack_bar(data.tahun, data.proses, data.selesai, data.tolak);
        }
    });
}
</script>

<?= $this->endSection() ?>
