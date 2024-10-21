<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Scaffolding\Traits\ScaffoldingTrait;
use App\Models\Device;
use Illuminate\Support\Facades\Hash;

class DeviceController extends Controller
{
    use ScaffoldingTrait;
    public function _vars()
    {
        return [
            'roles' => Role::pluck('name', 'name'),
        ];
    }



    public function __construct()
    {
        extract($this->_vars());
        $prefix = 'device';
        $title = 'Device';
        $model = new Device();
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
            'actions' => ['edit', 'view'],
            'withQuery' => Device::select([
                'devices.*',
            ])
        ])
            ->datatableColumnUnset([], true)
            ->datatableColumnSet([
                'id' => [
                    'title' => 'ID',
                    'searchPlaceHolder' => '',
                ],
                'users_id' => [
                    'title' => 'User',
                    'searchPlaceHolder' => '',
                ],
                'device_name' => [
                    'title' => 'Device Name',
                    'searchPlaceHolder' => '',
                ],
                'device_code' => [
                    'title' => 'Device Code',
                    'searchPlaceHolder' => '',
                ],
                'mode' => [
                    'title' => 'Mode',
                    'formatter' => function ($model) {
                        return '<span class="btn btn-sm btn-' . ($model->mode == 1 ? 'success' : 'danger') . '">' . ($model->mode == 1 ? 'Online' : 'Offline') . '</span>';
                    },
                    'searchPlaceHolder' => '',
                ],
                'ip' => [
                    'title' => 'IP',
                    'searchPlaceHolder' => '',
                ],
                'status_id' => [
                    'title' => 'Status',
                    'searchPlaceHolder' => '',
                ],
            ]);
    }

    public function create(Request $request)
    {
        extract($this->_vars());
        if ($request->isMethod('put')) return $this->save($request);
        return view('pages.device.create', get_defined_vars());
    }

    public function edit(Request $request, $id)
    {
        extract($this->_vars());
        if ($request->isMethod('patch')) return $this->save($request, $id);

        $model = Device::findOrFail($id);
        return view('pages.device.edit', get_defined_vars());
    }

    public function save(Request $request, $id = null)
    {
        try {
            DB::beginTransaction();
            $model = Device::findOrNew($id);
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
                'redirect' => route('device.index'),
                'data' => $model,
            ]) : redirect(route('device.index'))->with('success', 'Data saved!');
        } catch (\Exception $ex) {
            DB::rollback();
            return $request->ajax() ? response([
                'message' => 'An error occurred while saving data',
                'error' => $ex->getMessage(),
            ], 500) : redirect(route('device.index'))->with('error', 'An error occurred while saving data');
        }
    }
}
