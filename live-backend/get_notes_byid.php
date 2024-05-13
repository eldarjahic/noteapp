<?php

/*require_once __DIR__ . "/rest/services/NotesService.class.php";

$request_body = file_get_contents("php://input");

// Decode JSON data into an associative array
$payload = json_decode($request_body, true);

if ($payload === NULL) {
    header('HTTP/1.1 400 Bad Request');
    die(json_encode(['error' => "Invalid JSON"]));
}

if(!isset($payload['id']) || $payload['id'] == NULL || $payload['id'] == '') {
    header('HTTP/1.1 400 Bad Request');
    die(json_encode(['error' => "Note ID field is missing"]));
}

$id = $payload['id'];

$notes_service = new NotesService();

// Get the note with the provided ID
$note = $notes_service->get_by_id($id);

echo json_encode(['message' => "Note fetched", 'data' => $note]);*/
?>
