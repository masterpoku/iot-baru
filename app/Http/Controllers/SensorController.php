<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SensorController extends Controller
{


    public function index (Request $request)
    {
        if($request->ajax())
        {
            $data = Sensor::select(
                'sensors.*'
            )->orderBy('id', 'DESC')->get();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('actions', function($row){
                return '<div class="btn-group">
                            <button class="btn btn-sm btn-danger" data-id="'.$row['id'].'" id="deleteSensorBtn">Delete</button>
                        </div>';
                })
                ->addColumn('checkbox', function($row){
                    // return '<input type="checkbox" name="country_checkbox" data-id="'.$row['id'].'"><label></label>';
                    return '
                        <div class="custom-control custom-control-danger custom-checkbox">
                            <input type="checkbox" name="country_checkbox" class="custom-control-input" id="colorCheck'.$row['id'].'"/>
                            <label class="custom-control-label" for="colorCheck'.$row['id'].'"></label>
                        </div>
                    ';
                })
                ->rawColumns(['checkbox','actions'])
                ->removeColumn('id')
                ->make(true);

        }
        return view('pages.monitoring.index', get_defined_vars());
    }


    public function insertSensor(Request $request)
    {
        $currentUser = Auth::user();
        $id_device = $currentUser->id_device;
        $id_user = $currentUser->id;
        if ($request->id_device == $id_device) {
            DB::table('sensors')->insert([
                'id_user'  => $id_user,
                'tegangan' => $request->tegangan,
                'ph'       => $request->ph,
                'temp'     => $request->temp,
            ]);
            return response()->json("Mantap, data inserted successfully");
        }
        return response()->json("Device ID does not match", 400);
    }
    
}
