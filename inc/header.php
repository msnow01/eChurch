<?php
$site_title = mysqli_fetch_assoc(mysqli_query($link,"SELECT * from content WHERE type='site_title'"))['value'];
$admin_email_address = mysqli_fetch_assoc(mysqli_query($link,"SELECT * from content WHERE type='admin_email_address'"))['value'];
$header_address = mysqli_fetch_assoc(mysqli_query($link,"SELECT * from content WHERE type='header_address'"))['value'];
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="en" />
    <meta name="author" content="<?php echo $site_title;?>">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">

    <meta name="description" content="<?php echo $site_title;?>">
    <title><?php echo $title; ?> | <?php echo $site_title; ?></title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <script src="https://kit.fontawesome.com/b029d12cb7.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <link rel="shortcut icon" href="<?php echo $dir;?>img/icon.png">

</head>

<!-- TO DO _-->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-N5S1NNB9VW"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-N5S1NNB9VW');
</script>

<?php
$noreply_email_address = "";
$tiny_mce_id = "";
?>
<!-- END OF TO DO -->

<?php
$color1 = mysqli_fetch_assoc(mysqli_query($link,"SELECT * from content WHERE type='color1'"))['value'];
$color2 = mysqli_fetch_assoc(mysqli_query($link,"SELECT * from content WHERE type='color2'"))['value'];
$color3 = mysqli_fetch_assoc(mysqli_query($link,"SELECT * from content WHERE type='color3'"))['value'];
$fancy_font = mysqli_fetch_assoc(mysqli_query($link,"SELECT * from content WHERE type='fancy_font'"))['value'];
include "styles.php";
$base_url = "https://".$_SERVER['HTTP_HOST']."/";
$user_types = array("PENDING", "USER", "ADMIN", "SUPER");
$category_types = array("VIDEO", "AUDIO", "RESOURCE", "NOTICE", "ALERT", "LIVE");
$fancy_font_array = array('Dancing Script', 'Great Vibes', 'Kaushan Script', 'Arizonia', 'Eagle Lake', 'Comic Neue', 'Bad Script', 'Charm');
$maximages = "3";
$maxvideos = "16";
$maxaudio = "16";
$maxnotices = "5";
$live_stream_link = mysqli_fetch_assoc(mysqli_query($link,"SELECT * from content WHERE type='live_stream_link'"))['value'];
?>
