<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        $users = User::all();
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[0];
        });
        return view('pages.roles.index', get_defined_vars());
    }

    public function data()
    {
        $users = User::with('permissions')->get();
        return DataTables::of($users)
            ->addColumn('permissions', function ($user) {
                if ($user->permissions->isEmpty()) {
                    return '<span class="badge badge-danger">No Permissions</span>';
                }
                return $user->permissions->map(function ($permission) {
                    return '<span class="badge badge-success mt-1">' . htmlspecialchars($permission->name) . '</span>';
                })->implode(' ');
            })
            ->rawColumns(['permissions'])
            ->make(true);
    }
    
    public function getUserPermissions(Request $request) {
        $userId = $request->userId;
        $user = User::find($userId);
        if ($user->permissions == null) {
            return response()->json([
                'success' => true,
                'permissions' => [] 
            ]);
        }
        $permissions = $user->permissions->pluck('id');
        return response()->json([
            'success' => true,
            'permissions' => $permissions
        ]);
        dd($permissions);
        return response()->json(['permissions' => $permissions]);
    }

    public function assignPermissionsAjax(Request $request) {
        $user = User::find($request->user_id);
        $user->permissions()->sync($request->permissions);
    
        return response()->json(['success' => true]);
    }
}
