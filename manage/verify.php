<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/21
 * Time: 12:50
 */

class Verify{
    private $link = null;

    public function __construct()
    {
        $this->link = mysqli_connect("localhost", "root", "0412"
            , "php_exam", 3306);

    }

    function verifyType($username){
        if ($this->link == null){
//            echo "error";
            return "error";
        }
        $sql = "select u.type from users u where username=?";
        $pstmt = $this->link->prepare($sql);
        $pstmt->bind_param("s", $username);

        $pstmt->execute();

        $rst = $pstmt->get_result()->fetch_all();
        return $rst[0][0];
    }
}

function verifyType($username){
    $ver = new Verify();

    $type = $ver->verifyType($username);

    if ($type == '1'){
        return "admin";
    }else if ($type == '0'){
        return "guest";
    }
    return "error";
}
