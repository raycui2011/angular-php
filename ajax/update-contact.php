<?php
include '../includes/Database.php';
include '../entity/Contact.php';

$db = new Database();
$conn = $db->getConnection();
$oContact = new Contact($conn);
$postdata = file_get_contents("php://input");

	$std = json_decode($postdata);
	$oContact->setId($std->id);
	$oContact->setFirstName($std->first_name);
	$oContact->setLastName($std->last_name);
	$oContact->setEmail($std->email);
	$oContact->setMobile($std->mobile);
	$oContact->setCreatedAt($std->created_at);
	$oContact->setPostCode($std->post_code);
	
	$response = $oContact->updateContact($oContact);

echo json_encode($response);
exit;

