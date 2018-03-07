<?php

if (!function_exists('arrayToObject')) {
    /**
     * 数组转换对象
     *
     * @param $e 数组
     * @return object|void
     */
    function arrayToObject($e)
    {

        if (gettype($e) != 'array') return;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'array' || getType($v) == 'object')
                $e[$k] = (object)arrayToObject($v);
        }
        return (object)$e;
    }
}

if (!function_exists('objectToArray')) {
    /**
     * 对象转换数组
     *
     * @param $e StdClass对象实例
     * @return array|void
     */
    function objectToArray($e)
    {
        $e = (array)$e;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'resource') return;
            if (gettype($v) == 'object' || gettype($v) == 'array')
                $e[$k] = (array)objectToArray($v);
        }
        return $e;
    }
}

if (!function_exists('admin_toastr')) {

    /**
     * Flash a toastr message bag to session.
     *
     * @param string $message
     * @param string $type
     * @param array $options
     *
     * @return string
     */
    function admin_toastr($message = '', $type = 'success', $options = [])
    {

        $toastr = new \Illuminate\Support\MessageBag(get_defined_vars());

        \Illuminate\Support\Facades\Session::flash('toastr', $toastr);
    }
}

if (!function_exists('costFormat')) {
    /**
     * @param $num
     * @return float
     */
    function costFormat($num)
    {
        $num = round($num, 2);
        return $num;
    }
}
?>