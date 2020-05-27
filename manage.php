<?php
/**
 * Created by IntelliJ IDEA.
 * User: jason
 * Date: 2019/12/15
 * Time: 15:19
 */
include("manage/verify.php");
if (!isset($_COOKIE["sess_id"]) || !isset($_COOKIE["username"]) || !isset($_COOKIE["type"])) {
    header("location:index.html");
} else if (md5(hash("sha256", $_COOKIE["username"])) != $_COOKIE["sess_id"]) {
    header("location:index.html");
}
if (verifyType($_COOKIE["username"]) != "admin") {
//    echo "<script>alert('请先登录！');window.location='index.html'</script>";
    header("location:index.html");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>管理后端</title>
    <script src="resource/js/jquery-1.7.2.min.js"></script>
    <style>
        .nav {
            height: 40px;
            line-height: 40px;
            background: #ededed;
            border-radius: 0 0 5px 5px;

        }

        .nav li {
            list-style: none;
            float: left;
            width: 80px;
            text-align: center;
        }

        .nav a {
            display: block;
            color: black;
            text-decoration: none;
        }

        .nav a:hover {
            color: white;
            background: #ebccd1;
            border-radius: 10px;
            transform: rotate(10deg);
            /*transform: skew(10deg, 10deg);*/

            /*transition: all 0.5s;*/
            /*transform: scale(1.2);*/
            transition: all cubic-bezier(.4,0,.2,1) .5s;
        }

        .nav a:focus {
            color: white;
            background: #ee5f5b;
            border-radius: 10px;
        }

        .nav a:checked {
            color: white;
            background: #5eb95e;
            border-radius: 10px;
        }

        table {
            border-radius: 10px;
        }

        tr > td {
            text-align: center;
            border: 1px solid black;
            width: 200px;
            vertical-align: center;
            background: powderblue;

        }

        tr > td:hover {
            background: #1e347b;
            color: #dddddd;
            /*transform: scale(1.1);*/
            /*transition: all 0.25s;*/
        }
        input[type="button"]:hover{
            transform: scale(1.2);
            transition: all cubic-bezier(.4,0,.2,1) .5s;

        }
        input[type="button"]:active{
            background: #b84c4c;
            /*transition: all ;*/
            transition: all cubic-bezier(.4,0,.2,1) .5s;
        }


        /*第一行背景为浅橙色*/
        thead > tr > td {
            font-weight: bold;
            background-color: #F1D4AF;

        }

        .class_btn_add {
            border: 1px;
            background: #ebccd1;
            width: 100px;
            cursor: pointer;
        }

        .class_btn_remove {
            border: 1px;
            background: #ee5f5b;
            width: 100px;
            cursor: pointer;
        }

        .class_btn_classA {
            border: 1px;
            background: ;
            width: 100px;
            cursor: pointer;

        }

        td > span {
            overflow: hidden; /*自动隐藏文字*/
            text-overflow: ellipsis; /*文字隐藏后添加省略号*/
            display: -webkit-box;
            -webkit-line-clamp: 1; /*想要显示的行数*/
            -webkit-box-orient: vertical;
        }

        textarea {
            border-radius: 10px;
            background-color: papayawhip;
        }

        #btn_notice {
            border-radius: 10px;
            background-color: #5eb95e;
            border-width: 0px;
            width: 80px;
            cursor: pointer;
        }

        #o_detail{
            overflow-y:auto;
            overflow-x:auto;
            /*width:400px;*/
            height:200px;
        }
        #mainpage{
            overflow-y:auto;
            overflow-x:auto;
            /*width:400px;*/
            height:1080px;
        }

        .notice {
            background-image: url("resource/images/notice.jpg");
        }
    </style>
    <script>


        onload = function () {
            // islogined();
            var action = window.location.search.split("=")[1];
            $("#mainpage").empty()

            if (action == null) {
                showMain()
            }

            var title = "主页"
            if (action == "dishes") {
                var title = "菜单"
                showdishes()
            } else if (action == "notice") {
                var title = "公告"
                shownotice()
            } else if (action == "comment") {
                var title = "留言板"
                showcomment()
            } else if (action == "orders") {
                var title = "订单"
                append_time()
                showorders()
            } else if (action == "logout") {
                logout()
            } else if (action == "users") {
                var title = "用户管理"
                showusers()
            }else if (action == "data") {
                var title = "销售数据"
                showData()
            }
            $("#title").html("<h1>" + title + "</h1>")

        }

        function setNowDate() {
            var time = new Date();
            var day = ("0" + time.getDate()).slice(-2);
            var month = ("0" + (time.getMonth() + 1)).slice(-2);
            var today = time.getFullYear() + "-" + (month) + "-" + (day);
            $('#datetime').val(today);
        }

        function showMain() {
            var str = "<div align='center'><img src=\"resource/images/cai_1.jpg\"  alt=\"上海鲜花港 - 郁金香\" /></div>"
            $("#mainpage").html(str)
            showEvaluate()

        }

        function showEvaluate() {
            var dishes_tab = "<br/><div align='center'><h2>顾客评价</h2>" +
                "<table cellspacing='0' cellpadding='0'><thead><tr>" +
                "<td>用户</td>" +
                "<td>消费日期</td>" +
                "<td>评价</td>" +
                "</tr></thead><tbody id='div_dish'></tbody></table></div>";
            $("#mainpage").append(dishes_tab)

            var str = ""
            $.ajax({
                url: "/exam/product/showevaluate.php",
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        // console.log(data)
                        $(data).each(function (i, val) {
                            str += "<tr>" +
                                "<td>" + val[2] + "</td>" +
                                "<td>" + val[1] + "</td>" +
                                "<td>" + val[3] + "</td>" +
                                "</tr>";
                            $("#div_dish").append(str)
                        })

                    }
                }

            })

        }


        function showData() {
            var hotdish = "<div style='background: #dddddd; border-radius: 10px;' >热销排名(从上到下依次递减)<br/><table id='hot' cellspacing='0' cellpadding='0'>" +

                "</table><br/></div>"
            $("#mainpage").html(hotdish)
            var str = ""
            $.ajax({
                url: "/exam/manage/hotdish.php",
                data: "",
                type: "get",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        console.log(data)
                        $(data).each(function (i, val) {
                            str = "<tr>" +
                                "<td style='width: 50px'>No." + (i+1) + "</td>" +
                                "<td>" + val[0] + "</td>" +
                                "<td>" + val[1] + "例</td>" +
                                "</tr>";
                            $("#hot").append(str)
                        })
                    }
                }

            })
        }

        function add_user() {
            var username = $("#u_name").val();
            var password = $("#u_pwd").val();
            var type = $("#u_type").val();

            if (username.length < 5 || password == "" || (type != 1 && type != 0)) {
                alert("输入的信息有误！请检查后提交。")
                return;
            }

            $.ajax({
                url: "/exam/manage/add_user.php",
                data: "username=" + username + "&password=" + password + "&type=" + type,
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        console.log(data)
                        if (data[0] == "false") {
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else if (data[0] == "true") {
                            alert("添加成功！");
                            showusers();
                        } else if (data[0] == "false-1") {
                            $("#u_msg").html("用户名重复！");
                            $("#u_name").css("border", "3px solid red")
                        }
                    }
                }
            })

            // console.log(username + ".." + password + ".." + type)
        }

        function modify_user(username) {
            console.log(username)
            var password = $("#newPassword").val()
            if (password.length == "") {
                alert("密码不能为空！")
                return
            }

            $.ajax({
                url: "/exam/manage/modify_user_pwd.php",
                data: "username=" + username + "&password=" + password,
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        if (data[0] == "false") {
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else if (data[0] == "true") {

                        }
                    }
                }
            })


            showusers()
            MODIFY = 0;//修改完毕，标志位置为0
        }

        var MODIFY = 0;//正在修改某一项
        function get_userInfo(currentNode) {
            console.log(MODIFY)
            if (MODIFY == 1) {
                alert("不能同时修改多名用户！")
                return;
            }
            MODIFY = 1;//正在修改中，不能同时改多名

            var username = currentNode.parentNode.parentNode.childNodes[1].childNodes[0].innerText;
            var password = currentNode.parentNode.parentNode.childNodes[2].childNodes[0].innerText;

            //password
            passwordInput = document.createElement("span")
            passwordInput.innerHTML = "<input type='text' id='newPassword' value='" + password + "' onblur='modify_user(\"" + username + "\")'>"
            currentNode.parentNode.parentNode.childNodes[2].childNodes[0].replaceWith(passwordInput)

        }

        function delete_user(username) {
            $.ajax({
                url: "/exam/manage/delete_user.php",
                data: "username=" + username,
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        console.log(data)
                        if (data[0] == "false") {
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else if (data[0] == "true") {
                            showusers()
                        }
                    }
                }
            })
        }

        function searchuser() {
            var name = $("#username_search").val();
            user_search(name)

        }

        function user_search() {

            var dishes_tab = "<br/><div align='center' >" +
                "<table cellspacing='0' cellpadding='0' id='div_dish'><thead><tr>" +
                "<td>序号</td>" +
                "<td>用户ID</td>" +
                "<td>用户密码</td>" +
                "<td>用户类型</td>" +
                "<td>操作A</td>" +
                "<td>操作B</td>" +
                "</tr></thead></table></div>";
            $("#mainpage").html(dishes_tab + "<tbody>")
            //添加用户
            var new_user = "<tr>" +
                "<td style='background: #d0e9c6'>#</td>" +
                "<td style='background: #d0e9c6'><input type='text' placeholder='输入用户ID,长度大于等于5' id='u_name'></td>" +
                "<td style='background: #d0e9c6'><input type='text' placeholder='输入用户密码' id='u_pwd'></td>" +
                "<td style='background: #d0e9c6'>" +
                "<select id='u_type' class='class_btn_classA'>" +
                "<option value='0'>普通用户</option>" +
                "<option value='1'>管理员</option>" +
                "</select>" +
                "</td>" +
                "<td style='background: #d0e9c6' colspan=''><input type='button' class='class_btn_add' value='添加用户' onclick='add_user()'></td>" +
                "<td><span id='u_msg' style='color: red;'>#</span></td>" +
                "</tr>";
            $("#div_dish").append(new_user)
            $.ajax({
                url: "/exam/manage/usersearch.php",
                data: "s_name="+$("#username_search").val(),
                type: "get",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        console.log(data)
                        if (data[0] == "false") {
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else {

                            $(data).each(function (i, val) {
                                str = ""
                                str += "<tr>" +
                                    "<td>" + (i + 1) + "</td>" +
                                    "<td><span>" + val[0] + "</span></td>" +
                                    "<td><span>" + val[1] + "</span></td>" +
                                    "<td>" + ((val[2] == 1) ? "<span style='color: #b84c4c;font-weight: bold;'>管理员</span>" : "普通用户") + "</td>" +
                                    "<td><input type='button' onclick='get_userInfo(this)' class='class_btn_add' value='修改'></td>" +
                                    "<td><input type='button' onclick='delete_user(\"" + val[0] + "\")' class='class_btn_remove' value='删除'></td>" +
                                    "</tr>";
                                $("#div_dish").append(str)
                            })
                            $("#mainpage").append("</tbody>")
                        }
                    }
                }

            })
        }

        function showusers() {

            var search = "<input type='search' id='username_search'/>" +
                "<input type='button' onclick='user_search()' value='搜索'>"
            $("#timetab").html(search)

            var dishes_tab = "<br/><div align='center' >" +
                "<table cellspacing='0' cellpadding='0' id='div_dish'><thead><tr>" +
                "<td>序号</td>" +
                "<td>用户ID</td>" +
                "<td>用户密码</td>" +
                "<td>用户类型</td>" +
                "<td>操作A</td>" +
                "<td>操作B</td>" +
                "</tr></thead></table></div>";
            $("#mainpage").html(dishes_tab + "<tbody>")

            //添加用户
            var new_user = "<tr>" +
                "<td style='background: #d0e9c6'>#</td>" +
                "<td style='background: #d0e9c6'><input type='text' placeholder='输入用户ID,长度大于等于5' id='u_name'></td>" +
                "<td style='background: #d0e9c6'><input type='text' placeholder='输入用户密码' id='u_pwd'></td>" +
                "<td style='background: #d0e9c6'>" +
                "<select id='u_type' class='class_btn_classA'>" +
                "<option value='0'>普通用户</option>" +
                "<option value='1'>管理员</option>" +
                "</select>" +
                "</td>" +
                "<td style='background: #d0e9c6' colspan=''><input type='button' class='class_btn_add' value='添加用户' onclick='add_user()'></td>" +
                "<td><span id='u_msg' style='color: red;'>#</span></td>" +
                "</tr>";
            $("#div_dish").append(new_user)

            $.ajax({
                url: "/exam/manage/showusers.php",
                data: "",
                type: "get",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        // console.log(data)
                        if (data[0] == "false") {
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else {

                            $(data).each(function (i, val) {
                                str = ""
                                str += "<tr>" +
                                    "<td>" + (i + 1) + "</td>" +
                                    "<td><span>" + val[0] + "</span></td>" +
                                    "<td><span>" + val[1] + "</span></td>" +
                                    "<td>" + ((val[2] == 1) ? "<span style='color: #b84c4c;font-weight: bold;'>管理员</span>" : "普通用户") + "</td>" +
                                    "<td><input type='button' onclick='get_userInfo(this)' class='class_btn_add' value='修改'></td>" +
                                    "<td><input type='button' onclick='delete_user(\"" + val[0] + "\")' class='class_btn_remove' value='删除'></td>" +
                                    "</tr>";
                                $("#div_dish").append(str)
                            })
                            $("#mainpage").append("</tbody>")
                        }
                    }
                }

            })
        }


        function logout() {
            $.ajax({
                url: "/exam/account/logout.php",
                data: "",
                type: "get",
                dataType: "text",
                statusCode: {
                    200: function (data) {
                        alert("Bye！")
                        window.location = "index.html";
                    }
                }
            })
        }

        function islogined() {
            $.ajax({
                url: "/exam/account/islogined.php",
                data: "",
                type: "get",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        if (data[0] == "false") {
                            alert("请先登陆！")
                            window.location = "index.html";
                        }
                    }
                }
            })
        }

        function clearOrderDetail() {
            $("#detail_div").html("");
        }
        function chechout(order_id) {
            $.ajax({
                url:"/exam/manage/checkout.php",
                data:"order_id="+order_id,
                type:"post",
                dataType:"json",
                statusCode:{
                    200:function (data) {
                        // console.log(data)
                        if (data[0] == "false"){
                            alert("请先登陆！")
                            window.location = "index.html";
                        }else {
                            alert("结算成功！")
                            showorders();
                        }
                    }
                }
            })
            
        }
        function orderdetails(order_id, checkouted) {
            str = "<div id='o_detail' style='border: 1px solid #1e347b; background: #dddddd'><h4>当前订单号："+order_id+"</h4>" +
                "当前总价：<span id='now_price'></span>元" +
                "<table cellpadding='0' cellspacing='0'>" +
                "<thead>" +
                "<tr>" +
                "<td>菜品ID</td>" +
                "<td>菜品名称</td>" +
                "<td>菜品数量</td>" +
                "<td>菜品总价</td>" +
                "</tr>" +
                "</thead>" +
                "<tbody id='detail_body'></tbody>" +
                "</table>"
            str += checkouted == 0 ? "<input type='button' onclick='chechout("+order_id+")' value='结算'/>&nbsp;&nbsp;" :""

            str += "<input type='button' onclick='clearOrderDetail()' value='关闭'/> " +
                "<br/></div>";
            $("#detail_div").html(str);
            var price = 0;
            $.ajax({
                url: "/exam/manage/showorder_details.php",
                data: "order_id=" + order_id,
                type: "get",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        $(data).each(function (i, val) {
                            price += val[4];
                            str = "<tr>" +
                                "<td>" + val[1] + "</td>" +
                                "<td>" + val[2] + "</td>" +
                                "<td>" + val[3] + "</td>" +
                                "<td>" + val[4] + "</td>" +
                                "</tr>"
                            $("#detail_body").append(str)
                        })
                        $("#now_price").html(price);
                    }

                }
            })


        }

        function append_time() {
            var timetab = "<span>查找在此日期之前的订单：" +
                "<input type='date' value='' id='datetime' onchange='showorders()'><br/>"
            $("#timetab").html(timetab)
            setNowDate($("#datetime").val())

        }

        function showorders() {
            var dishes_tab = "<br/><div id='detail_div'></div><br/>" +
                "</span>" +
                "<div align='center'>" +
                "<table cellspacing='0' cellpadding='0'><thead><tr>" +
                "<td>序号</td>" +
                "<td>订单ID</td>" +
                "<td>用户ID</td>" +
                "<td>是否结账</td>" +
                "<td>时间</td>" +
                "<td>总价</td>" +
                "<td>查看详情</td>" +
                "</tr></thead><tbody id='div_dish'></tbody></table></div>";
            $("#mainpage").html(dishes_tab)


            $.ajax({
                url: "/exam/manage/showorders.php",
                data: "date=" + $("#datetime").val(),
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        // console.log(data)
                        if (data[0] == "false") {
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else {

                            $(data).each(function (i, val) {
                                str = ""
                                str += "<tr>" +
                                    "<td>" + (i + 1) + "</td>" +
                                    "<td>" + val[0] + "</td>" +
                                    "<td>" + val[1] + "</td>" +
                                    "<td>" + (val[2] == 0 ? "<span style='background:lightgreen'>未结账</span>" : "<span style='background:#ee5f5b'>已结账</span>") + "</td>" +
                                    "<td>" + val[3] + "</td>" +
                                    "<td>" + val[4] + "</td>" +
                                    "<td><input type='button' onclick='orderdetails(" + val[0] + ","+val[2]+")' class='class_btn_classA' value='查看订单'></td>" +
                                    "</tr>";
                                $("#div_dish").append(str)
                            })
                        }
                    }
                }

            })
        }


        function change_dish(dish_id, quantity) {
            // console.log(quantity)
            $.ajax({
                url: "/exam/product/change_dish.php",
                data: "dish_id=" + dish_id + "&quantity=" + quantity,
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        // console.log(data)
                        if (data[0] == "false") {
                            //操作失败
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else if (data[0] == "true") {
                            showorders();
                        }
                    }
                }
            })
        }

        function remove_dish(dish_id) {
            console.log(dish_id)
            $.ajax({
                url: "/exam/product/remove_dish.php",
                data: "dish_id=" + dish_id,
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        // console.log(data)
                        if (data[0] == "false") {
                            //操作失败
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else if (data[0] == "true") {
                            showorders();
                        }
                    }
                }

            })
            // throw new DOMException("移除功能")
        }


        function postnotice() {
            if ($("#notice").val() == "" || $("#notice").val().length < 5) {
                alert("公告字数太少！");
                return
            }
            $.ajax({
                url: "/exam/manage/postnotice.php",
                data: "msg=" + $("#notice").val(),
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        // console.log(data)
                        if (data[0] == "false") {
                            alert("请先登陆！")
                            window.location = "index.html";
                        } else {
                            alert("发布成功！")
                            shownotice()
                        }
                    }
                }

            })
        }

        function showcomment() {

            var dishes_tab = "<br/><div align='center' id='div_dish'><table cellspacing='0' cellpadding='0'><thead><tr>" +
                "<td>用户</td>" +
                "<td>日期</td>" +
                "<td>留言</td>" +
                // "<td>操作</td>" +
                "</tr></thead></table></div>";
            $("#mainpage").append(dishes_tab + "<tbody>")
            var str = ""
            $.ajax({
                url: "/exam/product/showcomment.php",
                data: "",
                type: "get",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        $(data).each(function (i, val) {
                            str = ""
                            str += "<tr>" +
                                "<td>" + val[0] + "</td>" +
                                "<td>" + val[1] + "</td>" +
                                "<td>" + val[2] + "</td>" +
                                // "<td><input type='button' class='class_btn_add' value='添加'></td>" +
                                "</tr>";
                            $("#div_dish").append(str)
                        })
                        $("#mainpage").append("</tbody>")

                    }
                }

            })

        }


        function shownotice() {

            var notice = "<br/><div>" +
                "<textarea name='notice' id='notice' placeholder='此区域写公告内容。' cols='60' rows='5' style='resize: none'>" +
                "</textarea>" +
                "<br/><input type='button' value='提交' id='btn_notice' onclick='postnotice()'>" +
                "" +
                "</div>";
            $("#mainpage").html(notice)
            var dishes_tab = "<br/><div align='center'><table cellspacing='0' cellpadding='0'><thead><tr>" +
                "<td>编号</td>" +
                "<td style='width: 200px'>日期</td>" +
                "<td>通知内容</td>" +
                "<td>操作</td>" +
                "</tr></thead><tbody id='div_dish'></tbody></table></div>";
            $("#mainpage").append(dishes_tab)
            var str = ""
            $.ajax({
                url: "/exam/product/shownotice.php",
                data: "",
                type: "get",
                dataType: "json",
                async: false,
                statusCode: {
                    200: function (data) {
                        $(data).each(function (i, val) {
                            str = ""
                            str += "<tr>" +
                                "<td>" + (i + 1) + "</td>" +
                                "<td style='width: 200px'>" + val[2] + "</td>" +
                                "<td><span title='" + val[1] + "'>" + val[1] + "</span></td>" +
                                "<td><input type='button' onclick='remove_notice(" + val[0] + ")' class='class_btn_remove' value='删除'></td>" +
                                "</tr>";
                            $("#div_dish").append(str)
                        })

                    }
                }

            })
            $.ajax({
                url: "/exam/manage/shownotice_outdate.php",
                data: "",
                type: "get",
                dataType: "json",
                async: false,
                statusCode: {
                    200: function (data) {
                        $(data).each(function (i, val) {
                            str = ""
                            str += "<tr>" +
                                "<td>#</td>" +
                                "<td style='width: 200px'>" + val[2] + "</td>" +
                                "<td><span title='" + val[1] + "'>" + val[1] + "</span></td>" +
                                "<td style='background: grey;color: red;'>已过时</td>" +
                                "</tr>";
                            $("#div_dish").append(str)
                        })

                    }
                }

            })
        }

        function remove_notice(notice_id) {
            $.ajax({
                url: "/exam/manage/remove_notice.php",
                data: "notice_id=" + notice_id,
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        if (data[0] == "false") {
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else if (data[0] == "true") {
                            shownotice();
                        }
                    }
                }
            })

        }


        function searchdish() {
            var name = $("#d_name").val();
            dishBySearch(name)

        }


        function dishBySearch(dishname) {
            // var search = "<input type='search' id='d_name'/>" +
            //     "<input type='button' onclick='searchdish()' value='搜索'>"
            // $("#timetab").html(search)

            var dishes_tab = "<br/><div align='center' ><table id='div_dish' cellspacing='0' cellpadding='0'><thead><tr>" +
                "<td>编号</td>" +
                "<td>菜品名称</td>" +
                "<td>菜品简介</td>" +
                "<td>单价</td>" +
                "<td>操作A</td>" +
                "<td>操作B</td>" +
                "</tr></thead></table></div>";

            $("#mainpage").html(dishes_tab + "<tbody>")

            //添加菜
            var new_dish = "<tr>" +
                "<td style='background: #d0e9c6'>#</td>" +
                "<td style='background: #d0e9c6'><input type='text' placeholder='输入菜名' id='n_name'></td>" +
                "<td style='background: #d0e9c6'><input type='text' placeholder='输入简介' id='n_intro'></td>" +
                "<td style='background: #d0e9c6'><input type='number' min='0' style='width: 98px;' max='999' value='0' id='n_price'></td>" +
                "<td style='background: #d0e9c6' colspan=''><input type='button' class='class_btn_add' value='添加菜品' onclick='insert_dish()'></td>" +
                "<td >#</td>" +
                "</tr>";
            $("#div_dish").append(new_dish)

            var str = ""
            $.ajax({
                url: "/exam/product/dish_by_name.php",
                data: "dishname="+dishname,
                type: "get",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        console.log(data)
                        $(data).each(function (i, val) {
                            str = ""
                            str += "<tr>" +
                                "<td>" + (i + 1) + "</td>" +
                                "<td>" + val[1] + "</td>" +
                                "<td><span title='" + val[2] + "'>" + val[2] + "</span></td>" +
                                "<td>" + val[3] + "</td>" +
                                "<td><input type='button' class='class_btn_add' value='修改单价' onclick='modify_dish(" + val[0] + "," + val[3] + ",\"" + val[1] + "\")'></td>" +
                                "<td><input type='button' class='class_btn_remove' value='移除' onclick='delete_dish(" + val[0] + ")'></td>" +
                                "</tr>";
                            $("#div_dish").append(str)
                        })
                        $("#mainpage").append("</tbody><span style='color: red;font-weight: bold'>鼠标悬浮查看详细介绍</span>")

                    }
                }

            })

        }

        function showdishes() {
            var search = "<input type='search' id='d_name'/>" +
                "<input type='button' onclick='searchdish()' value='搜索'>"
            $("#timetab").html(search)

            var dishes_tab = "<br/><div align='center' ><table id='div_dish' cellspacing='0' cellpadding='0'><thead><tr>" +
                "<td>编号</td>" +
                "<td>菜品名称</td>" +
                "<td>菜品简介</td>" +
                "<td>单价</td>" +
                "<td>操作A</td>" +
                "<td>操作B</td>" +
                "</tr></thead></table></div>";

            $("#mainpage").html(dishes_tab + "<tbody>")

            //添加菜
            var new_dish = "<tr>" +
                "<td style='background: #d0e9c6'>#</td>" +
                "<td style='background: #d0e9c6'><input type='text' placeholder='输入菜名' id='n_name'></td>" +
                "<td style='background: #d0e9c6'><input type='text' placeholder='输入简介' id='n_intro'></td>" +
                "<td style='background: #d0e9c6'><input type='number' min='0' style='width: 98px;' max='999' value='0' id='n_price'></td>" +
                "<td style='background: #d0e9c6' colspan=''><input type='button' class='class_btn_add' value='添加菜品' onclick='insert_dish()'></td>" +
                "<td >#</td>" +
                "</tr>";
            $("#div_dish").append(new_dish)

            var str = ""
            $.ajax({
                url: "/exam/product/showdishes.php",
                data: "",
                type: "get",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        // console.log(data)
                        $(data).each(function (i, val) {
                            str = ""
                            str += "<tr>" +
                                "<td>" + (i + 1) + "</td>" +
                                "<td>" + val[1] + "</td>" +
                                // "<td onmouseover='show_object(this)' onmouseout='hide_object(this)' ><span hidden >"+val[2]+"</span><span style='background: #5eb95e'>鼠标移入查看简介</span></td>" +
                                "<td><span title='" + val[2] + "'>" + val[2] + "</span></td>" +
                                "<td>" + val[3] + "</td>" +
                                "<td><input type='button' class='class_btn_add' value='修改单价' onclick='modify_dish(" + val[0] + "," + val[3] + ",\"" + val[1] + "\")'></td>" +
                                "<td><input type='button' class='class_btn_remove' value='移除' onclick='delete_dish(" + val[0] + ")'></td>" +
                                "</tr>";
                            $("#div_dish").append(str)
                        })
                        $("#mainpage").append("</tbody><span style='color: red;font-weight: bold'>鼠标悬浮查看详细介绍</span>")

                    }
                }

            })

        }

        function insert_dish() {
            var dish_name = $("#n_name").val();
            var dish_intro = $("#n_intro").val();
            var dish_price = $("#n_price").val();
            console.log(dish_name + "-" + dish_intro + "-" + dish_price)
            if (dish_name == "" || dish_intro == "" || dish_price == "") {
                alert("菜品各个属性不能为空！请重新填写！")
                return;
            }

            $.ajax({
                url: "/exam/manage/insert_dish.php",
                data: "dish_name=" + dish_name + "&dish_price=" + dish_price + "&dish_intro=" + dish_intro,
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        console.log(data)
                        if (data[0] == "false") {
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else if (data[0] == "true") {
                            showdishes();
                        }
                    }
                }
            })

        }

        function modify_dish(dish_id, price, dish_name) {
            // console.log("修改"+dish_name+"单价"+dish_id+"-"+price)
            price = prompt("修改【" + dish_name + "】定价为：", price)
            if (price == "" || price == null) {// 如果取消输入则会返回null
                return;
            }
            $.ajax({
                url: "/exam/manage/modify_dish.php",
                data: "dish_id=" + dish_id + "&price=" + price,
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        if (data[0] == "false") {
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else if (data[0] == "true") {
                            showdishes();
                        }
                    }
                }
            })

        }

        function delete_dish(dish_id) {
            // console.log("移除菜"+dish_id)
            $.ajax({
                url: "/exam/manage/delete_dish.php",
                data: "dish_id=" + dish_id,
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        if (data[0] == "false") {
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else if (data[0] == "true") {
                            showdishes();
                        }
                    }
                }
            })
        }

        function add_dish(dish_id) {
            console.log(dish_id)
            var quantity = 1;
            quantity = prompt("添加的数量？", quantity)
            console.log(quantity)
            $.ajax({
                url: "/exam/product/order_dish.php",
                data: "dish_id=" + dish_id + "&quantity=" + quantity,
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        console.log(data)
                        if (data[0] == "false") {
                            alert("请重新登陆！")
                            window.location = "index.html";
                        } else if (data[0] == "true") {
                            alert("添加成功！请到当前订单中查看。")
                        } else if (data[0] == "false-quantity") {
                            //点击取消
                        }
                    }
                }

            })
        }

        function show_object(obj) {
            obj.childNodes[0].removeAttribute("hidden");
            obj.childNodes[1].setAttribute("hidden", true);
        }

        function hide_object(obj) {
            obj.childNodes[0].setAttribute("hidden", true);
            obj.childNodes[1].removeAttribute("hidden");
        }


    </script>
</head>
<body style="background-repeat: repeat;" background="resource/images/bg3.jfif">

<!--<img src="" width="30px" height="50px">-->

<div class="nav" align="center" id="menu">
    <ul>
        <li><a id="m1" href="/exam/manage.php">首页</a></li>
        <li><a id="m2" href="/exam/manage.php?action=dishes">配置菜品</a></li>
        <li><a id="m3" href="/exam/manage.php?action=notice">编辑公告</a></li>
        <li><a id="m4" href="/exam/manage.php?action=comment">留言板</a></li>
        <li><a id="m5" href="/exam/manage.php?action=orders">查看订单</a></li>
        <li><a id="m8" href="/exam/manage.php?action=data">销售数据</a></li>
        <li><a id="m6" href="/exam/manage.php?action=users">用户管理</a></li>
        <li><a id="m7" href="/exam/manage.php?action=logout">退出登录</a></li>
    </ul>
</div>

<div id="title" align="center" style=""></div>
<div id="timetab" align="center" style=""></div>

<div id="mainpage" align="center" style=""></div>
<br/>

</body>
</html>