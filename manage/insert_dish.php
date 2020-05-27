<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/17
 * Time: 15:55
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
    $dish_name = $_POST["dish_name"];
    $dish_price = $_POST["dish_price"];
    $dish_intro = $_POST["dish_intro"];
//    $dish_name = "麻辣香锅";
//    $dish_intro = "这道菜麻辣鲜香，非常美味。";
//    $dish_price = "26";

    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "INSERT INTO dishes(dishname, intro, price) VALUES(?,?,?);";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("ssd", $dish_name, $dish_intro, $dish_price);
    $pstmt->execute();

    $json = json_encode(array("true"), JSON_UNESCAPED_UNICODE);
    echo $json;
}