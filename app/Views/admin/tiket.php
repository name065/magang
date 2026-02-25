<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<style>
    /* Tiket Page Styles */
    .tiket-header {
        background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);
        margin: -30px -30px 28px -30px;
        padding: 22px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .tiket-header h1 {
        font-size: 19px;
        font-weight: 700;
        color: #fff;
        margin: 0;
    }
    .tiket-header .breadcrumb-indigo {
        font-size: 12px;
        color: rgba(255,255,255,0.75);
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 0;
        padding: 0;
        list-style: none;
        background: transparent;
    }
    .tiket-header .breadcrumb-indigo a { color: rgba(255,255,255,0.85); text-decoration: none; }
    .tiket-header .breadcrumb-indigo a:hover { color: #fff; }
    .tiket-header .breadcrumb-indigo .sep { opacity: 0.5; }

    .tiket-body { padding: 28px; background: #f5f6fa; min-height: calc(100vh - 130px); }

    /* Tab pills indigo */
    .nav-pills-indigo .nav-link {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        border-radius: 10px;
        padding: 8px 16px;
        transition: all 0.2s;
    }
    .nav-pills-indigo .nav-link:hover { color: #6366f1; background: #eef2ff; }
    .nav-pills-indigo .nav-link.active {
        background: linear-gradient(135deg, #6366f1, #818cf8);
        color: #fff;
        box-shadow: 0 4px 14px rgba(99,102,241,0.3);
    }
    .nav-pills-indigo .badge {
        font-size: 10px;
        border-radius: 20px;
        padding: 2px 7px;
        margin-left: 4px;
    }
    .nav-pills-indigo .nav-link.active .badge { background: rgba(255,255,255,0.25); }
    .nav-pills-indigo .nav-link:not(.active) .badge { background: #e8ebf4; color: #475569; }

    .tiket-card {
        background: #fff;
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 14px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .tiket-card .card-header-custom {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .tiket-card .card-header-custom h4 {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .btn-indigo {
        background: linear-gradient(135deg, #6366f1, #818cf8);
        border: none;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 18px;
        border-radius: 10px;
        box-shadow: 0 4px 14px rgba(99,102,241,0.3);
        transition: all 0.2s;
    }
    .btn-indigo:hover { color: #fff; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(99,102,241,0.4); }
</style>

<div class="main-content">
<section class="section">

    <!-- Indigo Header -->
    <div class="tiket-header">
        <div>
            <h1><i class="fas fa-ticket-alt mr-2" style="font-size:17px;"></i>Daftar Tiket</h1>
            <ol class="breadcrumb-indigo mt-1">
                <li><a href="<?= base_url() ?>/<?= session()->get('role') ?>/dashboard">Dashboard</a></li>
                <li><span class="sep">›</span></li>
                <li>Tiket</li>
            </ol>
        </div>
    </div>

    <!-- Tab Filter -->
    <div class="mb-3 d-flex align-items-center justify-content-between flex-wrap" style="gap:12px;">
        <ul class="nav nav-pills-indigo" style="gap:4px; flex-wrap:wrap;">
            <li class="nav-item">
                <a class="nav-link active" id="proses_tab" onclick="refresh_table('proses',0)" href="javascript:void(0)">
                    Proses <span class="badge" id="proses_count">0</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="selesai_tab" onclick="refresh_table('selesai',1)" href="javascript:void(0)">
                    Selesai <span class="badge" id="selesai_count">0</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="ditolak_tab" onclick="refresh_table('ditolak',2)" href="javascript:void(0)">
                    Ditolak <span class="badge" id="ditolak_count">0</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="batal_tab" onclick="refresh_table('batal',3)" href="javascript:void(0)">
                    Dibatalkan <span class="badge" id="batal_count">0</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="semua_tab" onclick="refresh_table('semua',4)" href="javascript:void(0)">
                    Semua <span class="badge" id="semua_count">0</span>
                </a>
            </li>
        </ul>

        <!-- Year picker -->
        <div class="form-group mb-0" style="min-width:130px;">
            <input type="text" onchange="get_data()" class="form-control" id="datepicker"
                style="border-radius:10px; border:1.5px solid #e0e4f0; font-size:13px; padding:8px 12px;">
            <small class="text-muted" style="font-size:11px;">Filter Tahun</small>
        </div>
    </div>

    <!-- Table Card -->
    <div class="tiket-card">
        <div class="card-header-custom">
            <h4><i class="fas fa-list-ul mr-2" style="color:#6366f1;"></i>Tabel Tiket</h4>
            <button onclick="open_modal()" class="btn btn-indigo">
                <i class="fas fa-plus mr-1"></i> Buat Tiket
            </button>
        </div>
        <div class="card-body" style="padding:20px;">
            <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Kode Tiket</th>
                        <th>Pembuat</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</section>
</div>




<!-- Modal Ubah Foto -->
<div class="modal fade" role="dialog" id="tiketModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Buat Tiket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Pilih Pelayanan</label>
                    <select style="width:100%;" class="select2 form-control" id="myPelayanan">

                    </select>
                </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button onclick="open_form()" type="button" class="btn btn-primary">Buat</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    document.getElementById("<?= $title ?>").classList.add("active");
    $("#datepicker").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
    });
    document.getElementById("datepicker").value = new Date().getFullYear();

    get_pelayanan();
    // get_data();
});

var status = 0;

function open_modal() {
    $('#tiketModal').modal('show');
}

function open_form() {
    window.open("<?= base_url() ?>/form/" + $("#myPelayanan").val(), "_self");
}

function get_pelayanan() {
    $.ajax({
        url: "<?= base_url() ?>/<?= session()->get('role') ?>/pelayanan/get_pelayanan",
        type: "GET",
        dataType: "JSON",
        async: false,
        success: function(data) {
            var baris = "";
            for ($x = 0; $x < data.length; $x++) {
                if (data[$x].id_pelayanan != 13) {
                    baris += '<option value="' + data[$x].route + '">' + data[$x].nama_pelayanan +
                        '</option>';
                }
            }
            document.getElementById("myPelayanan").innerHTML = baris;
            $.fn.modal.Constructor.prototype.enforceFocus = function() {};
            $('#myPelayanan').select2({
                dropdownParent: $('#tiketModal')
            });

        },
    });
}

function refresh_table($id, $status) {
    ['proses_tab','selesai_tab','ditolak_tab','batal_tab','semua_tab'].forEach(function(id){
        document.getElementById(id).classList.remove('active');
    });
    document.getElementById($id + '_tab').classList.add('active');
    status = $status;
    get_data();
}

function get_data() {
    if ($("#datepicker").val() == "") {
        var tahun = new Date().getFullYear();
    } else {
        var tahun = $("#datepicker").val()
    }

    var formData = new FormData();
    formData.append('tahun', tahun);

    $.ajax({
        url: "<?= base_url() ?>/<?= session()->get('role') ?>/tiket/get_tiket/count",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        enctype: 'multipart/form-data',
        dataType: "JSON",
        success: function(data) {
            // console.log(data);
            document.getElementById("proses_count").innerHTML = data["proses"];
            document.getElementById("selesai_count").innerHTML = data["selesai"];
            document.getElementById("ditolak_count").innerHTML = data["tolak"];
            document.getElementById("batal_count").innerHTML = data["batal"];
            document.getElementById("semua_count").innerHTML = data["semua"];
        },
    });
    var table = $('#example').DataTable({
        destroy: true,
        responsive: true,
        pageLength: 10,
        "lengthChange": false,
        "ordering": false,
        pagingType: 'simple',
        "language": {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json',
        },
        "ajax": {
            "type": "POST",
            "url": "<?= base_url() ?>/<?= session()->get('role') ?>/tiket/get_tiket",
            "dataSrc": "",
            "data": function(data) {
                data.tahun = tahun;
                data.status = status;
            },
        },
        'columnDefs': [{
            "targets": [2], // your case first column
            "className": "text-center",
            "width": "4%"
        }],
        "columns": [{
                "data": "nama_pelayanan",
                "render": function(data, type, row) {
                    var button = row.kode_tiket;
                    button += '<div>';
                    button += '<a class="text-muted"><small>' + data + '</small></a>';

                    if (row.status == 0) {
                        button += '<div class="bullet text-primary"></div>';
                        button += '<a class="text-primary"><small>Proses</small></a>';
                    } else if (row.status == 1) {
                        button += '<div class="bullet text-success"></div>';
                        button += '<a class="text-success"><small>Selesai</small></a>';
                    } else if (row.status == 2) {
                        button += '<div class="bullet text-danger"></div>';
                        button += '<a class="text-danger"><small>Ditolak</small></a>';
                    } else {
                        button += '<div class="bullet text-dark"></div>';
                        button += '<a class="text-dark"><small>Dibatalkan</small></a>';
                    }
                    button += '</div>';

                    return button;
                }
            },
            {
                "data": "id_tiket",
                "render": function(data, type, row) {
                    var bulan_huruf = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
                        'Agustus', 'September', 'Oktober', 'November', 'Desember'
                    ];

                    var date = new Date(row.tgl_input);
                    var tahun = date.getFullYear();
                    var bulan = date.getMonth();
                    var tanggal = date.getDate();
                    var hari = date.getDay();
                    var jam = date.getHours();
                    var menit = date.getMinutes();
                    var detik = date.getSeconds();

                    var tampilTanggal = tanggal + " " + bulan_huruf[bulan] + " " + tahun;
                    var tampilWaktu = jam + ":" + menit + ":" + detik;
                    var ampm = jam >= 12 ? ' PM' : ' AM';
                    var button = row.nama;
                    button += '<div>';
                    button += '<a class="text-info"><small>' + tampilTanggal +
                        '<div class="bullet text-info"></div>' + tampilWaktu + ampm + '</small></a>';
                    button += '<div class="bullet text-info"></div>';
                    button += '<a class="text-muted"><small>' + row.akronim_opd + '</small></a>';
                    button += '</div>';

                    return button;
                }
            }, {
                "data": "id_tiket",
                "render": function(data, type, row) {
                    var button = "";
                    button +=
                        '<a href="<?= base_url() ?>/detail/' + row.route + "/" + row.id_tiket + '/' +
                        row.kode_tiket +
                        '" class="btn btn-primary btn-action mr-1" data-toggle="tooltip" title="Detail"><i class="fas fa-list-ul"></i></a>';
                    button +=
                        '<a href="<?= base_url() ?>/<?= session()->get('role') ?>/print_tiket/' + row
                        .id_tiket + '/' +
                        row.kode_tiket +
                        '" class="btn btn-info btn-action mr-1" data-toggle="tooltip" target="_blank" title="Cetak"><i class="far fa-file-pdf"></i></a>';

                    return button;
                }
            },
        ]
    });
    new $.fn.dataTable.FixedHeader(table);
}
</script>
<?= $this->endSection() ?>