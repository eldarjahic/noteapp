<?php
/*require_once __DIR__ . "/rest/services/NotesService.class.php";

$note_id = $_REQUEST['id'];

if($note_id == NULL || $note_id == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => "Note ID field is missing"]));
}

$notes_service = new NotesService();

$notes_service->delete_notes($note_id);

echo json_encode(['message' => "Note deleted successfully"]);*/
?>