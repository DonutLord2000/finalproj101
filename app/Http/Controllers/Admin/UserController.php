<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $sortColumn = $request->get('sort', 'name'); 
        $sortDirection = $request->get('direction', 'asc'); 

        $validSortColumns = ['name', 'email', 'role'];
        $validSortDirections = ['asc', 'desc'];

        if (!in_array($sortColumn, $validSortColumns) || !in_array($sortDirection, $validSortDirections)) {
            $sortColumn = 'name';
            $sortDirection = 'asc';
        }

        $search = $request->get('search');

        $users = User::when($search, function ($query) use ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
            });
        })
        ->orderBy($sortColumn, $sortDirection)
        ->get();

        if ($request->ajax()) {
            return view('admin.users.partials.table_rows', compact('users'))->render();
        }

        return view('admin.users.index', compact('users', 'sortColumn', 'sortDirection', 'search'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
        ]);

        $this->activityLogService->log('user', 'Created new user: ' . $user->name);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string',
        ]);

        $user->update($validatedData);

        $this->activityLogService->log('user', 'Updated user: ' . $user->name);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $this->activityLogService->log('user', 'Deleted user: ' . $user->name);

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }
}