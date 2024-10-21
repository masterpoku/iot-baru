<?php
use Carbon\Carbon;


if (!function_exists('getList')) {
    function getList($modelName, $label = 'name', $conditions = [], $prepend = false)
    {
        $list = collect([]);
        try {
            $modelName = \Illuminate\Support\Str::studly($modelName);
            /** @var \Illuminate\Database\Eloquent\Model $Model */
            try {
                $Model = app("App\\$modelName");
            } catch (\Exception $e) {
                $Model = app("App\\Models\\$modelName");
            }

            if (is_array($conditions)) {
                foreach ($conditions as $key => $value) {
                    $Model = $Model->where($key, $value);
                }
            }

            if (preg_match('/\./', $label)) {
                $labels = explode('.', $label);
                $Model = $Model->select([
                    'id',
                    DB::raw('CONCAT_WS(" ", ' . implode(',', $labels) . ') AS text')
                ]);
            } else {
                $Model = $Model->select([
                    'id',
                    DB::raw("$label AS text")
                ]);
            }

            $list = $Model->pluck('text', 'id');
            if ($prepend) $list = $list->prepend('-- Select --', '');

        } catch (\Exception $e) {

        }
        return $list->toArray();
    }
}

if (!function_exists('timestamp')) {

    /**
     * Check date is valid or not
     *
     * @param string $value date value
     * @return boolean|\Carbon\Carbon
     */
    function timestamp($date = null)
    {
        return is_date($date ? $date : date('Y-m-d H:i:s'))->getTimestamp();
    }
}

if (!function_exists('reset_increment')) {
    /**
     *
     */
    function reset_increment($tables = '', $dbName = null)
    {
        $dbName = $dbName ? $dbName : \DB::getDatabaseName();
        $resets = [];
        $tables = is_string($tables) ? func_get_args() : $tables;
        if (!$tables)
            $tables = collect(\DB::select('SHOW TABLES'))->pluck("Tables_in_$dbName")->toArray();
        foreach ((array)$tables as $table) {
            try {
                $isExist = \DB::statement("ALTER TABLE $table AUTO_INCREMENT = 1");
            } catch (\Exception $ex) {
                $isExist = false;
            }
            $resets[$table] = $isExist;
        }
        return $resets;
    }
}

if (!function_exists('ajax_response')) {
    /**
     * @param array $set_response
     * @param string $middleware
     * @return array
     */
    function ajax_response($set_response = [], $middleware = '')
    {
        $permission = !$middleware;
        if (!strcasecmp($middleware, 'auth'))
            $permission = \Auth::check();
        elseif (!strcasecmp($middleware, 'acl'))
            $permission = !!\Auth::user()->hasAccess();
        $response = [
            'error' => true,
            'permission' => $permission,
            'data' => [],
            'message' => !$permission ? trans('main.permission.denied') : trans('main.error'),
            'redirect' => false
        ];

        return $set_response && is_array($set_response) ? array_merge($response, $set_response) : $response;
    }
}

if (!function_exists('number2word')) {
    /**
     * @param $num
     * @return string
     * @internal param array $set_response
     * @internal param string $middleware
     */
    function number2word($num)
    {
        $word = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        if ($num < 12) return " " . $word[$num]; else
            if ($num < 20) return number2word($num - 10) . "belas"; else
                if ($num < 100) return number2word($num / 10) . " puluh" . number2word($num % 10); else
                    if ($num < 200) return " seratus" . number2word($num - 100); else
                        if ($num < 1000) return number2word($num / 100) . " ratus" . number2word($num % 100); else
                            if ($num < 2000) return " seribu" . number2word($num - 1000); else
                                if ($num < 1000000) return number2word($num / 1000) . " ribu" . number2word($num % 1000); else
                                    if ($num < 1000000000) return number2word($num / 1000000) . " juta" . number2word($num % 1000000);
    }
}

if (!function_exists('elapsed')) {
    /**
     *
     */
    function elapsed()
    {
        return number_format((microtime(true) - LARAVEL_START), 3);
    }
}

if (!function_exists('ee')) {
    function ee()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-XSRF-TOKEN, X-Requested-With');
        call_user_func_array('dd', func_get_args());
    }
}

if (!function_exists('compile_query')) {
    function compile_query($sql, array $bindings = [])
    {
        return \Helper::compileQuery($sql, $bindings);
    }
}

if (!function_exists('number_format_short')) {
    function number_format_short($n, $precision = 1)
    {
        return \Helper::numberFormatShort($n, $precision);
    }
}

if (!function_exists('timestamp')) {
    function timestamp($date = null)
    {
        return Carbon::parse($date ?: now())->getTimestamp();
    }
}

if (!function_exists('is_date')) {
    function is_date($date)
    {
        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('to_rupiah')) {
    /**
     * convert numeric to rupiah currency format
     *
     */
    function to_rupiah($number, $reverse = false)
    {
        return $reverse === true ? to_numeric(str_replace(',', '.', preg_replace('/([^0-9\,])/i', '', $number))) : (is_numeric($number) ? number_format($number, '0', ',', '.') : $number);
    }
}

if (!function_exists('to_currency')) {
    /**
     * @param $number
     * @param int $point
     * @return string
     */
    function to_currency($number, $point = 2)
    {
        $args = func_get_args();
        $confPoint = config('decimal_point', null);
        if(!isset($args[1]) && $confPoint !== null)
            $point = $confPoint;
        return is_numeric($number) ? point(number_format($point > 0 ? $number : intval($number), $point)) : $number;
    }
}

if (!function_exists('to_numeric')) {
    /**
     * @param $source
     * @param string $delimeter
     * @param int $point
     * @return mixed
     */
    function to_numeric($source, $delimeter = '.', $point = 2)
    {
        $confPoint = config('decimal_point', null);
        if(!isset($args[2]) && $confPoint !== null)
            $point = $confPoint;
        $isMinus = preg_match('/^-/', $source);
        $number = ($isMinus ? '-' : '') . preg_replace("/([^0-9\\" . $delimeter . "])/i", '', $source);
        return is_numeric($number) ? point($point > 0 ? number_format($number, $point, '.', '') : intval($number)) + 0 : $source;
    }
}

if (!function_exists('point')) {
    /**
     * @param $input
     * @param string $delimeter
     * @return mixed
     */
    function point($input, $delimeter = '.')
    {
        return preg_match("/\\$delimeter/", $input) ? rtrim(rtrim((string)$input, "0"), $delimeter) : $input;
    }
}

if(!function_exists('array_list')) {
    function array_list($items, $childrens = 'childrens', $text = 'name')
    {
        $tmp = [];
        $l = 0;
        foreach ($items as $item) {
            $label = isset($item->parent->id) ? [0 => $item->parent->$text] : [];
            $label += [1 => $item->$text];
            $tmp[$item->id] = implode('/', $label);
            if ($item->$childrens->count() > 0) {
                $level = $l + 1;
                $newItem = get_childrens($item->$childrens, $level, $childrens, $text);
                $tmp += $newItem;
            }
        }

        return $tmp;
    }
}

if(!function_exists('get_childrens')) {
    function get_childrens($parent, $level, $childrens = 'childrens', $text = 'name', $orderBy = null)
    {
        // dd($level);
        $tmp = [];
        foreach ($parent as $child) {
            $label = isset($child->parent->id) ? [0 => $child->parent->$text] : [];
            $label += [1 => $child->$text . ' -- ' . $level];

            $tmp[$child->id] = dub_char('--', $level) . ' ' . ($child->$text);
            //dd($tmp);
            if (count($child->$childrens) > 0) {
                if ($orderBy)
                    $newChild = get_childrens($child->$childrens()->orderBy($orderBy)->get(), $level + 1, $childrens, $text, $orderBy);
                else
                    $newChild = get_childrens($child->$childrens, $level + 1);
                $tmp += $newChild;
            }
        }
        return $tmp;
    }
}

if(!function_exists('dub_char')) {
    function dub_char($char, $duplicate = 1)
    {
        $tmp = '';
        for ($i = 0; $i < $duplicate; $i++) {
            $tmp .= $char;
        }
        return $tmp;
    }
}

if (!function_exists('indonesiaToDatabaseDateFormat')) {

    /**
     * Change date format from indonesia to english
     *
     * @param array $matches
     * @return string
     */
    function indonesiaToEnglishDateFormat($matches)
    {
        return sprintf('%2$s/%1$s/%3$s', $matches[1], $matches[2], $matches[3]);
    }
}

if (!function_exists('timezoneToDatabaseFormat')) {

    /**
     * Convert timezone date format to database date format
     *
     * @param string $value Date in specific timezone format
     * @return string Date in database format
     */
    function timezoneToDatabaseFormat($value)
    {
        $timezone = config('app.timezone');
        // Set database date format from timezone date format
        if ($timezone == "Asia/Jakarta") {
            $value = preg_replace_callback("#(\\d{2})/(\\d{2})/(\\d{4})#", 'indonesiaToEnglishDateFormat', $value);
        }
        // Date is valid
        $timestamp = strtotime($value);
        if (version_compare(PHP_VERSION, '5.1.0', '>=')) {
            $status = ($timestamp) ? true : false;
        } else {
            $status = ($timestamp != -1) ? true : false;
        }
        $value = ($status) ? date('Y-m-d', $timestamp) : null;
        return $value;
    }
}

if (!function_exists('databaseToTimezoneFormat')) {

    /**
     * Convert database date format to timezone date format
     *
     * @param string $value Date in database format
     * @return string Date in specific timezone format
     */
    function databaseToTimezoneFormat($value, $empty = null)
    {
        if ($value) {
            // Get valid format according to the current timezone
            $timezone = config('app.timezone');
            $format = ($timezone == "Asia/Jakarta") ? "d-m-Y" : "m/d/Y";
            // If date is valid modify database date format to current timezone date format
            $date = is_date($value);
            $value = $date ? $date->format($format) : $empty;
        }
        return $value;
    }
}

if(!function_exists('dbRaw')) {
    function dbRaw($string)
    {
        return \DB::raw($string);
    }
}

if (!function_exists('to_form_array')) {
    /**
     * @param $arr
     * @param array $formArray
     * @param string $namespace
     * @param string $separator_begin
     * @param string $separator_end
     * @return array
     */
    function to_form_array($arr, &$formArray = array(), $namespace = '', $separator_begin = '[', $separator_end = ']') {
        foreach ($arr as $key => $value) {
            $arrKey = $namespace ? "{$namespace}{$separator_begin}{$key}{$separator_end}" : $key;
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    if (is_array($v)) {
//                        $tempKey = "{$arrKey}{$separator_begin}{$k}{$separator_end}";
                        $tempKey = "{$arrKey}{$separator_begin}{$separator_end}";
                        to_form_array($v, $formArray, $tempKey);
                    } else {
                        $formArray["{$arrKey}{$separator_begin}{$k}{$separator_end}"] = $v;
                    }
                }
            } else {
                $formArray[$arrKey] = $value;
            }
        }
        return $formArray;
    }
}

if (!function_exists('getMonths')) {
    function getMonths()
    {
        $months = [];
        for ($i = 1 ; $i <= 12 ; $i++) {
            $months[$i] = date('F', strtotime("1-$i-1999"));
        }
        return $months;
    }
}

if (!function_exists('getYears')) {
    function getYears()
    {
        $years = [];
        for ($i = date('Y') - 2 ; $i <= date('Y') + 2 ; $i++) {
            $years[$i] = $i;
        }
        return $years;
    }
}

if (!function_exists('link_to_svg_editor_pisau')) {
    function link_to_svg_editor_pisau($id, $subtitle)
    {
        return url('vendors/svg-editor/build') . '?url=' . route('pisau.update-drawer-image', $id) . '&load=' . route('pisau.drawer-image.json', $id) . '&token=' . csrf_token() . '&title=PISAU&subtitle=' . $subtitle . '&unique-id=' . \Illuminate\Support\Str::random(5);
    }
}

if (!function_exists('link_to_svg_editor_plat')) {
    function link_to_svg_editor_plat($id, $subtitle)
    {
        return url('vendors/svg-editor/build') . '?url=' . route('plat.update-drawer-image', $id) . '&load=' . route('plat.drawer-image.json', $id) . '&token=' . csrf_token() . '&title=PLAT&subtitle=' . $subtitle . '&unique-id=' . \Illuminate\Support\Str::random(5);
    }
}

if (!function_exists('get_anilox_types')) {
    function get_anilox_types()
    {
        return collect([
            1 => 'Printing Anilox',
            'Art Anilox',
        ]);
    }
}

if (!function_exists('get_anilox_options')) {
    function get_anilox_options()
    {
        return collect(json_decode(json_encode([
            [
                'id' => 1,
                'type' => 1,
                'label' => '2 x 500',
                'value' => '5.5 x 400',
            ],
            [
                'id' => 2,
                'type' => 1,
                'label' => '2.5 x 500',
                'value' => '6 x 300',
            ],
            [
                'id' => 3,
                'type' => 1,
                'label' => '3 x 500',
                'value' => '7 x 300',
            ],
            [
                'id' => 4,
                'type' => 1,
                'label' => '3.5 x 500',
                'value' => '8 x 260',
            ],
            [
                'id' => 5,
                'type' => 1,
                'label' => '4 x 500',
                'value' => '9 x 240',
            ],
            [
                'id' => 6,
                'type' => 1,
                'label' => '4.5 x 500',
                'value' => '10 x 160',
            ],
            [
                'id' => 7,
                'type' => 1,
                'label' => '5 x 420',
                'value' => '15 x 120',
            ],
            [
                'id' => 8,
                'type' => 2,
                'label' => '30 x 80',
                'value' => '80 x 30',
            ],
            [
                'id' => 9,
                'type' => 2,
                'label' => 'SPOT UV',
                'value' => 'WHITE',
            ],
        ])));
    }
}
