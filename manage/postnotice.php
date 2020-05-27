<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/17
 * Time: 14:49
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
    $notice = $_POST["msg"];

    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "INSERT INTO notice(notice, time) VALUES(?,NOW());";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("s", $notice);
    $pstmt->execute();

    $json = json_encode(array("true"), JSON_UNESCAPED_UNICODE);
    echo $json;
}