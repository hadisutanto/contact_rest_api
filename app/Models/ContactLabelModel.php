<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactLabelModel extends Model{
    protected $table = 'contactlabel';
    protected $allowedFields = ['contact_id', 'label_id'];

    
}

?>