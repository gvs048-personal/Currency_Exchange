<?php
require_once 'admin/web-app/config/database.php';

// Update the SQL query to exclude Euro and Dollar from the main query and sort by currency_code in ascending order
$sql = "SELECT currency_code, currency_name, buy_price, sell_price, currency_logo FROM currencies WHERE currency_code NOT IN ('EUR', 'USD') ORDER BY currency_code ASC";
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
        /* Enhance table appearance */
        .currency-table {
            width: 100%; /* Set table width to 100% */
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .currency-table th {
            background-color: #007bff;
            color: white;
            padding: 15px;
            font-size: 1.1em;
            text-transform: uppercase;
        }

        .currency-table td {
            padding: 12px;
            font-size: 1em;
            border-bottom: 1px solid #ddd;
            text-align: left; /* Align buy and sell prices to the left */
        }

        .currency-table tr:hover {
            background-color: #f1f1f1;
        }

        .currency-table td:first-child img {
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* Popup styling */
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            width: 90%;
            max-width: 500px;
        }

        .popup h2 {
            margin-top: 0;
            font-size: 1.5em;
            color: #007bff;
            text-align: center;
        }

        .popup form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .popup input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }

        .popup button {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }

        .popup button:hover {
            background-color: #0056b3;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
            color: #007bff;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .currency-table th,
            .currency-table td {
                font-size: 0.9em;
                padding: 10px;
            }

            .popup {
                width: 95%;
            }
        }

        /* Ensure parent container does not restrict table width */
        .col-md-12.layout_padding {
            width: 100%;
            max-width: 100%;
            padding: 0;
        }

        /* Remove layout_padding restrictions */
        .layout_padding {
            padding: 0;
            margin: 0;
        }

        /* Adjust column width */
        .col-md-8 {
            width: 100%;
        }

        .currency-table td:last-child {
            text-align: center;
            vertical-align: middle;
        }

        .currency-table button {
            margin: 5px 0;
        }

        .currency-table td button {
            margin-left: 10px;
        }

        .currency-table {
            margin: 20px auto; /* Center the table horizontally */
        }

        .currency-table th:nth-child(3),
        .currency-table th:nth-child(4),
        .currency-table td:nth-child(3),
        .currency-table td:nth-child(4) {
            text-align: center; /* Center-align buy and sell price columns */
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
                                        <li><a class="nav-link" href="cart.html"><img src="images/cart-icon.png" alt="Cart" style="width: 40px; height: auto; margin-right: 5px;">0.00</a></li>
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
                <div class="col-md-8 layout_padding">
                    <div class="col-md-8">
                        <div class="full">
                            <div class="heading_main text_align_center">
                                <h2><span class="theme_color"></span>Exchange Rates</h2>
                            </div>
                        </div>
                    </div>
                    <div class="layout_padding">
                        <table class="currency-table" style="width: 1200px;">
                            <thead>
                                <tr>
                                    <th>Currency Code</th>
                                    <th style="text-align: left;">Currency Name</th>
                                    <th style="padding-left: 10px; text-align: right;">Buy Price</th>
                                    <th style="padding-left: 10px; text-align: right;">Sell Price</th>
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
                                        <td style="text-align: right;">
                                            <?= htmlspecialchars($row['buy_price']) ?>
                                            <button class="btn btn-primary" onclick="showPopup('buy', '<?= htmlspecialchars($row['currency_code']) ?>', '<?= htmlspecialchars($row['currency_name']) ?>', <?= htmlspecialchars($row['buy_price']) ?>)">Click & Collect</button>
                                        </td>
                                        <td style="text-align: right;">
                                            <?= htmlspecialchars($row['sell_price']) ?>
                                            <button class="btn btn-primary" onclick="showPopup('sell', '<?= htmlspecialchars($row['currency_code']) ?>', '<?= htmlspecialchars($row['currency_name']) ?>', <?= htmlspecialchars($row['sell_price']) ?>)">Click & Sell</button>
                                        </td>
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
                                            <td style="text-align: right;">
                                                <?= htmlspecialchars($row['buy_price']) ?>
                                                <button class="btn btn-primary" onclick="showPopup('buy', '<?= htmlspecialchars($row['currency_code']) ?>', '<?= htmlspecialchars($row['currency_name']) ?>', <?= htmlspecialchars($row['buy_price']) ?>)">Click & Collect</button>
                                            </td>
                                            <td style="text-align: right;">
                                                <?= htmlspecialchars($row['sell_price']) ?>
                                                <button class="btn btn-primary" onclick="showPopup('sell', '<?= htmlspecialchars($row['currency_code']) ?>', '<?= htmlspecialchars($row['currency_name']) ?>', <?= htmlspecialchars($row['sell_price']) ?>)">Click & Sell</button>
                                            </td>
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
                        <!-- <button>Submit</button -->
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
let cart = [];

function updateCartDisplay() {
    const cartTotal = cart.reduce((total, item) => total + (item.amount / item.rate), 0).toFixed(2);
    const cartElement = document.querySelector('.navbar-nav .nav-link[href="cart.html"]');
    if (cartElement) {
        cartElement.innerHTML = `<img src="images/cart-icon.png" alt="Cart" style="width: 40px; height: auto; margin-right: 5px;">${cartTotal}`;
    }
}

function showPopup(type, currencyCode, currencyName, rate) {
    const popupContent = `
        <div class="popup" id="popup">
            <h2>Click & ${type.charAt(0).toUpperCase() + type.slice(1)}</h2>
            <button class="close-btn" onclick="closePopup()">&times;</button>
            <p>You are ${type === 'buy' ? 'buying' : 'selling'} ${currencyName} (${currencyCode})</p>
            <p>At Our Best Exchange Rate of 1 GBP = ${rate} ${currencyCode}</p>
            <form>
                <label for="amount">You ${type === 'buy' ? 'want' : 'have'} ${currencyCode}</label>
                <input type="number" id="amount" placeholder="Enter Amount">
                <label for="gbp">You ${type === 'buy' ? 'have' : 'want'} GBP (£)</label>
                <input type="text" id="gbp" readonly>
                <button type="button" class="btn btn-primary" onclick="addToCart('${currencyCode}', '${currencyName}', ${rate}, '${type}')">Order Now</button>
            </form>
        </div>
    `;
    const popupContainer = document.createElement('div');
    popupContainer.id = 'popup-container';
    popupContainer.innerHTML = popupContent;
    document.body.appendChild(popupContainer);

    const gbpInput = document.getElementById('gbp');
    const amountInput = document.getElementById('amount');

    amountInput.addEventListener('input', function () {
        const amountValue = parseFloat(this.value) || 0;
        gbpInput.value = (amountValue / rate).toFixed(2); // Calculate the GBP value based on the entered amount
    });
}

function addToCart(currencyCode, currencyName, rate, type) {
    const amountInput = document.getElementById('amount');
    const amountValue = parseFloat(amountInput.value) || 0;

    if (amountValue <= 0) {
        alert('Please enter a valid amount.');
        return;
    }

    const cartItem = {
        currencyCode,
        currencyName,
        rate,
        type,
        amount: amountValue
    };

    cart.push(cartItem);
    updateCartDisplay();

    alert(`${currencyName} (${currencyCode}) has been added to your cart.`);
    closePopup();
}

function closePopup() {
    const popupContainer = document.getElementById('popup-container');
    if (popupContainer) {
        document.body.removeChild(popupContainer);
    }
}

function showCartPopup() {
    const popupContent = `
        <div class="popup" id="cart-popup">
            <h2>Your Cart</h2>
            <button class="close-btn" onclick="closePopup()">&times;</button>
            <div>
                ${cart.length > 0 ? cart.map(item => `
                    <div style="margin-bottom: 10px;">
                        <p><strong>${item.currencyName} (${item.currencyCode})</strong></p>
                        <p>Type: ${item.type.charAt(0).toUpperCase() + item.type.slice(1)}</p>
                        <p>Amount: ${item.amount}</p>
                        <p>Rate: ${item.rate}</p>
                        <p>Total: ${(item.amount / item.rate).toFixed(2)} GBP</p>
                    </div>
                `).join('') : '<p>Your cart is empty.</p>'}
            </div>
        </div>
    `;

    const popupContainer = document.createElement('div');
    popupContainer.id = 'popup-container';
    popupContainer.innerHTML = popupContent;
    document.body.appendChild(popupContainer);
}

// Attach event listener to cart menu item
const cartMenuItem = document.querySelector('.navbar-nav .nav-link[href="cart.html"]');
if (cartMenuItem) {
    cartMenuItem.addEventListener('click', function(event) {
        event.preventDefault();
        showCartPopup();
    });
}
</script>
</body>

</html>