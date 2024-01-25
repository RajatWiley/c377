<?php

session_start();
$logged_in = False;
require "db/db-conf.php";

// LOG IN FUNCTIONALITY
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $email_id = $_POST["email_id"];
  $password = $_POST["password"];

  $hash = password_hash($password, PASSWORD_DEFAULT);
  $sql = "SELECT * FROM users WHERE email_id='$email_id'";
  $result = mysqli_query($conn, $sql);

  $num = mysqli_num_rows($result);
  if ($num == 1) {
    while ($row = mysqli_fetch_assoc($result)) {
      if (password_verify($password, $row['password'])) {
        $login = true;
        $sql_get_user_id = "SELECT * FROM users WHERE email_id = '$email_id'";
        $result_get_user_id = mysqli_query($conn, $sql_get_user_id);
        $_SESSION["user_id"] = mysqli_fetch_assoc($result_get_user_id)["user_id"];
        $_SESSION['logged_in'] = true;
?>
        <!-- Superadmin123@ -->
        <!-- <script>window.location.reload()</script> -->
<?php
        header("Location: ./");
      } else {
        $show_alert = "Invalid Credentials";
      }
    }
  } else {
    $show_alert = "Invalid Credentials";
  }
}

// IF ALREADY SIGNED IN THEN REDIRECTED TO HOME PAGE
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] = true) {
  $logged_in = True;
}

if (isset($_SESSION["user_id"])) {
  $user_id = $_SESSION["user_id"];
  $sql_get_user = "SELECT * FROM users WHERE user_id = $user_id";
  $result_get_user = mysqli_query($conn, $sql_get_user);
  $row_get_user = mysqli_fetch_assoc($result_get_user);
  $base64ImageData = $row_get_user["profile_picture"];

  // CHECK IF ALL REQUIRED FIELDS ARE FILLED
  $name_check = $row_get_user["name"];
  $company_check = $row_get_user["company"];
  $designation_check = $row_get_user["designation"];
  $profile_check = $row_get_user["profile"];
  $office_email_id_check = $row_get_user["office_email_id"];
  $email_id_check = $row_get_user["email_id"];
  $phone_no_check = $row_get_user["phone_no"];
  $office_phone_no_check = $row_get_user["office_phone_no"];
  $facebook_check = $row_get_user["facebook"];
  $linkedin_check = $row_get_user["linkedin"];
  $state_check = $row_get_user["state"];
  $city_check = $row_get_user["city"];

  if ($name_check == "" || $company_check == "" ||  $designation_check == "" ||  $profile_check == "" ||  $office_email_id_check == "" ||  $email_id_check == "" ||  $phone_no_check == "" ||  $office_phone_no_check == "" ||  $facebook_check == "" ||  $linkedin_check == "" ||  $state_check == "" ||  $city_check == "" ){
    $_SESSION["incomplete_profile"] = True;
  }
  else{
    $_SESSION["incomplete_profile"] = FALSE;
  }

}

if ((isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] = true) && (!isset($_SESSION["user_id"]))) {
  header("Location: logout.php");
}

$sql_get_webinars = "SELECT * FROM webinars WHERE webinar_date >= curdate() ORDER BY webinar_date ASC LIMIT 1";
$result_get_webinars = mysqli_query($conn, $sql_get_webinars);
$row_get_webinars = mysqli_fetch_assoc($result_get_webinars);
$count_get_webinars = mysqli_num_rows($result_get_webinars);

$sql_get_sliders = "SELECT * FROM sliders";
$result_get_sliders = mysqli_query($conn, $sql_get_sliders);

if (isset($_SESSION["incomplete_profile"]) && $_SESSION["incomplete_profile"] == True){
  header("Location: profile.php");
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Farvision World</title>
  <link rel="shortcut icon" href="images/ico/favicon.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
  <link href="App_Themes/Blue/default.css" rel="stylesheet">
  <link href="css/color.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>

<body>
  <?php
  if (!$logged_in) {
  ?>
    <div class="modal-backdrop fade" id="backgroundDark"></div>
    <div class="modal fade" id="popupWindow" tabindex="-1">
      <form method="post" action="./">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Log in your account</h5>
            </div>
            <div class="modal-body">
              <div class="services-box-info">
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-xl-12 colHeight"> Login ID* <input type="text" class="form-control" name="email_id" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 col-lg-12 col-xl-12 colHeight"> Password* <input type="password" class="form-control" name="password" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 col-lg-6 col-xl-6 colHeight">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                      <label class="form-check-label" for="flexCheckDefault"> Remember me </label>
                    </div>
                  </div>
                  <!-- <div class="col-md-12 col-lg-6 col-xl-6 colHeight text-right"><a href="#s">Forgot Password?</a></div> -->
                </div>
                <div class="row">
                  <div class="col-md-12 col-lg-6 col-xl-12 colHeight">Don't have an account? <a href="signup.php?utm_source=Website&utm_medium=Website&utm_campaign=Farvision_World_Signup">Sign Up</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary theme-btn">Login</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  <?php
  }
  ?>
  <div class="wrapper">
    <p style="word-wrap: anywhere">
      <?php
      // echo $base64ImageData;
      ?>
    </p>
    <nav class="navbar fixed-top navbar-expand-lg navbar-light">
      <div class="container">
        <a class="navbar-brand" href="index.php">
          <img src="images/logo.png" alt="logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse float-end " id="navbarScroll">
          <ul class="navbar-nav ms-auto my-2 my-lg-0 navbar-nav-scroll " style="--bs-scroll-height: 200px;">
            <!-- <li class="nav-item active"><a class="nav-link" href="index.html">Home</a></li>-->
            <li class="nav-item">
              <a class="nav-link" href="refer-farvision.php?utm_source=Website&utm_medium=Website&utm_campaign=Farvision_World_Referral_Program">Refer Farvision</a>
            </li>
            <!--<li class="nav-item"><a class="nav-link" href="offer-of-the-month.html">Offer of the Month</a></li>-->
            <li class="nav-item">
              <a class="nav-link" href="webinar.php">Webinar</a>
            </li> <?php
                  if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] = true && $_SESSION["user_id"]) {
                  ?> <li class="nav-item">
                <ul class="list-unstyled">
                  <li class="dropdown ml-2">
                    <a class="rounded-circle " href="#" role="button" id="dropdownUser" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <div class="avatar avatar-md avatar-indicators avatar-online"> <?php
                                                                                      try {
                                                                                        if ($row_get_user["profile_picture_type"] == "") {
                                                                                          echo '
        <img src="images/user.png" class="rounded-circle" alt="avatar" style="width: 40px;height: 40px;">';
                                                                                        } else {
                                                                                          echo '
        <img src="data:' . $row_get_user["profile_picture_type"] . ';base64,' . $row_get_user["profile_picture"] . '" class="rounded-circle" alt="avatar" style="width: 40px;height: 40px;">';
                                                                                        }
                                                                                      } catch (PDOException $e) {
                                                                                        header("Location: logout.php");
                                                                                      }
                                                                                      ?> </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-avatar p-0" aria-labelledby="dropdownUser">
                      <div class="dropdown-item">
                        <div class="d-flex">
                          <div class="avatar avatar-md avatar-indicators avatar-online"> <?php
                                                                                          try {
                                                                                            if ($row_get_user["profile_picture_type"] == "") {
                                                                                              echo '
          <img src="images/user.png" class="rounded-circle" alt="avatar" style="width: 40px;height: 40px;">';
                                                                                            } else {
                                                                                              echo '
          <img src="data:' . $row_get_user["profile_picture_type"] . ';base64,' . $row_get_user["profile_picture"] . '" class="rounded-circle" alt="avatar" style="width: 40px;height: 40px;">';
                                                                                            }
                                                                                          } catch (PDOException $e) {
                                                                                            header("Location: logout.php");
                                                                                          }
                                                                                          ?> </div>
                          <div class="ml-3 lh-1">
                            <h6 class="mb-0 py-3"> <?php
                                                    try {
                                                      echo $row_get_user["name"];
                                                    } catch (PDOException $e) {
                                                      header("Location: logout.php");
                                                    }
                                                    ?> </h6>
                          </div>
                        </div>
                      </div>
                      <div class="dropdown-divider"></div>
                      <div class="">
                        <ul class="list-unstyled">
                          <li>
                            <a class="dropdown-item" href="profile.php">
                              <span class="mr-2">
                                <i class="fa fa-user"></i>
                              </span>Profile </a>
                          </li>
                        </ul>
                      </div>
                      <div class="dropdown-divider"></div>
                      <ul class="list-unstyled">
                        <li>
                          <a class="dropdown-item" href="logout.php">
                            <span class="mr-2">
                              <i class="fas fa-sign-out-alt"></i>
                            </span>Sign Out </a>
                        </li>
                      </ul>
                    </div>
                  </li>
                </ul>
              </li> <?php
                  }
                    ?>
          </ul>
        </div>
      </div>
    </nav>
    <!-- /Heder -->
    <div class="main-body">
      <div class="service pb-90">
        <div class="container">
          <!--<div class="row align-items-center"><div class="col-md-12 col-xl-12 revealOnScroll fadeInDown animated" data-animation="fadeInDown" data-timeout="100"><div class="title-modern"><div class="title-modern-subtitle"><span class="fvBlue-text">Farvision </span><span class="fvGreen-text">World</span></div></div></div></div>-->
          <div class="row">
            <div class="col-lg-8 col-md-6 col-sm-12">
              <!--<div class="row"><div class="col-lg-12 col-md-12 col-sm-12"><div class="offer-title"><h3>Offer of the Month</h3></div></div></div>-->
              <section id="main-slider">

                <!-- <div>
                      <div class="item">
                        <img alt="" src="images/sliders/slider2-25-11-2023.jpg">
                      </div>
                    </div> -->


                <div class="owl-carousel owl-theme">

                  <?php
                  while ($row_get_sliders = mysqli_fetch_assoc($result_get_sliders)) {
                    if ($row_get_sliders["check_slider"] == 0) {
                      continue;
                    }
                  ?>
                    <div>
                      <div class="item">
                        <?= '<img src="data:' . $row_get_sliders["slider_type"] . ';base64,' . $row_get_sliders["slider_img"] . '" alt="slider">'; ?>
                      </div>
                    </div>
                  <?php
                  }
                  ?>

                </div>
              </section>

            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 feature-single">

                  <div class="card">
                    <?php
                    if ($count_get_webinars == 1) {
                      echo '<img src="data:' . $row_get_webinars["thumbnail_type"] . ';base64,' . $row_get_webinars["thumbnail"] . '" alt="avatar">';
                      date_default_timezone_set('Asia/Kolkata');
                      $cur_date = date('Y-m-d');
                      $cur_time = date('H:i:s');
                      $webinar_date = DateTime::createFromFormat('Y-m-d', $row_get_webinars['webinar_date']);
                      $formatted_webinar_date = $webinar_date->format('d/m/Y');
                    ?>
                  </div>
                  <div class="card-body">
                    <h5 class="card-title"> <?= $row_get_webinars["title"]; ?> </h5>
                    <p class="card-text"> <?= $row_get_webinars["description"]; ?> </p>
                    <p>
                      <small>
                        <strong> <?= $formatted_webinar_date; ?> </strong>
                      </small>
                      <small>
                        <strong> <?= substr($row_get_webinars["webinar_time"], 0, 5); ?> </strong>
                      </small>
                    </p>
                    <a href="<?php echo substr($row_get_webinars["meeting_link"], 0); ?>" target="_blank" class="join-button" id="join-webinar-btn">Join Webinar </a>
                  </div>
                <?php
                    } else {
                      echo '<img src="images/default_webinar_image.jpeg">';
                    }
                ?>
                </div>

              </div>
              <!-- <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 feature-single">
                  <div class="card">
                    <img src="images/default_webinar_image.jpeg">
                  </div>

                </div>

              </div> -->

              <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 m-t">
                  <div class="offer-title">
                    <h3>Highlights</h3>
                  </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 feature-single">
                  <ul class="webinar-news">
                    <li>
                      <a href="#s">
                        <div class="news-text">
                          <h6 class="text-0x">Hold GST tax component of Vendor bill till it reflects in GSTR2A</h6>
                        </div>
                      </a>
                    </li>
                  </ul>
                </div>
                </a>
              </div>
            </div>
          </div>
          <!--<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 m-t"><div class="offer-title"><h3>Lucky One of the month</h3></div></div><div class="col-lg-2 col-md-6 col-sm-12 feature-single"><div class=" mb-4"><div class="card-body text-center"><img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar" class="rounded-circle img-fluid" style="width: 70px;"><h5 class="my-3">John Smith</h5></div></div></div></div>-->
          <!--<div class="col-lg-2 col-md-6 col-sm-12 feature-single"><a href="#s"><div class="feature-single-box"><div class="d-flex"><div class="flex-shrink-0"><div class="feature-icon"><i class="vs vs-notification"></i></div></div><div class="flex-grow-1 ms-3"><div class="feature-title"><h3>Invite Farvision User</h3></div></div></div></div></a></div><div class="col-lg-2 col-md-6 col-sm-12 feature-single"><a href="#s" target="_blank"><div class="feature-single-box"><div class="d-flex"><div class="flex-shrink-0"><div class="feature-icon"><i class="vs vs-webinar"></i></div></div><div class="flex-grow-1 ms-3"><div class="feature-title"><h3>Google Review</h3></div></div></div></div></a></div><div class="col-lg-2 col-md-6 col-sm-12 feature-single"><a href="#s" target="_blank"><div class="feature-single-box"><div class="d-flex"><div class="flex-shrink-0"><div class="feature-icon"><i class="vs vs-share"></i></div></div><div class="flex-grow-1 ms-3"><div class="feature-title"><h3>Post On Social Media</h3></div></div></div></div></a></div><div class="col-lg-2 col-md-6 col-sm-12 feature-single"><a href="#s" target="_blank"><div class="feature-single-box"><div class="d-flex"><div class="flex-shrink-0"><div class="feature-icon"><i class="vs vs-blogging"></i></div></div><div class="flex-grow-1 ms-3"><div class="feature-title"><h3>Blog</h3></div></div></div></div></a></div>-->
        </div>
      </div>
    </div>
  </div>
  <!-- /Main Body -->
  <footer class="footer-container">
    <div class="darkExBlue-bg  p-3">
      <div class="container  m-20">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
              <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                <div class="footer-header">
                  <h3 class="font-w-100 white-Text">Links</h3>
                </div>
                <ul class="footer-menu">
                  <!--<li><a href="index.php">Home</a></li>-->
                  <li>
                    <a href="refer-farvision.php?utm_source=Website&utm_medium=Website&utm_campaign=Farvision_World_Referral_Program">Refer Farvision </a>
                  </li>
                  <!--<li><a href="offer-of-the-month.html">Offer of the Month</a></li>-->
                  <li>
                    <a href="webinar.php">Webinar</a>
                  </li>
                </ul>
                <div class="social">
                  <a href="" class="btn btn-social-icon btn-facebook m-r">
                    <span class="fab fa-facebook-f"></span>
                  </a>
                  <a href="" class="btn btn-social-icon btn-twitter m-r">
                    <span class="fab fa-twitter"></span>
                  </a>
                  <a href="" class="btn btn-social-icon btn-linkedin m-r">
                    <span class="fab fa-linkedin-in"></span>
                  </a>
                  <a href="" class="btn btn-social-icon btn-google m-r">
                    <span class="fab fa-youtube"></span>
                  </a>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                <div class="footer-header">
                  <h3 class="font-w-100 white-Text">Latest News</h3>
                </div>
                <ul class="footer-news">
                  <li>
                    <div class="news-box bg-white "></div>
                    <div class="news-text text-white">
                      <h4>Upcoming Webinar</h4>
                      <h6 class="text-0x">Watch Out for our Upcoming Webinar.</h6>
                      <!--<small class="orangeLight-Text">10/11/2023 14:00</small>-->
                    </div>
                  </li>
                </ul>
              </div>
              <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                <div class="footer-header">
                  <h3 class="font-w-100 white-Text">Contact Us</h3>
                </div>
                <div class="footer-text ">
                  <h6>
                    <span class="font-w-100 white-Text">Gamut Infosystems Ltd.</span>
                  </h6>
                  <h6>
                    <span class="Font-bold white-Text">Adress: </span>
                    <span class="font-w-100 white-Text">Anuj Chambers, 6th Floor, 24 Park Street, Kolkata - 700016, India</span>
                  </h6>
                  <h6>
                    <span class="Font-bold white-Text">Email: </span>
                    <span class="font-w-100 white-Text">sales@gamutinfosystems.com</span>
                  </h6>
                  <h6>
                    <span class="Font-bold white-Text">Phone: </span>
                    <span class="font-w-100 white-Text">+91 33 4026 3000</span>
                  </h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container m-10 p-3">
      <p class="m-0 text-center text-0x">
        <strong> &copy; Copyright</strong> Gamut Infosystems Ltd <span id="printYear">2020</span>
      </p>
    </div>
    <!-- /Footer -->
  </footer>
  </div>
  <script>
    // Function to add class after a delay
    function addClassAfterDelay(idName) {
      //console.log("Hi");
      // Select the element where you want to add the class
      const element = document.getElementById(idName);
      // Add a class after a delay of 10 seconds (10000 milliseconds)
      setTimeout(function() {
        element.classList.add('show'); // Replace 'your-class' with your desired class name
      }, 10000); // 10 seconds delay (time is in milliseconds)
    }
    // Call the function to add class after a delay
    addClassAfterDelay("backgroundDark");
    addClassAfterDelay("popupWindow");
    // JavaScript to show modal after clicking on page
    document.addEventListener('click', function() {
      showModal();
    });
    // Your custom function to be executed
    function showModal() {
      let backgroundDark = document.getElementById("backgroundDark")
      let popupWindow = document.getElementById("popupWindow")
      backgroundDark.classList.add('show')
      popupWindow.classList.add('show')
    }
  </script>
  <script src="js/jquery-3.4.1.js" type="text/javascript"></script>
  <script src="js/popper.min.js" type="text/javascript"></script>
  <script src="js/plugins/bootstrap/bootstrap.js" type="text/javascript"></script>
  <script src="js/plugins/wowPageAnination/wow.min.js"></script>
  <script src="js/plugins/pace/pace.min.js"></script>
  <script src="js/plugins/owl.carousel/owl.carousel.js"></script>
  <script src="js/plugins/slick/slick.js"></script>
  <!-- Multi Select -->
  <script src="js/main.js"></script>
  <script>
    $('.owl-carousel').owlCarousel({
      items: 1,
      loop: true,
      autoplay: true,
      autoplayTimeout: 5000,
      autoplayHoverPause: true,
      nav: false,
      dots: false,
      navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
      transitionStyle: "fade"
    })
  </script>
</body>

</html>