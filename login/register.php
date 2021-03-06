<?php
require("nav.php");
//require(__DIR__ . "/../lib/myFunctions.php");
if (isset($_REQUEST["email"])) {
    $email = $_REQUEST["email"];
    $password = $_REQUEST["password"];
    $confirm = $_REQUEST["confirm"];
    $username = $_REQUEST["username"];
    //make sure values are set
    if (is_empty_or_null($email) || is_empty_or_null($password) || is_empty_or_null($confirm) || is_empty_or_null($username)) {
        echo "Something's missing here....";
        exit();
    }
    //remove beginning and trailing whitespace
    $email = trim($email);
    $password = trim($password);
    $confirm = trim($confirm);
    $username = trim($username);
    //verify passwords match
    if ($password !== $confirm) {
        echo "Passwords don't match...";
        exit();
    }
    //validate/sanitize email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email!!!";
        die();
    }
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    //validate/sanitize username
    /*if(strlen($username) < 4){
            echo "Username must be 4 or more characters";
            exit();
        }*/
    //using regex for length and character types
    $count = preg_match('/^[a-z]{4,20}$/i', $username, $matches);
    if ($count === 0) {
        echo "Username must be between 4 and 20 characters and only contain alphabetical characters.";
        exit();
    }
    $username = filter_var($username, FILTER_SANITIZE_STRING);

    //valid password
    if (strlen($password) < 6) {
        echo "Password must be 6 or more characters";
        exit();
    }

    require(__DIR__ . "/../lib/db.php"); //<-- gives us $db
    //mysqli escape
    $email = mysqli_real_escape_string($db, $email);
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $password = mysqli_real_escape_string($db, $password);

    ///$sql = "INSERT INTO mt_users (email, password, rawPassword, username) VALUES ('$email', '$hash','$password', '$username')";
    //query with placeholders
    $sql = "INSERT INTO rv8_users (email, password, rawPassword, username) VALUES (?,?,?,?)";
    //init a statement "object"
    $stmt = mysqli_stmt_init($db);
    //prepare the sql
    mysqli_stmt_prepare($stmt, $sql);
    //bind the values to pass in (sanitizes)
    mysqli_stmt_bind_param($stmt, "ssss", $email, $hash, $password, $username);
    //executes everything
    $retVal = mysqli_stmt_execute($stmt);

    //$retVal = mysqli_query($db, $sql);
    if ($retVal) {
        echo "Welcome to the club";
        die(header("Location: authenticate.php"));
    } else {
        echo mysql_error_info($db);
        //"practical" regex example
        /*if(preg_match('[Duplicate]', $error, $matches) > 0){
                echo "This email is already in use";
            }
            else{
                echo "Something didn't work out " . mysqli_error($db);
            }*/
    }
    //TODO: don't forget to close your connection, don't want resource leaks
    mysqli_close($db);
}
?>
<script>
function validate(form) {
    let isValid = true;
    //document.forms[0];var patt = new RegExp("e");
    let emailPattern = /^[a-z]{2,4}[0-9]{0,3}@[a-z]+\.[a-z]{2,4}$/i;
    let emailRegex = new RegExp(emailPattern);
    let emailInput = form.email.value.trim();
    console.log(emailInput, "is valid ", emailRegex.test(emailInput));
    if (emailRegex.test(emailInput)) {
        document.getElementById("vEmail").innerText = "";
    } else {
        document.getElementById("vEmail").innerText = "Invalid email address";
        isValid = false;
    }
    let usernamePattern = /^[a-z]{4,20}$/i;
    let usernameRegex = new RegExp(usernamePattern);
    let usernameInput = form.username.value.trim();
    console.log(usernameInput, " is valid ", usernameRegex.test(usernameInput));
    if (usernameRegex.test(usernameInput)) {
        document.getElementById("vUsername").innerText = "";
    } else {
        document.getElementById("vUsername").innerText =
            "Invalid Username: must only contain letters and be between 4-20 characters.";
        isValid = false;
    }
    //alert(emailPattern.test(emailInput));
    let password = form.password.value.trim();
    let confirm = form.confirm.value.trim();
    if (password !== confirm) {
        console.log("Passwords don't match!");
        document.getElementById("vConfirm").innerText = "Passwords don't match";
        isValid = false;
    } else {
        document.getElementById("vConfirm").innerText = "";
    }
    if (password.length < 6) {
        console.log("Password is too short, must be 6+");
        document.getElementById("vPassword").innerText = "Password is too short, must be 6+ characters";
        isValid = false;
    } else {
        document.getElementById("vPassword").innerText = "";
    }
    return isValid; //<--false prevents the form from submitting
}
</script>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../styles/register.css">
</head>

<body>
    <div id="wrapper">

        <div class="fields">
            <h2>Registration form</h2>

            <svg class="car" width="868" height="267" viewBox="0 0 868 267" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <g id="Orange_sport_car 1 1" clip-path="url(#clip0)">
                    <g id="Orange sport car 1">
                        <g id="layer1">
                            <path id="path3800" opacity="0.76" fill-rule="evenodd" clip-rule="evenodd"
                                d="M621.935 232.134L796.749 221.615C796.749 221.615 806.582 137.462 803.851 136.877C801.119 136.293 719.175 89.5412 719.175 89.5412L624.666 139.215L621.935 232.134Z"
                                fill="#4D4D4D" stroke="#100000" stroke-width="0.541867" />
                            <path id="path3796" opacity="0.76"
                                d="M106.779 131.033C106.779 133.371 95.8532 228.043 95.8532 228.043L277.223 230.965C277.223 230.965 273.945 130.449 270.121 126.943C266.297 123.436 193.094 96.5539 193.094 96.5539L106.779 131.033Z"
                                fill="#4D4D4D" stroke="#100000" stroke-width="0.541867" />
                            <path id="path2998"
                                d="M13.5679 227.357L0.103821 160.578C45.7923 132.195 90.2554 101.939 145.763 88.5613C179.293 85.6031 217.956 89.5104 246.133 79.3955C293.222 53.431 339.152 25.9185 391.793 7.37869C473.478 -6.36268 546.936 0.798034 617.014 16.5444L772.465 66.3016C796.122 65.7377 817.497 68.0213 849.407 57.1578L847.131 82.0143L867.94 152.722C847.85 213.801 842.039 205.06 831.219 220.81C831.219 220.81 793.274 239.142 789.602 232.595C785.93 226.048 788.378 164.506 788.378 164.506C773.553 136.642 752.355 114.16 710.04 109.512C663.261 113.031 645.781 139.454 636.598 172.363C629.623 196.805 631.035 221.247 634.15 245.689L539.9 240.451L515.42 229.976L265.718 236.523V168.435C256.724 141.926 244.606 116.566 189.828 106.893C147.417 112.107 122.44 135.051 107.687 168.294L109.042 241.761L13.5679 227.357Z"
                                fill="#FFB021" />
                            <path id="path3000" opacity="0.443946"
                                d="M342.542 87.788L404.819 23.5045C404.819 19.0167 548.495 15.3991 547.948 25.2577C547.752 28.8062 534.291 78.4377 534.291 78.4377L342.542 87.788Z"
                                fill="#1A1A1A" stroke="#100000" stroke-width="0.541867" />
                            <path id="path3772" opacity="0.76"
                                d="M551.463 24.0889C583.307 28.5521 618.619 35.6337 674.925 58.5683V67.9186L541.629 76.6845L551.463 24.0889Z"
                                fill="#333333" stroke="#100000" stroke-width="0.541867" />
                            <path id="path3774" opacity="0.76"
                                d="M318.195 92.4632C292.571 109.836 306.43 161.4 312.732 206.42" stroke="#100000"
                                stroke-width="0.541867" />
                            <path id="path3776" opacity="0.76"
                                d="M553.648 79.6065C564.619 92.5854 553.814 137.765 537.805 190.642C463.621 203.326 388.173 202.501 313.278 207.589"
                                stroke="#100000" stroke-width="1.26455" />
                            <path id="path3782" opacity="0.76"
                                d="M237.995 192.979C237.995 223.38 214.957 248.025 186.538 248.025C158.119 248.025 135.081 223.38 135.081 192.979C135.081 162.578 158.119 137.933 186.538 137.933C214.957 137.933 237.995 162.578 237.995 192.979Z"
                                stroke="#100000" stroke-width="0.408322" />
                            <path id="path3786" opacity="0.76"
                                d="M237.995 192.979C237.995 223.38 214.957 248.025 186.538 248.025C158.119 248.025 135.081 223.38 135.081 192.979C135.081 162.578 158.119 137.933 186.538 137.933C214.957 137.933 237.995 162.578 237.995 192.979Z"
                                fill="#808080" stroke="#100000" stroke-width="0.408322" />
                            <path id="path3790" opacity="0.76"
                                d="M764.623 191.226C764.623 221.627 741.585 246.272 713.166 246.272C684.747 246.272 661.709 221.627 661.709 191.226C661.709 160.825 684.747 136.18 713.166 136.18C741.585 136.18 764.623 160.825 764.623 191.226Z"
                                fill="#999999" stroke="#100000" stroke-width="0.408322" />
                            <g id="path3792" opacity="0.76">
                                <path id="Vector" opacity="0.76"
                                    d="M27.5663 139.799C73.7061 150.867 104.453 141.493 108.418 96.554L27.5663 139.799Z"
                                    fill="#808080" />
                                <path id="Vector_2" opacity="0.76"
                                    d="M27.5663 139.799C73.7061 150.867 104.453 141.493 108.418 96.554" stroke="#100000"
                                    stroke-width="0.541867" />
                            </g>
                            <path id="path3802" opacity="0.76"
                                d="M356.618 65.3861C354.465 67.0915 352.489 68.8919 352.247 71.6197C352.908 73.5191 352.206 74.4473 355.525 78.2429C364.812 82.6272 374.099 82.9824 383.386 83.3076C386.66 82.4614 387.351 80.234 387.757 77.8533L388.667 63.0486C388.027 60.4865 386.388 58.9927 384.297 57.9838C373.713 57.9617 363.83 59.2253 356.618 65.3861Z"
                                fill="#FF932A" stroke="#100000" stroke-width="0.541867" />
                            <g id="g3816">
                                <g id="path3794" opacity="0.76">
                                    <path id="Vector_3" opacity="0.76"
                                        d="M207.844 74.3469L238.982 85.4504L335.677 88.3724L383.204 35.1924L390.306 14.7386L363.538 -12.1436"
                                        fill="#808080" />
                                    <path id="Vector_4" opacity="0.76"
                                        d="M207.844 74.3469L238.982 85.4504L335.677 88.3724L383.204 35.1924L390.306 14.7386L363.538 -12.1436"
                                        stroke="#100000" stroke-width="0.541867" />
                                </g>
                                <g id="path3804" opacity="0.76">
                                    <path id="Vector_5" opacity="0.76"
                                        d="M863.226 77.5522L822.536 70.9157C805.899 89.1231 808.294 92.8812 805.749 100.39C806.498 103.253 806.606 106.115 810.129 108.978C828.619 110.307 847.108 107.407 865.598 105.27"
                                        fill="#FF0000" />
                                    <path id="Vector_6" opacity="0.76"
                                        d="M863.226 77.5522L822.536 70.9157C805.899 89.1231 808.294 92.8812 805.749 100.39C806.498 103.253 806.606 106.115 810.129 108.978C828.619 110.307 847.108 107.407 865.598 105.27"
                                        stroke="#100000" stroke-width="0.541867" />
                                </g>
                                <path id="path3806" opacity="0.76"
                                    d="M-10.2943 161.693C-3.36925 166.21 16.8991 170.727 16.8991 172.353C16.8991 173.979 23.993 186.988 23.993 186.988C23.993 186.988 -5.39608 185.543 -6.7473 185.543C-8.09852 185.543 -10.2943 161.693 -10.2943 161.693Z"
                                    fill="#FF8700" stroke="#100000" stroke-width="0.541867" />
                                <path id="path3810" opacity="0.76" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M0.34663 191.144C1.36004 191.325 24.8375 192.409 25.1753 193.854C25.5131 195.3 29.3979 207.044 29.3979 207.044C29.3979 207.044 24.3308 215.717 23.6552 215.175C22.9796 214.633 -7.92963 211.019 -7.92963 211.019L0.34663 191.144Z"
                                    fill="#FF8700" stroke="#100000" stroke-width="0.541867" />
                            </g>
                            <path id="path3812" opacity="0.76"
                                d="M631.395 141.739C631.395 150.896 624.455 158.319 615.895 158.319C607.335 158.319 600.396 150.896 600.396 141.739C600.396 132.581 607.335 125.158 615.895 125.158C624.455 125.158 631.395 132.581 631.395 141.739Z"
                                fill="#F9F9F9" stroke="#100000" stroke-width="0.541867" />
                        </g>
                        <g id="frontWheel">
                            <path id="path4015"
                                d="M260 186.94C260 227.533 227.093 260.44 186.501 260.44C145.907 260.44 113 227.533 113 186.94C113 146.347 145.907 113.44 186.501 113.44C227.093 113.44 260 146.347 260 186.94Z"
                                fill="black" stroke="#FF0000" stroke-width="0.0553196" />
                            <path id="path3766"
                                d="M246.35 186.94C246.35 219.995 219.554 246.791 186.501 246.791C153.446 246.791 126.65 219.995 126.65 186.94C126.65 153.885 153.446 127.09 186.501 127.09C219.554 127.09 246.35 153.885 246.35 186.94Z"
                                fill="#C6D2D2" stroke="#FF0000" stroke-width="0.045046" />
                            <path id="path3768"
                                d="M241.1 186.73C241.1 216.885 216.654 241.33 186.501 241.33C156.346 241.33 131.9 216.885 131.9 186.73C131.9 156.576 156.346 132.13 186.501 132.13C216.654 132.13 241.1 156.576 241.1 186.73Z"
                                fill="#4D4D4D" stroke="#FF0000" stroke-width="0.045046" />
                            <path id="path3770"
                                d="M204.141 186.94C204.141 196.683 196.243 204.58 186.501 204.58C176.758 204.58 168.86 196.683 168.86 186.94C168.86 177.198 176.758 169.3 186.501 169.3C196.243 169.3 204.141 177.198 204.141 186.94Z"
                                fill="#B3B3B3" stroke="#FF0000" stroke-width="0.045046" />
                            <path id="path2989"
                                d="M192.38 186.94L201.2 242.8H192.38C191.353 240.023 191.723 236.026 186.5 236.921V186.94H192.38Z"
                                fill="#CCCCCC" stroke="#FF0000" stroke-width="0.045046" />
                            <path id="path3772 1"
                                d="M192.38 186.94L201.2 242.8C198.458 243.448 195.808 244.171 192.38 244.27C191.353 241.493 191.723 236.026 186.5 236.921V186.94H192.38Z"
                                fill="#CCCCCC" stroke="#FF0000" stroke-width="0.045046" />
                            <path id="path3774 4"
                                d="M180.62 186.94L171.8 242.8C174.542 243.448 177.192 244.171 180.62 244.27C181.646 241.493 181.277 236.026 186.501 236.921V186.94H180.62Z"
                                fill="#CCCCCC" stroke="#FF0000" stroke-width="0.045046" />
                            <g id="g3788">
                                <path id="path3790 2"
                                    d="M192.38 186.94L201.2 242.8C198.458 243.448 195.808 244.171 192.38 244.27C191.353 241.493 191.723 236.026 186.501 236.921V186.94H192.38Z"
                                    fill="#CCCCCC" />
                                <path id="path3792 3"
                                    d="M187.453 191.905L137.052 217.555C135.589 215.147 134.084 212.85 132.929 209.62C135.253 207.786 140.568 206.447 138.103 201.757L185.636 186.312L187.453 191.905Z"
                                    fill="#CCCCCC" />
                                <path id="path3794 2"
                                    d="M181.209 188.753L141.24 148.745C143.078 146.609 144.797 144.467 147.512 142.372C149.974 144.015 152.89 148.655 156.589 144.861L185.966 185.296L181.209 188.753Z"
                                    fill="#CCCCCC" />
                                <path id="path3796 2"
                                    d="M182.277 181.84L207.975 131.464C210.574 132.551 213.143 133.525 215.975 135.459C215.173 138.309 211.66 142.516 216.412 144.861L187.034 185.296L182.277 181.84Z"
                                    fill="#CCCCCC" />
                                <path id="path3798"
                                    d="M189.181 180.72L245.032 189.594C244.802 192.402 244.67 195.145 243.705 198.436C240.747 198.554 235.661 196.513 234.898 201.757L187.364 186.312L189.181 180.72Z"
                                    fill="#CCCCCC" />
                                <path id="path3800 1"
                                    d="M180.62 186.94L171.8 242.8C174.542 243.448 177.192 244.171 180.62 244.27C181.646 241.493 181.277 236.026 186.501 236.921V186.94H180.62Z"
                                    fill="#CCCCCC" />
                                <path id="path3802 6"
                                    d="M183.819 180.72L127.968 189.594C128.199 192.402 128.331 195.145 129.296 198.436C132.253 198.554 137.339 196.513 138.103 201.757L185.636 186.312L183.819 180.72Z"
                                    fill="#CCCCCC" />
                                <path id="path3804 8"
                                    d="M190.723 181.84L165.025 131.464C162.425 132.551 159.857 133.525 157.026 135.459C157.827 138.309 161.34 142.516 156.589 144.861L185.966 185.296L190.723 181.84Z"
                                    fill="#CCCCCC" />
                                <path id="path3806 5"
                                    d="M191.791 188.753L231.76 148.745C229.924 146.609 228.203 144.467 225.489 142.372C223.026 144.015 220.111 148.655 216.412 144.861L187.034 185.296L191.791 188.753Z"
                                    fill="#CCCCCC" />
                                <path id="path3808"
                                    d="M185.547 191.905L235.948 217.555C237.412 215.147 238.917 212.85 240.071 209.62C237.747 207.786 232.433 206.447 234.898 201.757L187.364 186.312L185.547 191.905Z"
                                    fill="#CCCCCC" />
                            </g>
                            <path id="path3835" fill-rule="evenodd" clip-rule="evenodd"
                                d="M189.44 198.701L185.424 199.776L182.484 196.836L183.561 192.82L187.577 191.744L190.517 194.684L189.44 198.701Z"
                                fill="black" />
                            <g id="g3862">
                                <path id="path3864" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M189.44 198.701L185.424 199.776L182.484 196.836L183.561 192.82L187.577 191.744L190.517 194.684L189.44 198.701Z"
                                    fill="black" />
                                <path id="path3866" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M175.36 192.742L173.095 189.255L174.984 185.551L179.135 185.333L181.4 188.82L179.513 192.525L175.36 192.742Z"
                                    fill="black" />
                                <path id="path3868" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M176.675 177.512L179.292 174.28L183.399 174.93L184.889 178.812L182.272 182.044L178.165 181.393L176.675 177.512Z"
                                    fill="black" />
                                <path id="path3870" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M191.568 174.055L195.45 175.544L196.1 179.651L192.869 182.267L188.987 180.777L188.337 176.671L191.568 174.055Z"
                                    fill="black" />
                                <path id="path3872" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M199.457 187.15L199.239 191.302L195.535 193.19L192.048 190.926L192.266 186.773L195.97 184.885L199.457 187.15Z"
                                    fill="black" />
                            </g>
                            <g id="text3902">
                                <path id="path3914"
                                    d="M185.036 187.417L185.371 187.5C185.3 187.775 185.175 187.985 184.993 188.128C184.81 188.273 184.588 188.344 184.325 188.344C184.053 188.344 183.832 188.29 183.661 188.179C183.491 188.068 183.36 187.908 183.272 187.697C183.183 187.487 183.139 187.261 183.139 187.02C183.139 186.757 183.188 186.527 183.289 186.331C183.389 186.136 183.533 185.987 183.718 185.886C183.903 185.784 184.108 185.733 184.33 185.733C184.583 185.733 184.796 185.798 184.968 185.926C185.139 186.054 185.26 186.236 185.327 186.468L184.999 186.546C184.94 186.362 184.855 186.229 184.744 186.145C184.633 186.061 184.492 186.019 184.323 186.019C184.129 186.019 183.967 186.066 183.837 186.159C183.706 186.251 183.614 186.377 183.562 186.533C183.509 186.69 183.483 186.852 183.483 187.018C183.483 187.233 183.514 187.42 183.576 187.581C183.639 187.741 183.736 187.86 183.868 187.94C184 188.019 184.144 188.059 184.297 188.059C184.485 188.059 184.643 188.005 184.773 187.897C184.903 187.789 184.991 187.629 185.036 187.417Z"
                                    fill="black" />
                                <path id="path3916"
                                    d="M185.507 188.302L186.477 185.777H186.837L187.871 188.302H187.49L187.195 187.537H186.139L185.862 188.302H185.507ZM186.235 187.265H187.092L186.829 186.565C186.748 186.353 186.688 186.178 186.649 186.042C186.617 186.203 186.572 186.364 186.513 186.524L186.235 187.265Z"
                                    fill="black" />
                                <path id="path3918"
                                    d="M188.146 188.302V185.777H189.266C189.491 185.777 189.662 185.799 189.779 185.844C189.896 185.89 189.989 185.97 190.06 186.085C190.13 186.199 190.165 186.327 190.165 186.466C190.165 186.645 190.107 186.795 189.991 186.919C189.875 187.041 189.696 187.12 189.454 187.152C189.542 187.196 189.61 187.237 189.655 187.279C189.753 187.368 189.845 187.48 189.933 187.614L190.371 188.302H189.951L189.617 187.776C189.52 187.625 189.439 187.509 189.376 187.428C189.312 187.348 189.257 187.292 189.206 187.259C189.157 187.228 189.105 187.205 189.053 187.193C189.016 187.184 188.954 187.18 188.868 187.18H188.48V188.302H188.146ZM188.48 186.891H189.199C189.352 186.891 189.47 186.876 189.557 186.844C189.643 186.812 189.709 186.762 189.753 186.692C189.798 186.623 189.82 186.547 189.82 186.466C189.82 186.346 189.777 186.248 189.69 186.171C189.603 186.094 189.466 186.056 189.279 186.056H188.48V186.891Z"
                                    fill="black" />
                            </g>
                        </g>
                        <g id="backWheel">
                            <path id="path4652"
                                d="M786 189.5C786 230.093 753.093 263 712.501 263C671.907 263 639 230.093 639 189.5C639 148.907 671.907 116 712.501 116C753.093 116 786 148.907 786 189.5Z"
                                fill="black" stroke="#FF0000" stroke-width="0.0553196" />
                            <path id="path4654"
                                d="M772.35 189.5C772.35 222.555 745.554 249.351 712.501 249.351C679.446 249.351 652.65 222.555 652.65 189.5C652.65 156.445 679.446 129.65 712.501 129.65C745.554 129.65 772.35 156.445 772.35 189.5Z"
                                fill="#C6D2D2" stroke="#FF0000" stroke-width="0.045046" />
                            <path id="path4656"
                                d="M767.1 189.291C767.1 219.445 742.654 243.89 712.501 243.89C682.346 243.89 657.9 219.445 657.9 189.291C657.9 159.136 682.346 134.69 712.501 134.69C742.654 134.69 767.1 159.136 767.1 189.291Z"
                                fill="#4D4D4D" stroke="#FF0000" stroke-width="0.045046" />
                            <path id="path4658"
                                d="M730.14 189.5C730.14 199.243 722.243 207.14 712.5 207.14C702.757 207.14 694.86 199.243 694.86 189.5C694.86 179.758 702.757 171.86 712.5 171.86C722.243 171.86 730.14 179.758 730.14 189.5Z"
                                fill="#B3B3B3" stroke="#FF0000" stroke-width="0.045046" />
                            <path id="path4660"
                                d="M718.38 189.5L727.2 245.36H718.38C717.353 242.583 717.723 238.586 712.501 239.481V189.5H718.38Z"
                                fill="#CCCCCC" stroke="#FF0000" stroke-width="0.045046" />
                            <path id="path4662"
                                d="M718.38 189.5L727.2 245.36C724.458 246.008 721.808 246.731 718.38 246.83C717.353 244.053 717.723 238.586 712.501 239.481V189.5H718.38Z"
                                fill="#CCCCCC" stroke="#FF0000" stroke-width="0.045046" />
                            <path id="path4664"
                                d="M706.62 189.5L697.8 245.36C700.542 246.008 703.192 246.731 706.62 246.83C707.647 244.053 707.277 238.586 712.501 239.481V189.5H706.62Z"
                                fill="#CCCCCC" stroke="#FF0000" stroke-width="0.045046" />
                            <g id="backWheel_2">
                                <path id="path4668"
                                    d="M718.38 189.5L727.2 245.36C724.458 246.008 721.808 246.731 718.38 246.83C717.353 244.053 717.723 238.586 712.501 239.481V189.5H718.38Z"
                                    fill="#CCCCCC" />
                                <path id="path4670"
                                    d="M713.453 194.465L663.052 220.115C661.588 217.707 660.083 215.41 658.929 212.18C661.253 210.346 666.567 209.007 664.102 204.317L711.636 188.872L713.453 194.465Z"
                                    fill="#CCCCCC" />
                                <path id="path4672"
                                    d="M707.209 191.313L667.24 151.305C669.078 149.169 670.797 147.027 673.512 144.932C675.974 146.575 678.889 151.215 682.588 147.421L711.966 187.856L707.209 191.313Z"
                                    fill="#CCCCCC" />
                                <path id="path4674"
                                    d="M708.277 184.4L733.975 134.024C736.574 135.111 739.143 136.085 741.974 138.019C741.173 140.869 737.66 145.076 742.412 147.421L713.034 187.856L708.277 184.4Z"
                                    fill="#CCCCCC" />
                                <path id="path4676"
                                    d="M715.181 183.28L771.032 192.154C770.802 194.962 770.67 197.705 769.705 200.996C766.747 201.114 761.661 199.073 760.898 204.317L713.364 188.872L715.181 183.28Z"
                                    fill="#CCCCCC" />
                                <path id="path4678"
                                    d="M706.62 189.5L697.8 245.36C700.542 246.008 703.192 246.731 706.62 246.83C707.647 244.053 707.277 238.586 712.501 239.481V189.5H706.62Z"
                                    fill="#CCCCCC" />
                                <path id="path4680"
                                    d="M709.819 183.28L653.968 192.154C654.198 194.962 654.331 197.705 655.295 200.996C658.253 201.114 663.339 199.073 664.102 204.317L711.636 188.872L709.819 183.28Z"
                                    fill="#CCCCCC" />
                                <path id="path4682"
                                    d="M716.723 184.4L691.025 134.024C688.425 135.111 685.857 136.085 683.026 138.019C683.827 140.869 687.34 145.076 682.588 147.421L711.966 187.856L716.723 184.4Z"
                                    fill="#CCCCCC" />
                                <path id="path4684"
                                    d="M717.791 191.313L757.76 151.305C755.923 149.169 754.203 147.027 751.488 144.932C749.026 146.575 746.111 151.215 742.412 147.421L713.034 187.856L717.791 191.313Z"
                                    fill="#CCCCCC" />
                                <path id="path4686"
                                    d="M711.547 194.465L761.948 220.115C763.412 217.707 764.917 215.41 766.071 212.181C763.747 210.346 758.433 209.007 760.898 204.317L713.364 188.872L711.547 194.465Z"
                                    fill="#CCCCCC" />
                            </g>
                            <path id="path4688" fill-rule="evenodd" clip-rule="evenodd"
                                d="M715.44 201.261L711.424 202.336L708.484 199.396L709.56 195.38L713.576 194.304L716.517 197.244L715.44 201.261Z"
                                fill="black" />
                            <g id="g4690">
                                <path id="path4692" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M715.44 201.261L711.424 202.336L708.484 199.396L709.56 195.38L713.576 194.304L716.517 197.244L715.44 201.261Z"
                                    fill="black" />
                                <path id="path4694" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M701.36 195.302L699.095 191.815L700.984 188.111L705.135 187.893L707.4 191.38L705.513 195.085L701.36 195.302Z"
                                    fill="black" />
                                <path id="path4696" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M702.675 180.071L705.292 176.839L709.399 177.49L710.889 181.371L708.272 184.603L704.165 183.952L702.675 180.071Z"
                                    fill="black" />
                                <path id="path4698" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M717.568 176.615L721.449 178.104L722.1 182.211L718.868 184.827L714.987 183.337L714.337 179.231L717.568 176.615Z"
                                    fill="black" />
                                <path id="path4700" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M725.457 189.71L725.239 193.862L721.535 195.75L718.048 193.486L718.266 189.333L721.97 187.445L725.457 189.71Z"
                                    fill="black" />
                            </g>
                            <g id="g4702">
                                <path id="path4704"
                                    d="M711.036 189.977L711.371 190.06C711.3 190.335 711.175 190.545 710.993 190.688C710.81 190.833 710.588 190.904 710.325 190.904C710.053 190.904 709.831 190.849 709.661 190.739C709.49 190.628 709.36 190.468 709.272 190.257C709.183 190.047 709.138 189.821 709.138 189.58C709.138 189.317 709.188 189.087 709.289 188.892C709.389 188.696 709.532 188.547 709.717 188.446C709.903 188.344 710.108 188.293 710.33 188.293C710.583 188.293 710.795 188.358 710.967 188.486C711.139 188.614 711.26 188.796 711.327 189.028L710.998 189.106C710.94 188.922 710.855 188.789 710.743 188.705C710.632 188.621 710.492 188.579 710.323 188.579C710.129 188.579 709.967 188.626 709.836 188.719C709.706 188.811 709.614 188.937 709.562 189.093C709.509 189.25 709.482 189.412 709.482 189.578C709.482 189.793 709.514 189.98 709.576 190.141C709.639 190.301 709.736 190.42 709.868 190.5C710 190.579 710.144 190.619 710.297 190.619C710.484 190.619 710.643 190.565 710.773 190.457C710.903 190.349 710.991 190.189 711.036 189.977Z"
                                    fill="black" />
                                <path id="path4706"
                                    d="M711.507 190.862L712.477 188.337H712.837L713.87 190.862H713.489L713.195 190.097H712.139L711.862 190.862H711.507ZM712.235 189.825H713.092L712.828 189.125C712.747 188.913 712.688 188.738 712.649 188.602C712.617 188.763 712.572 188.924 712.513 189.084L712.235 189.825Z"
                                    fill="black" />
                                <path id="path4708"
                                    d="M714.145 190.862V188.337H715.266C715.491 188.337 715.662 188.359 715.779 188.404C715.896 188.45 715.989 188.53 716.059 188.644C716.13 188.759 716.165 188.887 716.165 189.025C716.165 189.205 716.107 189.355 715.99 189.479C715.875 189.601 715.695 189.679 715.454 189.712C715.541 189.756 715.609 189.797 715.655 189.839C715.752 189.928 715.845 190.04 715.933 190.174L716.371 190.862H715.951L715.617 190.336C715.52 190.185 715.439 190.069 715.375 189.988C715.312 189.908 715.256 189.852 715.206 189.819C715.157 189.787 715.105 189.765 715.053 189.753C715.016 189.744 714.953 189.74 714.868 189.74H714.48V190.862H714.145ZM714.48 189.451H715.198C715.352 189.451 715.47 189.435 715.557 189.404C715.643 189.372 715.709 189.322 715.753 189.252C715.797 189.183 715.82 189.106 715.82 189.025C715.82 188.906 715.777 188.807 715.69 188.731C715.603 188.654 715.466 188.615 715.279 188.615H714.48V189.451Z"
                                    fill="black" />
                            </g>
                        </g>
                    </g>
                </g>
                <defs>
                    <clipPath id="clip0">
                        <rect width="868" height="267" fill="white" />
                    </clipPath>
                </defs>
            </svg>


            <form method="POST" onsubmit="return validate(this);">
                <div>
                    <label>Email</label>

                </div>
                <div>
                    <input class="email" type="text" name="email" required autocomplete="off" />
                    <span id="vEmail"></span>
                </div>
                <div>
                    <label>Username</label>
                </div>

                <div>
                    <input class="username" type="text" name="username" required autocomplete="off" />
                    <span id="vUsername"></span>
                </div>
                <div>
                    <label>Password</label>
                </div>
                <div>
                    <input class="password" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}"
                        title="Must contain at least one  number and one uppercase and lowercase letter, and at least 6 or more characters"
                        name="password" required />
                    <span id="vPassword"></span>
                </div>
                <div>
                    <label>Confirm Password</label>
                </div>
                <div>
                    <input class="password" type="password" name="confirm" required />
                    <span id="vConfirm"></span>
                </div>

                <input id="submit-btn" type="submit" value="Register" />
            </form>
        </div>
    </div>




    <form method="POST" onsubmit="return validate(this);">

</body>

</html>
