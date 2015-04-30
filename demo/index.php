<?php 
	require_once('data.php');
	$current = 'gaudi';
	if(isset($_GET['page'])  && isset($pages[$_GET['page']]) ) $current = $_GET['page'];
	$content = $pages[$current];
	$site_url = "http://$_SERVER[HTTP_HOST]/demo/";
	$enabled = (!isset($_GET['ajaxnav']) || $_GET["ajaxnav"]!='no') ? true : false;  
	// First part of the server script, make sure we don't print the document head for jsonp calls...
	if(!isset($_GET['callback']) || !$_GET["callback"]) {
?><!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"> 
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<title><?php echo $content['title']; ?> | Demo ajaxNav</title>
		<meta name="description" content="Inspiration for Article Intro Effects" />
		<meta name="keywords" content="title, header, effect, scroll, inspiration, medium, web design" />
		<meta name="author" content="Codrops" />
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<!--[if IE]>
  		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body>
		<div id="container" class="container intro-effect-ajaxNav ">
			<!-- Top Navigation -->
			<header class="header" >
				<div class="bg-img"><img src="img/<?php echo $content['img']; ?>.jpg" alt="Background Image"/></div>
				<div class="title">
					<h1><?php echo $content['title']; ?></h1>
				</div>
			</header>
			<button class="trigger" data-info="Read the article"><span>Trigger</span></button>
			<ul class="grid">
				<?php 
				$grid = '';
				foreach($pages as $slug=>$link) 
					//$grid .= '<li class="thumb-'.$slug.'"><a href="?page='.$slug.'"><h2>'.$link['title'].'</h2></a></li>'; // rewrite url
					if($enabled) $grid .= '<li class="thumb-'.$slug.($current==$slug?' current':'').'"><a href="?page='.$slug.'"><h2>'.$link['title'].'</h2> <b>+</b></a></li>';
					else $grid .= '<li class="thumb-'.$slug.($current==$slug?' current':'').'"><a href="?page='.$slug.'&ajaxnav=no"><h2>'.$link['title'].'</h2></a></li>';
					 // no rewrite url
				echo $grid.'';
				?>
			</ul>
			<article class="content">
<?php
	// ...and let's prepare the data we will output
	} else {
		global $data;
		$data['title'] = $content['title'];
		$data['img'] = $content['img'];
		ob_start();
	}
?>	
				<div class="title">
					<h1>
						<?php echo $content['title']; ?> 
					</h1>
					<p class="subline">ajaxNav Demo // <a href="https://github.com/surgever/jsonp-ajaxnav">Read More</a></p>
					<p>by <strong>Sergio Oliver</strong> &#8212; Design from <em>"Inspiration for Article Intro Effects"</em> at <cite>Codrops</cite></p>
				</div>
				<div class="copy">
					<?php echo $content['content']; ?>
				</div>
<?php
	// Final part of the server script, make sure we don't print the document footer for jsonp calls...
	if(!isset($_GET['callback']) || !$_GET["callback"]) {
?>
				</article>
			</div><!-- /container -->
			<script src="js/jquery.min.js"></script>
<!-- 			<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
			<script src="js/slideup.js"></script>
			<?php if(isset($_GET['ajaxnav']) && $_GET["ajaxnav"]=='no') echo "<!-- Disabled ajaxNav:\r\n"; ?>
			<script src="../ajaxNav.js"></script>
			<script>
				/* Cache of some DOM elements, for performance */
				var cacheDOM = {container:$('#container')};
					cacheDOM.content = cacheDOM.container.find('article.content');
					cacheDOM.thumbs = cacheDOM.container.find('ul.grid li');
					cacheDOM.img = cacheDOM.container.find('header.header img')[0];
					cacheDOM.h1 = cacheDOM.container.find('header.header h1')[0];
				/* Preload the main images, for performance */
				function preloadImg() {
					var img = new Image();
					img.src = arguments[1];
				};
				$(['img/1.jpg','img/2.jpg','img/3.jpg','img/4.jpg','img/5.jpg','img/6.jpg']).each(preloadImg);

				/** ajaxNav:
				 *	This is the code specifically customized for this demo, you can see easier examples on the readme
				 */
				 
				// First initialize it with "new ajaxNav" and then pass it the absolute url */
				var myAjaxNavSite = new ajaxNav('<?php echo $site_url; ?>');
				
				// Now as an example of the API let's pass it some callbacks, in your element to be replace is #content, you don't really need any callback */
				myAjaxNavSite.callbacks = {
					preQuery: function(sec,element) {
						var classSelector = '.thumb-'+sec.replace('?page=','');
						cacheDOM.thumbs.removeClass('current').filter(classSelector).addClass('current');
					},
					putSec: function(data) {
						cacheDOM.h1.innerHTML = data.title;
						cacheDOM.img.src = 'img/'+data.img+'.jpg';
						cacheDOM.container.removeClass('fadein');
						setTimeout(function(content){
							return function() {
								cacheDOM.content.html(content);
							}
						}(data.contents),800);
						setTimeout(function(content){
							cacheDOM.container.addClass('fadein');
						},950);
						setTimeout(function(content){
							$('body').removeClass('loading');
						},2000);
					},
					ready: function() {void 0},
					closeSec: slideUp
				};
				$(document).ready(function(){
					$(document).keyup(function(e) {if (e.keyCode == 27) myAjaxNavSite.close();});
					$('ul.grid b').on('click touchstart', slideUp);
				});
		</script>
		<?php  echo "/ Disabled ajaxNav -->\r\n"; ?>
	</body>
</html>
<?php 
	// ...and let's output the data that we have collected!
} else {
	global $data;
	$data['contents'] = preg_replace(array('/\>[^\S ]+/s','/[^\S ]+\</s','/(\s)+/s'), array('>','<','\\1'),ob_get_contents());
	ob_end_clean();
	echo $_GET["callback"].'('.json_encode($data).');';
}
?>