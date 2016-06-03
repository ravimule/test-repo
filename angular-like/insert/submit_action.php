<?php
/*
Site : http:www.smarttutorials.net
Author :muni
*/
session_start();

require_once 'home/config.php';

ini_set('display_errors', 1);

if( isset($_POST['type']) && !empty( isset($_POST['type']) ) ){
	$type = $_POST['type'];

	switch ($type) {
		case "reg_user":
			reg_user($mysqli);
			break;
		case "login_user":
			login_user($mysqli);
			break;
		default:
			invalidRequest();
	}
}else{
	invalidRequest();
}

/**
 * This function will handle user add, update functionality
 * @throws Exception
 */
function reg_user($mysqli){
	try{
		$data = array();
		$name = $mysqli->real_escape_string(isset( $_POST['user']['name'] ) ? $_POST['user']['name'] : '');
		$email = $mysqli->real_escape_string( isset( $_POST['user']['email'] ) ? $_POST['user']['email'] : '');
		$pass = $mysqli->real_escape_string( isset( $_POST['user']['pass'] ) ? $_POST['user']['pass'] : '');
		
		if($name == '' || $email == '' || $pass == '' ){
			throw new Exception( "Required fields missing, Please enter and submit" );
		}

		$query1 = "SELECT * FROM `reg_user` WHERE `email` = '$email' LIMIT 1";

		$result =  $mysqli->query( $query1 );

		if ( mysqli_num_rows($result) == 1 )
		{
			$data['message'] = 'Already registered.';
		}
		else
		{
			$query = "INSERT INTO reg_user (`id`, `name`, email, `password`) VALUES ('', '$name', '$email', '$pass')";
		
			if( $mysqli->query( $query ) ){
				$data['success'] = true;
				$data['message'] = 'User inserted successfully.';
				if(empty($id))$data['id'] = (int) $mysqli->insert_id;
				else $data['id'] = (int) $id;
			}else{
				throw new Exception( $mysqli->sqlstate.' - '. $mysqli->error );
			}
		}
		$mysqli->close();
		echo json_encode($data);
		exit;
	}catch (Exception $e){
		$data = array();
		$data['success'] = false;
		$data['message'] = $e->getMessage();
		echo json_encode($data);
		exit;
	}
}
	
/**
 * This function gets list of users from database
 */
function login_user($mysqli){
	try{
		$email = $_POST['user']['email'];
		$pass = $_POST['user']['pass'];
		$query = "SELECT * FROM `reg_user` where `email` = '".$email."' AND `password` = '".$pass."'";
		$result = $mysqli->query( $query );
		$data = array();
		if(mysqli_num_rows($result) > 0) {
			while($row = $result->fetch_assoc()) { 
					$data['success'] = true;
					$row['reg_user_id'] = (int) $row['reg_user_id'];
					$data['data'][] = $row;
					$_SESSION['user_session'] = $row['reg_user_id'];
				}
			} 	
		else {
				$data['success'] = false;
				$data['message'] = "Username or password wrong!";
			}

		echo json_encode($data);exit;
	
	}catch (Exception $e){
		$data = array();
		$data['success'] = false;
		$data['message'] = $e->getMessage();
		echo json_encode($data);
		exit;
	}
}


function invalidRequest()
{
	$data = array();
	$data['success'] = false;
	$data['message'] = "Invalid request.";
	echo json_encode($data);
	exit;
}

