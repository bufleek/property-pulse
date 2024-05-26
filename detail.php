<?php
include 'includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: properties.php');
    exit();
}

$property_id = (int)$_GET['id'];

$query = "SELECT * FROM properties WHERE id = $property_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header('Location: properties.php');
    exit();
}

$property = mysqli_fetch_assoc($result);

$images = getPropertyImages($property_id, $conn);

$more_properties = [];
$query_more = "SELECT * FROM properties WHERE id != $property_id ORDER BY RAND() LIMIT 20";
$result_more = mysqli_query($conn, $query_more);

if ($result_more) {
    while ($row = mysqli_fetch_assoc($result_more)) {
        $more_properties[] = $row;
    }
}

?>

<style>
    .property-detail .container {
        max-width: 800px;
        margin: 0 auto;
    }

    .property-detail h1 {
        color: #9b2893;
        padding: 20px 0;
    }

    .property-images {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .property-images img {
        width: 100%;
        max-width: 200px;
        height: auto;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    .property-info {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .property-info p {
        margin: 10px 0;
    }

    .property-info strong {
        color: #9b2893;
    }
</style>
<div class="property-detail">
    <div class="container">
        <h1><?php echo htmlspecialchars($property['title']); ?></h1>
        <div class="property-images">
            <?php foreach ($images as $image) : ?>
                <img src="<?php echo $image; ?>" alt="Property Image">
            <?php endforeach; ?>
        </div>
        <div class="property-info">
            <p><strong>Location:</strong> <?php echo htmlspecialchars($property['location']); ?></p>
            <p><strong>Price:</strong> $<?php echo number_format($property['price']); ?></p>
            <p><strong>Bedrooms:</strong> <?php echo $property['bedrooms']; ?></p>
            <p><strong>Furnished:</strong> <?php echo $property['furnished'] ? 'Yes' : 'No'; ?></p>
            <p><strong>Serviced:</strong> <?php echo $property['serviced'] ? 'Yes' : 'No'; ?></p>
            <p><strong>Shared:</strong> <?php echo $property['shared'] ? 'Yes' : 'No'; ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
        </div>
    </div>
</div>

<div class="container" style="margin-top: 20px; margin-bottom: 20px;">
    <hr>
</div>

<section class="explore-more" style="margin-bottom:40px">
    <div class="container">
        <h2 style="margin-top: 40px; margin-bottom: 20px;">Explore More</h2>
        <div class="property-grid">
            <?php foreach ($more_properties as $property) : ?>
                <a href="detail.php?id=<?php echo $property['id']; ?>">
                    <div class="property-card">
                        <?php
                        $images = getPropertyImages($property['id'], $conn);
                        $image_url = !empty($images) ? $images[0] : 'assets/images/default-property.jpg';
                        ?>

                        <img src="<?php echo $image_url; ?>" alt="Property Image">
                        <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                        <p>$<?php echo number_format($property['price']); ?> - <?php echo $property['bedrooms']; ?> Beds</p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>