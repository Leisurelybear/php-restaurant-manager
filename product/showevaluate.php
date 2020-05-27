<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/19
 * Time: 14:34
 */

header('Content-Type:application/json; charset=utf-8');

//if (!isset($_COOKIE["sess_id"]) || !isset($_COOKIE["username"]) || !isset($_COOKIE["type"])){
//    header("location:index.html");
//}

$link = mysqli_connect("localhost", "root", "0412"
    , "php_exam", 3306);


$sql = "SELECT * FROM evaluate;";
$pstmt = $link->prepare($sql);
$pstmt->execute();
$rst = $pstmt->get_result()->fetch_all();


$json = json_encode($rst, JSON_UNESCAPED_UNICODE);
echo $json;