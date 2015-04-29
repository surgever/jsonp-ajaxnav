# JSONP ajaxNav v.0.3
Professionally accelerate your site navigation. Link directly thorugh ajax for improving the user experience. Now ajaxNav features history.

Developer friendly. Includes a versatile API. Created with simplicity in mind. 

## Documentation
### Instructions
JSONP ajaxNav it's a couple of shortcuts that I use to use extensively for all the web projects that needed ajax navigation, hence the name.
All the javascript functionalities are bundled together and aims to be compatible enough to be deployed in most enviroments (disclaimer: no guarantees :P).
I have a couple of new additions that i will be adding as soon as I test them thoroughly.
As this version with the "JSONP" prefix requires quite a few server work to be implemented in any site, I will be releasing another repo which won't require jsonp output.

### Steps

First of all you have to stop the output of the page when a jsonp call requests it and serve a `jsonp callback` instead.
In a PHP template we won't print innecessary code and just output the content in a callback like this:

```php
<?php
	// First part of the server script, make sure
	// we don't print the document head for jsonp calls...
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
			<header id="banner"><?-- etc. --></header>
			<nav id="menu"><?-- etc. --></nav>
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
				<h1>Lorem ipsum</h1>
				<p>Sit amet, consectetur <a href="<?php echo $site_url; ?>/index.php?sec=adipiscing">adipiscing</a> elit. Nunc fringilla cursus scelerisque. </p>
				<p><a href="<?php echo $site_url; ?>">Back ➜</a></p>
<?php
	// Final part of the server script, make sure we don't print the document footer for jsonp calls...
	if(!$_GET["callback"]) {
?>
			</main>
			<footer><?-- etc. --></footer>
		</div>
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
```

Then you want to include the `jQuery` and `ajaxNav` files at the
 bottom of your document (preferently, it really works anywhere you want).
 Also we will create an instance of ajaxNav and bind ajaxNav to all the hyperlinks

```html
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script type="text/javascript" src="ajaxNav.js"></script>
	<script type="text/javascript">
		var myAjaxNavSite = new ajaxNav('http://example.com');
		$(document).ready(function(){
			$('a').on('click touchstart', myAjaxNavSite.open);
		});
	</script>
</body>
```

## That's it!
You can watch a [demo here](http://surgever.com/ajaxnav/demo).

## License MIT
Project License can be found [here](LICENSE.md).