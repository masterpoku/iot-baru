<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function create(Request $request)
    {
        $permission = Permission::orderBy('name', 'ASC')->get();
        // dd( $permission );
        if ($request->isMethod('put')) return $this->save($request);
        return view('pages.permission.create', get_defined_vars());
    }

    public function save(Request $request, $id = null)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'options' => 'nullable|array',
            ]);
            $name = $validatedData['name'];
            $options = $validatedData['options'] ?? ['list', 'create', 'edit', 'delete'];

            foreach ($options as $option) {
                $permissionName = $name . '-' . $option;
                $permission = new Permission();
                $permission->name = $permissionName;
                $permission->guard_name = 'web';
                $permission->save();
            }
            return $request->ajax() ? response([
                'message' => 'Data saved',
                'redirect' => route('permission.create'),
                'data' => $permission,
            ]) : redirect(route('permission.create'))->with('success', 'Data saved!');
        } catch (\Exception $ex) {
            dd($ex);
        }
    }
    public function destroy(Request $request)
    {
        Permission::find($request->id)->delete();
        return redirect()->route('permission.create')
            ->with('success', 'List Permission deleted successfully');
    }
}
