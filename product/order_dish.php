<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/16
 * Time: 14:24
 */

header('Content-Type:application/json; charset=utf-8');


if (!isset($_COOKIE["sess_id"]) || !isset($_COOKIE["username"]) || !isset($_COOKIE["type"])) {
    $json = json_encode(array("false"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
} else if (md5(hash("sha256", $_COOKIE["username"])) != $_COOKIE["sess_id"]) {
    $json = json_encode(array("false"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
} else {

    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);

    //先查该用户当前一个订单的单号
    $sql = "SELECT checkout, id  FROM orders WHERE users_username=? ORDER BY id DESC LIMIT 1;";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("s", $_COOKIE["username"]);
    $pstmt->execute();
    $rst = $pstmt->get_result()->fetch_all();


    if (count($rst) == 0 || (isset($rst[0][0]) && $rst[0][0] == 1)) {
        $orderid = insert_order_getOrderId($_COOKIE["username"]);

    } else {
        $orderid = $rst[0][1];
    }
    add_dish($orderid, $_POST["dish_id"], $_POST["quantity"]);

    $json = json_encode(array("true"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
}

//添加菜
function add_dish($order_id, $dish_id, $quantity)
{
    //如果点击取消，则不添加
    if ($quantity == 0) {
        $json = json_encode(array("false-quantity"), JSON_UNESCAPED_UNICODE);
        echo $json;
        exit(0);
        return;
    }

    //如果添加的菜原本就在订单中，则修改数量
    if (exist_dish($order_id, $dish_id)) {
        $old_quantity = get_dish_quantity($order_id, $dish_id);
        change_dish($order_id, $dish_id, $quantity + $old_quantity);
        return;
    }

    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "INSERT INTO order_dishes VALUES(?,?,?);";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("iii", $order_id, $dish_id, $quantity);
    $pstmt->execute();
}

function get_dish_quantity($order_id, $dish_id)
{
    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "select quantity from order_dishes where orders_id=? and dishes_id=?";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("ii", $order_id, $dish_id);
    $pstmt->execute();
    $rst = $pstmt->get_result()->fetch_all();
    return $rst[0][0];
}

// 查看当前添加的菜是否存在，存在直接增加数量
function exist_dish($order_id, $dish_id)
{
    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);

    //先查该用户当前一个订单的单号
    $sql = "SELECT count(*) FROM order_dishes WHERE orders_id=? AND dishes_id=?;";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("ii", $order_id, $dish_id);
    $pstmt->execute();
    $rst = $pstmt->get_result()->fetch_all();
    return $rst[0][0];//等于0则不存在，等于1存在
}

//更改已存在订单菜的数量
function change_dish($order_id, $dish_id, $quantity)
{
    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);

    $sql = "UPDATE order_dishes set quantity=? WHERE orders_id=? AND dishes_id=?;";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("iii", $quantity, $order_id, $dish_id);
    $pstmt->execute();

}

//插入orders表新订单
function insert_order_getOrderId($username)
{
    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "INSERT INTO orders(users_username, checkout, time) VALUES (?, 0, NOW());";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("s", $username);
    $pstmt->execute();


    $sql = "SELECT id FROM orders WHERE users_username=? ORDER BY id DESC LIMIT 1;";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("s", $username);
    $pstmt->execute();
    $rst = $pstmt->get_result()->fetch_all();
    return $rst[0][0];
}