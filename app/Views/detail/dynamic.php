<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Tiket</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a
                        href="<?= base_url() ?>/<?= session()->get('role') ?>/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url() ?>/<?= session()->get('role') ?>/tiket">Tiket</a>
                </div>
                <div class="breadcrumb-item">Detail</div>
                <div class="breadcrumb-item"><?= esc($pelayanan['nama_pelayanan']) ?></div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col-lg-8 d-flex w-100 ">
                    <div class="card flex-grow-1 ">
                        <div class="card-header">
                            <h4>Detail - <?= esc($pelayanan['nama_pelayanan']) ?></h4>
                        </div>
                        <div class="card-body">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div><small class="text-muted">Kode Tiket</small></div>
                                    <div><b><?= esc($tiket['kode_tiket']) ?></b></div>
                                </div>
                                <div class="col-md-6">
                                    <div><small class="text-muted">Tanggal</small></div>
                                    <div><?= esc($tiket['tgl_input']) ?></div>
                                </div>
                            </div>

                            <hr>

                            <?php foreach ($fields as $f): ?>
                                <?php
                                    $d = $detailMap[(string)$f['id_field']] ?? null;
                                    $val = $d ? ($d['value_text'] ?? '') : '';
                                    $file = $d ? ($d['value_file'] ?? '') : '';
                                ?>
                                <div class="row mb-2">
                                    <div class="col-md-4"><b><?= esc($f['label']) ?></b></div>
                                    <div class="col-md-8">
                                        <?php if ($f['type'] === 'file' && !empty($file)): ?>
                                            <a target="_blank" href="<?= base_url() ?>/public/assets/berkas/dynamic/<?= esc($pelayanan['route']) ?>/<?= esc($file) ?>">
                                                <?= esc($file) ?>
                                            </a>
                                        <?php else: ?>
                                            <?= esc($val) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4 d-flex w-100 ">
                    <div class="card flex-grow-1 ">
                        <div class="card-header">
                            <h4>Status Tiket</h4>
                        </div>
                        <div class="card-body">
                            <?php
                                $status = (int)($tiket['status'] ?? 0);
                                $statusText = 'Diproses';
                                if ($status === 1) $statusText = 'Selesai';
                                if ($status === 2) $statusText = 'Ditolak';
                                if ($status === 3) $statusText = 'Dibatalkan';
                            ?>
                            <p class="mb-0"><b><?= esc($statusText) ?></b></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
