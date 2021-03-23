<?php
require_once('controller/authentication.php');

if (isset($_POST['signUp'])) {
    $user = new Authentication;
    if (!empty($_POST['fullName']) && !empty($_POST['userEmail']) && !empty($_POST['userPass'])) {
        $newUser = json_decode($user->userRegisteration($_POST['fullName'], $_POST['userEmail'], $_POST['userPass']));
        if ($newUser) {
            echo $user->showMessage("You successfully registered. Please login..").'<a href="Login.php">Click Here</a>';
        }else {
            echo $user->showMessage("User is already registered.");
        }
    }else {
        echo $user->showMessage("Field should not empty.");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp Page</title>
</head>
<body>
    <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
        Email : <input type="email" name="userEmail" id="userEmail">
        Password : <input type="password" name="userPass" id="userPass">
        Name : <input type="text" name="fullName" id="fullName">
        <input type="submit" name="signUp" value="Login">
    </form>
</body>
</html>