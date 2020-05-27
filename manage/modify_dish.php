<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/17
 * Time: 15:22
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
    $dish_id = $_POST["dish_id"];
    $price = $_POST["price"];

    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "update dishes set price=? where id=?;";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("di", $price, $dish_id);
    $pstmt->execute();

    $json = json_encode(array("true"), JSON_UNESCAPED_UNICODE);
    echo $json;
}