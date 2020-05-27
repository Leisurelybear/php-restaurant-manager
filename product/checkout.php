<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/16
 * Time: 16:09
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
    $order_id = get_order_id();
    $sum = calculate($order_id);//计算总价

    checkout_orders($order_id, $sum);

    $json = json_encode("true", JSON_UNESCAPED_UNICODE);
    echo $json;

}

function checkout_orders($order_id, $sum){
    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);

    $sql = "update orders set sum=? where id=?;";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("di", $sum, $order_id);
    $pstmt->execute();

    $sql = "update orders set checkout=1 where id=?;";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("i", $order_id);
    $pstmt->execute();

}

// 计算订单总价
function calculate($order_id){
    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "SELECT sum(od.quantity*d.price*d.discount) sum  FROM order_dishes od,dishes d WHERE od.dishes_id=d.id and od.orders_id=?;";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("i", $order_id);
    $pstmt->execute();
    $rst = $pstmt->get_result()->fetch_all();
    $sum = $rst[0][0];
    return $sum;
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