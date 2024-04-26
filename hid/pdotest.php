 <?php
$servername = "localhost";
$username = "moodydb_admin";
$password = "W7BeDTjpgvdqELQfaAb3hNFX";

try {
  $conn = new PDO("mysql:host=$servername;dbname=moodydb", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>
