<?php
include '../includes/Database.php';
include '../entity/Contact.php';

$db = new Database();
$conn = $db->getConnection();
$oContact = new Contact($conn);
//$params = json_decode($_POST);
$postdata = file_get_contents("php://input");

	$std = json_decode($postdata);
	$response = $oContact->create($std->first_name, $std->last_name, $std->mobile, $std->email,$std->post_code );

	$result = [];
	if ($response) {
		$result = ['success' => true];
	} else {
		$result = ['success' => false, 'error' => ['code' => 401, 'message' => 'something is wrong']];
	}
echo json_encode($result);
exit;

