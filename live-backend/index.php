<?php
    require 'vendor/autoload.php';

    Flight::register('noteService', 'NotesService');


    require 'vendor/autoload.php';
    require 'rest/routes/notes_routes.php';
    require 'rest/routes/user_routes.php';
    require 'rest/routes/auth_routes.php';
    require 'rest/routes/middleware_routes.php';

    Flight::start();
?>