<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<title><?php echo $data['current_article']->title." - ".$data['post']->title." - ".CONF_SITE_TITLE; ?></title>
<style type="text/css">
	a{margin:0 5px;}
	em{font-style:normal;display:inline;margin:0 5px;}
	img{max-width:100%;}
</style>
</head>
<body>
<div style="margin:0 auto;width:1000px;">
	<a href="/">回到首页</a>
	<?php foreach ($data['articles'] as $key => $value): ?>
	&gt;&gt;
	<?php if ($value->aid == $data['current_article']->aid): ?>
	<em><?php echo $value->title; ?></em>
	<?php else: ?>
	<a href="/?pid=<?php echo $value->pid; ?>&aid=<?php echo $value->aid; ?>"><?php echo $value->title; ?></a>
	<?php endif; ?>
	<?php endforeach; ?>
	<h2><?php echo $data['post']->title." - ".$data['current_article']->title; ?></h2>
	<?php echo $data['current_article']->content; ?>
</div>
<script type="text/javascript" src="js/imgview.js"></script>
</body>
</html>