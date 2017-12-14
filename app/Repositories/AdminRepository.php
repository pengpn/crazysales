<?php
namespace App\Repositories;


use App\Models\User;

class AdminRepository
{
    public function geAllAdmin()
    {
        return User::all();
    }
}