<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Management System</title>

    <!-- Include jQuery and Bootstrap -->
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>

    <!-- Custom styles -->
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
        }
        .title-bar {
            width: 100%;
            background-color: #13111F;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90px; 
            margin-bottom: 20px;
        }
        .title-bar h2 {
            color: white;
            margin: 0;
            font-weight: bold;
            font-size: 2rem;
        }
        table {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        table.table-bordered {
            border: 1px solid #ccc;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #ccc;
        }
        th, td {
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #13111F;
            color: white;
        }
        .btn {
            margin: 2px;
            border-radius: 6px;
        }
        .btn-create {
            background-color: #5483B3;
            color: white;
        }
        .btn-edit {
            background-color: #5483B3;
            color: white;
        }
        .btn-delete {
            background-color: #6D2932;
            color: white;
        }
        .alert-success {
            background-color:rgb(84, 179, 117);
            color: white;
            max-width: 100%;
        }
        .alert-info {
            background-color: rgb(84, 179, 117); 
            color: white;
        }
        .alert-danger {
            background-color: #6D2932;
            color: white;
            max-width: 100%;
        }
        .action-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1.5rem;
        }
        .table-container {
            padding: 1rem;
        }
    </style>
</head>
<body>
<?php require_once 'process.php'; ?>

<!-- Page heading -->
  <div class="title-bar">
    <h2 class="mb-3">PATIENT MANAGEMENT SYSTEM</h2>
  </div>

<div class="container mt-4">
    <!-- Action bar -->
    <div class="action-bar">
        <button class="btn btn-create" data-toggle="modal" data-target="#createModal">Create New Patient</button>
    </div>

    <!-- Display session message if available -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?=$_SESSION['msg_type'] ?> w-100">
            <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']);
            ?>
        </div>
    <?php endif ?>

    <?php
        // Connect to MySQL database
        $mysqli = new mysqli('localhost', 'root', 'CY041205in', 'db_pms') or die(mysqli_error($mysqli));

        // Get all patient records
        $result = $mysqli->query("SELECT * FROM tbl_patient") or die($mysqli->error);
    ?>

    <!-- Display patient records -->
    <div class="row justify-content-center">
        <div class="table-container w-100">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th colspan="2">Action</th>
                    </tr>
                </thead>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['patient_id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['address']; ?></td>
                        <td>
                            <button class="btn btn-edit btn-sm" data-toggle="modal" data-target="#editModal<?php echo $row['patient_id']; ?>">Edit</button>
                            <a href="process.php?delete=<?php echo $row['patient_id']; ?>" onclick="return confirm('Are you sure you want to delete this record?');" class="btn btn-delete btn-sm">Delete</a>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?php echo $row['patient_id']; ?>" tabindex="-1" role="dialog">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <form action="process.php" method="POST">
                            <div class="modal-header">
                              <h5 class="modal-title">Edit Patient</h5>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                              <input type="hidden" name="patient_id" value="<?php echo $row['patient_id']; ?>">
                              <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
                              </div>
                              <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" value="<?php echo $row['address']; ?>" required>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="submit" name="update" class="btn btn-edit">Update</button>
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="process.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Add New Patient</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control"  placeholder="e.g. Maria Juana Reyes" required>
          </div>
          <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" class="form-control"  placeholder="e.g. San Pablo City" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="save" class="btn btn-create">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>