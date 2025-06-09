<?php
require_once 'admin/web-app/config/database.php';

// Determine the sorting column and order
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'currency_name';
$sort_order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

// Validate the sorting column to prevent SQL injection
$allowed_columns = ['currency_code', 'currency_name', 'buy_price', 'sell_price'];
if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'currency_name';
}

// Update the SQL query to exclude Euro and Dollar from the main query
$sql = "SELECT currency_code, currency_name, buy_price, sell_price, currency_logo FROM currencies WHERE currency_code NOT IN ('EUR', 'USD') ORDER BY $sort_column $sort_order";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching data: " . $conn->error);
}

// Fetch static rows (Euro and Dollar) from the database
$static_sql = "SELECT currency_code, currency_name, buy_price, sell_price, currency_logo FROM currencies WHERE currency_code IN ('EUR', 'USD') ORDER BY FIELD(currency_code, 'EUR', 'USD')";
$static_result = $conn->query($static_sql);

if (!$static_result) {
    die("Error fetching static rows: " . $conn->error);
}

$static_rows = $static_result->fetch_all(MYSQLI_ASSOC);
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
    <title>Exchange Rates</title>
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
            width: 25%;
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

        .currency-table td:nth-child(2) {
            text-align: left !important;
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
                            <li><a href="#"><img src="images/mail_icon.png"
                                        alt="#" />rates@oxfordstreetfx.com</a></li>
                            <li><a href="#"><img src="images/phone_icon.png" alt="#" />+44
                                    7939838857</a></li>
									 <li>   
							 <a  href="https://twitter.com/oxfordstreetfx"> <img src="images/twitter-icon.png" alt="Twtter"> </a>  &nbsp; 
							 <a href="https://www.facebook.com/oxfordstreetfx/"><img src="images/fb-icon.png" alt="Facebook"></a> &nbsp; 
							 <a href="https://www.instagram.com/oxfordstreetfx/"><img src="images/insta-icon.png" alt="Instagram"></a> &nbsp;
							 <a href="https://www.youtube.com/@OxfordStreetFX"><img src="images/youtube-icon.png" alt="Youtube"></a>
							 <a href="https://www.linkedin.com/in/oxfordstreetfx/"><img src="images/linkedin-icon.png" alt="LinkedIn"></a>  &nbsp;
							 <a href="https://www.tiktok.com/@oxfordstreetfx"><img src="images/tiktok-icon.png" alt="TikTok"></a>
							 </li>
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
                                        <li><a class="nav-link" href="exchange.php">Exchange Rates</a></li>
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
    <div class="section inner_page_banner" style="background-color:blue;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner_title">
                        <h3>Exchange Rates</h3>
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
                        <img src="images/exchangeimg.png" alt="#" />
                    </div>
                </div>
                <div class="col-md-8 layout_padding">
                    <div class="col-md-8">
                        <div class="full">
                            <div class="heading_main text_align_center">
                                <h2><span class="theme_color"></span>Exchange Rates</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 layout_padding">
                        <table class="currency-table">
                            <thead>
                                <tr>
                                    <th><a href="?sort=currency_code&order=<?= $sort_order === 'ASC' ? 'desc' : 'asc' ?>">Currency Code</a></th>
                                    <th style="text-align: left;"><a href="?sort=currency_name&order=<?= $sort_order === 'ASC' ? 'desc' : 'asc' ?>">Currency Name</a></th>
                                    <th><a href="?sort=buy_price&order=<?= $sort_order === 'ASC' ? 'desc' : 'asc' ?>">Buy Price</a></th>
                                    <th><a href="?sort=sell_price&order=<?= $sort_order === 'ASC' ? 'desc' : 'asc' ?>">Sell Price</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($static_rows as $row): ?>
                                    <tr>
                                        <td>
                                            <img src="admin/web-app/uploads/<?= htmlspecialchars($row['currency_logo']) ?>"
                                                alt="<?= htmlspecialchars($row['currency_code']) ?>"
                                                style="width: 30px; height: auto; margin-right: 10px;">
                                            <?= htmlspecialchars($row['currency_code']) ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['currency_name']) ?></td>
                                        <td><?= htmlspecialchars($row['buy_price']) ?></td>
                                        <td><?= htmlspecialchars($row['sell_price']) ?></td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <img src="admin/web-app/uploads/<?= htmlspecialchars($row['currency_logo']) ?>"
                                                    alt="<?= htmlspecialchars($row['currency_code']) ?>"
                                                    style="width: 30px; height: auto; margin-right: 10px;">
                                                <?= htmlspecialchars($row['currency_code']) ?>
                                            </td>
                                            <td><?= htmlspecialchars($row['currency_name']) ?></td>
                                            <td><?= htmlspecialchars($row['buy_price']) ?></td>
                                            <td><?= htmlspecialchars($row['sell_price']) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4">No currencies available.</td>
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
    <footer class="footer-box" style="background-color:#dc3545;">
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
                                    <li><a href="exchange.php">> Exchange Rates</a></li>
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
<!-- Elfsight WhatsApp Chat | Untitled WhatsApp Chat -->
<script src="https://static.elfsight.com/platform/platform.js" async></script>
<div class="elfsight-app-3828b648-fdb8-4143-aeaf-a08a5e06a6d4" data-elfsight-app-lazy></div>
  <div class="footer_bottom" style="background-color:#dc3545;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="crp">© Oxford Street FX. All Right Reserved.</p>
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
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const headers = document.querySelectorAll('.currency-table th a');

        headers.forEach(header => {
            header.addEventListener('click', function (event) {
                event.preventDefault();

                const url = new URL(this.href, window.location.href);
                const currentOrder = url.searchParams.get('order');
                const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
                url.searchParams.set('order', newOrder);

                // Update the href attribute dynamically
                this.href = url.toString();

                // Send AJAX request
                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        // Parse the response and update the table body
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(data, 'text/html');
                        const newTableBody = doc.querySelector('.currency-table tbody');

                        if (newTableBody) {
                            document.querySelector('.currency-table tbody').innerHTML = newTableBody.innerHTML;
                        }
                    })
                    .catch(error => console.error('Error fetching sorted data:', error));
            });
        });
    });
</script>

</body>

</html>