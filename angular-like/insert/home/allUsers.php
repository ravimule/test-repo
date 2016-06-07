<?php
session_start();
require_once 'config.php';
$empid = '{{post.rowid}}';
//echo $empid;
//echo $_GET['id'];
$log_user = $_SESSION['user_session'];

$query = "SELECT `ru`.`name` FROM `reg_user` as ru left join `user_activity` as `ua` on `ru`.`reg_user_id` = `ua`.`reg_user_id` where `ua`.`emp_id`= ".$_GET['id']." and `ua`.`flag` = 1";

$result = $mysqli->query( $query ); ?>
<div class ="likedUser">
	<strong>Liked Users</strong>
	<hr>
	<?php
	if( $result->num_rows > 0 ){
		while ($row = $result->fetch_assoc()) {
			$user = $row['name']; ?>
			<p><?php echo $user ?> </p><?php
		}
	}
	else{ ?>
		<p><?php echo "No likes Yet...!"; ?></p><?php
	} ?>
</div>

