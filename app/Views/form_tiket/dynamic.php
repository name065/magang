<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Form Pelayanan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a
                        href="<?= base_url() ?>/<?= session()->get('role') ?>/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url() ?>/<?= session()->get('role') ?>/tiket">Tiket</a>
                </div>
                <div class="breadcrumb-item"><?= esc($pelayanan['nama_pelayanan']) ?></div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mt-4">
                <div class="col-lg-8 d-flex w-100 ">
                    <div class="card flex-grow-1 ">
                        <div class="card-header">
                            <h4>Form Buat Tiket - <?= esc($pelayanan['nama_pelayanan']) ?></h4>
                        </div>
                        <div class="card-body">

                            <form id="dynamicForm" enctype="multipart/form-data">
                                <input type="hidden" name="kode_tiket" id="kode_tiket">

                                <?php foreach ($fields as $f): ?>
                                    <?php
                                        $required = ((int)$f['is_required'] === 1);
                                        $reqAttr = $required ? 'required' : '';
                                        $label = esc($f['label']);
                                        $key = esc($f['field_key']);
                                        $placeholder = esc($f['placeholder'] ?? '');
                                        $help = $f['help_text'] ?? '';
                                        $type = $f['type'];
                                    ?>
                                    <div class="form-group">
                                        <label><?= $label ?><?= $required ? ' <span class="text-danger">*</span>' : '' ?></label>

                                        <?php if ($type === 'textarea'): ?>
                                            <textarea class="form-control" name="<?= $key ?>" placeholder="<?= $placeholder ?>" <?= $reqAttr ?>></textarea>

                                        <?php elseif ($type === 'select'): ?>
                                            <?php
                                                $options = [];
                                                if (!empty($f['options_json'])) {
                                                    $decoded = json_decode($f['options_json'], true);
                                                    if (is_array($decoded)) $options = $decoded;
                                                }
                                            ?>
                                            <select class="form-control" name="<?= $key ?>" <?= $reqAttr ?>>
                                                <option value="">-- pilih --</option>
                                                <?php foreach ($options as $opt): ?>
                                                    <option value="<?= esc($opt) ?>"><?= esc($opt) ?></option>
                                                <?php endforeach; ?>
                                            </select>

                                        <?php elseif ($type === 'date'): ?>
                                            <input type="date" class="form-control" name="<?= $key ?>" <?= $reqAttr ?>>

                                        <?php elseif ($type === 'datetime'): ?>
                                            <input type="datetime-local" class="form-control" name="<?= $key ?>" <?= $reqAttr ?>>

                                        <?php elseif ($type === 'number'): ?>
                                            <input type="number" class="form-control" name="<?= $key ?>" placeholder="<?= $placeholder ?>" <?= $reqAttr ?>>

                                        <?php elseif ($type === 'file'): ?>
                                            <input type="file" class="form-control" name="<?= $key ?>" <?= $reqAttr ?>>

                                        <?php else: ?>
                                            <input type="text" class="form-control" name="<?= $key ?>" placeholder="<?= $placeholder ?>" <?= $reqAttr ?>>
                                        <?php endif; ?>

                                        <?php if (!empty($help)): ?>
                                            <small class="text-muted"><?= esc($help) ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>

                                <div class="form-group">
                                    <button type="button" onclick="submitDynamic()" class="btn btn-primary btn-lg btn-block">
                                        Kirim
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4 d-flex w-100 ">
                    <div class="card flex-grow-1 ">
                        <div class="card-header">
                            <h4>Info Pelayanan</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><b><?= esc($pelayanan['nama_pelayanan']) ?></b></p>
                            <?php if (!empty($pelayanan['deskripsi'])): ?>
                                <p class="text-muted"><?= esc($pelayanan['deskripsi']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($pelayanan['url'])): ?>
                                <a class="btn btn-light btn-sm" target="_blank" href="<?= esc($pelayanan['url']) ?>">Referensi</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script src='<?= base_url() ?>/public/assets/kode_tiket.js'></script>
<script>
    document.getElementById('kode_tiket').value = randomString();

    function submitDynamic() {
        var form = document.getElementById('dynamicForm');
        var formData = new FormData(form);

        $.ajax({
            url: "<?= base_url() ?>/form/submit/<?= esc($pelayanan['route']) ?>",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            success: function(res) {
                if (res.status == 200) {
                    iziToast.success({
                        title: 'Sukses',
                        message: res.message,
                        position: 'topRight'
                    });

                    // arahkan ke detail
                    window.open("<?= base_url() ?>/detail/<?= esc($pelayanan['route']) ?>/" + res.id_tiket + "/" + res.kode_tiket, "_self");
                } else {
                    iziToast.error({
                        title: 'Gagal',
                        message: res.message,
                        position: 'topRight'
                    });
                }
            },
            error: function() {
                iziToast.error({
                    title: 'Gagal',
                    message: 'Terjadi kesalahan saat mengirim form',
                    position: 'topRight'
                });
            }
        });
    }
</script>

<?= $this->endSection() ?>
