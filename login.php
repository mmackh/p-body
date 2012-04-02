<?php if($_POST['user'] == '' && $_POST['password'] == '') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Thoughts & Notes: Login</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>
<body><h1>Thoughts & Notes<a class="permalink" href="http://www.twitter.com/mmackh"><br/>
Curated by @mmackh | </a><a class="permalink" href="feed.xml"> RSS</a></h1><div class="hr"></div>
<form action="login.php" method="post">
<b>User: </b><input type="text" name="user" />
<b>Password: </b><input type="text" name="password" />
<input type="submit" value="Login"/>
</form>
</body>
</html>
<?php } else if ($_POST['user'] == 'xxxxxxx' && $_POST['password'] == 'xxxxxxx') { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Thoughts & Notes: Welcome</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>
<body><h1>Thoughts & Notes<a class="permalink" href="http://www.twitter.com/mmackh"><br/>
Curated by @mmackh | </a><a class="permalink" href="feed.xml"> RSS</a></h1><div class="hr"></div>
</body>
</html>
<?php 
$i = 0;
$files = array_reverse(glob('raw/*.txt'));
$nof = count($files);
$ii	 = $nof;
foreach ($files as $file) {
	$i ++;
	if ($ii == $nof) {
		$status = '<form action="login.php" method="POST" style="margin: 0; padding: 0; display:inline;">
				<input type="submit" name="Delete" value="Delete"/>
				<input type="hidden" name="user" value="xxxxxxx" />
				<input type="hidden" name="password" value="xxxxxxx" />
				<input type="hidden" name="id" value="'.$file.'" />
				</form>';
	} else { $status = ''; }
	$ii --;
    $posts .= '<div  style="display:inline;" >'.str_replace('.txt','',str_replace('raw/','',$file)).'</div>'.$status.'<br /><br />';
} 
$i ++;
echo '<b>Publish</b> #'.$i.'<b>:</b>'; ?>
<form action="login.php" method="post"
enctype="multipart/form-data">
<br /><b>Title: </b><input type="text" name="title" />
<br />
<label for="file"><b>Document:</b></label>
<input type="file" name="file" id="file" /> 
<br />
<input type="hidden" name="user" value="xxxxxxx" />
<input type="hidden" name="password" value="xxxxxxx" />
<input type="hidden" name="postnbr" value="<?php echo $i ?>" />
<input type="submit" name="submit" value="Submit" />
</form>
<h4><div class="hr"></div><b>Published:</b></h4>
<?php 
if ($_POST['Delete'] != '') {
@unlink($_POST['id']);
header ('Location: generate.php');
}
if ($_POST['postnbr'] != '') {
move_uploaded_file($_FILES["file"]["tmp_name"],
"raw/" . $_POST['postnbr'].'-'.$_POST['title'].'.txt');
header ('Location: generate.php');
}
echo $posts; ?>
<?php } else { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Thoughts & Notes: Failed</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>
<body><h1>Thoughts & Notes<a class="permalink" href="http://www.twitter.com/mmackh"><br/>
Curated by @mmackh | </a><a class="permalink" href="feed.xml"> RSS</a></h1><div class="hr"></div>
<h4>Wrong Credentials</h4>
</body>
</html>
<?php } ?>
