<?php
namespace App\Models;

use CodeIgniter\Model;

class Tiket_detail extends Model
{
    protected $table      = 'tb_tiket_detail';
    protected $primaryKey = 'id_detail';
    protected $allowedFields = ['id_tiket','tipe','judul','mulai','selesai','detail'];
}