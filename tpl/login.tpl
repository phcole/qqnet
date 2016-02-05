<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<script type="text/javascript" src="/js/jquery.min.js"></script>
<title><?php echo CONF_SITE_TITLE; ?></title>
</head>
<body>
<form action="/?act=login" name="login_FORM" method="post">
<input name="ACTSUB" value="123" type="hidden">
<label>邮箱<input name="email" id="in_email" placeholder="输入邮箱" type="text"></label>
<label>密码<input name="password" id="in_password" placeholder="输入密码" type="password"></label>
<button type="button" name="head_login">登录</button>
</form>
<script type="text/javascript">
var _this = $("form[name=login_FORM]"), btn = $('button[name=head_login]'), restore = true;
btn.on("click", function(e){
	if ($('#in_email').val().length == 0 || $('#in_password').val().length == 0){
		alert("用户名或密码不能为空！");
		return;
	}
	btn.attr("disabled", "disabled");
	btn.text("登录中...");
	$.post(
		_this.attr("action"),
		_this.serialize(),
		function(data){
			if (data["ret"] == true){
				location.href="/";
				restore = false;
			} else {
				alert("用户名和密码错误！");
			}
		},
		"json"
	).fail(function(){
		alert("网络错误，登录失败！");
	}).always(function(){
		if (restore){
			btn.removeAttr("disabled");
			btn.text("登录");
			restore = true;
		}
	});
});
</script>
</body>
</html>