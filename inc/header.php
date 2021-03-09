<?php
$admin_email_address = "EMAILADDRESS";
$site_title = "eChurch";
$tiny_mce_id = "TINYMCEID";
$webex_link = "";
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="en" />
    <meta name="author" content="<?php echo $site_title;?>">
    <meta name="viewport" content="width=device-width, initial-scale=0.8">

    <meta name="description" content="<?php echo $site_title;?> is an online platform used for worship and praise.">
    <title><?php echo $title; ?> | <?php echo $site_title; ?></title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <script src="https://kit.fontawesome.com/b029d12cb7.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <link rel="shortcut icon" href="<?php echo $dir;?>img/icon.jpg">
    <link rel="stylesheet" href="<?php echo $dir;?>css/styles.css">

</head>

<?php
$user_types = array("PENDING", "USER", "ADMIN", "SUPER");
$category_types = array("VIDEO", "AUDIO", "RESOURCE", "NOTICE", "ALERT", "LIVE");
$maximages = "3";
$maxvideos = "16";
$maxaudio = "16";
$maxnotices = "5";
?>
