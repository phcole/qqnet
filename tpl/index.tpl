<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<script type="text/javascript" src="/js/jquery.min.js"></script>
<title><?php echo CONF_SITE_TITLE; ?></title>
<style type="text/css">
	div.large-block a{display:block;float:right;margin:2px;}
	img.large-title{width:200px;height:500px;background-color:yellow;}
	img.small-title{width:200px;height:150px;background-color:yellow;}
</style>
</head>
<body>
<div>
	<?php if ($user->uid > -1): ?>
	<?php echo $user->nickname ?>|<a id="logout" href="/?act=logout">注销</a>
	<?php else: ?>
	<a href="/?act=login">登录</a>
	<?php endif; ?>
</div>
<div class="large-block" style="margin:0 auto;width:1020px;">
	<?php foreach ($data['posts'] as $key => $value): ?>
	<?php if ($value->type == 'evaluate'): ?>
	<a href="/?pid=<?php echo $value->pid ?>"><img class="large-title" src="<?php echo $value->pic_large; ?>" alt="<?php echo $value->title; ?>"></a>
	<?php elseif ($value->type == 'forecast'): ?>
	<a href="#"><img class="large-title" src="<?php echo $value->pic_large; ?>" alt="<?php echo $value->title; ?>"></a>
	<?php elseif ($value->type == 'link'): ?>
	<a href="<?php echo $value->url ?>"><img class="large-title" src="<?php echo $value->pic_large; ?>" alt="<?php echo $value->title; ?>"></a>
	<?php endif; ?>
	<?php endforeach; ?>
</div>
<?php if ($user->uid > -1): ?>
<script type="text/javascript">
var btn = $('a#logout'), sended = false, restore = true;
btn.on("click", function(e){
	e.preventDefault();
	e.stopPropagation();
	if (sended == true)
		return;
	btn.text("正在注销...");
	$.post(
		btn.attr("href"),
		"",
		function(data){
			if (data["ret"] == true){
				restore = false;
				btn.text("注销成功！");
				btn.attr('href', '#');
				setTimeout(function(){location.href="/";}, 1000);
			} else {
				alert("注销失败，服务器发生了错误。");
			}
		},
		"json"
	).fail(function(){
		alert("网络错误，注销失败。");
	}).a =lways(function(){
		sended = false;
		if (restore){
			btn.text("注销");
			restore = true;
		}
	});
	sended = true;
	return false;
});
</script>
<?php endif; ?>
</body>
</html>