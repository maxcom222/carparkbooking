<!doctype html>
<html>
	<head>
		<title></title>
		<?php
		foreach ($controller->getCss() as $css)
		{
			echo '<link type="text/css" rel="stylesheet" href="'.(isset($css['remote']) && $css['remote'] ? NULL : PJ_INSTALL_URL).$css['path'].$css['file'].'" />';
		}
		?>
	</head>
	<body>
	<?php
	require $content_tpl;
	foreach ($controller->getJs() as $js)
	{
		echo '<script src="'.(isset($js['remote']) && $js['remote'] ? NULL : PJ_INSTALL_URL).$js['path'].$js['file'].'"></script>';
	}
	?>
	</body>
</html>