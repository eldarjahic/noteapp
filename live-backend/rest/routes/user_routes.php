<?php

require_once __DIR__ . '/../services/UserService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('user_service', new UserService());

/**
 * @OA\Get(
 *      path="/users",
 *      tags={"users"},
 *      summary="Get all users - dummy route for understanding the benefit of tags in the swagger",
 *      @OA\Response(
 *           response=200,
 *           description="Array of all users in the databases"
 *      )
 * )
 */
Flight::route('GET /users', function() {
    Flight::json([
        [
            'username' => 'Eldar',
            
        ]
    ]);
});

Flight::group('/users', function() {
    
    /**
     * @OA\Get(
     *      path="/users/all",
     *      tags={"users"},
     *      summary="Get all users",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all users in the databases"
     *      )
     * )
     */
    Flight::route('GET /all', function() {
        $data = Flight::get('user_service')->get_all_users();
        Flight::json($data, 200);
    });

    /**
     * @OA\Get(
     *      path="/users/user",
     *      tags={"users"},
     *      summary="Get user by id",
     *      @OA\Response(
     *           response=200,
     *           description="user data, or false if user does not exist"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="query", name="user_id", example="1", description="user ID")
     * )
     */
    Flight::route('GET /user', function() {
        $params = Flight::request()->query;

        $user = Flight::get('user_service')->get_user_by_id($params['user_id']);
        Flight::json($user);
    });

    /**
     * @OA\Get(
     *      path="/users/info",
     *      tags={"users"},
     *      summary="Get logged in users data by id",
     *      @OA\Response(
     *           response=200,
     *           description="user data, or false if user does not exist"
     *      )
     *     
     * )
     */
    Flight::route('GET /info', function() {
        
        Flight::json(Flight::key('user'));
    });

    Flight::route('GET /', function() {    
        
        $payload = Flight::request()->query;

        $params = [
            'start' => (int)$payload['start'],
            'search' => $payload['search']['value'],
            'draw' => $payload['draw'],
            'limit' => (int)$payload['length'],
            'order_column' => $payload['order'][0]['name'],
            'order_direction' => $payload['order'][0]['dir'],
        ];

        $data = Flight::get('user_service')->get_users_paginated($params['start'], $params['limit'], $params['search'], $params['order_column'], $params['order_direction']);

        foreach($data['data'] as $id => $user) {
            $data['data'][$id]['action'] = '<div class="btn-group" role="group" aria-label="Actions">' .
                                                '<button type="button" class="btn btn-warning" onclick="userService.open_edit_user_modal('. $user['id'] .')">Edit</button>' .
                                                '<button type="button" class="btn btn-danger" onclick="userService.delete_user('. $user['id'] .')">Delete</button>' .
                                            '</div>';
        }

        Flight::json([
            'draw' => $params['draw'],
            'data' => $data['data'],
            'recordsFiltered' => $data['count'],
            'recordsTotal' => $data['count'],
            'end' => $data['count']
        ], 200);
    });

    /**
     * @OA\Post(
     *      path="/users/add",
     *      tags={"users"},
     *      summary="Add user data to the database",
     *      @OA\Response(
     *           response=200,
     *           description="user data, or exception if user is not added properly"
     *      ),
     *      @OA\RequestBody(
     *          description="user data payload",
     *          @OA\JsonContent(
     *              required={"username","","email"},
     *              @OA\Property(property="id", type="string", example="1", description="user ID"),
     *              @OA\Property(property="username", type="string", example="Some first name", description="user first name"),
     *              @OA\Property(property="", type="string", example="Some last name", description="user last name"),
     *              @OA\Property(property="email", type="string", example="example@example.com", description="user email address"),
     *              @OA\Property(property="created_at", type="string", format="date", example="2024-04-29", description="user email address"),
     *              @OA\Property(property="password", type="string", example="some_secret_password", description="user password")
     *          )
     *      )
     * )
     */
    Flight::route('POST /add', function() {
        $payload = Flight::request()->data->getData();

        if($payload['username'] == NULL || $payload['username'] == '') {
            Flight::halt(500, "Username field is missing");
        }

       
        $user = Flight::get('user_service')->add_user($payload);
        

        Flight::json(['message' => "You have successfully added the user", 'data' => $payload]);
    });

    /**
     * @OA\Delete(
     *      path="/users/delete/{user_id}",
     *      tags={"users"},
     *      summary="Delete user by id",
     *      @OA\Response(
     *           response=200,
     *           description="Deleted user data or 500 status code exception otherwise"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="user_id", example="1", description="user ID")
     * )
     */
    Flight::route('DELETE /delete/@user_id', function($user_id) {
        if($user_id == NULL || $user_id == '') {
            Flight::halt(500, "You have to provide valid user id!");
        }

        Flight::get('user_service')->delete_user_by_id($user_id);
        Flight::json(['message' => 'You have successfully deleted the user!'], 200);
    });

    /**
     * @OA\Get(
     *      path="/users/{user_id}",
     *      tags={"users"},
     *      summary="Get user by id",
     *      @OA\Response(
     *           response=200,
     *           description="user data, or false if user does not exist"
     *      ),
     *      @OA\Parameter(@OA\Schema(type="number"), in="path", name="user_id", example="1", description="user ID")
     * )
     */
    Flight::route('GET /@user_id', function($user_id) {
        $user = Flight::get('user_service')->get_user_by_id($user_id);

        Flight::json($user, 200);
    });
});