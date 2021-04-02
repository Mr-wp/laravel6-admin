<?php
/**
 * 自定义函数库
 * @filename  helpers.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2017-8-9 17:08:32
 */


/**
 * Models类 快捷函数
 * @param $classname
 * @param string $path
 * @return mixed
 * @throws Exception
 * @return \Illuminate\Database\Eloquent\Model
 */
function M($classname, $path = 'Models')
{
    return load_class($classname, $path);
}


/**
 * 服务类快捷函数
 * @param $classname
 * @param string $path
 * @return \App\Services\Service;
 */
function S($classname, $path = 'Services')
{
    return load_class($classname . 'Service', $path);
}


/**
 * 加载类,单例模式实例化
 * @param $classname
 * @param $path
 * @return mixed
 * @throws Exception
 */
function load_class($classname, $path)
{
    $classname = ucfirst($classname);
    $class = "\\App\\" . $path . "\\" . $classname;
    if (!class_exists($class)) {
        throw new \Exception('找不到文件' . $class);
    }
    static $classes = [];
    $key = md5($class);
    if (!isset($classes[$key])) {
        //$classes[$key] = (new ReflectionClass($class))->newInstance();
        $classes[$key] = new $class;
    }
    return $classes[$key];
}


function arr2str($arr, $str = '')
{

    if (is_array($arr)) {
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                return arr2str($v, $str);
            } else {
                $str .= "<p>{$k}-->{$v}</p>";
            }
        }
    }
    return $str;
}


/**
 * 下拉选择框
 */
function form_select($array = [], $id = 0, $str = '', $default_option = '',$select2_name='')
{
    $string = '<select ' . $str . '>';
    $default_selected = (empty($id) && $default_option) ? 'selected' : '';
    if ($default_option){
        $string .= "<option value='' $default_selected>$default_option</option>";
    }

    $ids = array();
    if (isset($id)) {
        $ids = explode(',', $id);
    }
    if(is_array($array)){
        foreach ($array as $key => $value) {
            $selected = in_array($key, $ids) ? 'selected' : '';
            $string .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
        }
    }
    $string .= '</select>';
    if($select2_name){
        $string.="<script>$('select[name=\"".$select2_name."\"]').select2();</script>";
    }
    return $string;
}

/**
 * 复选框
 *
 * @param $array 选项 二维数组
 * @param $id 默认选中值，多个用 '逗号'分割
 * @param $str 属性
 * @param $defaultvalue 是否增加默认值 默认值为 -99
 * @param $width 宽度
 * @return string
 */
function form_checkbox($array = array(), $id = '', $str = '', $defaultvalue = '', $width = 5, $field = '')
{
    $string = '';
    $id = trim($id);
    if ($id != '')
        $id = strpos($id, ',') ? explode(',', $id) : array($id);
    if ($defaultvalue)
        $string .= '<input type="hidden" ' . $str . ' value="-99">';
    $i = 1;
    foreach ($array as $key => $value) {
        $key = trim($key);
        $checked = ($id && in_array($key, $id)) ? 'checked' : '';
        if ($width)
            $string .= '<label class="checkbox-inline" >';
        $string .= '<input type="checkbox" ' . $str . ' id="' . $field . '_' . $i . '" ' . $checked . ' value="' . $key . '"> ' . $value;
        if ($width)
            $string .= '</label>';
        $i++;
    }
    return $string;
}

/**
 * 单选框
 *
 * @param $array 选项 二维数组
 * @param $id 默认选中值
 * @param $str 属性
 */
function form_radio($array = array(), $id = 0, $str = '', $width = 1, $field = '')
{
    $string = '';
    foreach ($array as $key => $value) {
        $checked = trim($id) == trim($key) ? 'checked' : '';
        if ($width)
            $string .= '<label class="ib">';
        $string .= '<input type="radio" ' . $str . 'style="margin-top:12px" id="' . $field . '_' . $key . '" ' . $checked . ' value="' . $key . '"> ' . $value.'&nbsp;&nbsp;&nbsp;&nbsp;';
        if ($width)
            $string .= '</label>';
    }
    return $string;
}
