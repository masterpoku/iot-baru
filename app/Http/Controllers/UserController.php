<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Spatie\Permission\Models\Role;
use Scaffolding\Traits\ScaffoldingTrait;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use ScaffoldingTrait;
    public function _vars()
    {
        return [
            'roles' => Role::pluck('name','name'),
        ];
    }



    public function __construct()
    {
        extract($this->_vars());
        $prefix = 'user';
        $title = 'User';
        $model = new User();
        $this->setConfig([
            'model' => $model,
            'prefix' => $prefix,
            'title' => $title,
        ]);
        $this->scaffolding()->datatableSet([
            'checkbox' => false,
            'timestamp' => false,
            'dom' => '<"top display-flex">lrt<"bottom"p>',
            'viewToolbar' => view('scaffolding::index-toolbar'),
            'lengthMenu' => [10, 30, 50, 100, 200],
            'order' => [0, 'asc'],
            'actions' => ['edit','view'],
            'withQuery' => User::select([
                'users.*',
                'bb.name as nama_akses',
                'bb.guard_name'
            ])
            ->leftJoin('model_has_roles as aa','aa.model_id','=','users.id')
            ->leftJoin('roles as bb','bb.id','=','aa.role_id')
        ])
            ->datatableColumnUnset([], true)
            ->datatableColumnSet([
                'id' => [
                    'title' => 'ID',
                    'searchPlaceHolder' => '',
                ],
                'email' => [
                    'title' => 'email',
                    'searchPlaceHolder' => '',
                ],
                'nama_akses' => [
                    'title' => 'Roles',
                    'searchPlaceHolder' => '',
                ],
                'guard_name' => [
                    'title' => 'Guard Name',
                    'formatter' => function ($model) {
                        return '<span class="btn btn-sm btn-primary">'.$model->guard_name.'</span>';
                    },
                    'searchPlaceHolder' => '',
                ],
            ]);
    }

    public function create(UserRequest $request) 
    {
        extract($this->_vars());
        if($request->isMethod('put')) return $this->save($request);
        return view('pages.user.create',get_defined_vars());
    }

    public function edit(UserRequest $request, $id)
    {
        extract($this->_vars());
        if($request->isMethod('patch')) return $this->save($request, $id);

        $model = User::findOrFail($id);
        return view('pages.user.edit',get_defined_vars());
    }

    public function save(UserRequest $request, $id = null)
    {
        try {
            DB::beginTransaction();
            $model = User::findOrNew($id);
            $data = $request->all();
            if ($request->has('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }
            $model->fill($data);
            $model->save();
            if ($request->has('role')) {
                $role = $request->role;
                if (Role::where('name', $role)->where('guard_name', 'web')->exists()) {
                    $model->syncRoles([$role]);
                } else {
                    throw new \Exception("Role '{$role}' does not exist for guard 'web'.");
                }
            }
    
            DB::commit();
            
            return $request->ajax() ? response([
                'message' => 'Data saved',
                'redirect' => route('user.index'),
                'data' => $model,
            ]) : redirect(route('user.index'))->with('success', 'Data saved!');
        } catch (\Exception $ex) {
            DB::rollback();
            dd($ex);
        }
    }
}
