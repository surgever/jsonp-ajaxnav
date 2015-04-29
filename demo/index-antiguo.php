<?php
$site_url = 'http://surgever.com/ajaxnav/demo';
$page_title = 'Demo ajaxNav';
switch ($_GET["sec"]) {
    case 'adipiscing':
        $page_title = 'Adipiscing | '.$page_title;
		$body_classes = 'sec-adipiscing';
		$content = '<h1>Adipiscing & Edipo</h1>
				<p>Edipo (en griego antiguo Οἰδίπους, cuyo significado es pies hinchados ) era un rey mítico de Tebas, hijo de Layo y Yocasta que, sin saberlo, mató a su propio padre y desposó a su 
					 <a href="'. $site_url .'/index.php?sec=fringilla">fringilla</a> . </p>
				<p><a href="'. $site_url .'/">Back ➜</a></p>';
        break;
    case 'fringilla':
        $page_title = 'Fringilla | '.$page_title;
		$body_classes = 'sec-fringilla';
		$content = '<h1>Fringilla & Priscilla</h1>
				<p>Priscilla Betti, de son vrai nom Préscillia Betti, est une chanteuse et actrice française née le 2 août 1989 à Nice (Alpes-Maritimes)
					 <a href="'. $site_url .'/index.php?sec=adipiscing">adipiscing</a> . </p>
				<p><a href="'. $site_url .'/">Back ➜</a></p>';
        break;
    default:
		$body_classes = 'home';
		$content = '<h1>Home: Lorem ipsum</h1>
				<p>Sit amet, consectetur <a href="'. $site_url .'/index.php?sec=adipiscing">adipiscing</a> elit.</p>
				<p>Nunc <a href="'. $site_url .'/index.php?sec=fringilla">fringilla</a> cursus scelerisque. </p>';
}
	// First part of the server script, make sure we don't print the document head for jsonp calls...
	if(!$_GET["callback"]) {
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $page_title; ?></title>
	</head>
	<body class="<?php echo $body_classes; ?>">
		<div class="wrapper">
			<header id="banner"><!-- etc. --></header>
			<nav id="menu"><!-- etc. --></nav>
			<main id="content">
<?php
	// ...and let's prepare the data we will output
	} else {
		global $data;
		$data['title'] = $page_title;
		$data['bodyclasses'] = $body_classes;
		ob_start();
	}
?>	
				<!-- And now is the time for the actual bits of content: -->
				<?php echo $content; ?>
<?php
	// Final part of the server script, make sure we don't print the document footer for jsonp calls...
	if(!$_GET["callback"]) {
?>
			</main>
			<footer><!-- etc. --></footer>
		</div>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script type="text/javascript" src="ajaxNav.js"></script>
		<script type="text/javascript">
			var myAjaxNavSite = new ajaxNav('<?php echo $site_url; ?>/');
			$(document).ready(function(){
				$('a').on('click touchstart', myAjaxNavSite.open);
			});
		</script>
	</body>
</html>
<?php 
	// ...and let's output the data that we have collected!
} else {
	global $data;
	$data['contents'] = ob_get_contents();
	ob_end_clean();
	echo $_GET["callback"].'('.json_encode($data).');';
}
?>