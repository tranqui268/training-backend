<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getAll(){
        try {
            $users =  $this->userRepo->getAll();
            return response()->json([
                'status' => true,
                'message' => 'Get all users successfully',
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to get users',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
