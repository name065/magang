<?php

namespace App\Models;

use CodeIgniter\Model;

class V_vefirikator extends Model
{
    protected $table      = 'v_verifikator';
    protected $allowedFields = ['id_ssuser', 'username', 'email' ,'active','role_id', 'file_foto', 'nama', 'id_pelayanan',"nama_pelayanan"];

}