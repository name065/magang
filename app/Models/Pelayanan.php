<?php

namespace App\Models;

use CodeIgniter\Model;

class Pelayanan extends Model
{
    protected $table      = 'sspelayanan';
    protected $primaryKey = 'id_pelayanan';

    protected $useAutoIncrement = true;
    // NOTE:
    // - is_visible: tampil di dropdown pemilihan pelayanan (default 1)
    // - is_dynamic: form pelayanan dibangun dari DB (master field) (default 0)
    protected $allowedFields = ['id_opd', 'nama_pelayanan', 'route', 'url', 'file_foto', 'active', 'tgl_input', 'deskripsi', 'is_visible', 'is_dynamic', 'icon_mode', 'iconify_name', 'icon_color', 'icon_bg_color', 'icon_updated_at'];
}