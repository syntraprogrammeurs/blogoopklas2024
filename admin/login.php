<?php
require_once("includes/header.php");
$the_message= "";
//controle of iemand was ingelogd?
if ($session->is_signed_in()) { //wanneer dit true is
    //wil zeggen dat iemand is ingelogd
    header("Location:index.php");
}

if(isset($_POST['submit'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    //check of the user bestaat in de database
    $user_found = User::verify_user($username, $password);

    if($user_found){
        $session->login($user_found);
        header("Location:index.php");
    }else{
        $username = "";
        $password="";
    }
}
?>
<div class="container-fluid">
    <div class="row vh-100">
        <div class="col-lg-6 offset-lg-3 my-auto">
            <div class="auth-logo">
                <a href="index.html"><img src="../admin/assets/compiled/svg/logo.svg" alt="Logo"></a>
            </div>
            <h1 class="auth-title">Log in.</h1>
            <p class="auth-subtitle mb-5">Log in with your data that you entered during registration.</p>
            <h2 class="bg-danger"><?php echo $the_message; ?></h2>
            <form action="" method="POST">
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="text" class="form-control form-control-xl" placeholder="Username" name="username">
                    <div class="form-control-icon">
                        <i class="bi bi-person"></i>
                    </div>
                </div>
                <div class="form-group position-relative has-icon-left mb-4">
                    <input type="password" class="form-control form-control-xl" placeholder="Password" name="password">
                    <div class="form-control-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                </div>
                <div class="form-check form-check-lg d-flex align-items-end">
                    <input class="form-check-input me-2" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label text-gray-600" for="flexCheckDefault">
                        Keep me logged in
                    </label>
                </div>
                <input type="submit" name="submit" value="Log in" class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
            </form>
            <div class="text-center mt-5 text-lg fs-4">
                <p class="text-gray-600">Don't have an account? <a href="auth-register.html" class="font-bold">Sign
                        up</a>.</p>
                <p><a class="font-bold" href="auth-forgot-password.html">Forgot password?</a>.</p>
            </div>
        </div>
    </div>
</div>