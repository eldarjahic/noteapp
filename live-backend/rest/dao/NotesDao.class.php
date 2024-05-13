<?php
require_once __DIR__ ."/BaseDao.class.php";
class NotesDao extends BaseDao{
    public function __construct(){
        parent::__construct("notes");
    }

    public function add_notes($notes){
    
        return $notes;
    }
    
    public function add($entity){
    $query = "INSERT INTO notes (title, content) VALUES (:title, :content)";
   
    
    $stmt= $this->connection->prepare($query);
    $stmt->bindParam(':title', $entity['title']);
    $stmt->bindParam(':content', $entity['content']);
    $stmt->execute();
    $entity['id'] = $this->connection->lastInsertId();
    return $entity;
  }
    public function get_all(){
    $stmt = $this->connection->prepare("SELECT * FROM ".$this->table);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  public function delete_notes($id){
    $stmt = $this->connection->prepare("DELETE FROM ".$this->table." WHERE id=:id");
    $stmt->bindParam(':id', $id); // SQL injection prevention
    $stmt->execute();
  }
public function edit_notes($id, $body){
    // Fetch the note with the provided ID
    $note = $this->get_by_id($id);

    // If the note does not exist, return an error
    if ($note === NULL) {
        return ['error' => "Note with the provided ID does not exist"];
    }

    // If the note exists, update it
    $query = "UPDATE ".$this->table." SET content = :content, title = :title WHERE id = :id";

    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':content', $body["content"]);
    $stmt->bindParam(':title', $body["title"]);
    $stmt->bindParam(':id', $id);

    $stmt->execute();

    // Return the number of affected rows
    return ['affectedRows' => $stmt->rowCount()];
}

public function get_by_id($id){
    $stmt = $this->connection->prepare("SELECT * FROM ".$this->table." WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return reset($result);
  }


}

?>