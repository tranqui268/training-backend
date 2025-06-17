<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function index(){
        return Inertia::render('UserManager');
    }

    public function getAll(){
        Log::info("Fetching all users from the repository", ['auth_user' => auth()->user()]);
        try {
            $users =  $this->userRepo->getAll();
            Log::info("Fetched all users successfully", ['count' => count($users)]);
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
