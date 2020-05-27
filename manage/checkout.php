<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/23
 * Time: 13:38
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

    if (!isset($_POST["order_id"])){
        $json = json_encode(array("false-1"), JSON_UNESCAPED_UNICODE);
        echo $json;
        exit(0);
    }

    $order_id = $_POST["order_id"];
    $sum = calculate($order_id);

    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);

    $sql = "UPDATE orders SET checkout=1, sum = ? where id = ?;";

    $pstmt = $link->prepare($sql);

    $pstmt->bind_param("di", $sum, $order_id);

    $pstmt->execute();

    $json = json_encode(array("true"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);

}


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



