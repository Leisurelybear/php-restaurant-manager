<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/16
 * Time: 14:07
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
    $json = json_encode(array("true"), JSON_UNESCAPED_UNICODE);
    echo $json;
    exit(0);
}