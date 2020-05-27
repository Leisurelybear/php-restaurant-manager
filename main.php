<?php
include("manage/verify.php");
if (!isset($_COOKIE["username"])) {

} else if (verifyType($_COOKIE["username"]) != "guest") {
    header("location:index.html");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>订餐系统主页</title>
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
            transform: rotate(10deg);
            /*transition: all 0.5s;*/
            /*transform: scale(1.2);*/
            transition: all 0.25s;
            color: white;
            background: #ebccd1;
            border-radius: 10px;

        }

        .nav a:focus {
            color: white;
            background: #ee5f5b;
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

        /*第一行背景为浅橙色*/
        thead > tr > td {
            font-weight: bold;
            background-color: #F1D4AF;
        }
        tr > td:hover {
            background: #1e347b;
            color: #dddddd;
            /*transform: scale(1.1);*/
            /*transition: all 0.25s;*/
        }
        input[type="button"]:hover{
            transform: scale(1.2);
            transition: all 0.25s;
        }

        .class_btn_add {
            border: 0px;
            background: #ebccd1;
            width: 100px;
            cursor: pointer;
        }

        .class_btn_remove {
            border: 0px;
            background: #ee5f5b;
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

        #btn_comment {
            border-radius: 10px;
            background-color: #5eb95e;
            border-width: 0px;
            width: 80px;
            cursor: pointer;
        }

        #hotdiv{
            background: url(resource/images/hot1.jpg) 50%;
            background-size: 100% 100%;
            width: 600px;
            height: 300px;
            text-align: center;
        }
        li{
            list-style: none;

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
        var ORDER_PRICE = 0;

        onload = function () {
            islogined();

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
                showorders()
            } else if (action == "logout") {
                logout()
            } else if (action == "login") {
                window.location = "index.html"
            }
            $("#title").html("<h1>" + title + "</h1>")
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
                        console.log(data)
                        if (data[0] == "false") {
                            document.getElementById("current_order").setAttribute("hidden", true)
                            document.getElementById("out").setAttribute("hidden", true)
                            document.getElementById("login").removeAttribute("hidden")

                        } else {
                            document.getElementById("current_order").removeAttribute("hidden")
                            document.getElementById("out").removeAttribute("hidden")
                            document.getElementById("login").setAttribute("hidden", true)
                        }
                    }
                }
            })
        }

        function evaluate(msg) {

            if (msg == null || msg == null) {
                return;
            }

            $.ajax({
                url: "/exam/product/evaluate.php",
                data: "msg=" + msg,
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        if (data[0] == "false") {
                            alert("请先登陆！")
                            window.location = "index.html";
                        } else {

                        }
                    }
                }
            })
        }

        function checkout() {
            console.log("结账功能")
            if (ORDER_PRICE == 0) {
                alert("您还没有点餐呢！不可以结账哦！")
                return;
            }
            $.ajax({
                url: "/exam/product/checkout.php",
                data: "",
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        console.log(data)
                        if (data[0] == "false") {
                            alert("请先登陆！")
                            window.location = "index.html";
                        } else {
                            var msg = prompt("结账成功！请您对本单进行评价，如果不评价请点击取消。")
                            evaluate(msg);//评价
                            window.location = "main.php"
                        }
                    }
                }
            })
        }

        function showorders() {
            var dishes_tab = "<br/><div align='center' style='width:1000px;'>" +
                "<div style='background: gold'>合计：<span style='font-weight: bold' id='price'></span>元&nbsp;&nbsp;" +
                "<input onclick='checkout()' type='button' value='结账'>" +
                "</div>" +
                "<table cellspacing='0' id='div_dish' cellpadding='0'><thead><tr>" +
                "<td>序号</td>" +
                "<td>菜品名</td>" +
                "<td>数量</td>" +
                "<td>总价</td>" +
                "<td>操作</td>" +
                "</tr></thead><tbody id='div_dish'></tbody></table></div>";
            $("#mainpage").html(dishes_tab)
            var price = 0;

            $.ajax({
                url: "/exam/product/showorders.php",
                data: "",
                type: "get",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        // console.log(data)
                        if (data[0] == "false") {
                            alert("请先登陆！")
                            window.location = "index.html";
                        } else {

                            $(data).each(function (i, val) {
                                price += val[3];//计算价钱
                                str = ""
                                str += "<tr>" +
                                    "<td>" + (i + 1) + "</td>" +
                                    "<td>" + val[1] + "</td>" +
                                    "<td><input type='number' min='1' max='20' onchange='change_dish(" + val[0] + ",this.value)' style='width: 90px' value='" + val[2] + "'></td>" +
                                    "<td>" + val[3] + "</td>" +
                                    "<td><input type='button' class='class_btn_remove' onclick='remove_dish(" + val[0] + ")' value='移除'></td>" +
                                    "</tr>";
                                $("#div_dish").append(str)
                            })
                        }
                        $("#price").html(price)
                        ORDER_PRICE = price;
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
                            alert("请先登陆！")
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


        function postcomment() {
            if ($("#comment").val() == "" || $("#comment").val().length < 5) {
                alert("评论字数太少！");
                return
            }
            $.ajax({
                url: "/exam/product/postcomment.php",
                data: "msg=" + $("#comment").val(),
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        // console.log(data)
                        if (data[0] == "false") {
                            alert("请先登陆！")
                            window.location = "index.html";
                        } else {
                            alert("评论成功！")
                            showcomment()
                        }
                    }
                }

            })
        }

        function showcomment() {
            var comment = "<br/><div>" +
                "<textarea name='comment' id='comment' placeholder='请在此区域写入留言内容，点击提交可发表留言。' cols='60' rows='5' style='resize: none'>" +
                "</textarea>" +
                "<br/><input type='button' value='提交' id='btn_comment' onclick='postcomment()'>" +
                "" +
                "</div>";
            $("#mainpage").html(comment)
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
            var dishes_tab = "<br/><div align='center'><table cellspacing='0' cellpadding='0'><thead><tr>" +
                "<td>编号</td>" +
                "<td>日期</td>" +
                "<td>通知内容</td>" +
                // "<td>操作</td>" +
                "</tr></thead><tbody id='div_dish'></tbody></table></div>";
            $("#mainpage").html(dishes_tab)
            var str = ""
            $.ajax({
                url: "/exam/product/shownotice.php",
                data: "",
                type: "get",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        $(data).each(function (i, val) {
                            str = ""
                            str += "<tr>" +
                                "<td>" + (i + 1) + "</td>" +
                                "<td>" + val[2] + "</td>" +
                                "<td>" + val[1] + "</td>" +
                                // "<td><input type='button' class='class_btn_add' value='添加'></td>" +
                                "</tr>";
                            $("#div_dish").append(str)
                        })
                        // $("#mainpage").append("")

                    }
                }

            })
        }


        function showdishes() {
            var hotdish = "<div id='hotdiv'><br/><br/><br/><br/>热门菜品<br/><ol id='hot'>" +

                "</ol><br/></div>"
            $("#mainpage").append(hotdish)
            var str = ""
            $.ajax({
                url: "/exam/product/hotdish.php",
                data: "",
                type: "get",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        console.log(data)
                        $(data).each(function (i, val) {
                            str = "<li>" + val[1] +
                                "               " + val[2] + "元/例" +
                                "               " +
                                "<input type='button' class='class_btn_add' value='加入订单' onclick='add_dish(" + val[0] + ")'>" +
                                "</li>";
                            $("#hot").append(str)
                        })
                    }
                }

            })


            var dishes_tab = "<br/><div align='center'><table cellspacing='0' cellpadding='0'><thead><tr>" +
                "<td>编号</td>" +
                "<td>菜品名称</td>" +
                "<td>菜品简介</td>" +
                "<td>单价</td>" +
                "<td>操作</td>" +
                "</tr></thead><tbody id='div_dish'></tbody></table></div>";

            $("#mainpage").append(dishes_tab)
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
                                "<td>" + val[0] + "</td>" +
                                "<td>" + val[1] + "</td>" +
                                // "<td onmouseover='show_object(this)' onmouseout='hide_object(this)' ><span hidden >"+val[2]+"</span><span style='background: #5eb95e'>鼠标移入查看简介</span></td>" +
                                "<td><span title='" + val[2] + "'>" + val[2] + "</span></td>" +
                                "<td>" + val[3] + "</td>" +
                                "<td><input type='button' class='class_btn_add' value='添加' onclick='add_dish(" + val[0] + ")'></td>" +
                                "</tr>";
                            $("#div_dish").append(str)
                        })
                        $("#mainpage").append("<span style='color: red;font-weight: bold'>鼠标悬浮查看详细介绍</span>")

                    }
                }

            })
        }

        function add_dish(dish_id) {
            // console.log(dish_id)
            var quantity = 1;
            quantity = prompt("添加的数量？", quantity)
            // console.log(quantity)
            if (quantity == "" || quantity == null) {// 如果取消输入则会返回null
                return;
            }
            $.ajax({
                url: "/exam/product/order_dish.php",
                data: "dish_id=" + dish_id + "&quantity=" + quantity,
                type: "post",
                dataType: "json",
                statusCode: {
                    200: function (data) {
                        console.log(data)
                        if (data[0] == "false") {
                            alert("请先登陆！")
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


<div class="nav" align="center">
    <ul>
        <li><a href="/exam/main.php">首页</a></li>
        <li><a href="/exam/main.php?action=dishes">查看菜品</a></li>
        <li><a href="/exam/main.php?action=notice">查看公告</a></li>
        <li><a href="/exam/main.php?action=comment">留言板</a></li>
        <li id="current_order" hidden="true"><a href="/exam/main.php?action=orders">当前订单</a></li>
        <li id="out" hidden="true"><a href="/exam/main.php?action=logout">退出登录</a></li>
        <li id="login"><a href="/exam/main.php?action=login">登录</a></li>

    </ul>
</div>
<div id="title" align="center" style=""></div>
<br/>
<div id="mainpage" align="center" style="">
</div>
<br/>

</body>
</html>