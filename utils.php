<?php

/*=======================
 * helper functions
=======================*/
function _json_encode($arr){
    header("Content-Type: application/json");
    echo json_encode($arr);
};

function GetIntParam($val, $arr){
    if (( ! array_key_exists($val, $arr))
            || ( ! is_numeric($arr[$val]))
            || (($val = intval($arr[$val])) > 0 && ! is_int($val))
    ){
        return -1;
    } else {
        return $val;
    }
};

function GetStrParam($val, $arr, $min_len = 1, $max_len = 99999, $reg_exp = ""){
    if (( ! array_key_exists($val, $arr))
            || (($val = $arr[$val]) == "")
    ){
        return "";
    }
    $val = strip_tags(strtr($val, array(','=>'',';'=>'','='=>'','*'=>'')));
    $len = mb_strlen($val, mb_detect_encoding($val));
    if (($len < $min_len)
            || ($len > $max_len)
            || (($reg_exp != "" && preg_match($reg_exp, $val) == 0))
    ){
        return "";
    } else {
        return $val;
    }
};

function dump_exit($var){
    var_dump($var);
    exit;
}

/*=======================
 * page handler
=======================*/
function ShowTemplatePage($page){
    global $user,$data; //传递数据对象
    $tpl = "tpl\\".$page.".tpl";
    include $tpl;
}

function ShowErrorPage($code, $description, $notice){
    global $user,$data;
    header('HTTP/1.1 '.$code.' '.$description);
    header('status: '.$code.' '.$description);
    if (isset($notice)){
        $data['title'] = $code.' '.$description;
        $data['description'] = $notice;
        ShowTemplatePage("_error");    
    }
    exit;
}

function Show301(){
    ShowErrorPage(301, 'Permanently Moved');
}

function Show302(){
    ShowErrorPage(301, 'Temporarily Moved');
}

function Show404(){
    ShowErrorPage(404, 'Not Found', "页面当前无法访问");
}

/*=======================
 * i18n / translation
=======================*/
function _T($str){
    return $str;
}

/*=======================
 * Cookie & Session
=======================*/
function GetCookieExpire(){
    return time() + (7 * 24 * 60 * 60);
}

/*=======================
 * page compress
=======================*/
function compress($buffer) {
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  '), '', $buffer);
    $buffer = preg_replace('/ ?([{},:;|=]|==) ?/', '$1', $buffer);
    return $buffer;
  }
ob_start('compress');

?>