<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include 'db.php';
$errors=[];
$errors_login=[];
$show_register_form = false;
if(isset($_POST['register'])){
	$username=trim($_POST['username']?? '');  //rana rjoub
	$email=trim($_POST['email']?? ''); //ranarjoub@gmail.com
	$password=trim($_POST['password']?? ''); //r123456$
	 if (empty($password)) {
        $errors['password'] = 'password required';
    }
	else if(strlen($password)<6){
		$errors['password']='Password Should Consist At Least 6 Digits';
	}
	   if (empty($username)) {
        $errors['username'] = 'username required';
    }

    if (empty($email)) {
        $errors['email'] = 'email required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'email incorrect';
    }

   

if(empty($errors)){
 $stmt=$conn->prepare('SELECT user_id FROM users WHERE name= ? OR email= ?');
 $stmt->bind_param("ss" ,$name ,$email);
 $stmt->execute();
 $stmt->store_result();
 if($stmt->num_rows>0){
	$errors[]='the username and email already exist';
 }
 else{
	$hash=password_hash($password,PASSWORD_DEFAULT);
	$ins=$conn->prepare("INSERT INTO users (name,email,password) VALUES (?,?,?)");
	$ins->bind_param("sss",$username,$email,$hash);
	if($ins->execute()){
		$_SESSION['user_id']=$ins->insert_id;
		$_SESSION['name']=$username;
		   header('Location: index.php');
                exit;
	}
             else {
                $errors[] = 'an error occurred';}
	$ins->close();
}

$stmt->close();
	}
else{
	$show_register_form = true;
}

}
if(isset($_POST['login'])){
	  $email=trim($_POST['email']??'');
    $password=trim($_POST['password']??'');
    if (empty($email)) {
        $errors_login['email'] = 'email required';
    }
     if (empty($password)) {
        $errors_login['password'] = 'password required';
    }
    if(empty($errors)){
        $stmt=$conn->prepare("SELECT user_id,name,password FROM users  WHERE email= ? LIMIT 1 ");
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows==1){
          $stmt->bind_result($user_id,$name,$hash);
          $stmt->fetch();
          if(password_verify($password ,$hash)){
            session_regenerate_id(true);
            $_SESSION['user_id']=$user_id;
            $_SESSION['name']=$name;
            echo "تم تسجيل الدخول بنجاح. سيتم تحويلك الآن...";
            header('location:index.php');
            exit;
        }
          else{
            $errors_login[]='Password Incorrect';
        }
        }
        else{ 
        $errors_login[]='No User Found With This Email ';
    }
    $stmt->close();
}


}



?>

<style>
input.error {
    border: 2px solid red;
}

small.error-text {
    color: red;
    font-size: 0.85em;
}
</style>



<html>
    <head>
<link rel="stylesheet" href="css/account.css">
<script src="js/account.js" defer></script>
    </head>
    <body>
		

<div class="container <?php echo ($show_register_form) ? 'right-panel-active' : '' ?>" id="container">
	<div class="form-container sign-up-container">
		<form action="" method="post" autocomplete="off">
			<h1>Create Account</h1>
			<div class="social-container">
<a href="#" class="social"><img src="img/facebook.png" alt="Facebook" width="20px" height="20px" /></a>
<a href="#" class="social"><img src="img/google.png" alt="Google" width="20px" height="20px" /></a>
<a href="#" class="social"><img src="img/linkedin.png" alt="LinkedIn"width="20px" height="20px" /></a>

			</div>
			<span>or use your email for registration</span>
			<input type="text" placeholder="Name" name="username" />
			 <?php if (isset($errors['username'])): ?>
        <small class="error-text"><?php echo $errors['username']; ?></small>
    <?php endif; ?>
	
			<input type="email" placeholder="Email" name="email" />
			 <?php if (isset($errors['email'])): ?>
        <small class="error-text"><?php echo $errors['email']; ?></small>
    <?php endif; ?>
	
			<input type="password" placeholder="Password" name="password" />
			   <?php if (isset($errors['password'])): ?>
      <small class="error-text"><?php echo $errors['password']; ?></small>
    <?php endif; ?>
	<br>
    
			<button name="register">Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="" method="POST" autocomplete="pff">
			<h1>Sign in</h1>
			<div class="social-container">
				<a href="#" class="social"><img src="img/facebook.png" alt="Facebook" width="20px" height="20px" /></a>
<a href="#" class="social"><img src="img/google.png" alt="Google" width="20px" height="20px" /></a>
<a href="#" class="social"><img src="img/linkedin.png" alt="LinkedIn"width="20px" height="20px" /></a>
			</div>
			<span>or use your account</span>
			<input type="email" placeholder="Email"  name='email'/>
				 <?php if (isset($errors_login['email'])): ?>
        <small class="error-text"><?php echo $errors_login['email']; ?></small>
    <?php endif; ?>
			<input type="password" placeholder="Password" name='password' />
				   <?php if (isset($errors_login['password'])): ?>
      <small class="error-text"><?php echo $errors_login['password']; ?></small>
    <?php endif; ?>
	<br>
			<a href="#">Forgot your password?</a>
			<button name="login">Sign In</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Hello, Friend!</h1>
				<p>Enter your personal details and start journey with us</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>


</body>
</html>