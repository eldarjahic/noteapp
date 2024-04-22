<?php
require_once __DIR__ . "/rest/services/NotesService.class.php";

$note_id = $_REQUEST['id'];

$request_body = file_get_contents("php://input");

// Decode JSON data into an associative array
$payload = json_decode($request_body, true);

if ($payload === NULL) {
    header('HTTP/1.1 400 Bad Request');
    die(json_encode(['error' => "Invalid JSON"]));
}

if(!isset($note_id) || $note_id == NULL || $note_id == '') {
    header('HTTP/1.1 400 Bad Request');
    die(json_encode(['error' => "Note ID field is missing"]));
}

$notes_service = new NotesService();

$result = $notes_service->edit_notes($note_id, $payload);

if (isset($result['error'])) {
    header('HTTP/1.1 404 Not Found');
    die(json_encode(['error' => $result['error']]));
}

echo json_encode(['message' => "Note updated successfully"]);
?>