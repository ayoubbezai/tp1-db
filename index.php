<?php
// Database connection details
$host = "localhost";
$username = "root"; 
$password = ""; 
$database = "homeWork1";

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!<br>";

// SQL query to create a table
$sql = "CREATE TABLE IF NOT EXISTS products (
    id INT(6) UNSIGNED  PRIMARY KEY,
    product_name VARCHAR(50) NOT NULL,
    product_type VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT(6) NOT NULL
)";

// Execute the query
if ($conn->query($sql) ) {
    echo "Table 'products' created successfully!";
} else {
    echo "Error creating table: " . $conn->error;
}

$file_path = "products.txt";
$data = [];

if($file = fopen($file_path,"r")){
    while(!feof($file)){
        $line = fgets($file);
        $line = trim($line);
    if(empty($line)){
        continue;
    };

    $row = explode(",",$line);

    $tuple =[
        (int)$row[0], //product id
        $row[1],//product name
        $row[2],//product type
        (float)$row[3], //price
        (int)$row[4], //quantity
    ];
            $data[] = $tuple;

        }
            fclose($file);
} else {
    die("Unable to open the file.");
}

foreach ($data as $tuple){
    $sql = "INSERT INTO products (id,product_name,product_type,price,quantity)
     VALUES (?,?,?,?,?)";
         $stmt = $conn->prepare($sql);
           if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
        
        
    }
$stmt->bind_param("issdi", $tuple[0], $tuple[1], $tuple[2], $tuple[3], $tuple[4]);
      if ($stmt->execute()) {
        echo "Record inserted successfully: ID = {$tuple[0]}, Name = {$tuple[1]}<br>";
    } else {
        echo "Error inserting record: " . $stmt->error . "<br>";
    }

    $stmt->close();

}

// q 1
$sql = "SELECT product_name FROM products WHERE product_type=' Furniture' ";

$result = $conn->query($sql);

if (!$result)  {
    die("Error retrieving data: " . $conn->error);
}
if ($result->num_rows > 0) {
     echo "</br>"."</br>"."query 1  product name: "."</br>";
    while ($row = $result->fetch_assoc()) {
             echo "<td>{$row['product_name']}</td>"."</br>";

    } 

}else {
    echo "No data found .";
}

// q 2

$sql2 = "SELECT AVG(price)AS average_price FROM products WHERE product_type=' Electronics' ";
$result2 = $conn->query($sql2);

if ($result2 === FALSE) {
    die("Error retrieving data: " . $conn->error);
}
if ($row = $result2->fetch_assoc()) {
    $average_price = $row['average_price'];
    echo "</br>Query 2 - Average Price of Electronics: " . number_format($average_price, 2) . "</br>";
} else {
    echo "No data found for Electronics products.</br>";
}


$conn->close();
?>