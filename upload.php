<?php include 'includes/header.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $description = mysqli_real_escape_string($conn, $_POST['description']);
  $location = mysqli_real_escape_string($conn, $_POST['location']);
  $property_type = mysqli_real_escape_string($conn, $_POST['property_type']);
  $bedrooms = (int)$_POST['bedrooms'];
  $price = (float)$_POST['price'];
  $furnished = isset($_POST['furnished']) ? 1 : 0;
  $serviced = isset($_POST['serviced']) ? 1 : 0;
  $shared = isset($_POST['shared']) ? 1 : 0;
  $keywords = mysqli_real_escape_string($conn, $_POST['keywords']);
  $property_ref = mysqli_real_escape_string($conn, $_POST['property_ref']);
  $latitude = (float)$_POST['latitude'];
  $longitude = (float)$_POST['longitude'];
  $available = isset($_POST['available']) ? 1 : 0;
  $agent_id = $_SESSION['user_id'];

  $sql = "INSERT INTO properties (title, description, location, property_type, bedrooms, price, furnished, serviced, shared, keywords, property_ref, agent_id, latitude, longitude, available)
          VALUES ('$title', '$description', '$location', '$property_type', $bedrooms, $price, $furnished, $serviced, $shared, '$keywords', '$property_ref', $agent_id, $latitude, $longitude, $available)";

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
      <label for="title">Title:</label>
      <input type="text" id="title" name="title" required />

      <label for="description">Description:</label>
      <textarea id="description" name="description" required></textarea>

      <label for="location">Location:</label>
      <input type="text" id="location" name="location" required />

      <label for="property_type">Property Type:</label>
      <select id="property_type" name="property_type" required>
        <option value="apartment">Apartment</option>
        <option value="house">House</option>
        <option value="commercial_property">Commercial Property</option>
      </select>
    </fieldset>

    <!-- Pricing and Details Section -->
    <fieldset>
      <legend>Pricing and Details</legend>
      <label for="bedrooms">Bedrooms:</label>
      <input type="number" id="bedrooms" name="bedrooms" required />

      <label for="price">Price (USD):</label>
      <input type="number" id="price" name="price" required />

      <div class="checkbox-container">
        <label for="furnished" class="inline">Furnished:</label>
        <input type="checkbox" id="furnished" name="furnished" />
      </div>

      <div class="checkbox-container">
        <label for="serviced" class="inline">Serviced:</label>
        <input type="checkbox" id="serviced" name="serviced" />
      </div>

      <div class="checkbox-container">
        <label for="shared" class="inline">Shared:</label>
        <input type="checkbox" id="shared" name="shared" />
      </div>

    </fieldset>

    <!-- Additional Information Section -->
    <fieldset>
      <legend>Additional Information</legend>
      <label for="keywords">Keywords:</label>
      <input type="text" id="keywords" name="keywords" placeholder="e.g., 'pool', 'jacuzzi'" />

      <label for="property_ref">Property Reference:</label>
      <input type="text" id="property_ref" name="property_ref" />

      <label for="latitude">Latitude:</label>
      <input type="text" id="latitude" name="latitude" />

      <label for="longitude">Longitude:</label>
      <input type="text" id="longitude" name="longitude" />
    </fieldset>

    <!-- Images Upload Section -->
    <fieldset>
      <legend>Images</legend>
      <label for="images">Upload Images:</label>
      <input type="file" id="images" name="images[]" multiple accept="image/*" required />
    </fieldset>

    <button type="submit">Submit Listing</button>
  </form>
</div>

<?php include 'includes/footer.php'; ?>