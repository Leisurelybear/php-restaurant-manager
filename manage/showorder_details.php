<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/22
 * Time: 14:44
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
}else {

    $order_id = $_GET["order_id"];

    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "SELECT od.orders_id, od.dishes_id,d.dishname, od.quantity,od.quantity*d.price*d.discount sumprice FROM orders o, order_dishes od, dishes d 
	WHERE od.dishes_id = d.id and o.id=od.orders_id and o.id = ?;";

    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("i", $order_id);
    $pstmt->execute();
    $rst = $pstmt->get_result()->fetch_all();
    $json = json_encode($rst, JSON_UNESCAPED_UNICODE);
    echo $json;
}

