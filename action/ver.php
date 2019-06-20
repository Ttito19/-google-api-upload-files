<?php
require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../classes/AuthDrive.php';

$objAuthDrive=new AuthDrive();

$id=$_GET['id'];

try{
	echo $objAuthDrive->ver($id);
}catch(Exception $e){
	echo $e->getMessage();
}