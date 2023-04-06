<?php
$base_url = "https://f5e5-158-101-98-242.ngrok.io";
$current_path = isset($_GET['path']) ? $_GET['path'] : '';
$current_directory = basename($current_path) ?: 'Root';

$content = file_get_contents($base_url . '/' . $current_path);
$dom = new DOMDocument();
@$dom->loadHTML($content);
$xpath = new DOMXPath($dom);
$links = $xpath->query('//a');

$links_array = array();

$image_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$video_extensions = ['mp4', 'webm', 'ogg'];

foreach ($links as $link) {
    $href = $link->getAttribute('href');
    $text = $link->nodeValue;

    if (substr($href, -1) === '/') {
        $icon = '<i class="fas fa-folder"></i>';
        $link_url = "?path=" . urlencode($current_path . $href);

        if ($href !== '.git/') {
            $links_array[] = '<a href="' . $link_url . '" class="list-group-item list-group-item-action">' . $icon . ' ' . $text . '</a>';
        }
    } else {
        $file_ext = pathinfo($href, PATHINFO_EXTENSION);

        if (in_array($file_ext, $video_extensions)) {
            $video_url = $base_url . '/' . $current_path . $href;
            $links_array[] = '<div class="list-group-item"><a href="#" onclick="event.preventDefault(); var video = this.nextElementSibling; video.style.display = video.style.display === \'none\' ? \'block\' : \'none\'">' . $text . '</a><video width=80% controls style="display:none" loading="lazy"><source src="' . $video_url . '" type="video/' . $file_ext . '"><span>Your browser does not support the video tag.</span></video></div>';
        } elseif (in_array($file_ext, $image_extensions)) {
            $image_url = $base_url . '/' . $current_path . $href;
            $links_array[] = '<div class="list-group-item"><a href="' . $image_url . '"><img src="' . $image_url . '" alt="' . $text . '" height=567vh loading="lazy"></a><a href="' . $image_url . '"><p>' . $text . '</p></a></div>';
        } else {
            if ($href !== '.gitattributes' && $href !== '.git') {
                $icon = '<i class="fas fa-file"></i>';
                $link_url = $base_url . '/' . $current_path . $href;
                $links_array[] = '<a href="' . $link_url . '" class="list-group-item list-group-item-action">' . $icon . ' ' . $text . '</a>';
            }
        }
    }
}

shuffle($links_array);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
    <h1><?php echo $current_directory ?></h1>
    <div class="list-group">
        <?php echo implode($links_array); ?>
    </div>
</div>
</body>
</html>