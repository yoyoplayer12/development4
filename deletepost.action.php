<?php
include_once(__DIR__ . "/bootstrap.php");
//logindetection dine in deletepost()
$user_id = $_GET['uid'];
$delete = new Action();
$delete->deletepost($user_id);
