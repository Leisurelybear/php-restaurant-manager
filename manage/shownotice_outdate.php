<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/17
 * Time: 15:02
 */
header('Content-Type:application/json; charset=utf-8');

if (!isset($_COOKIE["sess_id"]) || !isset($_COOKIE["username"]) || !isset($_COOKIE["type"])){
    header("location:index.html");
}

$link = mysqli_connect("localhost", "root", "0412"
    , "php_exam", 3306);


$sql = "SELECT id,notice,time FROM notice where outmoded=1 order by id desc ;";
$pstmt = $link->prepare($sql);
$pstmt->execute();
$rst = $pstmt->get_result()->fetch_all();


$json = json_encode($rst, JSON_UNESCAPED_UNICODE);
echo $json;