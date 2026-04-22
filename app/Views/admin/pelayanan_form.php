<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>
<script src="https://code.iconify.design/iconify-icon/2.3.0/iconify-icon.min.js"></script>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <div class="d-flex align-items-center">
                <button type="button"
                        class="btn btn-primary btn-icon mr-3"
                        onclick="goBackPage()"
                        title="Kembali">
                    <i class="fas fa-arrow-left"></i>
                </button>

                <div>
                    <h1 class="mb-0">Master Form Pelayanan</h1>
                    <div class="text-muted small">Kembali ke halaman sebelumnya</div>
                </div>
            </div>

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
                            <div class="row mb-4">
                                <div class="col-lg-8">
                                    <div class="alert alert-light border mb-0">
                                        <strong>Info:</strong> Tambahkan field form pelayanan pada tabel di bawah. Di panel sebelah kanan, super admin juga bisa mengatur icon layanan untuk ditampilkan di homepage.
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-3 mt-lg-0">
                                    <div class="card shadow-sm border mb-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div>
                                                    <small class="text-muted d-block">Icon Layanan</small>
                                                    <strong><?= esc($pelayanan['nama_pelayanan']) ?></strong>
                                                </div>
                                                <div id="iconPreviewBox" class="d-flex align-items-center justify-content-center" style="width:72px;height:72px;border-radius:18px;background:#eef2ff;overflow:hidden;">
                                                    <i class="fas fa-image" style="font-size:22px;color:#94a3b8;"></i>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-outline-primary btn-sm btn-block" onclick="openIconModal()">
                                                <i class="fas fa-pen"></i> Edit Icon
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

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

<!-- Modal Icon Layanan -->
<div class="modal fade" id="iconModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Icon Layanan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="icon_id_pelayanan" value="<?= (int)$pelayanan['id_pelayanan'] ?>">
                <div class="row">
                    <div class="col-md-4">
                        <div class="border rounded p-3 text-center h-100">
                            <div id="iconPreviewModal" class="d-flex align-items-center justify-content-center mx-auto mb-3" style="width:100px;height:100px;border-radius:22px;background:#eef2ff;overflow:hidden;">
                                <i class="fas fa-image" style="font-size:28px;color:#94a3b8;"></i>
                            </div>
                            <small class="text-muted">Preview icon layanan</small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Mode Icon</label>
                            <select class="form-control" id="icon_mode">
                                <option value="iconify">Pilih dari Iconify</option>
                                <option value="image">Upload Gambar Sendiri</option>
                            </select>
                        </div>
                        <div id="group_upload_icon" class="form-group" style="display:none;">
                            <label>Upload Icon</label>
                            <input type="file" class="form-control" id="icon_file" accept=".png,.jpg,.jpeg,.webp,.svg">
                            <small class="text-muted">Format yang didukung: png, jpg, jpeg, webp, svg.</small>
                        </div>
                        <div id="group_iconify">
                            <div class="form-group">
                                <label>Nama Iconify</label>
                                <input type="text" class="form-control" id="iconify_name" placeholder="contoh: mdi:file-upload-outline">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label>Cari Icon dari Iconify</label>
                                    <input type="text" class="form-control" id="iconify_search_keyword" placeholder="contoh: upload, document, wifi, server">
                                </div>
                                <div class="form-group col-md-4 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-info btn-block" onclick="searchIconify()">
                                        <i class="fas fa-search"></i> Cari Icon
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="suggestIcon()">
                                    <i class="fas fa-magic"></i> Saran Otomatis dari Nama Layanan
                                </button>
                            </div>
                            <div id="iconifySearchResults" class="row"></div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="form-group col-md-6">
                                <label>Warna Icon</label>
                                <input type="color" class="form-control" id="icon_color" value="#4f46e5">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Background Icon</label>
                                <input type="color" class="form-control" id="icon_bg_color" value="#eef2ff">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveIcon()">Simpan Icon</button>
            </div>
        </div>
    </div>
</div>

<script>
const ID_PELAYANAN = <?= (int)$pelayanan['id_pelayanan'] ?>;
let FIELDS = [];
let CURRENT_ICON = null;

$(document).ready(function() {
    loadFields();
    loadIconPanel();

    $('#icon_mode').on('change', function() {
        toggleIconMode();
        renderIconPreview();
        renderTopPreview();
    });

    $('#iconify_name, #icon_color, #icon_bg_color').on('keyup change', function() {
        renderIconPreview();
        renderTopPreview();
    });

    $('#icon_file').on('change', function() {
        if ($('#icon_mode').val() !== 'image') {
            return;
        }

        const file = this.files && this.files[0] ? this.files[0] : null;
        if (!file) {
            renderIconPreview();
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            $('#iconPreviewModal').css('background', $('#icon_bg_color').val() || '#eef2ff');
            $('#iconPreviewModal').html('<img src="' + e.target.result + '" style="max-width:60px;max-height:60px;object-fit:contain;">');
        };
        reader.readAsDataURL(file);
    });
});

function loadIconPanel() {
    $.ajax({
        url: "<?= base_url() ?>/admin/pelayanan/get_icon",
        type: "GET",
        data: { id_pelayanan: ID_PELAYANAN },
        dataType: "JSON",
        success: function(res) {
            if (res.status === 200) {
                CURRENT_ICON = res.data || {};
                $('#icon_mode').val(CURRENT_ICON.icon_mode || 'iconify');
                $('#iconify_name').val(CURRENT_ICON.iconify_name || '');
                $('#icon_color').val(CURRENT_ICON.icon_color || '#4f46e5');
                $('#icon_bg_color').val(CURRENT_ICON.icon_bg_color || '#eef2ff');
                $('#iconify_search_keyword').val("<?= esc($pelayanan['nama_pelayanan']) ?>");
                toggleIconMode();
                renderIconPreview();
                renderTopPreview();
            }
        }
    });
}

function openIconModal() {
    toggleIconMode();
    renderIconPreview();
    $('#iconModal').modal('show');
}

function toggleIconMode() {
    const mode = $('#icon_mode').val();
    $('#group_upload_icon').toggle(mode === 'image');
    $('#group_iconify').toggle(mode === 'iconify');
}

function renderCurrentImage(fileName, targetSelector, bgColor, maxSize) {
    const target = $(targetSelector);
    target.css('background', bgColor || '#f8fafc');
    const finalFile = fileName || 'logokominfo.png';
    target.html('<img src="<?= base_url('assets/image/logo_app') ?>/' + finalFile + '" style="max-width:' + (maxSize || 60) + 'px;max-height:' + (maxSize || 60) + 'px;object-fit:contain;">');
}

function renderIconPreview() {
    const mode = $('#icon_mode').val();
    const iconifyName = ($('#iconify_name').val() || '').trim();
    const iconColor = $('#icon_color').val() || '#4f46e5';
    const iconBgColor = $('#icon_bg_color').val() || '#eef2ff';

    if (mode === 'iconify' && iconifyName !== '') {
        $('#iconPreviewModal').css('background', iconBgColor);
        $('#iconPreviewModal').html('<iconify-icon icon="' + iconifyName + '" width="58" height="58" style="color:' + iconColor + ';"></iconify-icon>');
        return;
    }

    const fileInput = document.getElementById('icon_file');
    if (mode === 'image' && fileInput && fileInput.files && fileInput.files[0]) {
        return;
    }

    renderCurrentImage(CURRENT_ICON && CURRENT_ICON.file_foto ? CURRENT_ICON.file_foto : 'logokominfo.png', '#iconPreviewModal', iconBgColor, 60);
}

function renderTopPreview() {
    const mode = $('#icon_mode').val();
    const iconifyName = ($('#iconify_name').val() || '').trim();
    const iconColor = $('#icon_color').val() || '#4f46e5';
    const iconBgColor = $('#icon_bg_color').val() || '#eef2ff';

    if (mode === 'iconify' && iconifyName !== '') {
        $('#iconPreviewBox').css('background', iconBgColor);
        $('#iconPreviewBox').html('<iconify-icon icon="' + iconifyName + '" width="38" height="38" style="color:' + iconColor + ';"></iconify-icon>');
        return;
    }

    renderCurrentImage(CURRENT_ICON && CURRENT_ICON.file_foto ? CURRENT_ICON.file_foto : 'logokominfo.png', '#iconPreviewBox', iconBgColor, 40);
}

function suggestIcon() {
    $.ajax({
        url: "<?= base_url() ?>/admin/pelayanan/suggest_icon",
        type: "POST",
        data: { nama_pelayanan: "<?= esc($pelayanan['nama_pelayanan']) ?>" },
        dataType: "JSON",
        success: function(res) {
            if (res.status === 200) {
                $('#icon_mode').val('iconify');
                $('#iconify_name').val(res.data.iconify_name || 'mdi:shape-outline');
                $('#icon_color').val(res.data.icon_color || '#4f46e5');
                $('#icon_bg_color').val(res.data.icon_bg_color || '#eef2ff');
                $('#iconify_search_keyword').val("<?= esc($pelayanan['nama_pelayanan']) ?>");
                toggleIconMode();
                renderIconPreview();
                renderTopPreview();
            }
        }
    });
}

function searchIconify() {
    const keyword = ($('#iconify_search_keyword').val() || '').trim();
    if (keyword === '') {
        $('#iconifySearchResults').html('<div class="col-12"><div class="alert alert-warning mb-0">Masukkan kata kunci icon terlebih dahulu.</div></div>');
        return;
    }

    $('#iconifySearchResults').html('<div class="col-12"><div class="alert alert-light mb-0">Mencari icon dari Iconify...</div></div>');

    fetch('https://api.iconify.design/search?query=' + encodeURIComponent(keyword) + '&limit=12')
        .then(response => response.json())
        .then(data => {
            const icons = data.icons || [];
            if (!icons.length) {
                $('#iconifySearchResults').html('<div class="col-12"><div class="alert alert-warning mb-0">Icon tidak ditemukan. Coba kata kunci lain.</div></div>');
                return;
            }

            let html = '';
            icons.forEach(function(iconName) {
                const safeName = iconName.replace(/'/g, "\\'");
                html += '<div class="col-md-3 col-6 mb-3">';
                html += '<div class="border rounded text-center p-2 h-100" style="cursor:pointer;" onclick="chooseIcon(\'' + safeName + '\')">';
                html += '<div class="d-flex align-items-center justify-content-center mx-auto mb-2" style="width:56px;height:56px;border-radius:16px;background:' + ($('#icon_bg_color').val() || '#eef2ff') + ';">';
                html += '<iconify-icon icon="' + iconName + '" width="30" height="30" style="color:' + ($('#icon_color').val() || '#4f46e5') + ';"></iconify-icon>';
                html += '</div>';
                html += '<small class="d-block text-muted" style="word-break:break-word;">' + iconName + '</small>';
                html += '</div>';
                html += '</div>';
            });
            $('#iconifySearchResults').html(html);
        })
        .catch(() => {
            $('#iconifySearchResults').html('<div class="col-12"><div class="alert alert-danger mb-0">Gagal mengambil data icon dari Iconify. Cek koneksi internet atau isi nama icon secara manual.</div></div>');
        });
}

function chooseIcon(iconName) {
    $('#icon_mode').val('iconify');
    $('#iconify_name').val(iconName);
    toggleIconMode();
    renderIconPreview();
    renderTopPreview();
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

function saveIcon() {
    const formData = new FormData();
    formData.append('id_pelayanan', ID_PELAYANAN);
    formData.append('icon_mode', $('#icon_mode').val());
    formData.append('iconify_name', ($('#iconify_name').val() || '').trim());
    formData.append('icon_color', $('#icon_color').val());
    formData.append('icon_bg_color', $('#icon_bg_color').val());

    const fileInput = document.getElementById('icon_file');
    if (fileInput && fileInput.files && fileInput.files[0]) {
        formData.append('icon_file', fileInput.files[0]);
    }

    $.ajax({
        url: "<?= base_url() ?>/admin/pelayanan/save_icon",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        dataType: "JSON",
        success: function(res) {
            if (res.status === 200) {
                $('#iconModal').modal('hide');
                Swal.fire({
                    position: 'top-center',
                    icon: 'success',
                    title: 'Berhasil !',
                    text: res.message || 'Icon layanan berhasil disimpan.',
                    showConfirmButton: false,
                    timer: 1500
                });
                loadIconPanel();
            } else {
                Swal.fire('Gagal', res.message || 'Icon layanan gagal disimpan.', 'error');
            }
        },
        error: function(xhr) {
            let msg = 'Icon layanan gagal disimpan.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            Swal.fire('Gagal', msg, 'error');
        }
    });
}

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
        error: function() {
            Swal.fire('Gagal', 'Data field gagal dimuat.', 'error');
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
        Swal.fire({ icon: 'error', title: 'Gagal...', text: 'Field Key wajib diisi!' });
        return;
    }

    if (payload.label === '') {
        $('#label').focus();
        Swal.fire({ icon: 'error', title: 'Gagal...', text: 'Label wajib diisi!' });
        return;
    }

    if (payload.type === 'select' && payload.options_json !== '') {
        try {
            JSON.parse(payload.options_json);
        } catch (e) {
            $('#options_json').focus();
            Swal.fire({ icon: 'error', title: 'Gagal...', text: 'Options JSON tidak valid!' });
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
                Swal.fire('Gagal', res.message || 'Field gagal disimpan.', 'error');
            }
        },
        error: function(xhr) {
            let msg = 'Terjadi kesalahan pada server.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            }
            Swal.fire('Gagal', msg, 'error');
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
        data: { id_field: id_field, active: active },
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
                Swal.fire('Gagal', res.message || 'Status field gagal diubah.', 'error');
            }
        },
        error: function() {
            Swal.fire('Gagal', 'Status field gagal diubah.', 'error');
        }
    });
}

function delField(id_field) {
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: 'Field akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id_field', id_field);

            $.ajax({
                url: "<?= base_url() ?>/admin/pelayanan/del_field",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                enctype: 'multipart/form-data',
                dataType: "JSON",
                success: function(res) {
                    if (res.status == 200) {
                        Swal.fire({
                            position: 'top-center',
                            icon: 'success',
                            title: 'Berhasil !',
                            text: res.message || 'Field berhasil dihapus.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        loadFields();
                    } else {
                        Swal.fire('Gagal', res.message || 'Field gagal dihapus.', 'error');
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    let msg = 'Field gagal dihapus.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    } else if (errorThrown) {
                        msg = errorThrown;
                    }
                    Swal.fire('Gagal', msg, 'error');
                }
            });
        }
    });
}
function goBackPage() {
    if (window.history.length > 1 && document.referrer !== '') {
        window.history.back();
    } else {
        window.location.href = "<?= base_url() ?>/admin/pelayanan";
    }
}
</script>

<?= $this->endSection() ?>
