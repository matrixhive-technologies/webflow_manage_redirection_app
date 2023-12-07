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

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

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
            <option value="" disabled selected>Select Site</option>
        </select>
    </div>



    <!-- Collection Editor -->
    <div class="container mt-3">
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-success add-collection-item" data-toggle="modal" data-target="#addItemModal" style="display: none;">Create Collection Item</button>
        </div>
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

    <!-- Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Create Collection Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="validation-message" style="color: yellow; margin-bottom: 10px;"></div>
                    <!-- Form for adding a new collection item -->
                    <form id="addItemForm">
                        <div class="form-group">
                            <label for="itemName">Name:</label>
                            <input type="text" class="form-control" id="itemName" name="name" placeholder="Enter Item Name..." required>
                        </div>
                        <div class="form-group">
                            <label for="itemFrom">From:</label>
                            <input type="text" class="form-control" id="itemFrom" name="from" placeholder="Enter OLD URL here...." required>
                        </div>
                        <div class="form-group">
                            <label for="itemTo">To:</label>
                            <input type="text" class="form-control" id="itemTo" name="to" placeholder="Enter NEW URL here...." required>
                        </div>
                        <button type="button" class="btn btn-primary" id="submitItem">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>