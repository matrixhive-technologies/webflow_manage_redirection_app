<?php
require_once('config/app.php');
?>
<!DOCTYPE html>

<html>

<head>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Include DataTables CSS and JavaScript files -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- DataTables JavaScript from CDN -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- Custom CSS File -->
    <link rel="stylesheet" href="assets/css/main.css">

    <!-- Custom JS File -->
    <script type="text/javascript" charset="utf8" src="assets/js/main.js" data-appurl="<?php echo APP_URL; ?>"></script>

</head>

<body>
    <!-- Site Dropdown -->
    <div class="container mt-3">
        <label for="selectSite">Select Site:</label>
        <select id="selectSite" class="form-control">
            <option value="">Select Site</option>
        </select>
    </div>

    <!-- Collection Editor -->
    <div class="container mt-3">
        <table id="collectionEditor" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</body>

</html>