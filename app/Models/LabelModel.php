<?php

namespace App\Models;

use CodeIgniter\Model;

class LabelModel extends Model{
    protected $table = 'label';
    protected $primary_key = 'id';
    protected $allowedFields = ['name', 'slug'];

    
}

?>