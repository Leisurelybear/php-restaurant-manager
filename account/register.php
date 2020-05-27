<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/19
 * Time: 13:31
 */
session_start();

header('Content-Type:application/json; charset=utf-8');

$username = $_POST["username"];
$password = $_POST["password"];
$type = 0;

//1. 获取到用户提交的验证码
$captcha = $_POST["captcha"];
//2. 将session中的验证码和用户提交的验证码进行核对,当成功时提示验证码正确，并销毁之前的session值,不成功则重新提交
if(strtolower($_SESSION["captchaimg"]) == strtolower($captcha)){

}else{
    $json = json_encode(array("false-2"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
}

if (strlen($username) <= 4){
    $json = json_encode(array("false-3"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
}


if (exist($username) == true) {
    $json = json_encode(array("false-1"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
} else {

    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "INSERT INTO users VALUES(?,?,?);";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("ssi", $username, $password, $type);
    $pstmt->execute();

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



