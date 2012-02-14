<?php

$author = 'Maximilian';
$twitter = 'mmackh';
$website = 'http://restfulpanda.com/';
$blog_name = 'Thoughts & Notes';

$stylesheet = '<style type="text/css">
	body {
	background-color:#F5F5F5;
	}
		
	h1 {
	font-family: "Adobe Caslon Pro", "Hoefler Text",Georgia, "Times New Roman", Times, serif;
	color: #777777;
	margin: 0;
	padding: 0px 0px 6px 0px;
	font-size: 27px;
	line-height: 150px;
	font-weight: normal;
	text-align: center;
	font-style: italic;
	}
	
  	h3 { 
	font-family: Georgia, "Times New Roman", Times, serif;
	font-size:24px;
	font-color:24px;
	margin-top: 5px; margin-bottom: 0px;
	text-align: center;
	font-weight: normal;
	color: #222;
	}
	
	h4 {
	font-family: "Lucida Grande", Tahoma;
	font-size: 10px;
	font-weight: lighter;
	font-variant: normal;
	text-transform: uppercase;
	color: #666666;
	margin-top: 10px;
	text-align: center!important;
	letter-spacing: 0.3em;
	}
	
	a:link {color:#555555;
	text-decoration:none;}
	a:visited {color:#333333;
	text-decoration:none;}
	a:hover {color:#111111;
	text-decoration:none;}
	a:active {color:#111111
	text-decoration:none;;}
</style>
';

$stylesheet_article = '<style type="text/css">
body{
    margin: 0 auto;
    font-family: Georgia, Palatino, serif;
    background-color: #F5F5F5;
    color: #333333;
    line-height: 1;
    max-width: 560px;
    padding: 30px;
}

.permalink { 
	font-style: normal;
	text-decoration: none;
	color: #888888;
    font-size: 14px;
    margin: 10px;
}

.hr {
    border: none;
    border-bottom:1px solid #FFFFFF; 
    border-top:1px solid #dcdcdc; 
    clear:both; 
    height:0; 
    width: 100%;
}
        
h1, h2, h3, h4 {
    color: #111111;
    font-weight: 400;
}
h1, h2, h3, h4, h5, p {
    margin-bottom: 24px;
    padding: 0;
    
}
h1 {
    font-size: 28px;
    
}
h2 {
    font-size: 20px;
    margin: 24px 0 6px;
}
h3 {
    font-size: 18px;
}
h4 {
    font-size: 17px;
}
h5 {
    font-size: 17px;
}
	a:link {color:#777777;}
	a:visited {color:#333333;
	text-decoration:none;}
	a:hover {color:#111111;}
	a:active {color:#111111;}
ul, ol {
    padding: 0;
    margin: 0;
}
li {
    line-height: 24px;
}
li ul, li ul {
    margin-left: 24px;
}
p, ul, ol {
    font-size: 16px;
    line-height: 24px;
    max-width: 540px;
}
pre {
    padding: 0px 24px;
    max-width: 800px;
    white-space: pre-wrap;
}
code {
    font-family: Consolas, Monaco, Andale Mono, monospace;
    line-height: 1.5;
    font-size: 13px;
}
aside {
    display: block;
    float: right;
    width: 390px;
}
blockquote {
    border-left:.5em solid #eee;
    padding: 0 2em;
    margin-left:0;
    max-width: 476px;
}
blockquote  cite {
    font-size:14px;
    line-height:20px;
    color:#bfbfbf;
}
blockquote cite:before {
    content: "\2014 \00A0";
}

blockquote p {  
    color: #666;
    max-width: 460px;
}
hr {
    width: 540px;
    text-align: left;
    margin: 0 auto 0 0;
    color: #999;
}</style>';

function urlconvert($string) {
    $string = strtolower($string);
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    $string = preg_replace("/[\s-]+/", " ", $string);
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string.'.html';
}

function count_words($string)
{
    $string = htmlspecialchars_decode(strip_tags($string));
    if (strlen($string)==0)
        return 0;
    $t = array(' '=>1, '_'=>1, "\x20"=>1, "\xA0"=>1, "\x0A"=>1, "\x0D"=>1, "\x09"=>1, "\x0B"=>1, "\x2E"=>1, "\t"=>1, '='=>1, '+'=>1, '-'=>1, '*'=>1, '/'=>1, '\\'=>1, ','=>1, '.'=>1, ';'=>1, ':'=>1, '"'=>1, '\''=>1, '['=>1, ']'=>1, '{'=>1, '}'=>1, '('=>1, ')'=>1, '<'=>1, '>'=>1, '&'=>1, '%'=>1, '$'=>1, '@'=>1, '#'=>1, '^'=>1, '!'=>1, '?'=>1); // separators
    $count= isset($t[$string[0]])? 0:1;
    if (strlen($string)==1)
        return $count;
    for ($i=1;$i<strlen($string);$i++)
        if (isset($t[$string[$i-1]]) && !isset($t[$string[$i]])) // if new word starts
            $count++;
    return $count;
}


$files = array_reverse(glob('raw/*.txt'));

$i = count($files);

foreach($files as $file) {
	
	$urlname = urlconvert(substr($file,4,-3));
	include_once "markdown.php";
	$html = Markdown(file_get_contents ($file));
	$wc = count_words($html);
	$title = str_replace($i.'-','',substr($file,4,-4));
	$date = date("M d, Y", filemtime($file));
	$article = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>'.$title.'</title>
'.$stylesheet_article.'
</head>
<body><h1>'.$title.'<a class="permalink" href="'.$website.$urlname.'">'.$date.' | &#8734;</a></h1><div class="hr"></div>'.$html.'</body>
</html>';
	file_put_contents($urlname, $article);
	
	$index .= '<h3><a href="'.$urlname.'">'.$title.'</a></h3>
	<h4>Posted on '.$date.' by '.$author.' ('.$wc.' Words)
	<br><br><a href="'.$website.$urlname.'">&#8734;</a></h4>';
	
	$i--;
	
}

$result = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
'.$stylesheet.'
</head>
<body>
<h1>'.$blog_name.'</h1>
'.$index.'
<br>
<br>
<br>
<center>
<a href="http://www.twitter.com/'.$twitter.'"> 
<img src="images/twitter.png" alt="twitter"/>
</a>
</body>
</html>';

file_put_contents('index.html', $result);
echo $result;



?>