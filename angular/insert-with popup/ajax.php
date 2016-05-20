<?php
/*
Site : http:www.smarttutorials.net
Author :muni
*/
require_once 'config.php';

ini_set('display_errors', 1);

if( isset($_POST['type']) && !empty( isset($_POST['type']) ) ){
	$type = $_POST['type'];
	
	switch ($type) {
		case "save_user":
			save_user($mysqli);
			break;
		case "delete_user":
			delete_user($mysqli, $_POST['id']);
			break;
		case "getUsers":
			getUsers($mysqli);
			break;
		case "search_user":
			search_user($mysqli,  $_POST['companyName']);
			break;
		case "avatar_upload":
			avatar_upload();
			break;
		case "search_des":
			search_des($mysqli,  $_POST['designation']);
			break;
		case "autocomplete":
			autocomplete($mysqli,  $_POST['autocomplete']);
			break;
		case "user_selected":
			user_selected($mysqli,  $_POST['user_selected']);
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
function save_user($mysqli){
	try{
		$data = array();
		$name = $mysqli->real_escape_string(isset( $_POST['user']['name'] ) ? $_POST['user']['name'] : '');
		$avatar = $mysqli->real_escape_string(isset( $_POST['user']['avatar'] ) ? $_POST['user']['avatar'] : '');
		$companyName = $mysqli->real_escape_string(isset( $_POST['user']['companyName'] ) ? $_POST['user']['companyName'] : '');
		$designation = $mysqli->real_escape_string( isset( $_POST['user']['designation'] ) ? $_POST['user']['designation'] : '');
		$email = $mysqli->real_escape_string( isset( $_POST['user']['email'] ) ? $_POST['user']['email'] : '');
		$id = $mysqli->real_escape_string( isset( $_POST['user']['id'] ) ? $_POST['user']['id'] : '');
	
		if($name == '' || $companyName == '' || $designation == ''|| $email == '' ){
			throw new Exception( "Required fields missing, Please enter and submit" );
		}
	
	
		if(empty($id)){
			$query = "INSERT INTO employee (`id`, `name`, email, `companyName`, `designation`, `avatar`) VALUES ('', '$name', '$email', '$companyName', '$designation', '$avatar')";
		}else{
			$query = "UPDATE employee SET `name` = '$name', email = '$email', `companyName` = '$companyName', `designation` = '$designation', `avatar` = '$avatar' WHERE `employee`.`id` = $id";
		}
	
		if( $mysqli->query( $query ) ){
			$data['success'] = true;
			if(!empty($id))$data['message'] = 'User updated successfully.';
			else $data['message'] = 'User inserted successfully.';
			if(empty($id))$data['id'] = (int) $mysqli->insert_id;
			else $data['id'] = (int) $id;
		}else{
			throw new Exception( $mysqli->sqlstate.' - '. $mysqli->error );
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
 * This function will handle user deletion
 * @param string $id
 * @throws Exception
 */
function delete_user($mysqli, $id = ''){
	try{
		if(empty($id)) throw new Exception( "Invalid User." );
		$query = "DELETE FROM `employee` WHERE `id` = $id";
		if($mysqli->query( $query )){
			$data['success'] = true;
			$data['message'] = 'User deleted successfully.';
			echo json_encode($data);
			exit;
		}else{
			throw new Exception( $mysqli->sqlstate.' - '. $mysqli->error );
		}
		
	
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
function getUsers($mysqli){
	try{
	
		$query = "SELECT * FROM `employee` order by id desc limit 8";
		$result = $mysqli->query( $query );
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$row['id'] = (int) $row['id'];
			$data['data'][] = $row;
		}
		$data['success'] = true;
		echo json_encode($data);exit;
	
	}catch (Exception $e){
		$data = array();
		$data['success'] = false;
		$data['message'] = $e->getMessage();
		echo json_encode($data);
		exit;
	}
}

function search_user($mysqli,  $companyName = ''){
	try{
		if( $companyName != ""){
			$query = "SELECT * FROM `employee` where `companyName` = '$companyName' order by id";
		}
		else{
			$query = "SELECT * FROM `employee` order by id";
		}
		$result = $mysqli->query( $query );
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$row['id'] = (int) $row['id'];
			$data['data'][] = $row;
		}
		$data['success'] = true;
		echo json_encode($data);exit;
	
	}catch (Exception $e){
		$data = array();
		$data['success'] = false;
		$data['message'] = $e->getMessage();
		echo json_encode($data);
		exit;
	}
}

function search_des($mysqli,  $designation = ''){
	try{
		if( $designation != ""){
			$query = "SELECT * FROM `employee` where `designation` = '$designation' order by id";
		}
		else{
			$query = "SELECT * FROM `employee` order by id";
		}
		$result = $mysqli->query( $query );
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$row['id'] = (int) $row['id'];
			$data['data'][] = $row;
		}
		$data['success'] = true;
		echo json_encode($data);exit;
	
	}catch (Exception $e){
		$data = array();
		$data['success'] = false;
		$data['message'] = $e->getMessage();
		echo json_encode($data);
		exit;
	}
}

function autocomplete($mysqli,  $autocomplete = ''){
	try{
		if( $autocomplete != ""){
			$query = "SELECT * FROM `employee` where `name` LIKE '%".$autocomplete."%' order by name";
		}
		else{
			$query = "SELECT * FROM `employee` order by id";
		}
		$result = $mysqli->query( $query );
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$row['id'] = (int) $row['id'];
			$data['data'][] = $row;
		}
		$data['success'] = true;
		echo json_encode($data);exit;
	
	}catch (Exception $e){
		$data = array();
		$data['success'] = false;
		$data['message'] = $e->getMessage();
		echo json_encode($data);
		exit;
	}
}

function user_selected($mysqli,  $user_selected = ''){
	try{
		if( $user_selected != ""){
			$query = "SELECT * FROM `employee` where `name` LIKE '%".$user_selected."%' order by name";
		}
		else{
			$query = "SELECT * FROM `employee` order by id";
		}
		$result = $mysqli->query( $query );
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$row['id'] = (int) $row['id'];
			$data['data'][] = $row;
		}
		$data['success'] = true;
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

