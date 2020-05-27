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

    if (!isset($_POST["date"])){
        $date = "2099-01-01";
    }else{
        $time_int = strtotime($_POST["date"]);
        $time_int += 86400;
        $date = date("Y-m-d", $time_int);
    }



//    $sql = "SELECT o.users_username, o.checkout, od.dishes_id,d.dishname, od.quantity,od.quantity*d.price*d.discount sumprice FROM orders o, order_dishes od, dishes d
//	WHERE od.dishes_id = d.id and o.id=od.orders_id order BY checkout;";
    $sql = "SELECT * FROM orders o where o.time<=? ORDER BY o.checkout, o.time desc;";

    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("s", $date);
    $pstmt->execute();
    $rst = $pstmt->get_result()->fetch_all();
    $json = json_encode($rst, JSON_UNESCAPED_UNICODE);
    echo $json;
}