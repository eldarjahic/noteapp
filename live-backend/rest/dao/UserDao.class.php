<?php

require_once __DIR__ . '/BaseDao.class.php';

class UserDao extends BaseDao {
    public function __construct() {
        parent::__construct('users');
    }
    public function add_user($user){
        /* 
        $query = "INSERT INTO users (username, last_name, created_at, email)
                  VALUES(:username, :last_name, :created_at, :email)";
        $statement = $this->connection->prepare($query);
        $statement->execute($user);
        $user['id'] = $this->connection->lastInsertId();
        return $user;
        */
        // $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        
        
        return $this->insert('users', $user);
    }

    public function count_users_paginated($search) {
        $query = "SELECT COUNT(*) AS count
                  FROM users
                  WHERE LOWER(username) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(last_name) LIKE CONCAT('%', :search, '%') OR
                        LOWER(email) LIKE CONCAT('%', :search, '%');";
        return $this->query_unique($query, [
            'search' => $search
        ]);
    }

    public function get_users_paginated($offset, $limit, $search, $order_column, $order_direction) {
        $query = "SELECT *
                  FROM users
                  WHERE LOWER(username) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(last_name) LIKE CONCAT('%', :search, '%') OR
                        LOWER(email) LIKE CONCAT('%', :search, '%')
                  ORDER BY {$order_column} {$order_direction}
                  LIMIT {$offset}, {$limit}";
        return $this->query($query, [
            'search' => $search
        ]);
    }

    public function delete_user_by_id($id) {
        $query = "DELETE FROM users WHERE id = :id";
        $this->execute($query, [
            'id' => $id
        ]);
    }

    public function get_user_by_id($user_id){
        return $this->query_unique(
            "SELECT *, DATE(created_at) as created_at FROM users WHERE id = :id", 
            [
                'id' => $user_id
            ]
        );
    }

    public function edit_user($id, $user) {
        $query = "UPDATE users SET username = :username, email = :email
                  WHERE id = :id";
        $this->execute($query, [
            'username' => $user['username'],
            'email' => $user['email'],
            'id' => $id
        ]);
    }

    public function get_all_users(){
        $query = "SELECT *
                  FROM users;";
        return $this->query($query, []);
    }
}