<?php

require_once __DIR__ . '/../dao/UserDao.class.php';

class UserService {
    private $user_dao;
    public function __construct() {
        $this->user_dao = new UserDao();
    }
    public function add_user($user){
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        return $this->user_dao->add_user($user);
    }
    public function get_users_paginated($offset, $limit, $search, $order_column, $order_direction){
        $count = $this->user_dao->count_users_paginated($search)['count'];
        $rows = $this->user_dao->get_users_paginated($offset, $limit, $search, $order_column, $order_direction);

        return [
            'count' => $count,
            'data' => $rows
        ];
    }
    public function delete_user_by_id($user_id) {
        $this->user_dao->delete_user_by_id($user_id);
    }

    public function get_user_by_id($user_id) {
        return $this->user_dao->get_user_by_id($user_id);
    }

    public function edit_user($user) {
        $id = $user['id'];
        unset($user['id']);

        $this->user_dao->edit_user($id, $user);
    }

    public function get_all_users(){
        return $this->user_dao->get_all_users();
    }
}