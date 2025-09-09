<?php
if(session_status()==PHP_SESSION_NONE) session_start();
include 'db.php';

$errors=[];
if($_SERVER['REQUEST_METHOD']=='POST'){
    $email=trim($_POST['email']??'');
    $password=trim($_POST['password']??'');
    if (empty($email)) {
        $errors['email'] = 'email required';
    }
     if (empty($password)) {
        $errors['password'] = 'password required';
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
            $errors[]='Password Incorrect';
        }
        }
        else{ 
        $errors[]='No User Found With This Email ';
    }
    $stmt->close();
}


}
?>