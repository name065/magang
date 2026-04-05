<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Master Form Pelayanan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url() ?>/admin/dashboard">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url() ?>/admin/pelayanan">Master Pelayanan</a></div>
                <div class="breadcrumb-item"><?= esc($pelayanan['nama_pelayanan']) ?></div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Field Form - <?= esc($pelayanan['nama_pelayanan']) ?></h4>
                            <div class="card-header-action">
                                <button class="btn btn-primary" onclick="openAdd()"><i class="fas fa-plus"></i> Tambah Field</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="tblFields">
                                    <thead>
                                        <tr>
                                            <th style="width:80px">Order</th>
                                            <th>Key</th>
                                            <th>Label</th>
                                            <th>Type</th>
                                            <th>Required</th>
                                            <th>Status</th>
                                            <th style="width:220px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <small class="text-muted">Catatan: Untuk type <b>select</b>, isi <i>Options (JSON)</i> contoh: ["Opsi A","Opsi B"]</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Add/Edit -->
<div class="modal fade" id="fieldModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fieldModalTitle">Tambah Field</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_field">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Field Key</label>
                            <input type="text" class="form-control" id="field_key" placeholder="contoh: nama_pemohon">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Label</label>
                            <input type="text" class="form-control" id="label" placeholder="Nama Pemohon">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" id="type">
                                <option value="text">text</option>
                                <option value="textarea">textarea</option>
                                <option value="number">number</option>
                                <option value="date">date</option>
                                <option value="datetime">datetime</option>
                                <option value="select">select</option>
                                <option value="file">file</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Order</label>
                            <input type="number" class="form-control" id="sort_order" value="0">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Required</label>
                            <select class="form-control" id="is_required">
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Placeholder</label>
                            <input type="text" class="form-control" id="placeholder">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Help Text</label>
                            <input type="text" class="form-control" id="help_text">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Options (JSON)</label>
                            <textarea class="form-control" id="options_json" rows="2" placeholder='["Opsi A","Opsi B"]'></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSaveField" onclick="saveField()">Simpan</button>
                <button type="button" class="btn btn-primary btn-progress" id="btnSaveFieldLoading" style="display:none;" disabled>Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    const ID_PELAYANAN = <?= (int)$pelayanan['id_pelayanan'] ?>;
    let FIELDS = [];

    $(document).ready(function() {
        loadFields();
    });

    function loadFields() {
        $.ajax({
            url: "<?= base_url() ?>/admin/pelayanan/get_fields",
            type: "GET",
            data: { id_pelayanan: ID_PELAYANAN },
            dataType: "JSON",
            success: function(data) {
                FIELDS = data || [];
                let html = '';

                FIELDS.forEach(function(row) {
                    const st = row.active == 1
                        ? '<span class="badge badge-success">Enabled</span>'
                        : '<span class="badge badge-danger">Disabled</span>';

                    html += '<tr>';
                    html += '<td>' + row.sort_order + '</td>';
                    html += '<td><code>' + row.field_key + '</code></td>';
                    html += '<td>' + row.label + '</td>';
                    html += '<td>' + row.type + '</td>';
                    html += '<td>' + (row.is_required == 1 ? 'Ya' : 'Tidak') + '</td>';
                    html += '<td>' + st + '</td>';
                    html += '<td>';
                    html += '<button class="btn btn-sm btn-info" onclick="openEdit(' + row.id_field + ')">Edit</button> ';
                    html += '<button class="btn btn-sm btn-warning" onclick="toggleStatus(' + row.id_field + ',' + (row.active == 1 ? 0 : 1) + ')">' + (row.active == 1 ? 'Nonaktifkan' : 'Aktifkan') + '</button> ';
                    html += '<button class="btn btn-sm btn-danger" onclick="delField(' + row.id_field + ')">Hapus</button>';
                    html += '</td>';
                    html += '</tr>';
                });

                $('#tblFields tbody').html(html);
            },
            error: function(xhr) {
                Swal.fire(
                    'Gagal',
                    'Data field gagal dimuat.',
                    'error'
                );
            }
        });
    }

    function openAdd() {
        $('#fieldModalTitle').text('Tambah Field');
        $('#id_field').val('');
        $('#field_key').val('');
        $('#label').val('');
        $('#type').val('text');
        $('#placeholder').val('');
        $('#help_text').val('');
        $('#options_json').val('');
        $('#is_required').val('0');
        $('#sort_order').val('0');
        $('#fieldModal').modal('show');
    }

    function openEdit(id) {
        const row = FIELDS.find(x => parseInt(x.id_field) === parseInt(id));
        if (!row) return;

        $('#fieldModalTitle').text('Edit Field');
        $('#id_field').val(row.id_field);
        $('#field_key').val(row.field_key);
        $('#label').val(row.label);
        $('#type').val(row.type);
        $('#placeholder').val(row.placeholder || '');
        $('#help_text').val(row.help_text || '');
        $('#options_json').val(row.options_json || '');
        $('#is_required').val(row.is_required);
        $('#sort_order').val(row.sort_order);
        $('#fieldModal').modal('show');
    }

    function toggleSaveButton(isLoading) {
        if (isLoading) {
            $('#btnSaveField').hide();
            $('#btnSaveFieldLoading').show();
        } else {
            $('#btnSaveField').show();
            $('#btnSaveFieldLoading').hide();
        }
    }

    function saveField() {
        const payload = {
            id_pelayanan: ID_PELAYANAN,
            id_field: $('#id_field').val(),
            field_key: $('#field_key').val().trim(),
            label: $('#label').val().trim(),
            type: $('#type').val(),
            placeholder: $('#placeholder').val().trim(),
            help_text: $('#help_text').val().trim(),
            options_json: $('#options_json').val().trim(),
            is_required: $('#is_required').val(),
            sort_order: $('#sort_order').val()
        };

        if (payload.field_key === '') {
            $('#field_key').focus();
            Swal.fire({
                icon: 'error',
                title: 'Gagal...',
                text: 'Field Key wajib diisi!'
            });
            return;
        }

        if (payload.label === '') {
            $('#label').focus();
            Swal.fire({
                icon: 'error',
                title: 'Gagal...',
                text: 'Label wajib diisi!'
            });
            return;
        }

        if (payload.type === 'select' && payload.options_json !== '') {
            try {
                JSON.parse(payload.options_json);
            } catch (e) {
                $('#options_json').focus();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal...',
                    text: 'Options JSON tidak valid!'
                });
                return;
            }
        }

        const isEdit = payload.id_field && payload.id_field !== '';
        const url = isEdit
            ? "<?= base_url() ?>/admin/pelayanan/update_field"
            : "<?= base_url() ?>/admin/pelayanan/set_field";

        toggleSaveButton(true);

        $.ajax({
            url: url,
            type: "POST",
            data: payload,
            dataType: "JSON",
            success: function(res) {
                if (res.status == 200) {
                    $('#fieldModal').modal('hide');

                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'Berhasil !',
                        text: res.message || 'Field berhasil disimpan.',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    loadFields();
                } else {
                    Swal.fire(
                        'Gagal',
                        res.message || 'Field gagal disimpan.',
                        'error'
                    );
                }
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan pada server.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

                Swal.fire(
                    'Gagal',
                    msg,
                    'error'
                );
            },
            complete: function() {
                toggleSaveButton(false);
            }
        });
    }

    function toggleStatus(id_field, active) {
        $.ajax({
            url: "<?= base_url() ?>/admin/pelayanan/update_field_status",
            type: "POST",
            data: {
                id_field: id_field,
                active: active
            },
            dataType: "JSON",
            success: function(res) {
                if (res.status == 200) {
                    Swal.fire({
                        position: 'top-center',
                        icon: 'success',
                        title: 'Berhasil !',
                        text: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    loadFields();
                } else {
                    Swal.fire(
                        'Gagal',
                        res.message || 'Status field gagal diubah.',
                        'error'
                    );
                }
            },
            error: function(xhr) {
                Swal.fire(
                    'Gagal',
                    'Status field gagal diubah.',
                    'error'
                );
            }
        });
    }

    function delField(id_field) {
        swal({
            title: 'Hapus field?',
            text: 'Field akan dihapus permanen.',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: "<?= base_url() ?>/admin/pelayanan/del_field",
                    type: "POST",
                    data: { id_field: id_field },
                    dataType: "JSON",
                    success: function(res) {
                        if (res.status == 200) {
                            Swal.fire({
                                position: 'top-center',
                                icon: 'success',
                                title: 'Berhasil !',
                                text: res.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            loadFields();
                        } else {
                            Swal.fire(
                                'Gagal',
                                res.message || 'Field gagal dihapus.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Gagal',
                            'Field gagal dihapus.',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>

<?= $this->endSection() ?>
