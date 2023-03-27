<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'fullname'];

    public function getUsername($username)
    {
        $builder = $this->table('users');
        $data = $builder->where('username', $username)->first();
        if (!$data) {
            throw new Exception("Data tidak ditemukan, silahkan regist terlebih dahulu");
        }
        return $data;
    }
}
