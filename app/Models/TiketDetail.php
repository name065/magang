<?php

namespace App\Models;

use CodeIgniter\Model;

class TiketDetail extends Model
{
    protected $table      = 'tb_tiket_dynamic_detail';
    protected $primaryKey = 'id_detail';

    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'id_tiket',
        'id_field',
        'value_text',
        'value_file',
        'tgl_input'
    ];
}
