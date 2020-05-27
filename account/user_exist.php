<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/23
 * Time: 12:58
 */
header('Content-Type:application/json; charset=utf-8');

$username = $_POST["username"];

if (strlen($username) <= 4){
    $json = json_encode(array("false-2"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
}

if (exist($username) == true) {
    $json = json_encode(array("false-1"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
}else{
    $json = json_encode(array("true"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
}

function exist($username)
{
    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "select count(*) from users where username=?";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("s", $username);
    $pstmt->execute();
    $rst = $pstmt->get_result()->fetch_all();

    $isExist = $rst[0][0] == 1 ? true : false;

    return $isExist;
}