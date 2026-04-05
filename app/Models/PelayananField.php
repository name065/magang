<?php

namespace App\Models;

use CodeIgniter\Model;

class PelayananField extends Model
{
    protected $table      = 'sspelayanan_field';
    protected $primaryKey = 'id_field';

    protected $useAutoIncrement = true;

    // options_json: untuk type select/radio/checkbox (json array)
    protected $allowedFields = [
        'id_pelayanan',
        'field_key',
        'label',
        'type',
        'placeholder',
        'help_text',
        'options_json',
        'is_required',
        'sort_order',
        'active'
    ];
}
