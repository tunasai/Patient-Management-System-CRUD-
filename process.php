<?php

session_start();

$patient_id =0;
$update = false;
$name ='';
$address ='';

$mysqli = new mysqli('localhost', 'root', 'CY041205in', 'db_pms');

// Check if the connection is successful
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle form submission
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];

    $mysqli->query("INSERT INTO tbl_patient (name, address) VALUES('$name', '$address')") or die ($mysqli->error);

    $_SESSION['message']= "Record successfully saved.";
    $_SESSION['msg_type']= "success";

    header("location: index.php");
    

    // // Use prepared statements to avoid SQL injection
    // $stmt = $mysqli->prepare("INSERT INTO tbl_patient (name, address) VALUES (?, ?)");
    // $stmt->bind_param("ss", $name, $address); // "ss" means two strings
    // $stmt->execute();

    // if ($stmt->affected_rows > 0) {
    //     echo "Patient added successfully!";
    // } else {
    //     echo "Failed to add patient.";
    // }

    // // Close the statement and the connection
    // $stmt->close();
}

if (isset($_GET['delete'])){
    $patient_id = $_GET['delete'];
    $mysqli->query("DELETE FROM tbl_patient WHERE patient_id=$patient_id") or die ($mysqli->error);

    $_SESSION['message']= "Record has been deleted.";
    $_SESSION['msg_type']= "danger";

    header("location: index.php");

}

if (isset($_GET['edit'])){
    $patient_id = $_GET['edit'];
    $update = true;
    $result = $mysqli->query("SELECT * FROM tbl_patient WHERE patient_id=$patient_id") or die ($mysqli->error);
    if ($result->num_rows == 1){
        $row = $result->fetch_array();
        $name = $row['name'];
        $address = $row['address'];
    }
}

if (isset($_POST['update'])){
    $patient_id = $_POST['patient_id'];
    $name = $_POST['name'];
    $address = $_POST['address'];

    $mysqli->query("UPDATE tbl_patient SET name='$name', address='$address' WHERE patient_id=$patient_id") or die ($mysqli->error);

    $_SESSION['message']= "Record has been updated.";
    $_SESSION['msg_type']= "info";

    header("location: index.php");

}

// Close the database connection
//$mysqli->close();
?>
