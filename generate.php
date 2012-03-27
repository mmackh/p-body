<?php
// Filling in of details about the author, his twitter username, etc.
$author    = 'Maximilian';
$twitter   = 'mmackh';
$website   = 'http://restfulpanda.com/';
$blog_name = 'Thoughts & Notes';
$rss_items = 10;

// Stylesheet of the Main homepage
$stylesheet = '<link href="css/main.css" rel="stylesheet" type="text/css" />';

// Stylesheet OR Scripts of the an Article
$stylesheet_article = '<link href="css/article.css" rel="stylesheet" type="text/css" />';

// Makes all the URLs SEO friendly (i.e. Declutters your titles and makes them accessible)
function urlconvert($string)
{
    $string = strtolower($string);
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    $string = preg_replace("/[\s-]+/", " ", $string);
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string . '.html';
}

// Counts words and lists them under the Title in the main page.
function count_words($string)
{
    $string = htmlspecialchars_decode(strip_tags($string));
    if (strlen($string) == 0)
        return 0;
    $t     = array(
        ' ' => 1,
        '_' => 1,
        "\x20" => 1,
        "\xA0" => 1,
        "\x0A" => 1,
        "\x0D" => 1,
        "\x09" => 1,
        "\x0B" => 1,
        "\x2E" => 1,
        "\t" => 1,
        '=' => 1,
        '+' => 1,
        '-' => 1,
        '*' => 1,
        '/' => 1,
        '\\' => 1,
        ',' => 1,
        '.' => 1,
        ';' => 1,
        ':' => 1,
        '"' => 1,
        '\'' => 1,
        '[' => 1,
        ']' => 1,
        '{' => 1,
        '}' => 1,
        '(' => 1,
        ')' => 1,
        '<' => 1,
        '>' => 1,
        '&' => 1,
        '%' => 1,
        '$' => 1,
        '@' => 1,
        '#' => 1,
        '^' => 1,
        '!' => 1,
        '?' => 1
    ); // separators
    $count = isset($t[$string[0]]) ? 0 : 1;
    if (strlen($string) == 1)
        return $count;
    for ($i = 1; $i < strlen($string); $i++)
        if (isset($t[$string[$i - 1]]) && !isset($t[$string[$i]])) // if new word starts
            $count++;
    return $count;
}

//Ensures that the latest entry is posted first. 
$files = array_reverse(glob('raw/*.txt'));

//Counting the items in the "Raw Directory"
$counter = 0;
$i       = count($files);

//Converts every file from Markdown to HTML and gets the modification date to figure out when something was posted.
foreach ($files as $file) {
    $urlname = urlconvert(substr($file, 4, -3));
    include_once "markdown.php";
    $html    = Markdown(file_get_contents($file));
    $wc      = count_words($html);
    $title   = str_replace($i . '-', '', substr($file, 4, -4));
    $date    = date("M d, Y", filemtime($file));
    preg_match('/^([^.!?]*[\.!?]+){0,3}/', strip_tags($html), $abstract);
	$abstract = preg_replace('/\s+/', ' ',trim($abstract[0]));
    $article = '
<html>
<head>
<title>' . $title . '</title>
' . $stylesheet_article . '
</head>
<body><h1>' . $title . '<a class="permalink" href="' . $website . $urlname . '"><br>' . $date . ' | &#8734;</a></h1><div class="hr"></div>' . $html . '</body>
</html>';
//Writes the Articles to the disk
    file_put_contents($urlname, $article);
//Stores each article's title, link, author and wordcount to a string. For Main homepage.    
    $index .= '<h3><a href="' . $urlname . '">' . $title . '</a></h3>
	<p><b>' . $date.':</b> '.$abstract.' <br /><small><small><i>(' . $wc . ' Words)</i></small></small></p><div class="hr"></div>';
    $i--;
   
//Carefully counts how many items should be in the RSS feed
    if ($counter < $rss_items) {
        $rss_item .= '<item><title>' . $title . '</title><description>' . htmlspecialchars($html) . '</description><pubDate>' . date("Y-m-d H:i", filemtime($file)) . '</pubDate><link>' . $website . $urlname . '</link></item>';
        $counter++;
    } else {
    }
}

//Generates the RSS Feed
$feed = '<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"><channel><title>Thoughts and Notes (http://restfulpanda.com)</title>
<description>Authored by '.$author.' Full content RSS feed. Let me know if you have any suggestions.</description>
<link>http://restfulpanda.com</link>' . $rss_item . '</channel></rss>';
file_put_contents('feed.xml', $feed);

//Constructs the homepage
$result = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>' . $blog_name . '</title>
' . $stylesheet . '
</head>
<body><a href="http://github.com/inoads/p-body"><img style="position: absolute; top: 0; right: 0; border: 0;" src="images/fork.png" alt="Fork me on GitHub"></a><h1>' . $blog_name . '<a class="permalink" href="http://www.twitter.com/'.$twitter.'"><br/>
Curated by @' . $twitter . ' | </a><a class="permalink" href="feed.xml"> RSS</a></h1><div class="hr"></div>
' . $index . '
</body>
</html>';

//Writes the homepage and echos the result
file_put_contents('index.html', $result);
echo $result;
?>