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
$query_more = "SELECT * FROM properties WHERE available = true AND id != $property_id ORDER BY RAND() LIMIT 20";
$result_more = mysqli_query($conn, $query_more);

if ($result_more) {
    while ($row = mysqli_fetch_assoc($result_more)) {
        $more_properties[] = $row;
    }
}

?>

<div class="property-detail">
    <div class="container">
        <div class="property-images" style="margin-top: 30px;">
            <?php foreach ($images as $image) : ?>
                <img src="<?php echo $image; ?>" alt="Property Image">
            <?php endforeach; ?>
        </div>
        <div class="flex space-between">
            <div>
                <h1><?php echo htmlspecialchars($property['title']); ?></h1>
                <p style="margin-top: 10px;"><?php echo htmlspecialchars($property['description']); ?></p>
            </div>

        </div>

        <div class="purchase-info">
            <div>
                <div class="flex space-between">
                    <div>
                        <p class="price-lg">
                            $<?php echo number_format($property['price']); ?>
                        </p>
                        <div class="tag" style="max-width: 50px;">
                            <?php echo ucfirst($property['sales_type']); ?>
                        </div>
                    </div>
                </div>
                <div class="flex space-between">
                    <?php
                    $agent_id = $property['agent_id'];
                    $agent_query = "SELECT * FROM users WHERE id = $agent_id";
                    $agent_result = mysqli_query($conn, $agent_query);
                    $agent = mysqli_fetch_assoc($agent_result);
                    ?>
                    <div>
                        <p>Agent: <strong><?php echo htmlspecialchars($agent['full_name']); ?></strong></p>
                        <p>Email: <strong><?php echo htmlspecialchars($agent['email']); ?></strong></p>
                    </div>
                    <div>
                        <form action="buy.php" method="GET">
                            <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
                            <button type="submit" class="btn btn-solid">Buy Property</button>
                        </form>
                    </div>
                </div>
                <div style="margin-top:30px;">
                    <a href="mailto:<?php echo $agent['email']; ?>" class="btn btn-outline">Contact Agent</a>
                </div>
            </div>

        </div>
        <div class="property-info">
            <div class="info-card">
                <h3 class="info-title">Address</h3>
                <p class="info-text"><?php echo htmlspecialchars($property['location']); ?></p>
            </div>

            <div class="info-card">
                <h3 class="info-title">Price</h3>
                <p class="info-text">$<?php echo number_format($property['price']); ?></p>
            </div>

            <?php if ($property['bedrooms'] > 0) : ?>
                <div class="info-card">
                    <h3 class="info-title">Bedrooms</h3>
                    <p class="info-text"><?php echo $property['bedrooms']; ?></p>
                </div>
            <?php endif; ?>

            <?php if ($property['area_code']) : ?>
                <div class="info-card">
                    <h3 class="info-title">Area Code</h3>
                    <p class="info-text"><?php echo $property['area_code']; ?></p>
                </div>
            <?php endif; ?>

            <?php if ($property['property_type']) : ?>
                <div class="info-card">
                    <h3 class="info-title">Property Type</h3>
                    <p class="info-text"><?php echo ucfirst($property['property_type']); ?></p>
                </div>
            <?php endif; ?>

            <div class="info-card">
                <h3 class="info-title">Furnished</h3>
                <p class="info-text">
                    <?php echo $property['furnished'] ? 'Yes' : 'No'; ?>
                </p>
            </div>

            <div class="info-card">
                <h3 class="info-title">Shared</h3>
                <p class="info-text">
                    <?php echo $property['shared'] ? 'Yes' : 'No'; ?>
                </p>
            </div>

        </div>
    </div>
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
                        <div class="info">
                            <div class="flex space-between">
                                <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                                <div>
                                    <div class="tag">
                                        <?php echo ucfirst($property['sales_type']); ?>
                                    </div>
                                </div>
                            </div>
                            <p class="price">$<?php echo number_format($property['price']); ?></p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>