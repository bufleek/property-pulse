<?php include 'includes/header.php';

// Ensure that the user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php?next_url=listings.php');
  exit;
}

// get all properties listed by the agent
$agent_id = $_SESSION['user_id'];
$query = "SELECT * FROM properties WHERE agent_id = $agent_id";
$result = mysqli_query($conn, $query);

$properties = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $properties[] = $row;
  }
}
?>


<section class="property-list" style="min-height: 60vh; padding-bottom: 40px;">
    <div class="container">
        <h2 style="margin-bottom: 20px; margin-top:30px;">Your Listings</h2>
        <div class="property-grid">
            <?php if (empty($properties)) : ?>
                <p style="padding: 20px;">No results</p>
            <?php else : ?>
                <?php foreach ($properties as $property) : ?>
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
            <?php endif; ?>
        </div>
        
    </div>
</section>

<?php include 'includes/footer.php'; ?>