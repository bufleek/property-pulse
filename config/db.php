<?php
$host = 'localhost';
$user = 'id22222243_property_pulse';
$password = "'E'Qa(5gJEdYEs.";
$database = 'id22222243_property_pulse';

// $host = 'localhost';
// $user = 'property_pulse';
// $password = 'password';
// $database = 'property_pulse';

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

function close_db_connection()
{
    global $conn;
    mysqli_close($conn);
}


function getPropertyImages($property_id, $conn)
{
    $images = [];
    $query = "SELECT image_url FROM property_images WHERE property_id = $property_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $images[] = $row['image_url'];
        }
    }

    return $images;
}
