<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/16
 * Time: 14:02
 */
setcookie("sess_id", "", time() + 60 * 60 * 24, "/");
setcookie("username", "", time() + 60 * 60 * 24, "/");
setcookie("type", "", time() + 60 * 60 * 24, "/");