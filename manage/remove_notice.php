<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/15
 * Time: 17:28
 */

header('Content-Type:application/json; charset=utf-8');


if (!isset($_COOKIE["sess_id"]) || !isset($_COOKIE["username"]) || !isset($_COOKIE["type"])){
    $json = json_encode(array("false"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
} else if (md5(hash("sha256", $_COOKIE["username"])) != $_COOKIE["sess_id"]){
    $json = json_encode(array("false"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
}else{
    $notice_id = $_POST["notice_id"];

    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "update notice set outmoded=1 where id=?;";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("i", $notice_id);
    $pstmt->execute();

    $json = json_encode(array("true"), JSON_UNESCAPED_UNICODE);
    echo $json;
}