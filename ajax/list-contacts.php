<?php
include '../includes/Database.php';
include '../entity/Contact.php';

$db = new Database();
$postdata = file_get_contents("php://input");
$data = json_decode($postdata);
$limit = $data->entryLimit;


$connection = $db->getConnection();
$oContact = new Contact($connection);
$result = $oContact->listContacts();
echo json_encode($result);
exit;

