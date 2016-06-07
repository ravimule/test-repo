<?php
/*
Site : http:www.smarttutorials.net
Author :muni
*/
session_start();

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
			getUsers($mysqli, $_SESSION['user_session']);
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
		case "sort_user":
			sort_user($mysqli,  $_POST['sortUser']);
			break;
		case "like":
			like($mysqli,  $_POST['row_selected'], $_SESSION['user_session']);
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
		$dob = $mysqli->real_escape_string( isset( $_POST['user']['birthDate'] ) ? $_POST['user']['birthDate'] : '');
		$id = $mysqli->real_escape_string( isset( $_POST['user']['id'] ) ? $_POST['user']['id'] : '');
	
		if($name == '' || $companyName == '' || $designation == ''|| $email == '' ){
			throw new Exception( "Required fields missing, Please enter and submit" );
		}
	
	
		if(empty($id)){
			$query = "INSERT INTO employee (`emp_id`, `name`, email, `companyName`, `designation`, `avatar`, `dob`) VALUES ('', '$name', '$email', '$companyName', '$designation', '$avatar', '$dob')";
		}else{
			$query = "UPDATE employee SET `name` = '$name', email = '$email', `companyName` = '$companyName', `designation` = '$designation', `avatar` = '$avatar', `dob` = '$dob' WHERE `employee`.`emp_id` = $id";
		}
	
		if( $mysqli->query( $query ) ){
			$data['success'] = true;
			if(!empty($id))$data['message'] = 'User updated successfully.';
			else $data['message'] = 'User inserted successfully.';
			if(empty($id))$data['emp_id'] = (int) $mysqli->insert_id;
			else $data['emp_id'] = (int) $id;
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
		$query = "DELETE FROM `employee` WHERE `emp_id` = $id";
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
function getUsers($mysqli, $log_user = ''){
	try{
		//$query = "SELECT * from ( Select  `emp`.`emp_id`, `emp`.`name`, `emp`.`email`, `emp`.`companyName`, `emp`.`designation`, `emp`.`avatar`, `emp`.`dob`, `ua`.`emp_id` as `activity_emp_id`, `ua`.`reg_user_id`, `ua`.`flag` from `employee` as `emp` left join `user_activity` as `ua` on `emp`.`emp_id` = `ua`.`emp_id`) as `data` where (`data`.`reg_user_id` is null  ) order by `data`.`emp_id` desc limit 8";
		
		//$query = "SELECT `employee`.*, count(`ua`.`user_id`) as `likes`, `ua`.`reg_user_id`, `ua`.`flag`, group_concat(`ua`.`reg_user_id`) as `liked_by` FROM `employee` left join `user_activity` as `ua` ON `employee`.`emp_id` = `ua`.`emp_id` group by `ua`.`emp_id` limit 8";

		$query = "SELECT * from ( SELECT * from employee ) as FirstSet
		left join ( SELECT `user_id`,`emp_id` as `e_id`, SUM(if(`flag` = 1, 1, 0)) as `likes`, group_concat(`reg_user_id`) as `liked_by`, `reg_user_id` FROM user_activity group by `e_id` ) as SecondSet on FirstSet.`emp_id` = SecondSet.`e_id`
		left join ( select `flag`, `emp_id` as `temp_id` from user_activity where `reg_user_id` = $log_user ) as third on SecondSet.`e_id` = third.`temp_id` limit 8";

		$result = $mysqli->query( $query );
		$record = array();
		while ($row = $result->fetch_assoc()) {
			$row['emp_id'] = (int) $row['emp_id'];
			$data['data'][] = $row;
		}

		$data['success'] = true;
		$data['user_session'] = $_SESSION['user_session'];
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
			$query = "SELECT * FROM `employee` where `companyName` = '$companyName' order by emp_id";
		}
		else{
			$query = "SELECT * FROM `employee` order by emp_id";
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
			$query = "SELECT * FROM `employee` where `designation` = '$designation' order by emp_id";
		}
		else{
			$query = "SELECT * FROM `employee` order by emp_id";
		}
		$result = $mysqli->query( $query );
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$row['emp_id'] = (int) $row['emp_id'];
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
			$query = "SELECT * FROM `employee` order by emp_id";
		}
		$result = $mysqli->query( $query );
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$row['emp_id'] = (int) $row['emp_id'];
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
			$query = "SELECT * FROM `employee` order by emp_id";
		}
		$result = $mysqli->query( $query );
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$row['emp_id'] = (int) $row['emp_id'];
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

function sort_user($mysqli,  $sort_user = ''){
	try{
		if( $sort_user == "a-z"){
			$query = "SELECT * FROM `employee` order by name ASC";
		}
		else if( $sort_user == "z-a"){
			$query = "SELECT * FROM `employee` order by name DESC";
		}
		else{
			$query = "SELECT * FROM `employee` order by ".$sort_user." ASC";
		}
		$result = $mysqli->query( $query );
		$data = array();
		while ($row = $result->fetch_assoc()) {
			$row['emp_id'] = (int) $row['emp_id'];
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

function like($mysqli,  $likeid = '', $log_user = ''){
	try{
		$data = array();

		$query = "SELECT * from user_activity where `emp_id` = '$likeid' and `reg_user_id` = '$log_user'";

		$result = $mysqli->query( $query );

	    if($result->num_rows == 1) {
	    	$row = $result->fetch_array();
	    	if( $row['flag'] == 0 ){
	    		$sql = "UPDATE user_activity SET `flag` = '1' WHERE `emp_id`='$likeid' and `reg_user_id` = '$log_user'";
	    	}
	    	else{
	    		$sql = "UPDATE user_activity SET `flag` = '0' WHERE `emp_id`='$likeid' and `reg_user_id` = '$log_user'";
	    	}
	    }
		else{
			$sql = "INSERT INTO user_activity (`user_id`,`emp_id`,`reg_user_id`, `flag`) VALUES ('','$likeid','$log_user', '1') ON DUPLICATE KEY UPDATE flag = '0'";
		}

		if( $mysqli->query( $sql ) == true ){
			$data['success'] = true;
			$query = "SELECT flag from user_activity where `emp_id` = '$likeid' and `reg_user_id` = '$log_user'";
			$result = $mysqli->query( $query );
			$row = $result->fetch_array();

			$data['likestatus'] = $row['flag'];

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

