<?php
require_once 'admin/web-app/config/database.php';

$sql = "SELECT currency_name, buy_price, sell_price, currency_logo FROM currencies";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching data: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<!-- Basic -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- Site Metas -->
    <title>Oxford Street FX</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Site Icons -->
    <link rel="shortcut icon" href="#" type="image/x-icon" />
    <link rel="apple-touch-icon" href="#" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- Pogo Slider CSS -->
    <link rel="stylesheet" href="css/pogo-slider.min.css" />
    <!-- Site CSS -->
    <link rel="stylesheet" href="css/style.css" />
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/custom.css" />

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .currency-table th,
        .currency-table td {
            width: 33%;
            text-align: center;
            padding: 10px;
        }

        .currency-table th {
            background-color: #007bff;
            color: white;
        }

        .currency-table td:first-child {
            text-align: left;
        }

        .currency-table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
        }
    </style>

</head>

<body id="inner_page" data-spy="scroll" data-target="#navbar-wd" data-offset="98">

    <!-- LOADER -->
    <div id="preloader">
        <div class="loader">
            <img src="images/loader.gif" alt="#" />
        </div>
    </div>
    <!-- end loader -->
    <!-- END LOADER -->

    <!-- Start header -->
    <header class="top-header">
        <div class="header_top">

            <div class="container">
                <div class="row">
                    <div class="logo_section">
                        <a class="navbar-brand" href="index.html"><img src="images/logo.png" alt="image"></a>
                    </div>
                    <div class="site_information">
                        <ul>
                            <li><a href="mailto:exchang@gmail.com"><img src="images/mail_icon.png"
                                        alt="#" />rates@oxfordstreetfx.com</a></li>
                            <li><a href="tel:exchang@gmail.com"><img src="images/phone_icon.png" alt="#" />+44
                                    7939838857</a></li>
                            <!-- <li><a class="join_bt" href="#">Join us</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        <div class="header_bottom">
            <div class="container">
                <div class="col-sm-12">
                    <div class="menu_orange_section" style="background: #ff880e;">
                        <nav class="navbar header-nav navbar-expand-lg">
                            <div class="menu_section">
                                <button class="navbar-toggler" type="button" data-toggle="collapse"
                                    data-target="#navbar-wd" aria-controls="navbar-wd" aria-expanded="false"
                                    aria-label="Toggle navigation">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </button>
                                <div class="collapse navbar-collapse justify-content-end" id="navbar-wd">
                                    <ul class="navbar-nav">
                                        <li><a class="nav-link" href="index.html">Home</a></li>
                                        <li><a class="nav-link" href="about.html">About</a></li>
                                        <li><a class="nav-link" href="exchange.php">Exchange</a></li>
                                        <li><a class="nav-link" href="services.html">Services</a></li>
                                        <!-- <li><a class="nav-link" href="new.html">News</a></li> -->
                                        <li><a class="nav-link" href="contact.html">Contact</a></li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                        <!-- <div class="search-box"> -->
                        <!-- <input type="text" class="search-txt" placeholder="Search"> -->
                        <!-- <a class="search-btn"> -->
                        <!-- <img src="images/search_icon.png" alt="#" /> -->
                        <!-- </a> -->
                        <!-- </div>  -->
                    </div>
                </div>
            </div>
        </div>

    </header>
    <!-- End header -->

    <!-- Start Banner -->
    <div class="section inner_page_banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner_title">
                        <h3>Exchange</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Banner -->

    <!-- section -->
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="full text_align_right_img">
                        <img src="images/img1.png" alt="#" />
                    </div>
                </div>
                <div class="col-md-8 layout_padding">
                    <div class="col-md-4">
                        <div class="full">
                            <div class="heading_main text_align_center">
                                <h2><span class="theme_color"></span>Exchange</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 layout_padding">
                        <table class="currency-table">
                            <thead>
                                <tr>
                                    <th>Currency Name</th>
                                    <th>We Buy Price</th>
                                    <th>We Sell Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <img src="admin/web-app/uploads/<?= htmlspecialchars($row['currency_logo']) ?>"
                                                    alt="<?= htmlspecialchars($row['currency_name']) ?>"
                                                    style="width: 30px; height: auto; margin-right: 10px;">
                                                <?= htmlspecialchars($row['currency_name']) ?>
                                            </td>
                                            <td><?= htmlspecialchars($row['buy_price']) ?></td>
                                            <td><?= htmlspecialchars($row['sell_price']) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3">No currencies available.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- end section -->

    <!-- Start Footer -->
    <footer class="footer-box">
        <div class="container">
            <div class="row">
                <div class="col-md-12 white_fonts">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="full">
                                <img class="img-responsive" src="images/logo.png" alt="#" />
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="full">
                                <h3>Quick Links</h3>
                            </div>
                            <div class="full">
                                <ul class="menu_footer">
                                    <li><a href="about.html">> About</a></li>
                                    <li><a href="exchange.php">> Exchange</a></li>
                                    <li><a href="services.html">> Services</a></li>
                                    <li><a href="privacy.html">> Privacy Policy</a></li>
                                    <li><a href="terms.html">> Terms and Conditions</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- <div class="col-sm-6 col-md-6 col-lg-3"> -->
                        <!-- <div class="full"> -->
                        <!-- <div class="footer_blog full white_fonts"> -->
                        <!-- <h3>Newsletter</h3> -->
                        <!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do</p> -->
                        <!-- <div class="newsletter_form"> -->
                        <!-- <form action="index.html"> -->
                        <!-- <input type="email" placeholder="Your Email" name="#" required=""> -->
                        <!-- <button>Submit</button> -->
                        <!-- </form> -->
                        <!-- </div> -->
                        <!-- </div> -->
                        <!-- </div> -->
                        <!-- </div> -->
                        <div class="col-sm-6 col-md-6 col-lg-3">
                            <div class="full">
                                <div class="footer_blog full white_fonts">
                                    <h3>Contact us</h3>
                                    <ul class="full">
                                        <li><img src="images/i5.png"><span>470 Oxford Street, London <br>W1C 1LA <br>
                                                United Kingdom</span></li>
                                        <li><img src="images/i6.png"><span>rates@oxfordstreetfx.com</span></li>
                                        <li><img src="images/i7.png"><span>+44 7939838857</span></li>
                                    </ul>
                                    <table>
                                        <tr>
                                            <td> <a href="https://twitter.com/oxfordstreetfx"> <img
                                                        src="images/twitter-icon.png" alt="Twtter"> </a>
                                            <td> &nbsp;&nbsp;&nbsp;&nbsp; </td>
                                            </td>
                                            <td> <a href="https://www.facebook.com/oxfordstreetfx/"><img
                                                        src="images/fb-icon.png" alt="Facebook"><span> </span></a>
                                            </td>
                                            <td> &nbsp;&nbsp;&nbsp;&nbsp; </td>
                                            <td> <a href="https://www.instagram.com/oxfordstreetfx/"><img
                                                        src="images/insta-icon.png" alt="Instagram"><span> </span></a>
                                            </td>

                                            <td> &nbsp;&nbsp;&nbsp;&nbsp; </td>
                                            <td> <a href="https://www.youtube.com/@OxfordStreetFX"><img
                                                        src="images/youtube-icon.png" alt="Youtube"><span> </span></a>
                                            </td>

                                            <td> &nbsp;&nbsp;&nbsp;&nbsp; </td>
                                            <td> <a href="https://www.linkedin.com/in/oxfordstreetfx/"><img
                                                        src="images/linkedin-icon.png" alt="LinkedIn"><span> </span></a>
                                            </td>

                                            <td> &nbsp;&nbsp;&nbsp;&nbsp; </td>
                                            <td> <a href="https://www.tiktok.com/@oxfordstreetfx"><img
                                                        src="images/tiktok-icon.png" alt="TikTok"><span> </span></a>
                                            </td>


                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->

    <div class="footer_bottom">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="crp">© Copyrights 2025 design by ExpertiseIT</p>
                </div>
            </div>
        </div>
    </div>

    <a href="#" id="scroll-to-top" class="hvr-radial-out"><i class="fa fa-angle-up"></i></a>

    <!-- ALL JS FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- ALL PLUGINS -->
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/jquery.pogo-slider.min.js"></script>
    <script src="js/slider-index.js"></script>
    <script src="js/smoothscroll.js"></script>
    <script src="js/form-validator.min.js"></script>
    <script src="js/contact-form-script.js"></script>
    <script src="js/isotope.min.js"></script>
    <script src="js/images-loded.min.js"></script>
    <script src="js/custom.js"></script>

</body>

</html>