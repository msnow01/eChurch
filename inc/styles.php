<style>
@import url('https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Open+Sans&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Comic+Neue:wght@300&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Bad+Script&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Charm&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Arizonia&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Eagle+Lake&display=swap');

:root {
  --dark-grey: #4f4e4e;
  --black: #000000;
  --white: #ffffff;
  --color1: <?php echo $color1;?>;
  --color2: <?php echo $color2;?>;
  --color3: <?php echo $color3;?>;
  --fancy-font: '<?php echo $fancy_font; ?>', cursive;
  --light-grey: #f3f3f3;
  --lighter-grey: #dddddd;
  --body-font: 'Open Sans', sans-serif;
  --header-height: 100px;
  --alert-height: 60px;
  --alert-offset: 155px;
  --container-offset: 250px;
}

html {
    scroll-behavior: smooth;
}

body {
    color: var(--black);
    font-family: var(--body-font);
}

.bible {
    background-image: url("../img/bkgd.jpg");
    background-size: cover;
    background-position: center;
    height: 100%;
    width: 100%;
    z-index: -100;
    position: fixed;
}

.overflow {
    overflow-x: auto;
}

.highlight-num {
    color: var(--color1) !important;
}

nav {
    z-index: 1000;
    margin-top: var(--header-height);
    border-radius: none!important;
}

.tooltipdiv{
    font-size: 1.5em;
    float: right;
}

.tooltiptext {
    display:none;
    background-color: var(--dark-grey);
    color: var(--white);
    padding: 10px;
    border-radius: 0.5em;
    font-size: 0.8em;
    float: right;
    margin-right: 10px;
}

.alert-success {
    background-color: var(--color3) !important;
    color: var(--black) !important;
    border: var(--color3) solid 1px !important;
}

.alert-danger {
    background-color: var(--black) !important;
    color: var(--white) !important;
    border: var(--black) solid 1px !important;
}

.tooltipdiv:hover + .tooltiptext {
    display: block;
}

.alert-bar {
    background-color: var(--color3);
    position: fixed;
    width: 100%;
    z-index: 1000;
    min-height: var(--alert-height);
    margin-top: var(--alert-offset);
    padding-top: 1%;
    padding-left: 1%;
    padding-right: 1%;
    font-weight: bold;
    filter: drop-shadow(3px 3px 3px #000);
    font-size: 0.9em;
}


.header-bar {
    background-color: var(--black);
    height: var(--header-height);
    position: fixed;
    width: 100%;
    z-index: 1000;
    top: 0px;
    color: var(--white);
}

.header-bar img {
    height: var(--header-height);
    padding: 0.5%;
    filter: grayscale(100%);
}

.header-bar h1 {
    font-family: var(--fancy-font);
    font-size: 2.25em;
    padding-top: 1%;
}

.header-bar p {
    font-size: 0.9em;
}

.audio-download {
    color: var(--black);
    font-size: 1.2em;
    vertical-align: top;
    padding-top: 4%;
}

.audio-download:hover, .image-download:hover {
    color: var(--dark-grey);
    text-decoration: none;
}

.no-link {
    color: var(--white);
    text-decoration: none;
}

.no-link:hover {
    color: var(--dark-grey);
    text-decoration: none;
}

.image-download {
    color: var(--color2);
    padding: 2%;
    font-size: 2em;
}

.admin-icon {
    font-size: 3em;
    color: var(--black);
}

.admin-link {
    color: var(--color2);
}

.admin-link:hover {
    color: var(--black);
}

audio::-webkit-media-controls-play-button, audio::-webkit-media-controls-panel {
     background-color: var(--white);
     color: var(--white);
     border: none;
 }

 .top3 {
     margin-top: 1%;
     margin-bottom: -1%;
 }

.text-middle {
    text-align: center !important;
}

.text-right {
    text-align: right !important;
}

.text-left {
    text-align: left !important;
}

.audio-blurb {
    background-color: white;
    border-radius:0.5em;
    padding-left: 2%;
    padding-right: 2%;
    padding-top: 1%;
    padding-bottom: 0.5%;
}

.blurb {
    background-color: white;
    border-radius:0.5em;
    padding: 2%;
}

h2 {
    font-size: 2em;
    margin-bottom: 30px;
    font-weight: 400;
}

h3 {
    font-size: 1.3em;
    margin-top: 0.5rem;
}

.container {
    margin-bottom: 5%;
    padding-top: var(--container-offset);
}

@media only screen and (max-width: 1000px) {
    .container {
        padding-top: 60%;
        margin-bottom: 5%;
    }
    h1 {
        font-size: 1.5em;
    }
    .info-box {
        margin-bottom: 5%;
    }

    .alert-bar {
        font-size: 0.8em;
        padding-top: 3%;
    }

    .page-arrow{
        display: none;
    }
}

.admin {
    background-color: var(--lighter-grey);
}

.shadow {
    filter: drop-shadow(3px 3px 3px #000);
}

.notice {
    background-color: var(--light-grey);
    border: var(--light-grey) solid 0.5px;
    padding: 30px;
    border-radius: 0.5em;
}

.notice-title {
    text-decoration: none;
    color: var(--black);
}

.notice-title:hover {
    text-decoration: underline;
    color: var(--dark-grey);
}

.nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
    color: var(--white) !important;
    background-color: var(--color1);
    border-color: var(--dark-grey) var(--dark-grey) var(--dark-grey);
}

.nav-tabs .nav-link {
    border: none;
    color: var(--color1) !important;
}

.nav-tabs {
    border-bottom: var(--dark-grey) solid 1px !important;
}

.dropdown-menu {
    background-color: var(--color2) !important;
    border: none !important;
    left: -15%;
}

.dropdown-item {
    color: var(--white) !important;
    background-color: var(--color2) !important;
}

.dropdown-item:hover {
    opacity: 0.5;
}

.fa-cog, .fa-user, .fa-sign-out-alt {
    color: var(--white);
    padding-right: 20px;
}

.navbar-nav .nav-link, nav .nav-item .nav-link, div .nav-link {
    color: var(--white)!important;
}

.navbar-nav .nav-link {
    padding-right: 35px!important;
    display: block;
}

.navbar-nav .nav-link:hover, nav .nav-item .nav-link:hover, div .nav-link:hover {
    opacity: 0.5;
}

.bg-light {
    font-size: 0.8em;
    background-color: var(--dark-grey)!important;
}

.bg-dark {
    background-color: var(--color1)!important;
}

.info-box {
    border-radius: 0.5em;
    border: var(--light-grey) solid 0.5px;
    padding: 30px;
    width: 100%;
    background: var(--light-grey);
    text-decoration: none;
    color: var(--black);
}

.info-box:hover {
    text-decoration: none;
    color: var(--black);
    filter: none;
}

.btn {
    background: var(--color1) !important;
    background-color: var(--color1) !important;
    border-color: var(--color1) !important;
    border-radius: 0.5em;
    width: 150px;
}

.display-none {
    display:none;
}

.view-more {
    text-decoration: none;
    color: var(--color2);
}

.view-more:hover {
    text-decoration: underline;
    color: var(--black);
}

a {
    text-decoration: none;
    color: var(--color2);
}

a:hover {
    text-decoration: underline;
    color: var(--black);
}

.white-box {
    border-radius: 0.5em;
    background: var(--light-grey);
    text-align: center;
    padding: 3%;
}

.sm-font {
    font-size: 1em;
    color: var(--color2);
}

.designed-by {
    font-size: 0.8em;
    width: 100%;
    padding: 1%;
    bottom: 0px;
    position: relative;
    color: var(--dark-grey);
}

.designed-by a {
    text-decoration: underline;
    color: var(--dark-grey);
}

.designed-by a:hover {
    text-decoration: none;
}

.home-icon {
    float: right;
    text-decoration: none;
    font-size: 1.5em;
    color: var(--black);
}

.home-icon:hover {
    opacity: 0.5;
    text-decoration: none;
    color: var(--black);
}

.video-frame iframe {
    width: 100% !important;
    height: 140px !important;
    filter: drop-shadow(3px 3px 3px #000);
}

</style>
