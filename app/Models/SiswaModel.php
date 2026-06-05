<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama',
        'gender',
        'id_kelas',
        'status_kehadiran',
        'keterangan',
        'created_at',
        'user_id'
    ];
}
