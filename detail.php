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

<style>
    .property-detail .container {
        max-width: 800px;
        margin: 0 auto;
    }

    .property-detail h1 {
        margin-top: 20px;
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
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .info-card {
        padding: 20px;
        border: 1px solid #f0f0f0;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .info-title {
        color: #9b2893;
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    .info-text {
        font-size: 1.1rem;
    }

    .purchase-info {
        margin-top: 20px;
        margin-bottom: 20px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    .purchase-info p {
        margin-top: 10px;
    }

    .price-lg {
        font-size: 2.5rem;
        font-weight: 700;
        margin-top: 10px;
        color: #9b2893;
    }

    .mt-10 {
        margin-top: 10px;
    }
</style>
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