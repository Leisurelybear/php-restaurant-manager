<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/26
 * Time: 23:19
 */

header('Content-Type:application/json; charset=utf-8');

$s_name = $_GET["s_name"];


if (!isset($_COOKIE["sess_id"]) || !isset($_COOKIE["username"]) || !isset($_COOKIE["type"])){
    $json = json_encode(array("false"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
} else if (md5(hash("sha256", $_COOKIE["username"])) != $_COOKIE["sess_id"]){
    $json = json_encode(array("false"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
}else{
    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "select u.username, u.password, u.type FROM users u where u.username!=? and u.username like '%".$s_name."%';";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("s", $_COOKIE["username"]);
    $pstmt->execute();
    $rst = $pstmt->get_result()->fetch_all();
    $json = json_encode($rst, JSON_UNESCAPED_UNICODE);
    echo $json;
}