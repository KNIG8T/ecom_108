<?php

require_once("../dbconn.php");
if (isset($_SESSION)) {
    session_start();
}
$cities = array("Yangon", "Mandalay", "NayPyiTaw", "Taungyi", "Bago", "Ayeyarwady", "Magway");

if (isset($_POST["signUp"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $gender = $_POST['gender'];
    $city = $_POST['city'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $profile = $_FILES['profile'];
    $filePath = "profile/" . $_FILES['profile']['name'];
    function isPasswordStrong($password)
    {
        $digitCount = 0;
        $capitalCount = 0;
        $specialCount = 0;
        for ($i = 0; $i < strlen($password); $i++) {
            if (ctype_digit($password[$i])) { // checking whether letter is digit or not
                $digitCount++;
            } elseif (ctype_upper($password[$i])) {
                $capitalCount++;
            } elseif (preg_match('/[^a-zA-Z0-9\s]/', $password[$i])) {
                $specialCount++;
            } //end of for
        }
        if ($digitCount >= 1 && $capitalCount >= 1 && $specialCount >= 1) {
            return true;
        } else {
            return false;
        }
    } //function end

    if ($password === $cpassword) {
        if (strlen($password) >= 8) {
            if (isPasswordStrong($password)) {
                try {
                    $hashcode = password_hash($password, PASSWORD_BCRYPT);
                    $status = move_uploaded_file($_FILES['profile']['tmp_name'], $filePath);
                    if ($status) {
                        $sql = "insert into users values (?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        //userid	username	email	gender	city	phone	profile_path	password	
                        $stmt->execute([null, $username, $email, $gender, $city, $phone, $filePath, $hashcode]);
                        $_SESSION['customerEmail'] = $email;
                        $_SESSION['customerSignupSuccess'] = "Signup Success!! YOu can join here!";
                        header("Location:clogin.php");
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }// end if of password strong
            else{
            $errMessage = "Password musct include at least one uppercase letter, one digit and one special character";
        }
        }
         else {
            $errMessage = "Password length must be at least 8";
        }
    } else { //else of passwords are not the same
        $errMessage = "Password and Confirm Password must be the same";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Signup</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mx-auto py-3">
                <h3 class="text-center"> Sign Up</h3>
                <form action="signup.php" method="post" enctype="multipart/form-data">


                    <div class="mb-1">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-1">
                        <?php
                        if(isset($errMessage)){
                            echo "<p class = 'alert alert-danger'>$errMessage </p>";
                        }
                        ?>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-1">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-1">
                        <label for="cpassword" class="form-label">Confirm Password</label>
                        <input type="password" name="cpassword" class="form-control" required>
                    </div>

                    <div class="mb-1">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" required>
                    </div>

                    <p class="text fw-bold"> Choose Gender </p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" value="female">
                        <label class="form-check-label" for="gender">
                            female
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" value="male">
                        <label class="form-check-label" for="gender">
                            male
                        </label>
                    </div>

                    <div class="mb-3">
                        <select name="city" class="form-select">
                            <option value="" class="fw-bold">Choose City</option>
                            <?php
                            if (isset($cities)) {
                                foreach ($cities as $city) {
                                    echo "<option value = $city>$city</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-1">
                        <label for="profile" class="form-label">Choose Profile Image</label>
                        <input type="file" name="profile" class="form-control" required>
                    </div>

                    <div class="mb-1">
                        <button type="submit" name="signUp" class="btn btn-primary"> Signup </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>