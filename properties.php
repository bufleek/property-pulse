<?php
include 'includes/header.php';

$properties = [];
$filters_exist = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query = "SELECT * FROM properties WHERE 1=1";

    if (!empty($_GET['property_type'])) {
        $property_type = mysqli_real_escape_string($conn, $_GET['property_type']);
        $query .= " AND property_type = '$property_type'";
        $filters_exist = true;
    }

    if (!empty($_GET['bedrooms'])) {
        $bedrooms = (int)$_GET['bedrooms'];
        if ($bedrooms == 6) {
            $query .= " AND bedrooms >= $bedrooms";
        } else {
            $query .= " AND bedrooms = $bedrooms";
        }
        $filters_exist = true;
    }

    if (!empty($_GET['min_price'])) {
        $min_price = (float)$_GET['min_price'];
        $query .= " AND price >= $min_price";
        $filters_exist = true;
    }

    if (!empty($_GET['max_price'])) {
        $max_price = (float)$_GET['max_price'];
        $query .= " AND price <= $max_price";
        $filters_exist = true;
    }

    if (!empty($_GET['furnished'])) {
        $value = $_GET['furnished'] === 'true' ? 1 : 0;
        $query .= " AND furnished = $value";
        $filters_exist = true;
    }

    if (!empty($_GET['serviced'])) {
        $value = $_GET['serviced'] === 'true' ? 1 : 0;
        $query .= " AND serviced = $value";
        $filters_exist = true;
    }

    if (!empty($_GET['shared'])) {
        $value = $_GET['shared'] === 'true' ? 1 : 0;
        $query .= " AND shared = $value";
        $filters_exist = true;
    }

    if (!empty($_GET['location'])) {
        $location = mysqli_real_escape_string($conn, $_GET['location']);
        $query .= " AND LOWER(location) LIKE LOWER('%$location%')";
        $filters_exist = true;
    }

    if (!empty($_GET['added_to_site'])) {
        $added_to_site = mysqli_real_escape_string($conn, $_GET['added_to_site']);
        switch ($added_to_site) {
            case 'last-24-hours':
                $query .= " AND created_at >= NOW() - INTERVAL 1 DAY";
                break;

            case 'last-3-days':
                $query .= " AND created_at >= NOW() - INTERVAL 3 DAY";
                break;

            case 'last-7-days':
                $query .= " AND created_at >= NOW() - INTERVAL 7 DAY";
                break;

            case 'last-14-days':
                $query .= " AND created_at >= NOW() - INTERVAL 14 DAY";
                break;

            case 'last-30-days':
                $query .= " AND created_at >= NOW() - INTERVAL 30 DAY";
                break;

            default:
                break;
        }
        $filters_exist = true;
    }


    $query .= " ORDER BY created_at DESC";

    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $properties[] = $row;
        }
    }
}
?>

<section class="filter-section" style="margin-top: 20px; margin-bottom: 20px;" id="filter-section">
    <div class="container">
        <form class="search-form" method="GET" action="properties.php" id="filter-form">
            <div id="filter-container" class="filters hidden-filters">
                <select name="property_type">
                    <option value="">All Types</option>
                    <option value="apartment">Apartment</option>
                    <option value="house">House</option>
                    <option value="commercial_property">Commercial Property</option>
                </select>
                <select name="bedrooms">
                    <option value="">Bedrooms</option>
                    <option value="any">Any</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6+</option>
                </select>
                <select name="min_price">
                    <option value="">Min Price</option>
                    <option value="any">No Min</option>
                    <option value="1000">1,000 USD</option>
                    <option value="5000">5,000 USD</option>
                    <option value="10000">10,000 USD</option>
                </select>
                <select name="max_price">
                    <option value="">Max Price</option>
                    <option value="any">No Max</option>
                    <option value="10000">10,000 USD</option>
                    <option value="50000">50,000 USD</option>
                    <option value="100000">100,000 USD</option>
                </select>
            </div>
            <div class="filters">
                <?php if ($filters_exist) : ?>
                    <button type="button" class="btn btn-outline" id="clear-filters">Clear Filters</button>
                <?php endif; ?>
                <button type="submit" class="btn-solid">Filter</button>
            </div>
        </form>
    </div>
</section>

<section class="property-list" style="min-height: 60vh; padding-bottom: 40px;">
    <div class="container">
        <h2 style="margin-bottom: 20px;">Showing <?php echo count($properties); ?> Results</h2>
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
                            <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                            <p>$<?php echo number_format($property['price']); ?> - <?php echo $property['bedrooms']; ?> Beds</p>
                        </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
        </div>
    </div>
</section>

<script>
    document.getElementById('clear-filters').addEventListener('click', function() {
        document.getElementById('filter-form').reset();
        document.getElementById('filter-form').submit();
    });
</script>


<?php include 'includes/footer.php'; ?>