<?php
require_once __DIR__ ."/../dao/NotesDao.class.php";

class NotesService
{
    private $notes_dao;
    public function __construct()
    {
        $this->notes_dao = new NotesDao();


    }
    public function add($notes)
    {
        
        return $this->notes_dao->add($notes);
    }

    public function get_all()
    {
        return $this->notes_dao->get_all();
    }


     public function delete($id)
    {
        return $this->notes_dao->delete_notes($id);
    }
    public function update($id, $body)
    {
        return $this->notes_dao->edit_notes($id, $body);
    }
     public function get_by_id($id)
    {
        return $this->notes_dao->get_by_id($id);
    }
    
}

?>