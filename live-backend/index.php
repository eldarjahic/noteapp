<?php
    require 'vendor/autoload.php';
    header('Access-Control-Allow-Origin: *');
    Flight::set('flight.log_errors', true);

    Flight::register('noteService', 'NotesService');


    require 'vendor/autoload.php';
    require 'rest/routes/middleware_routes.php';
    require 'rest/routes/notes_routes.php';
    require 'rest/routes/user_routes.php';
    require 'rest/routes/auth_routes.php';
    Flight::map('error', function (Throwable $error) {
        // Handle error
        echo $error->getTraceAsString();
      });

    Flight::start();
?>