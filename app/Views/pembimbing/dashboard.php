<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<style>
    .dash-header {
        background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);
        padding: 24px 30px;
        margin: -30px -30px 28px -30px;
    }
    .dash-header h2 { font-size: 20px; font-weight: 700; color: #fff; margin: 0; }
    .dash-header p { color: rgba(255,255,255,0.75); font-size: 13px; margin: 3px 0 0; }

    .stat-card {
        background: #fff;
        border-radius: 16px;
        padding: 22px;
        box-shadow: 0 2px 14px rgba(0,0,0,0.06);
        height: 100%;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(99,102,241,0.12); }

    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    .icon-indigo { background: #eef2ff; color: #6366f1; }
    .icon-blue   { background: #eff6ff; color: #3b82f6; }

    .stat-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; margin-bottom: 4px; }
    .stat-value { font-size: 28px; font-weight: 800; color: #1e293b; line-height: 1; }
    .stat-sub { font-size: 11px; color: #94a3b8; margin-top: 6px; }
    .stat-row { display: flex; gap: 16px; margin-top: 12px; flex-wrap: wrap; }
    .stat-mini .num { font-size: 16px; font-weight: 700; color: #1e293b; }
    .stat-mini .lbl { font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }

    .profile-card {
        background: #fff; border-radius: 16px; padding: 22px;
        box-shadow: 0 2px 14px rgba(0,0,0,0.06); text-align: center; height: 100%;
    }
    .profile-card img {
        width: 64px; height: 64px; border-radius: 50%; object-fit: cover;
        border: 3px solid #e8e4fd; margin-bottom: 10px;
    }
    .profile-card .p-name { font-size: 15px; font-weight: 700; color: #1e293b; }
    .profile-card .p-role { font-size: 11px; background: #eef2ff; color: #6366f1; border-radius: 20px; padding: 3px 10px; display: inline-block; font-weight: 600; margin-top: 4px; }
    .profile-card .p-badge { font-size: 12px; color: #22c55e; margin-top: 8px; font-weight: 600; }

    .tiket-filter-badge {
        background: #eef2ff; color: #6366f1; border: none;
        font-size: 11px; font-weight: 600; border-radius: 8px; padding: 4px 10px;
        cursor: pointer; transition: all 0.15s;
    }
    .tiket-filter-badge.active, .tiket-filter-badge:hover { background: #6366f1; color: #fff; }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 4px; }
</style>

<div class="main-content">
<section class="section">

    <div class="dash-header">
        <h2>Selamat Datang, <?= session()->get('nama') ?> 👋</h2>
        <p>Dashboard Pembimbing — <?= date('l, d F Y') ?></p>
    </div>

    <div class="row mb-4">

        <!-- Card Sub Bagian -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="stat-label">Instansi</div>
                        <div class="stat-value" style="font-size:18px;" id="jumlah_pembimbing">—</div>
                    </div>
                    <div class="stat-icon icon-indigo"><i class="fas fa-landmark"></i></div>
                </div>
                <div class="stat-sub">
                    Sub Bagian: <strong id="jumlah_total">—</strong>
                </div>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="profile-card">
                <img src="<?= base_url() ?>/public/assets/image/avatar/<?= $url ?>" alt="avatar">
                <div class="p-name"><?= $nama ?></div>
                <div class="p-role"><?= $role ?></div>
                <div class="p-badge"><i class="fas fa-check-circle mr-1"></i>Identitas Terverifikasi</div>
            </div>
        </div>

        <!-- Card Tiket -->
        <div class="col-lg-4 col-md-12 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <div class="stat-label">Jumlah Tiket</div>
                        <div class="stat-value" id="orders-semua">0</div>
                    </div>
                    <div class="stat-icon icon-blue"><i class="fas fa-ticket-alt"></i></div>
                </div>
                <div class="mb-2">
                    <button class="tiket-filter-badge active" id="btn-0" onclick="change_tiket(0)">Hari Ini</button>
                    <button class="tiket-filter-badge" id="btn-1" onclick="change_tiket(1)">Bulan</button>
                    <button class="tiket-filter-badge" id="btn-2" onclick="change_tiket(2)">Tahun</button>
                </div>
                <div class="stat-row">
                    <div class="stat-mini">
                        <div class="num" id="orders-proses" style="color:#6366f1;">0</div>
                        <div class="lbl"><span class="status-dot" style="background:#6366f1;"></span>Proses</div>
                    </div>
                    <div class="stat-mini">
                        <div class="num" id="orders-selesai" style="color:#22c55e;">0</div>
                        <div class="lbl"><span class="status-dot" style="background:#22c55e;"></span>Selesai</div>
                    </div>
                    <div class="stat-mini">
                        <div class="num" id="orders-tolak" style="color:#ef4444;">0</div>
                        <div class="lbl"><span class="status-dot" style="background:#ef4444;"></span>Ditolak</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
</div>

<script>
$(document).ready(function() {
    document.getElementById("<?= $title ?>").classList.add("active");
    change_tiket_count(0);
    tiket_count_user();
});

function change_tiket(id) {
    [0,1,2].forEach(i => document.getElementById('btn-'+i).classList.remove('active'));
    document.getElementById('btn-'+id).classList.add('active');
    change_tiket_count(id);
}

function change_tiket_count(id) {
    var fd = new FormData(); fd.append('id', id);
    $.ajax({
        url: "<?= base_url() ?>/<?= session()->get('role') ?>/dashboard/get_orders",
        type: "POST", data: fd, contentType: false, processData: false, cache: false, dataType: "JSON",
        success: function(d) {
            document.getElementById("orders-proses").innerHTML = d["count_proses"];
            document.getElementById("orders-selesai").innerHTML = d["count_selesai"];
            document.getElementById("orders-tolak").innerHTML = d["count_tolak"];
            document.getElementById("orders-semua").innerHTML = d["count_semua"];
        }
    });
}

function tiket_count_user() {
    $.ajax({
        url: "<?= base_url() ?>/<?= session()->get('role') ?>/dashboard/get_user",
        type: "GET", dataType: "JSON",
        success: function(d) {
            document.getElementById("jumlah_pembimbing").innerHTML = d["instansi"];
            document.getElementById("jumlah_total").innerHTML = d["count_pelayanan"];
        }
    });
}
</script>
<?= $this->endSection() ?>