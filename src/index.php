<?php
$servername = "192.168.69.51";
$username = "root";
$password = "admin";
$dbname = "data_set";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT * FROM data";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        foreach ($row as $key => $value) {
            echo $key.": ".$value;
            echo "<br>";
        }
        echo "<br>";
        echo "---------------------------------";
        echo "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>