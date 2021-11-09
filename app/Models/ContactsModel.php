<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactsModel extends Model{

    protected $table = 'contact';
    protected $primaryKey = 'id';
    protected $allowedFields =  ['name', 'email', 'phone','notes', 'created'];
    //protected $returnType = App\Entities\Contacts::class;
    //protected $useTimestamps = false;

    public function findById($id)
    {
        $data = $this->find($id);
        if($data)
        {
            return $data;
        }
        return false;
    }

    public function get_all_data() {
        $this->join('contactlabel', 'contactlabel.contact_id = contact.id', 'INNER');
        $this->join('label', 'contactlabel.label_id = label.id', 'INNER');
        $this->select('contact.name as name_contact');
        $this->select('contact.email');
        $this->select('contact.phone');
        $this->select('contact.notes');
        $this->select('label.name');
        $this->select('label.slug');
        $this->orderBy('contact.created','desc');
        $result = $this->findAll();
    
        //echo $this->db->getLastQuery();
    
        return $result;
    }

    public function search_data($param1,$param2) {
        $this->join('contactlabel', 'contactlabel.contact_id = contact.id', 'INNER');
        $this->join('label', 'contactlabel.label_id = label.id', 'INNER');
        $this->select('contact.name as name_contact');
        $this->select('contact.email');
        $this->select('contact.phone');
        $this->select('contact.notes');
        $this->select('label.name');
        $this->select('label.slug');
        $this->where('label.slug', $param2);
        $this->like('contact.name', $param1);
        $this->orlike('contact.email', $param1);
        $this->orlike('contact.phone', $param1);
        $this->orlike('contact.notes', $param1);
        $this->orderBy('contact.created','desc');
        $result = $this->findAll();
    
        //echo $this->db->getLastQuery();
    
        return $result;
    }

    
}

?>