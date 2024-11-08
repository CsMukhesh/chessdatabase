<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ChessDB Sign Up</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">

    <style>
        html, body {
            height: 100%; /* Make the body take full height */
            margin: 0; /* Remove default margin */
        }

        body {
            font-size: 14px;
            line-height: 1.8;
            color: #222;
            font-weight: 400;
            font-family: 'Montserrat', sans-serif;
            background-image: url("https://wallpapers.com/images/hd/chess-background-na0c3ufv4kcfdcmk.jpg");
            background-repeat: no-repeat;
            background-size: cover; /* Ensure background covers the entire viewport */
            background-position: center center; /* Center the background image */
            padding: 0;
        }

        .main {
            display: flex;
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
            height: 100%; /* Full height to center the content */
        }

        .container {
            width: 100%;
            max-width: 500px; /* Limit the width */
            padding: 20px; /* Padding inside the container */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Optional: Add a subtle shadow for depth */
            background: rgba(255, 255, 255, 0.8); /* Slightly more opaque for better readability */
            border-radius: 10px;
        }

        .signup-content {
            padding: 30px; /* Adjust padding for content */
        }

        .form-group {
            overflow: hidden;
            margin-bottom: 20px;
        }

        .form-input {
            width: 100%;
            border: 1px solid #ebebeb;
            border-radius: 5px;
            padding: 15px;
            font-size: 14px;
            font-weight: 500;
            color: #222;
        }

        .form-submit {
            width: 100%;
            border-radius: 5px;
            padding: 15px 55px;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            border: none;
            background-image: linear-gradient(to left, darkblue, blue);
        }

        .availability {
            font-size: 12px;
            color: red;
        }

        .loginhere {
            color: #555;
            font-weight: 500;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .loginhere a {
            font-weight: 700;
            color: #222;
        }

        .field-icon {
            float: right;
            margin-right: 17px;
            margin-top: -32px;
            position: relative;
            z-index: 2;
            color: #555;
        }

        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .success {
            color: green;
            background-color: #dff0d8;
        }

        .error {
            color: red;
            background-color: #f2dede;
        }
    </style>
</head>
<body>
    <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <!-- Display success or error messages -->
                    <?php
                    session_start();
                    if (isset($_SESSION['success'])) {
                        echo '<div class="message success">' . $_SESSION['success'] . '</div>';
                        unset($_SESSION['success']);
                    }
                    if (isset($_SESSION['error'])) {
                        echo '<div class="message error">' . $_SESSION['error'] . '</div>';
                        unset($_SESSION['error']);
                    }
                    ?>
                    <form action="save.php" method="POST" id="signup-form" class="signup-form">
                        <h2 class="form-title">Create account</h2>
                        <div class="form-group">
                            <input type="text" class="form-input" name="username" id="username" placeholder="Your Name" required>
                            <div id="username-feedback" class="availability"></div>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-input" name="email" id="email" placeholder="Your Email" required>
                            <div id="email-feedback" class="availability"></div>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-input" name="password" id="password" placeholder="Password" required>
                            <span toggle="#password" class="zmdi zmdi-eye field-icon toggle-password"></span>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-input" name="re_password" id="re_password" placeholder="Repeat your password" required>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" required>
                            <label for="agree-term" class="label-agree-term">I agree all statements in <a href="#" class="term-service">Terms of service</a></label>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" id="submit" class="form-submit" value="Sign up"/>
                        </div>
                    </form>
                    <p class="loginhere">
                        Have already an account? <a href="sign-in.php">Login here</a>
                    </p>
                </div>
            </div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#username').on('input', function() {
                var username = $(this).val();
                if (username.length > 0) {
                    $.ajax({
                        url: 'check_credentials.php',
                        type: 'POST',
                        data: { username: username },
                        dataType: 'json',
                        success: function(response) {
                            $('#username-feedback').text(response.username);
                            if (response.username === 'Username is available.') {
                                $('#username-feedback').css('color', 'green');
                            } else {
                                $('#username-feedback').css('color', 'red');
                            }
                        }
                    });
                } else {
                    $('#username-feedback').text('');
                }
            });

            $('#email').on('input', function() {
                var email = $(this).val();
                if (email.length > 0) {
                    $.ajax({
                        url: 'check_credentials.php',
                        type: 'POST',
                        data: { email: email },
                        dataType: 'json',
                        success: function(response) {
                            $('#email-feedback').text(response.email);
                            if (response.email === 'Email is available.') {
                                $('#email-feedback').css('color', 'green');
                            } else {
                                $('#email-feedback').css('color', 'red');
                            }
                        }
                    });
                } else {
                    $('#email-feedback').text('');
                }
            });

            $(".toggle-password").click(function() {
                $(this).toggleClass("zmdi-eye zmdi-eye-off");
                var input = $($(this).attr("toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        });
    </script>
</body>
</html>
