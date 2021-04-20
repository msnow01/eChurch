<?php
$dir="../";
include $dir."inc/session-start.php";
$title = "Contact";
include $dir."inc/connection.php";
include $dir."inc/header.php";
include $dir."inc/functions.php";
?>

<!DOCTYPE html>
<html lang="en">

<body>
    <?php include $dir."inc/main-menu.php"; ?>
    <div class="container" data-aos="fade-in">
        <div class="row justify-content-center notice shadow">
            <div class="col-md-12 ">
                <div class="row">
                    <div class="col-md-12">
                        <a class="home-icon" href="<?php echo $dir;?>home"><i class="fas fa-home"></i></a>
                        <h2>&nbsp;<i class="far fa-envelope"></i>&nbsp;Contact</h2>
                        <?php
                        if(isset($_POST['submitcontact'])){
                            $email = addslashes(strtolower($_POST['email']));
                            $name = addslashes($_POST['name']);
                            $form_message = addslashes($_POST['message']);
                            $to = $admin_email_address;
                            $subject = "Message from ".$name;
                            $message = "<p>Message from ".$name." at " .$email."</p><p>".$form_message."</p>";
                            $header = "From: ".$site_title." <".$noreply_email_address."> \r\n";
                            $header .= "MIME-Version: 1.0\r\n";
                            $header .= "Reply-To: ".$email."" . "\r\n";
                            $header .= "Content-type: text/html\r\n";

                            $retval = mail ($to,$subject,$message,$header);
                            if ($retval == TRUE) {
                                echo "<div class='alert alert-success'><p>Thank you, your message has been sent.</p></div>";
                            } else {
                                echo "<div class='alert alert-danger'><p>We're sorry, there has been an error. Please try again later.</div>";
                            }
                        }
                        ?>
                        <div class="blurb">
                            <p>Fill out the contact form below to send an email to <?php echo $site_title; ?> regarding access to online worship materials.</p>
                            <form method="post" action="">
                                <p><label for="name" style="display:none">Full Name</label><input type="text" autocomplete="off" required class="form-control" name="name" id="name" placeholder="Full Name"></p>
                                <p><label for="email" style="display:none">E-mail Address</label><input type="text" autocomplete="off" required class="form-control" name="email" id="email" placeholder="E-mail Address"></p>
                                <p><label for="message" style="display:none">E-mail Message</label><textarea required class="form-control" rows="5" name="message" id="message" placeholder="E-mail Message"></textarea></p>
                                <p><input class="btn btn-dark" type="submit" name="submitcontact" id="submitcontact" value="Send"></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include $dir."inc/footer.php"; ?>
</body>
</html>
