<?php

include "config.php";

// Create connection
$MYSQL = new mysqli(
    $M_CONFIG->db->host,
    $M_CONFIG->db->username,
    $M_CONFIG->db->password,
    $M_CONFIG->db->database
);

// Check for connection errors
if ($MYSQL->connect_error) {
    die("Connection failed: " . $MYSQL->connect_error);
}

// Create the table if it doesn't exist
$MYSQL->query("CREATE TABLE IF NOT EXISTS api_keys(
    `key` TEXT NOT NULL PRIMARY KEY,
    created_at BIGINT NOT NULL,
    name TEXT NOT NULL
)");

function verify_api_key($api_key)
{
    global $MYSQL;

    // Prepare and execute the query to check if the API key exists
    $query = "SELECT * FROM api_keys WHERE `key` = ?";
    $stmt = $MYSQL->prepare($query);
    $stmt->bind_param("s", $api_key);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if any row is returned
    if ($result->num_rows > 0) {
        return true; // API key exists
    } else {
        return false; // API key doesn't exist
    }
}
