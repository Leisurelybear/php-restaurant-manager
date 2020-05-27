<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/16
 * Time: 15:10
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
    $dish_id = $_POST["dish_id"];

    $current_order_id = get_order_id();

    remove_dish($current_order_id, $dish_id);

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

function remove_dish($order_id, $dish_id){
    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "DELETE FROM order_dishes WHERE orders_id=? AND dishes_id=?;";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("ii", $order_id, $dish_id);
    $pstmt->execute();

    $json = json_encode(array("true"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
}
