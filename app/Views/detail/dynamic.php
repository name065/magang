<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<?php
$textFields = [];
$fileFields = [];

foreach ($fields as $f) {
    $detail = $detailMap[(string) $f['id_field']] ?? null;

    if (($f['type'] ?? 'text') === 'file') {
        $fileFields[] = [
            'label' => $f['label'],
            'file'  => $detail['value_file'] ?? '',
            'type'  => $f['type'] ?? 'file',
        ];
    } else {
        $textFields[] = [
            'label' => $f['label'],
            'value' => $detail['value_text'] ?? '',
            'type'  => $f['type'] ?? 'text',
        ];
    }
}

$catatanTiket = $tiket['catatan'] ?? '';
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Tiket</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="<?= base_url() ?>/<?= session()->get('role') ?>/dashboard">Dashboard</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="<?= base_url() ?>/<?= session()->get('role') ?>/tiket">Tiket</a>
                </div>
                <div class="breadcrumb-item">
                    <a href="<?= base_url() ?>/<?= session()->get('role') ?>/tiket">Detail</a>
                </div>
                <div class="breadcrumb-item"><?= esc($pelayanan['nama_pelayanan']) ?></div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col-lg-6 d-flex w-100">
                    <div class="card flex-grow-1">
                        <div class="card-header">
                            <h4>Detail <?= esc($pelayanan['nama_pelayanan']) ?></h4>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-4">
                                    <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="home-tab4" data-toggle="tab" href="#home4"
                                                role="tab" aria-controls="home4" aria-selected="true">Tiket</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="detail-tab4" data-toggle="tab" href="#detail4"
                                                role="tab" aria-controls="detail4" aria-selected="false">Detail Pelayanan</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="file-tab4" data-toggle="tab" href="#file4"
                                                role="tab" aria-controls="file4" aria-selected="false">Berkas Upload</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="contact-tab4" data-toggle="tab" href="#contact4"
                                                role="tab" aria-controls="contact4" aria-selected="false">Catatan</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-12 col-sm-12 col-md-8">
                                    <div class="tab-content no-padding" id="myTab2Content">
                                        <div class="tab-pane fade show active" id="home4" role="tabpanel"
                                            aria-labelledby="home-tab4">
                                            <div class="form-group">
                                                <label>Kode Tiket</label>
                                                <input disabled="true" type="text"
                                                    value="<?= esc($tiket['kode_tiket']) ?>" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Tanggal Pengajuan</label>
                                                <input disabled="true" type="text"
                                                    value="<?= esc($tiket['tgl_input']) ?>" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Status</label>
                                                <input id="status" disabled="true" type="text" class="form-control">
                                            </div>

                                            <div class="card-footer text-right" id="confirm_btn"></div>
                                        </div>

                                        <div class="tab-pane fade" id="detail4" role="tabpanel"
                                            aria-labelledby="detail-tab4">
                                            <?php if (count($textFields) > 0): ?>
                                                <?php foreach ($textFields as $item): ?>
                                                    <?php
                                                    $value = (string) ($item['value'] ?? '');
                                                    $type  = $item['type'] ?? 'text';
                                                    $isLongText = $type === 'textarea' || strpos($value, "\n") !== false || strlen($value) > 90;
                                                    ?>
                                                    <div class="form-group">
                                                        <label><?= esc($item['label']) ?></label>

                                                        <?php if ($isLongText): ?>
                                                            <textarea rows="4" disabled="true"
                                                                class="form-control"><?= esc($value) ?></textarea>
                                                        <?php else: ?>
                                                            <input type="text" disabled="true" class="form-control"
                                                                value="<?= esc($value) ?>">
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="alert alert-light mb-0">
                                                    Belum ada detail pelayanan.
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="tab-pane fade" id="file4" role="tabpanel"
                                            aria-labelledby="file-tab4">
                                            <?php
                                            $hasFile = false;
                                            foreach ($fileFields as $fileItem) {
                                                if (!empty($fileItem['file'])) {
                                                    $hasFile = true;
                                                    break;
                                                }
                                            }
                                            ?>

                                            <?php if ($hasFile): ?>
                                                <?php foreach ($fileFields as $fileItem): ?>
                                                    <?php if (!empty($fileItem['file'])): ?>
                                                        <div class="form-group">
                                                            <label><?= esc($fileItem['label']) ?></label><br>
                                                            <button type="button"
                                                                onclick="window.open('<?= base_url() ?>/public/assets/berkas/dynamic/<?= esc($pelayanan['route']) ?>/<?= esc($fileItem['file']) ?>','_blank')"
                                                                class="btn btn-primary btn-icon icon-left">
                                                                <i class="fas fa-file-download"></i>
                                                                Unduh <?= esc($fileItem['label']) ?>
                                                            </button>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="alert alert-light mb-0">
                                                    Tidak ada berkas upload.
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="tab-pane fade" id="contact4" role="tabpanel"
                                            aria-labelledby="contact-tab4">
                                            <div class="form-group">
                                                <label>Catatan</label>
                                                <textarea id="catatan" rows="4" disabled="true"
                                                    class="form-control"><?= esc($catatanTiket) ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 d-flex w-100">
                    <div class="card flex-grow-1">
                        <div class="card-header">
                            <h4>Log Aktifitas</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="activities" id="list_history"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Berikan Alasan Anda</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group row align-items-center">
                    <label class="col-md-12 text-md-left text-left">Catatan</label>
                    <div class="col-lg-12 col-md-12">
                        <textarea id="catatan_update" rows="4" class="form-control"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="d-flex justify-content-end" id="div_catatan"></div>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

<script>
$(document).ready(function() {
    var menuTiket = document.getElementById("tiket");
    if (menuTiket) {
        menuTiket.classList.add("active");
    }

    access_status("<?= (int) $tiket['status'] ?>");
    get_history();
});

function access_button() {
    var button = "";

    if (<?= (int) session()->get('id_role') ?> == 0 || <?= (int) session()->get('id_role') ?> == 2) {
        button +=
            '<a href="javascript:void(0)" onclick="update_status(2)" class="btn btn-danger btn-action mr-1"><i class="fa fa-times"></i> Tolak</a>';
        button +=
            '<a href="javascript:void(0)" onclick="update_status(1)" class="btn btn-success btn-action mr-1"><i class="fa fa-check"></i> Selesai</a>';
    }

    if (<?= (int) session()->get('id_role') ?> == 0 || (<?= (int) session()->get('id_role') ?> == 1 &&
            <?= (int) session()->get('id_user') ?> == <?= (int) $tiket['id_user'] ?>)) {
        button +=
            '<a href="javascript:void(0)" onclick="update_status(3)" class="btn btn-dark btn-action mr-1"><i class="fa fa-times"></i> Batalkan</a>';
    }

    document.getElementById("confirm_btn").innerHTML = button;
}

function update_status(status) {
    if (status == 1) {
        Swal.fire({
            title: 'Apakah anda yakin ?',
            text: "Tiket yang sudah diselesaikan tidak dapat diubah !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Selesai'
        }).then((result) => {
            if (result.isConfirmed) {
                update_catatan(status);
            }
        });
    } else {
        var button = '';
        button += '<button onclick="update_catatan(' + status +
            ')" id="btn_catatan" class="btn btn-primary">Simpan</button>';
        button +=
            '<button id="loader_catatan" style="display: none;" class="btn disabled rounded-pill btn-primary btn-progress">Simpan</button>';

        document.getElementById("div_catatan").innerHTML = button;
        $('#updateModal').modal('show');
    }
}

function access_status(status) {
    var el = document.getElementById("status");
    el.className = "form-control";

    if (status == 0) {
        el.value = "Proses";
        el.classList.add("border", "border-primary", "text-primary", "bg-white");
        access_button();
    } else if (status == 1) {
        el.value = "Selesai";
        el.classList.add("border", "border-success", "text-success", "bg-white");
        document.getElementById("confirm_btn").innerHTML = "";
    } else if (status == 2) {
        el.value = "Ditolak";
        el.classList.add("border", "border-danger", "text-danger", "bg-white");
        document.getElementById("confirm_btn").innerHTML = "";
    } else {
        el.value = "Dibatalkan";
        el.classList.add("border", "border-dark", "text-dark", "bg-white");
        document.getElementById("confirm_btn").innerHTML = "";
    }
}

function get_history() {
    var formData = new FormData();
    formData.append('id_tiket', <?= (int) $tiket['id_tiket'] ?>);

    $.ajax({
        url: "<?= base_url() ?>/detail/get_history",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        enctype: 'multipart/form-data',
        dataType: "JSON",
        success: function(data) {
            var baris = "";

            if (!data || data.length === 0) {
                baris = '<div class="text-muted">Belum ada log aktifitas.</div>';
            } else {
                for (var x = 0; x < data.length; x++) {
                    baris += '<div class="activity">';
                    baris += '<div class="activity-icon bg-' + data[x].color + ' text-white shadow-' + data[x].color + '"><i class="' + data[x].icon + '"></i></div>';
                    baris += '<div class="activity-detail">';
                    baris += '<div class="mb-2">';

                    const now_start = new Date(data[x].tgl);
                    var dateStringWithTimeStart = moment(now_start).format('DD-MM-YYYY HH:mm:ss A');

                    baris += '<span class="text-job text-primary">' + dateStringWithTimeStart + '</span>';
                    baris += '<span class="bullet text-secondary"></span>';
                    baris += '<a class="text-job" href="javascript:void(0)">' + moment(now_start).fromNow() + '</a>';
                    baris += '</div>';
                    baris += '<p><a class="text-job" href="javascript:void(0)">' + data[x].nama + '</a><span class="bullet text-primary"></span> ' + data[x].aktifitas + '</p>';
                    baris += '</div>';
                    baris += '</div>';
                }
            }

            document.getElementById("list_history").innerHTML = baris;
        }
    });
}

function update_catatan(status) {
    if (status == 1) {
        var formData = new FormData();
        formData.append('id_tiket', <?= (int) $tiket['id_tiket'] ?>);
        formData.append('status', status);

        $.ajax({
            url: "<?= base_url() ?>/detail/update_catatan",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            async: true,
            enctype: 'multipart/form-data',
            dataType: "JSON",
            success: function(data) {
                if (data.status == 200) {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'Berhasil !',
                        showConfirmButton: false,
                        timer: 1500,
                    });

                    get_history();
                    access_status(status);
                } else {
                    Swal.fire("Gagal", data.message, 'error');
                }
            }
        });
    } else {
        if (isEmptyOrSpaces(document.getElementById('catatan_update').value)) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal...',
                text: 'Form Catatan Kosong !'
            });
        } else if (/[^a-zA-Z0-9\ .,\-_]/.test(document.getElementById("catatan_update").value)) {
            Swal.fire('Gagal', "Isian Catatan tidak sesuai format.", 'error');
        } else {
            var formData = new FormData();
            formData.append('id_tiket', <?= (int) $tiket['id_tiket'] ?>);
            formData.append('catatan', document.getElementById('catatan_update').value);
            formData.append('status', status);

            $.ajax({
                url: "<?= base_url() ?>/detail/update_catatan",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                async: true,
                enctype: 'multipart/form-data',
                dataType: "JSON",
                beforeSend: function() {
                    document.getElementById("btn_catatan").style.display = "none";
                    document.getElementById("btn_catatan").disabled = true;
                    document.getElementById("loader_catatan").style.display = "block";
                },
                success: function(data) {
                    if (data.status == 200) {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            title: 'Berhasil !',
                            showConfirmButton: false,
                            timer: 1500,
                        });

                        get_history();
                        access_status(status);
                        $('#updateModal').modal('hide');
                        document.getElementById("catatan").value = document.getElementById('catatan_update').value;
                        document.getElementById("catatan_update").value = "";
                    } else {
                        Swal.fire("Gagal", data.message, 'error');
                    }
                },
                complete: function() {
                    document.getElementById("btn_catatan").style.display = "block";
                    document.getElementById("btn_catatan").disabled = false;
                    document.getElementById("loader_catatan").style.display = "none";
                }
            });
        }
    }
}

function isEmptyOrSpaces(str) {
    return str === null || str.match(/^ *$/) !== null;
}
</script>

<?= $this->endSection() ?>