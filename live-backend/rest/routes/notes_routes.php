<?php


require_once __DIR__ . '/../services/NotesService.class.php';




 /**
 * @OA\Get(
 *      path="/notes",
 *      tags={"notes"},
 *      summary="Get all notes - dummy route for understanding the benefit of tags in the swagger",
 *      @OA\Response(
 *           response="200",
 *           description="Array of all notes in the databases"
 *      )
 * )
 */

Flight::route('GET /notes', function(){
  Flight::json(Flight::noteService()->get_all());

});

/**
 * @OA\Get(path="/notes/{id}", tags={"notes"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of note"),
 *     @OA\Response(response="200", description="Fetch individual note")
 * )
 */

Flight::route('GET /notes/@id', function($id){
  Flight::json(Flight::noteService() -> get_by_id($id));
});

/**
 * @OA\Get(path="/notes/{id}/notes", tags={"note"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="id", example=1, description="List notes"),
 *     @OA\Response(response="200", description="Fetch note's ")
 * )
 */

Flight::route('POST /notes', function(){
  $data = json_decode(Flight::request()->getBody(), true);

  Flight::json (Flight::noteService()->add($data));
});

Flight::route('PUT /notes/@id', function($id){
  $data = json_decode(Flight::request()->getBody(), true);
  Flight::json(Flight::noteService()->update($id, $data));


});
 /**
     * @OA\Delete(
     *      path="/notes/delete/{notes_id}",
     *      tags={"notes"},
     *      summary="Delete notes by id",
     *      @OA\Response(
     *           response=200,
     *           description="Deleted note data or 500 status code exception otherwise"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="note_id", example="1", description="Note ID")
     * )
     */

Flight::route('DELETE /notes/@id', function($id){
  Flight::noteService()->delete($id);
  Flight::json(["massage" => "deleted"]);
});


?>
