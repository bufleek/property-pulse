<?php include 'includes/header.php';

// Ensure that the user is logged in
if (!isset($_SESSION['user_id'])) {
  header('Location: login.php?next_url=upload.php');
  exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $location = mysqli_real_escape_string($conn, $_POST['location']);
  $property_type = mysqli_real_escape_string($conn, $_POST['property_type']);
  $bedrooms = isset($_POST['bedrooms']) ? (int)$_POST['bedrooms'] : 1;
  $price = (float)$_POST['price'];
  $furnished = isset($_POST['furnished']) ? 1 : 0;
  $serviced = isset($_POST['serviced']) ? 1 : 0;
  $shared = isset($_POST['shared']) ? 1 : 0;
  $keywords = mysqli_real_escape_string($conn, $_POST['keywords']);
  $property_ref = mysqli_real_escape_string($conn, $_POST['property_ref']);
  $latitude = (float)$_POST['latitude'];
  $longitude = (float)$_POST['longitude'];
  $available = 1;
  $area_code = $_POST['area_code'];
  $sale_type = $_POST['sale_type'];
  $agent_id = $_SESSION['user_id'];

  $sql = "INSERT INTO properties (title, description, location, property_type, bedrooms, price, furnished, serviced, shared, keywords, property_ref, agent_id, latitude, longitude, available, area_code, sales_type)
          VALUES ('$title', '$description', '$location', '$property_type', $bedrooms, $price, $furnished, $serviced, $shared, '$keywords', '$property_ref', $agent_id, $latitude, $longitude, $available, '$area_code', '$sale_type')";

  if (mysqli_query($conn, $sql)) {
    $property_id = mysqli_insert_id($conn);
    $error = "Property listed successfully!";
    $images = $_FILES['images'];

    foreach ($images['name'] as $key => $image_name) {
      $image_tmp_name = $images['tmp_name'][$key];
      $image_size = $images['size'][$key];
      $image_error = $images['error'][$key];

      if ($image_error === UPLOAD_ERR_OK) {
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_target = "uploads/$property_id-$key.$image_ext";

        if (move_uploaded_file($image_tmp_name, $image_target)) {
          $sql = "INSERT INTO property_images (property_id, image_url) VALUES ($property_id, '$image_target')";
          mysqli_query($conn, $sql);
        }
      }
    }

    $success = true;
    $error =  null;
  } else {
    $error = "Error: " . mysqli_error($conn);
    $success = null;
  }
}
?>


<div class="container upload">
  <h1>List a property</h1>
  <?php if (isset($error)) : ?>
    <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 10px; margin-top: 10px;"><?php echo $error; ?></div>
  <?php endif; ?>

  <?php if (isset($success) && $success) : ?>
    <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px; margin-top: 10px;">
      Property listed successfully!
    </div>
  <?php endif; ?>
  <form action="upload.php" method="POST" enctype="multipart/form-data">
    <!-- Basic Information Section -->
    <fieldset>
      <legend>Basic Information</legend>
      <label for="title">Title <span style="color:red;">*</span></label>
      <input type="text" id="title" name="title" required />

      <label for="description">Description <span style="color:red;">*</span></label>
      <textarea id="description" name="description" required></textarea>

      <label for="location">Address <span style="color:red;">*</span></label>
      <input type="text" id="location" name="location" required />

      <label for="area_code">Area Code <span style="color:red;">*</span></label>
      <input type="text" id="area_code" name="area_code" required />

      <label for="property_type">Property Type <span style="color:red;">*</span></label>
      <select id="property_type" name="property_type" required>
        <option value="apartment">Apartment</option>
        <option value="house">House</option>
        <option value="commercial_property">Commercial Property</option>
      </select>
    </fieldset>

    <!-- Pricing and Details Section -->
    <fieldset>
      <legend>Pricing and Details</legend>
      <label for="bedrooms">Bedrooms</label>
      <input type="number" id="bedrooms" name="bedrooms" />

      <label for="price">Price (USD) <span style="color:red;">*</span></label>
      <input type="number" id="price" name="price" required />

      <div class="checkbox-container">
        <label for="furnished" class="inline">Furnished</label>
        <input type="checkbox" id="furnished" name="furnished" />
      </div>

      <div class="checkbox-container">
        <label for="serviced" class="inline">Serviced</label>
        <input type="checkbox" id="serviced" name="serviced" />
      </div>

      <div class="checkbox-container">
        <label for="shared" class="inline">Shared</label>
        <input type="checkbox" id="shared" name="shared" />
      </div>

      <label for="sale_type">Sale Type</label>
      <select id="sale_type" name="sale_type">
        <option value="sale" selected>For Sale</option>
        <option value="rent">For Rent</option>
        <option value="short_lease">Short Let</option>
      </select>

    </fieldset>

    <!-- Additional Information Section -->
    <fieldset>
      <legend>Additional Information (Optional)</legend>
      <label for="keywords">Keywords</label>
      <input type="text" id="keywords" name="keywords" placeholder="e.g., 'pool', 'jacuzzi'" />

      <label for="property_ref">Property Reference</label>
      <input type="text" id="property_ref" name="property_ref" />

      <label for="latitude">Latitude</label>
      <input type="text" id="latitude" name="latitude" />

      <label for="longitude">Longitude</label>
      <input type="text" id="longitude" name="longitude" />
    </fieldset>

    <!-- Images Upload Section -->
    <fieldset>
      <legend>Images</legend>
      <label for="images">Upload Images <span style="color:red;">*</span></label>
      <input type="file" id="images" name="images[]" multiple accept="image/*" required />
    </fieldset>

    <button type="submit">Submit Listing</button>
  </form>
</div>

<?php include 'includes/footer.php'; ?>