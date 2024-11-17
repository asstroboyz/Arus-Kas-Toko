<?php

namespace App\Controllers;

use Myth\Auth\Models\UserModel;

class Auth extends BaseController
{
    public function __construct()
    {
        $this->db = \Config\Database::connect();

    }
    public function index()
    {
        
        return view('login');
    }

    public function reset()
    {
        return view('Auth/Reset');
    }
    public function reset_password()
    {
        $username = $this->request->getPost('username');
        $passwordbaru = $this->request->getPost('password');
       

      
        $builder = $this->db->table('users');
        $builder->where('username', $username);
        $query = $builder->get()->getRow();
        // dd($query->id);

        $users = new UserModel();
        $entity = new \Myth\Auth\Entities\User();

        $entity->setPassword($passwordbaru);
        $hash = $entity->password_hash;
        $users->update($query->id, ['password_hash' => $hash]);
        session()->setFlashdata('message', 'Password berhasil Diubah');
        // return redirect()->route('/login')->with('message');
        return redirect()->to('/login');
    }

    // public function user()
    // {
    //     return view('auth/index');
    // }
}
