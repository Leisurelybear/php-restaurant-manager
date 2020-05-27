<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/15
 * Time: 14:34
 */
session_start();
if (isset($_POST["username"]) && isset($_POST["password"])){
    $username = $_POST["username"];
    $password = $_POST["password"];
}else{
    header("location:index.html");
}


$link = mysqli_connect("localhost", "root", "0412"
    , "php_exam", 3306);


$sql = "select count(*) cnt, type from users where username=? and password=?;";
$pstmt = $link->prepare($sql);
$pstmt->bind_param("ss", $username, $password);
$pstmt->execute();
$rst = $pstmt->get_result()->fetch_array();

$check = $rst[0] == "1" ? "true" : "false";//用户名密码是否正确
if ($check == "true"){
    $account_type = $rst["type"];

    //加密设置有效期为一天的cookie
    $sess_id = md5(hash("sha256", $username));
    setcookie("sess_id", $sess_id, time() + 60 * 60 * 24, "/");
    setcookie("username", $username, time() + 60 * 60 * 24, "/");
    setcookie("type", $account_type, time() + 60 * 60 * 24, "/");

    if ($account_type == "1"){
        header("location:../manage.php");
    }else{
        header("location:../main.php");
    }

}else if ($check == "false"){
    setcookie("sess_id", "",time() + 60 * 60 * 24, "/");
    setcookie("username", "", time() + 60 * 60 * 24, "/");
    echo "<script>onload=function() {
  alert('用户名或密码错误！')
  window.location = '../index.html'
}</script>";
}

