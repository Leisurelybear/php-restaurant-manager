<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/24
 * Time: 18:24
 */

header('Content-Type:application/json; charset=utf-8');

//if (!isset($_COOKIE["sess_id"]) || !isset($_COOKIE["username"]) || !isset($_COOKIE["type"])){
//    header("location:index.html");
//}

$link = mysqli_connect("localhost", "root", "0412"
    , "php_exam", 3306);


$sql = "SELECT d.id, d.dishname, d.price*d.discount price, count(*) cnt 
          FROM dishes d,order_dishes od
		  where od.dishes_id=d.id GROUP BY d.id ORDER BY cnt desc limit 3;";
$pstmt = $link->prepare($sql);
$pstmt->execute();
$rst = $pstmt->get_result()->fetch_all();


$json = json_encode($rst, JSON_UNESCAPED_UNICODE);
echo $json;