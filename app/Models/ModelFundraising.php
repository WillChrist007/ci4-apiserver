<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelFundraising extends Model
{
    protected $table = 'fundraising';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id','judul','dana','lokasi','durasi'
    ];
}
