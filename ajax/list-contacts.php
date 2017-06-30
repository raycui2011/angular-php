<?php
include '../includes/Database.php';
include '../entity/Contact.php';

$db = new Database();
$connection = $db->getConnection();
$oContact = new Contact($connection);
$result = $oContact->listAll();
echo json_encode($result);

