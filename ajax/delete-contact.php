<?php
include '../includes/Database.php';
include '../entity/Contact.php';

$db = new Database();
$conn = $db->getConnection();
$oContact = new Contact($conn);
$postdata = file_get_contents("php://input");

$oId = json_decode($postdata);
$response = $oContact->softDelete($oId->id);

$result = [];
if ($response) {
	$result = ['success' => true];
} else {
	$result = ['success' => false, 'error' => ['code' => 401, 'message' => 'something is wrong']];
}
echo json_encode($result);
exit;

