<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/19
 * Time: 10:59
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
    $username = $_POST["username"];
    $password = $_POST["password"];
    $type = $_POST["type"];

    if (exist($username) == true){
        $json = json_encode(array("false-1"), JSON_UNESCAPED_UNICODE);
        echo $json;
        exit(0);
    }else{
        $link = mysqli_connect("localhost", "root", "0412"
            , "php_exam", 3306);


        $sql = "INSERT INTO users VALUES(?,?,?);";
        $pstmt = $link->prepare($sql);
        $pstmt->bind_param("ssi", $username, $password, $type);
        $pstmt->execute();

        $json = json_encode(array("true"), JSON_UNESCAPED_UNICODE);
        echo $json;
    }

}


function exist($username)
{
    $link = mysqli_connect("localhost", "root", "0412"
        , "php_exam", 3306);


    $sql = "select count(*) from users where username=?";
    $pstmt = $link->prepare($sql);
    $pstmt->bind_param("s", $username);
    $pstmt->execute();
    $rst = $pstmt->get_result()->fetch_all();

    $isExist = $rst[0][0] == 1 ? true : false;

    return $isExist;
}
