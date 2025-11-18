<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NinjaLegacy Ký Ức Tuổi Thơ</title>
    <link rel="stylesheet" href="/../static/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
    <!--<link rel="icon" href="/../images/1.gif" type="image/x-icon">--?
           Favicon and Touch Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/../images/1.gif">
    <link rel="icon" type="image/png" sizes="32x32" href="/../images/1.gif">
    <link rel="icon" type="image/png" sizes="16x16" href="/../images/1.gif">
    <link rel="shortcut icon" href="/../images/1.gif">
    <meta name="description" content="NinjaLegacy Ký Ức Tuổi Thơ">
    <meta property="og:image" content="/../images/1.gif">
    <meta property="og:image:width" content="800">
    <meta property="og:image:height" content="600">
    <!-- <script async defer crossorigin="anonymous" src="//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v17.0" nonce="tQcugFbH"></script> -->
</head>

<?php ob_start(); ?>
<?php
//ob_start();
include_once './CMain/connect.php';
if (!$connect)
    exit(0);
session_start();
?>




<body>
    <div id="root">
        <div class="container">
            <div class="main">
                <div class="text-center card">
                    <div class="card-body">
                        <div class="">
                            <a href="/">
                                <img class="logo" alt="Logo" src="/images/1.gif" style="max-width: 220px;">
                            </a>
                        </div>
                        <div class="mt-3">
                            <?php if (isset($_SESSION['username'])) {
                                $sql = "SELECT * FROM `users` WHERE `username` = '" . $_SESSION['username'] . "' LIMIT 1";
                                $query = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_array($query);
                                $formattedcoin = number_format($row['coin']);
                                echo
                                '<div class="my-2 my-md-0 mr-md-3">
                                <a class="btn btn-dangerxyz fw-semibold" href="/profile">' . $_SESSION['username'] . ' - ' . number_format($row['coin']) . ' P</a>
                                <span>&nbsp;</span><a class="btn btn-dangerxyz fw-semibold" href="/logout">		<span>Đăng xuất</span></a>
                               </div>
                               ';
                                if ($row['status'] == 0) {
                                    echo '<div class="mt-2"><small class="text-danger fw-semibold mt-3">Tài khoản của bạn chưa được kích hoạt, click vào phía dưới để kích hoạt.</small></div><div class="mt-2"> <span class="mb-3 px-2 py-1 fw-semibold text-secondary bg-danger bg-opacity-25 border border-danger border-opacity-75 rounded-2 link-success cursor-pointer" data-bs-toggle="modal" data-bs-target="#modalActive">Kích hoạt tài khoản</span></div>';
                                }
                            } else {
                                echo '<a class="mt-3" data-bs-toggle="modal" data-bs-target="#modalLogin">
                                <span class="btn btn-dangerxyz fw-semibold">Đăng nhập?</span></a>';
                                echo '<a class="mt-3" data-bs-toggle="modal" data-bs-target="#modalRegister">
                                <span class="btn btn-dangerxyz fw-semibold">Đăng ký</span></a>';
                            }
                            ?>


                        </div>
                    </div>
                </div>
                <?php include_once './thongbao.php'; ?>
                <?php include_once './nude.php'; ?>
                <?php include_once './lo-ginnhap.php'; ?>
                <?php include_once './kichactive.php'; ?>
                <?php include_once './reg-in.php'; ?>
                <?php //include_once './forgotpassword.php'; 
                ?>
                <?php
                $file_path = './loading.php';
                if (file_exists($file_path)) {
                    include_once $file_path;
                    //echo "có file.";
                } else {
                    echo "Error: File not found!";
                }
                ?>

                <style>
                    .modal {
                        transition: opacity 0.3s ease-in-out;
                    }

                    .modal.show {
                        opacity: 1;
                    }

                    .modal-close-btn {
                        background-color: #007BFF;
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 16px;
                        margin-top: 10px;
                        transition: background-color 0.3s ease-in-out;
                    }

                    .modal-close-btn:hover {
                        background-color: #0056b3;
                    }
                </style>

                <div class="modal fade" id="modalQuangCao" tabindex="-1" aria-labelledby="modalQuangCaoLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="my-2">
                                    <div class="text-center"><a href="/"><img class="logo" alt="Logo" src="/images/1.gif" style="max-width: 250px;"></a></div>
                                </div>
                                <div class="text-center fw-semibold">
                                    <div id="noti" style="text-align: center;"></div>
                                    <div class="text-white text-center mb-2" id="waiting-times"></div>
                                    <div class="fs-6 mb-2" id="rainbowText">NSO</div>
                                    <div id="noti-active"></div>
                                    <span id="blinkingText" class="blink">Liên hệ ngay</span>
                                    <a class="w-100 fw-semibold" href="">
                                        <div class="mt-2 aci">
                                            <button type="button" onclick="handleConfirm()" class="btn-rounded btn btn-primary btn-sm">NSO</button>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <style>
                    .btn-rounded {
                        transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out, color 0.2s ease-in-out;
                    }

                    .btn-rounded:hover {
                        transform: scale(1.1);
                        background-color: #007bff;
                        color: #ffffff;
                    }
                </style>


                <script type="text/javascript">
                    function toSpans(span) {
                        var str = span.firstChild.data;
                        var a = str.length;
                        span.removeChild(span.firstChild);
                        for (var i = 0; i < a; i++) {
                            var theSpan = document.createElement("SPAN");
                            theSpan.appendChild(document.createTextNode(str.charAt(i)));
                            span.appendChild(theSpan);
                        }
                    }

                    function RainbowSpan(span, hue, deg, brt, spd, hspd) {
                        this.deg = (deg == null ? 360 : Math.abs(deg));
                        this.hue = (hue == null ? 0 : Math.abs(hue) % 360);
                        this.hspd = (hspd == null ? 3 : Math.abs(hspd) % 360);
                        this.length = span.firstChild.data.length;
                        this.span = span;
                        this.speed = (spd == null ? 50 : Math.abs(spd));
                        this.hInc = this.deg / this.length;
                        this.brt = (brt == null ? 255 : Math.abs(brt) % 256);
                        this.timer = null;
                        toSpans(span);
                        this.moveRainbow();
                    }

                    RainbowSpan.prototype.moveRainbow = function() {
                        if (this.hue > 359) this.hue -= 360;
                        var color;
                        var b = this.brt;
                        var a = this.length;
                        var h = this.hue;

                        for (var i = 0; i < a; i++) {
                            if (h > 359) h -= 360;

                            if (h < 60) {
                                color = Math.floor((h / 60) * b);
                                red = b;
                                grn = color;
                                blu = 0;
                            } else if (h < 120) {
                                color = Math.floor(((h - 60) / 60) * b);
                                red = b - color;
                                grn = b;
                                blu = 0;
                            } else if (h < 180) {
                                color = Math.floor(((h - 120) / 60) * b);
                                red = 0;
                                grn = b;
                                blu = color;
                            } else if (h < 240) {
                                color = Math.floor(((h - 180) / 60) * b);
                                red = 0;
                                grn = b - color;
                                blu = b;
                            } else if (h < 300) {
                                color = Math.floor(((h - 240) / 60) * b);
                                red = color;
                                grn = 0;
                                blu = b;
                            } else {
                                color = Math.floor(((h - 300) / 60) * b);
                                red = b;
                                grn = 0;
                                blu = b - color;
                            }

                            h += this.hInc;

                            this.span.childNodes[i].style.color = "rgb(" + red + ", " + grn + ", " + blu + ")";
                        }
                        this.hue += this.hspd;
                    }

                    var rainbowText = document.getElementById("rainbowText");
                    var rainbowEffect = new RainbowSpan(rainbowText, 0, 360, 255, 50, 18);
                    rainbowEffect.timer = window.setInterval(function() {
                        rainbowEffect.moveRainbow();
                    }, rainbowEffect.speed);
                    /*hoandz*/
                    $(document).ready(function() {
                        //   $('#modalQuangCao').modal('show');
                    });

                    function handleConfirm() {}
                </script>
                <script src="./static/js/jquery.min.js"></script>
                <script src="./static/js/popper.min.js"></script>
                <script src="./static/js/bootstrap.min.js"></script>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


                <script>
                    $(document).ready(function() {
                        var currentPage = window.location.pathname.split('/').pop();
                        if (currentPage === '') {
                            currentPage = 'home';
                        }
                        $('.btn-menu').each(function() {
                            if ($(this).attr('href') === '/' + currentPage) {
                                $(this).addClass('active');
                            }
                        });
                    });
                </script>
                <!-- <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        playMusic();
                    });

                    function playMusic() {
                        var audio = new Audio('./images/nhacnen.mp3');
                        audio.play();
                    }
                </script> -->
                <script>
                    const flurryContainer = document.createElement('div');
                    flurryContainer.className = 'flurry-container';
                    flurryContainer.style.cssText = 'pointer-events:none;position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:9999;overflow:hidden;';
                    document.body.appendChild(flurryContainer);

                    const icons = ['🌸', '❄', '🍁', '🍂', '🍃', '🪶', '🦋', '⭐', '✨'];

                    function createFlake() {
                        const flake = document.createElement('span');
                        flake.innerText = icons[Math.floor(Math.random() * icons.length)];
                        flake.style.position = 'absolute';
                        flake.style.top = '-24px';
                        flake.style.left = Math.random() * window.innerWidth + 'px';
                        flake.style.fontSize = (12 + Math.random() * 16) + 'px';
                        flake.style.opacity = 0.8 + Math.random() * 0.2;
                        flake.style.transition = `transform 8s linear, opacity 8s cubic-bezier(1,0.3,0.6,0.74)`;
                        flake.style.transform = `translateY(0px) rotateZ(${Math.random()*360}deg)`;
                        flake.style.textShadow = '0 0 2px #fff';
                        flurryContainer.appendChild(flake);

                        setTimeout(() => {
                            flake.style.transform = `translateY(${window.innerHeight + 40}px) rotateZ(${Math.random()*360}deg)`;
                            flake.style.opacity = 0;
                        }, 50);

                        setTimeout(() => {
                            if (flake.parentNode) flurryContainer.removeChild(flake);
                        }, 8000);
                    }

                    // Tạo hoa/lá rơi liên tục
                    setInterval(createFlake, 2000);
                </script>