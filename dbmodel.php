<?php

require_once('config.inc.php'); //配置文件
require_once('utils.php'); //配置文件

define("ADMIN_TYPE", "Admin");
define("USER_TYPE", "User");

class User {
    var $db;
    var $uid;//用户ID标识符
    var $nickname;//昵称
    var $email;//电子邮箱
    var $type;//用户类型
    var $enable;//是否启用

    function __construct($db){
        $this->db = $db;
        session_start();
        if (array_key_exists('ip', $_SESSION)
                && array_key_exists('browseragent', $_SESSION)
                && array_key_exists('UID', $_COOKIE)
                && array_key_exists('uid', $_SESSION)
                && $_SESSION['ip'] == $_SERVER['REMOTE_ADDR']
                && $_SESSION['browseragent'] == $_SERVER['HTTP_USER_AGENT']
                && $_SESSION['uid'] == $_COOKIE['UID']){
            $this->_SetInfo($_SESSION['uid'], $_SESSION['nickname'], $_SESSION['email'], $_SESSION['type']);
        } else {
            $this->_SetInfo(-1, _T("未登录"), "", "");
        }
    }

    function IsLogined()
    {
        return ($this->uid > -1);
    }

    function _SetInfo($uid, $nickname, $email, $type){
        $this->uid = $uid;
        $this->nickname = $nickname;
        $this->email = $email;
        $this->type = $type;
    }

    function _SetSession($uid, $nickname, $email, $type, $ip, $browseragent){
        $_SESSION['uid'] = $uid;
        $_SESSION['nickname'] = $nickname;
        $_SESSION['email'] = $email;
        $_SESSION['type'] = $type;
        $_SESSION['ip'] = $ip;
        $_SESSION['browseragent'] = $browseragent;
    }

    function _SetCookie($key, $value){
        if ($value != ""){
            setcookie($key, $value, GetCookieExpire(), "/");
        } else {
            setcookie($key, '', 0, "/");
        }
    }

    function _SetAll($uid, $nickname, $email, $type, $ip, $browseragent){
        $this->_SetCookie("UID", $uid);
        $this->_SetInfo($uid, $nickname, $email, $type);
        $this->_SetSession($uid, $nickname, $email, $type, $ip, $browseragent);
    }

    function Login($email, $password){
        if ( ! $this->IsLogined() || $this->email != $email){
            $sql = sprintf("SELECT * FROM users WHERE email='%s' AND password='%s';", $email, $password);
            $result = $this->db->Query($sql);
            if ($result->num_rows > 0){
                $ret = $result->fetch_assoc();
                $this->_SetAll($ret['uid'], $ret['nickname'], $ret['email'], $ret['type'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
                return true;
            }
            return false;
        }
        return true;
    }

    function Logout(){
        if ($this->IsLogined())
            $this->_SetAll(-1, _T("未登录"), "", "", "", "");
        return true;
    }

    function AddNewUser($nickname, $email, $type){
    }

    function ModifyUser($nickname, $email, $type){
    }

    function DeleteUser($nickname, $email, $type){
    }
}

class LoginSession {
    var $uid;
    var $browseragent;
    var $ip;

    function __construct($uid, $agent, $ip){
        $this->uid = $uid;
        $this->browseragent = $agent;
        $this->ip = $ip;
    }
}

class Post {
    var $pid;
    var $title;
    var $pic_large;
    var $pic_small;
    var $pub_time;
    var $type;
    var $url;

    function __construct($apid, $title, $pic_large, $pic_small, $pub_time, $type, $url=""){
        $this->pid = $apid;
        $this->title = $title;
        $this->pic_large = $pic_large;
        $this->pic_small = $pic_small;
        $this->pub_time = $pub_time;
        $this->type = $type;
        $this->url = $url;
    }

    function _ToInsertSQL($tbl_name){
        return sprintf('INSERT INTO %s VALUES (pid=%s,title="%s",pic_large="%s",pic_small="$s",pub_time="%s");',
                $tbl_name, $this->pid, $this->title, $this->pic_large, $this->pic_small, $this->pub_time);
    }

    function _ToUpdateSQL($tbl_name){
        return sprintf('UPDATE %s SET title="%s",pic_large="%s",pic_small="%s" WHERE %s;',
                $tbl_name, $this->title, $this->pic_large, $this->pic_small, $this->pid);
    }
};

class Article {
    var $aid;
    var $pid;
    var $title;
    var $content;

    function __construct($aid, $pid, $title, $content){
        $this->aid = $aid;
        $this->pid = $pid;
        $this->title = $title;
        $this->content = $content;
    }

    function _ToInsertSQL($tbl_name){
        return sprintf('INSERT INTO %s VALUES (pid=%s,title="%s",content="%s");',
                $tbl_name, $this->pid, $this->title, $this->content);
    }

    function _ToUpdateSQL($tbl_name){
        return sprintf('UPDATE %s SET title="%s",pic_large="%s",pic_small="%s" WHERE aid="%s";',
                $tbl_name, $this->title, $this->content, $this->aid);
    }

    function _ToDeleteSQL($tbl_name){
        return sprintf('DELETE FROM %s WHERE aid="%s";',
                $tbl_name, $this->aid);
    }
}

class DataManager {
    private $mysqli;
    private $connected;

    function __construct(){
        $this->mysqli = new mysqli(CONF_DB_ADDR, CONF_DB_USER, CONF_DB_PASS, CONF_DB_NAME);
        if ($this->connected = ($this->mysqli->connect_errno == 0))
            $this->mysqli->set_charset("utf8");
    }

    function __destruct(){
        if ($this->connected)
        $this->mysqli->close();
    }

    function IsConnected(){
        return $this->connected;
    }

    function GetPosts(){
        $sql = "SELECT * FROM posts;";
        if (($sql_result = $this->mysqli->query($sql)) == false)
            return NULL;
        $ret = [];
        while ($arr = $sql_result->fetch_assoc()){
            $ret[] = (new Post($arr['pid'], $arr['title'],$arr['pic_large'],$arr['pic_small'],$arr['pub_time'],$arr['type'],$arr['url']));
        }
        return empty($ret) ? NULL : $ret;
    }

     function GetPost($post_id){
        $sql = sprintf("SELECT * FROM posts WHERE pid=%d;", $post_id);
        if (($sql_result = $this->mysqli->query($sql)) == false)
            return NULL;
        $ret = $sql_result->fetch_assoc();
        return $ret ? (new Post($ret['pid'], $ret['title'],$ret['pic_large'],$ret['pic_small'],$ret['pub_time'],$ret['type'],$ret['url'])) : NULL;
    }

    function GetArticlesSummary($post_id){
        $sql = sprintf("SELECT aid,title FROM articles WHERE pid=%d;", $post_id);
        if (($sql_result = $this->mysqli->query($sql)) == false)
            return NULL;
        $ret = [];
        while($arr = $sql_result->fetch_assoc()){
            $ret[] = (new Article($arr['aid'], $post_id, $arr['title'], ""));
        }
        return empty($ret) ? NULL : $ret;
    }

    function GetArticleDetail($post_id, $article_id){
        $sql = sprintf("SELECT * FROM articles WHERE aid=%d and pid=%d;", $article_id, $post_id);
        $ret = $this->mysqli->query($sql);
        if (($sql_result = $this->mysqli->query($sql)) == false)
            return NULL;
        $ret = $sql_result->fetch_assoc();
        return $ret ? (new Article($ret['aid'], $ret['pid'], $ret['title'], $ret['content'])) : NULL;
    }

    function SavePost($post){
        if ($post->pid > -1)
            $sql = $post->_ToUpdateSQL();
        else
            $sql = $post->_ToInsertSQL();
        $ret = $this->mysqli->query($sql);
        return $ret;
    }

    function SaveArticle($article){
        if ($post->aid > -1)
            $sql = $article->_ToUpdateSQL();
        else
            $sql = $article->_ToInsertSQL();
        $ret = $this->mysqli->query($sql);
        return $ret;
    }

    function Query($sql){
        return $this->mysqli->query($sql);
    }
}

?>