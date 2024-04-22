<?php
require_once __DIR__ ."/../dao/NotesDao.class.php";

class NotesService
{
    private $notes_dao;
    public function __construct()
    {
        $this->notes_dao = new NotesDao();


    }
    public function add_notes($notes)
    {
        return $this->notes_dao->add($notes);
    }

    public function get_notes()
    {
        return $this->notes_dao->get_all();
    }


     public function delete_notes($id)
    {
        return $this->notes_dao->delete_notes($id);
    }
    public function edit_notes($id, $body)
    {
        return $this->notes_dao->edit_notes($id, $body);
    }
     public function get_by_id($id)
    {
        return $this->notes_dao->get_by_id($id);
    }
    
}

?>