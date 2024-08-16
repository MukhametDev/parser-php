<?php

use Framework\CMain;
use Framework\CDatabase;
use Framework\CApi;
use Framework\CUser;
use Framework\Validators\Validator;
use Framework\Validators\UserValidator;
use Symfony\Component\VarDumper\VarDumper;

//dd($_SERVER["DOCUMENT_ROOT"]);
include_once $_SERVER["DOCUMENT_ROOT"] . "/../vendor/autoload.php";

global $APPLICATION;

//$APPLICATION = new CMain();
$DB = new CDatabase();
//$API = new CApi();