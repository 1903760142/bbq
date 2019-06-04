<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="jquery-3.2.1.min.js"></script>
</head>
<body>
    <form action="{{url('domeadd')}}" method="post" enctype="multipart/form-data">
        @csrf
        <a href="">查看商品</a>
        <table>
            <tr>
                <td>用户名</td>
                <td><input type="text" name="name" id="name"></td>
            </tr>
            <tr>
                <td>密码</td>
                <td><input type="password" name="pwd" id="pwd"></td>
            </tr>
            <tr>
                <td><input type="button" value="立即登录" id="btn"></td>
            </tr>
        </table>

</form>
</body>
<script src="jquery-3.2.1.min.js"></script>
<script>
    $("#btn").click(function () {
        alert(1);
    })
</script>
</html>