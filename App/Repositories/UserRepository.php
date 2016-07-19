<?php
/**
 * User: Sephy
 * Date: 12/07/2016
 * Time: 11:46.
 */
namespace App\Repositories;

use Core\Lib\Repository;

class UserRepository extends Repository
{
    public function __construct()
    {
        $this->model = $this->setModel('User');
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function findByEmail($email)
    {
        return $this->model->where([
            'email' => $email,
        ])->first();
    }

    public function all()
    {
        return $this->model->all();
    }
}
