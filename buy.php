<?php
include 'includes/header.php';

$property_id = (int)$_GET['property_id'];

// if property_id is not set, redirect to properties page
if (!$property_id) {
    header('Location: properties.php');
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header("Location: login.php?next_url=buy.php?property_id=$property_id");
    exit;
}

// Get property details from the database
$query = "SELECT title, description, price FROM properties WHERE id = $property_id";
$result = mysqli_query($conn, $query);

// Check if property exists
if (mysqli_num_rows($result) > 0) {
    $property = mysqli_fetch_assoc($result);
    $title = $property['title'];
    $description = $property['description'];
    $price = $property['price'];
} else {
    // Redirect to properties page
    header('Location: properties.php');
    exit;
}
?>

<style>
    .property-detail {
        max-width: 500px;
        min-width: 400px;
        margin: 0 auto;
        background-color: #9b289341;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .purchase-confirmation {
        display: flex;
        justify-content: center;
        align-items: center;
        height: calc(100vh - 200px);
    }

    .property-detail {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .property-detail h2 {
        padding: 30px 0;
    }

    .property-detail p {
        margin-bottom: 20px;
    }
</style>

<div class="container purchase-confirmation">
    <div class="property-detail">
        <h2><?php echo $title; ?></h2>
        <p><?php echo $description; ?></p>
        <p>Price: $<?php echo $price; ?></p>
        <form action="purchases.php" method="POST">
            <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">
            <button type="submit" class="btn btn-solid">Confirm Purchase</button>
        </form>
    </div>
</div>
