<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/15
 * Time: 20:39
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
    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "SELECT od.dishes_id,d.dishname, od.quantity,od.quantity*d.price*d.discount sumprice FROM order_dishes od, dishes d 
	WHERE od.dishes_id = d.id 
	and od.orders_id = (SELECT id FROM orders WHERE checkout=0 AND users_username=? LIMIT 1);";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("s", $_COOKIE["username"]);
    $pstmt->execute();
    $rst = $pstmt->get_result()->fetch_all();
    $json = json_encode($rst, JSON_UNESCAPED_UNICODE);
    echo $json;
}