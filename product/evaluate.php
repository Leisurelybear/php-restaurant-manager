<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/19
 * Time: 14:06
 */
if (!isset($_COOKIE["sess_id"]) || !isset($_COOKIE["username"]) || !isset($_COOKIE["type"])){
    $json = json_encode(array("false"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
} else if (md5(hash("sha256", $_COOKIE["username"])) != $_COOKIE["sess_id"]){
    $json = json_encode(array("false"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
}else{
    if (!isset($_POST["msg"]) || $_POST["msg"]==""){
        $json = json_encode(array("false"), JSON_UNESCAPED_UNICODE);
        echo $json;
        exit(0);
    }
    $username = $_COOKIE["username"];
    $msg = $_POST["msg"];
    $order_id = get_order_id();


    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "INSERT INTO evaluate VALUES(?,?,?,now());";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("iss", $order_id, $msg, $username);
    $pstmt->execute();

    $json = json_encode(array("true"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
}

//得到当前订单号
function get_order_id(){
    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "SELECT checkout, id  FROM orders WHERE users_username=? ORDER BY id DESC LIMIT 1;";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("s", $_COOKIE["username"]);
    $pstmt->execute();
    $rst = $pstmt->get_result()->fetch_all();
    return $rst[0][1];
}