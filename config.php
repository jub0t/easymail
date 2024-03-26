<?php

// Configuration for Mail server and MySQL Database
include "./vendor/autoload.php";

use Dallgoot\Yaml\Loader;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$DEBUG = false;
$YLOADER = new Loader(null, 0, $DEBUG);
$M_CONFIG = $YLOADER->load("./config.yml")->parse();

function SendMail()
{
}
