<?php
include 'includes/header.php';


$buyerId = $_SESSION['user_id'];
// ensure that the user is logged in
if (!$buyerId || !is_numeric($buyerId)) {
    header('Location: login.php?next_url=purchases.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $propertyId = $_POST['property_id'];

    // Retrieve the amount from the properties table
    $query = "SELECT price FROM properties WHERE id = $propertyId";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $price = $row['price'];

    $sql = "INSERT INTO purchases (property_id, buyer_id, amount) VALUES ($propertyId, $buyerId, $price)";

    mysqli_query($conn, $sql);

    // update the availability of the property
    $updateQuery = "UPDATE properties SET available = 0 WHERE id = $propertyId";
    mysqli_query($conn, $updateQuery);

    header('Location: purchases.php');
    exit;    
}

$query = "SELECT p.title, pu.amount, pu.created_at
          FROM properties p
          INNER JOIN purchases pu ON p.id = pu.property_id
          WHERE pu.buyer_id = $buyerId";
    $result = mysqli_query($conn, $query);
?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>


<div class="container" style="margin-top: 30px;">
    <h1>Your Purchases</h1>
    <table style="margin-top:30px;">
        <thead>
            <tr>
                <th>Property Name</th>
                <th>Amount</th>
                <th>Purchase Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['amount'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No purchases found.</td></tr>";
            }
            ?>
        </tbody>
    </table>