<?php

require_once __DIR__ . '/../services/AuthService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('auth_service', new AuthService());

Flight::group('/auth', function() {
    
    /**
     * @OA\Post(
     *      path="/auth/login",
     *      tags={"auth"},
     *      summary="Login to system using email and password",
     *      @OA\Response(
     *           response=200,
     *           description="user data and JWT"
     *      ),
     *      @OA\RequestBody(
     *          description="Credentials",
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", example="example@example.com", description="user email address"),
     *              @OA\Property(property="password", type="string", example="some_password", description="user password")
     *          )
     *      )
     * )
     */
    Flight::route('POST /login', function() {
        try {
            $payload = Flight::request()->data->getData();

            $user = Flight::get('auth_service')->get_user_by_email($payload['email']);


            if(!isset($user) || !password_verify($payload['password'], $user['password'])){
                $hased_pass = password_hash('123456789', PASSWORD_DEFAULT);

                Flight::json([
                    'message' => password_verify('123456789', $hased_pass),
                    'hased_pass' => $hased_pass,
                    'user_pass' => $user['password'],
                ], 401);
                return;
                //Flight::halt(500, "Invalid username or password");
            }

            unset($user['password']);
            
            $jwt_payload = [
                'user' => $user,
                'iat' => time(),
                // If this parameter is not set, JWT will be valid for life. This is not a good approach
                'exp' => time() + (60 * 60 * 24) // valid for day
            ];

            $token = JWT::encode(
                $jwt_payload,
                Config::JWT_SECRET(),
                'HS256'
            );

            Flight::json(
                array_merge($user, ['token' => $token])
            );
        } catch (\Exception $e) {
            Flight::halt(400, $e->getMessage());
        }
    });

    /**
     * @OA\Post(
     *      path="/auth/logout",
     *      tags={"auth"},
     *      summary="Logout from the system",
     *      security={
     *          {"ApiKey": {}}   
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Success response or exception if unable to verify jwt token"
     *      ),
     * )
     */
    Flight::route('POST /logout', function() {
        try {
            $token = Flight::request()->getHeader("Authentication");
            if(!$token)
                Flight::halt(401, "Missing authentication header");

            $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

            Flight::json([
                'jwt_decoded' => $decoded_token,
                'user' => $decoded_token->user
            ]);
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    });
});