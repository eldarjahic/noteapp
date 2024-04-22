<?php
require_once __DIR__ . "/rest/services/NotesService.class.php";

$request_body = file_get_contents("php://input");

// Decode JSON data into an associative array
$payload = json_decode($request_body, true);

if($payload['title'] == NULL || $payload['title'] == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => "Note title field is missing"]));
}

if($payload['content'] == NULL || $payload['content'] == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => "Note content field is missing"]));
}

$notes_service = new NotesService();


if(isset($payload['id']) && $payload['id'] != NULL && $payload['id'] != ''){
    $note = $notes_service->edit_notes($payload['id'], $payload['content'], $payload['title']);
} else {
    $newNotePayload = ['content' => $payload['content'], 'title' => $payload['title']];
    $note = $notes_service->add_notes($newNotePayload);
}


echo json_encode(['message' => "You have successfully added the note", 'data' => $note]);
?>