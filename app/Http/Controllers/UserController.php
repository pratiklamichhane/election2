<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $search = request()->query('search');

        $users = User::with('kyc')
            ->where('role', 'user')
            ->when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%")
                      ->orWhereHas('kyc', function ($q) use ($search) {
                          $q->where('nagrita_number', 'LIKE', "%{$search}%")
                            ->orWhere('verification_status', 'LIKE', "%{$search}%")
                            ->orWhere('district', 'LIKE', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(5);

        return view('users.index', compact('users'));
    }

    public function approve(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()
            ->route('users.index')
            ->with('success', "User {$status} successfully.");
    }
}
