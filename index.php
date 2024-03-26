<?php

include "./config.php";

header("Content-type: application/json");
$messages = [];
$success = false;

// Logic Start

// Logic End

echo json_encode([
    "success" => $success,
    "messages" => $messages,
    "data" => [
        "config" => $M_CONFIG,
    ]
]);
