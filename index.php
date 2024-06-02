<?php
include 'includes/header.php';

$properties = [];

$query = "SELECT * FROM properties WHERE available = true ORDER BY RAND() LIMIT 12";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $properties[] = $row;
    }
}
?>

<section class="hero">
    <div class="container">
        <h2>Find Your New Property</h2>
        <form class="search-form" action="properties.php" method="GET">
            <div class="search-options">
                <label>
                    <input type="radio" name="option" value="buy" checked /> Buy
                </label>
                <label>
                    <input type="radio" name="option" value="rent" /> Rent
                </label>
                <label>
                    <input type="radio" name="option" value="short-let" /> Short Let
                </label>
            </div>

            <input type="text" placeholder="Enter area code" name="area_code" />

            <div class="filters">
                <select name="property_type">
                    <option value="">All Types</option>
                    <option value="apartment">Apartment</option>
                    <option value="house">House</option>
                    <option value="commercial-property">Commercial Property</option>
                </select>
                <select name="bedrooms">
                    <option value="">Bedrooms</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6+</option>
                </select>
                <select name="min_price">
                    <option value="">Min Price</option>
                    <option value="1000">1,000 USD</option>
                    <option value="5000">5,000 USD</option>
                    <option value="10000">10,000 USD</option>
                </select>
                <select name="max_price">
                    <option value="">Max Price</option>
                    <option value="10000">10,000 USD</option>
                    <option value="50000">50,000 USD</option>
                    <option value="100000">100,000 USD</option>
                </select>
            </div>
            <div class="filters">
                <select name="furnished">
                    <option value="">Furnishing</option>
                    <option value="true">Furnished</option>
                    <option value="false">Unfurnished</option>
                </select>
                <select name="serviced">
                    <option value="">Serviced</option>
                    <option value="true">Serviced</option>
                </select>
                <select name="shared">
                    <option value="">Shared</option>
                    <option value="true">Shared</option>
                </select>
                <select name="added_to_site">
                    <option value="">Added to site</option>
                    <option value="last-24-hours">Last 24 hours</option>
                    <option value="last-3-days">Last 3 days</option>
                    <option value="last-7-days">Last 7 days</option>
                    <option value="last-14-days">Last 14 days</option>
                    <option value="last-30-days">Last 30 days</option>
                </select>
                <input type="text" placeholder="Keywords (e.g., 'pool' or 'jacuzzi')" name="keywords" />
                <input type="text" placeholder="Property Ref (e.g., 83256)" name="property_ref" />
            </div>

            <button type="submit">Search</button>
        </form>
    </div>
</section>

<section class="key-stats">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Properties Listed</h3>
                <p>1,200+</p>
            </div>
            <div class="stat-card">
                <h3>Properties Sold</h3>
                <p>900+</p>
            </div>
            <div class="stat-card">
                <h3>Happy Clients</h3>
                <p>800+</p>
            </div>
            <div class="stat-card">
                <h3>Agents</h3>
                <p>150</p>
            </div>
        </div>
    </div>
</section>

<section class="popular-properties">
    <div class="container">
        <h2>Popular Properties</h2>
        <div class="property-grid">
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
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>