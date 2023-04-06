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
        <h1 class="mt-4 mb-4">A</h1>
        <?php
        $base_url = "https://f5e5-158-101-98-242.ngrok.io";
        $current_path = isset($_GET['path']) ? $_GET['path'] : '';
        $current_directory = basename($current_path) ?: 'Root';
        ?>
        <div class="row mb-3">
            <div class="col-md-6">
                <?php if ($current_path): ?>
                    <a href="?path=<?php echo urlencode(dirname($current_path) . '/'); ?>" class="btn btn-primary">
                        <i class="fas fa-folder-open"></i> .. (Parent Directory)
                    </a>
                <?php endif; ?>
            </div>
            <div class="col-md-6 text-right">
                <h4><?php echo $current_directory; ?></h4>
            </div>
        </div>
        <div class="list-group">
          <?php
          $content = file_get_contents($base_url . '/' . $current_path);
          $dom = new DOMDocument();
          @$dom->loadHTML($content);
          $xpath = new DOMXPath($dom);
          $links = $xpath->query('//a');
          $image_extensions = ['jpg', 'jpeg', 'png', 'gif'];
          $links = iterator_to_array($links);
          shuffle($links);
          foreach ($links as $link) {
              $href = $link->getAttribute('href');
              $text = $link->nodeValue;
              if (substr($href, -1) === '/') {
                  $icon = '<i class="fas fa-folder"></i>';
                  $link_url = "?path=" . urlencode($current_path . $href);
                  if ($href !== '.git/') {
                      echo '<a href="' . $link_url . '" class="list-group-item list-group-item-action">' . $icon . ' ' . $text . '</a>';
                  }
              } else {
                  $file_ext = pathinfo($href, PATHINFO_EXTENSION);
                  $video_extensions = ['mp4', 'webm', 'ogg'];
          
                  if (in_array($file_ext, $video_extensions)) {
                      $video_url = $base_url . '/' . $current_path . $href;
                      echo '<div class="list-group-item">';
                      echo '<a href="#" onclick="event.preventDefault(); var video = this.nextElementSibling; video.style.display = video.style.display === \'none\' ? \'block\' : \'none\'">' . $text . '</a>';
                      echo '<video width=80% controls style="display:none" loading="lazy">';
                      echo '<source src="' . $video_url . '" type="video/' . $file_ext . '">';
                      echo 'Your browser does not support the video tag.';
                      echo '</video>';
                      echo '</div>';
                  } elseif (in_array($file_ext, $image_extensions)) {
                      $image_url = $base_url . '/' . $current_path . $href;
                      echo '<div class="list-group-item">';
                      echo '<a href="' . $image_url . '"><img src="' . $image_url . '" alt="' . $text . '" height=567vh loading="lazy"></a>';
                      echo '<a href="' . $image_url . '"><p>' . $text . '</p></a>';
                      echo '</div>';
                  } else {
                       if ($href !== '.gitattributes') {
                          $icon = '<i class="fas fa-file"></i>';
                          $link_url = $base_url . '/' . $current_path . $href;
                          echo '<a href="' . $link_url . '" class="list-group-item list-group-item-action">' . $icon . ' ' . $text . '</a>';
                      }
                  }
              }
          }
            ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>