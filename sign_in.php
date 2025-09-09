<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include 'db.php';
$errors=[];
$show_register_form = false;
if($_SERVER['REQUEST_METHOD']=='POST'){
	$username=trim($_POST['username']?? '');  //rana rjoub
	$email=trim($_POST['email']?? ''); //ranarjoub@gmail.com
	$password=trim($_POST['password']?? ''); //r123456$
    
    if (empty($password)) {
        $errors['password'] = 'password required';
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

?>