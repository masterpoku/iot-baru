<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_name', 'device_code', 'ip'];

    // Define the fields() method
    public static function fields()
    {
        return [
            'device_name' => 'Device Name',
            'device_code' => 'Device Code',
            'ip' => 'IP Address',
            // Add other fields as needed
        ];
    }
}
