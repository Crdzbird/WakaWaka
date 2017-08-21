<?php
/**
 *
 */
class UsuarioController extends CI_Controller
{

	function __construct(argument)
	{
		parent::__construct();
		$this->load->model('UsuarioModel');
	}

	function userAccess(){
		if($_SERVER['REQUEST_METHOD']=='POST'){
		 $username = $_POST['username'];
		 $password = $_POST['password'];

		 if($name == '' || $username == '' || $password == '' || $email == ''){
		 echo 'please fill all values';
		 }else{
		 require_once('dbConnect.php');
		 $sql = "SELECT * FROM users WHERE username='$username' OR email='$email'";

		 $check = mysqli_fetch_array(mysqli_query($con,$sql));

		 if(isset($check)){
		 echo 'username or email already exist';
		 }else{
		 $sql = "INSERT INTO users (name,username,password,email) VALUES('$name','$username','$password','$email')";
		 if(mysqli_query($con,$sql)){
		 echo 'successfully registered';
		 }else{
		 echo 'oops! Please try again!';
		 }
		 }
		 mysqli_close($con);
		 }
		}else{
		echo 'error';
		}
			}
}

 ?>
