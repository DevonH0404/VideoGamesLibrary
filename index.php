<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $inventorycode = $_POST['inventorycode'];
    $game = $_POST['game'];
    $console = $_POST['console'];
    $price = $_POST['price'];
    $uploadedFile = $_FILES['cover'];

    // array for holding errors
    $errors = [];

    // checks and validates data, all are required, so if any are null, send it back
    // various other validations for other fields
    if (empty($inventorycode)) {
        $errors["inventorycode"] = "Inventory code is required.";
    } elseif (!preg_match('/^GAME-[0-9]{4}$/', $inventorycode)) {
        $errors["inventorycode"] = "Inventory code must be formatted! ex. GAME-1234.";
    }

    if (empty($game)) {
        $errors["game"] = "Game title is required.";
    }

    if (empty($console)) {
        $errors["console"] = "Console is required.";
    }

    if (empty($price)) {
        $errors["price"] = "Price is required.";
    } elseif (!is_numeric($price)) {
        $errors["price"] = "Price must be a number.";
    } elseif ($price < 0) {
        $errors["price"] = "Price cannot be negative.";
    }

    // if upload fails, give back error
    if ($uploadedFile['error'] === UPLOAD_ERR_NO_FILE) {
        $errors["cover"] = "Cover image is required.";
    }

    // if there are no errors proceed with processing
    if (empty($errors)) {

        // generate unique file name by using time and name
        $filename = time() . '_' . basename($uploadedFile['name']);
        // move the uploaded file to uploads
        $destination = __DIR__ . '/uploads/' . $filename;

        // move the uploaded file to the destination
        if (move_uploaded_file($uploadedFile['tmp_name'], $destination)) {

        // create a csv file if it doesn't exist, add data
            $csvFilename = __DIR__ . '/games.csv';

            $file = fopen($csvFilename, 'a');

            // if cant find the file to open, throw error and die
            if ($file === false) {
                die("Error opening the file " . $csvFilename);
            }

            // add data
            $row = [$inventorycode, $game, $console, $price, $filename];

            fputcsv($file, $row);

            // close file
            fclose($file);

            // redirect to games.php and kill
            header("Location: games.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Stop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php require './includes/navigation.php'; ?>

    <div class="container mt-4">
        <h1>Game Stop</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/Projects/Video%20Games/index.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="inventorycode" class="form-label">Inventory Code:</label>
                <input type="text" class="form-control" id="inventorycode" name="inventorycode">

                <label for="game" class="form-label">Game Title:</label>
                <input type="text" class="form-control" id="game" name="game">

                <label for="console" class="form-label">Console:</label>
                <select class="form-select" id="console" name="console">
                    <option value="">Select a console</option>
                    <!-- Could have put these in an array and looped -->
                    <option value="Nintendo Switch">Nintendo Switch</option>
                    <option value="PlayStation 5">PlayStation 5</option>
                    <option value="Xbox Series X">Xbox Series X</option>
                    <option value="PlayStation 2">PlayStation 2</option>
                    <option value="Nintendo 64">Nintendo 64</option>
                </select>

                <label for="price" class="form-label">Price:</label>
                <input type="text" class="form-control" id="price" name="price">

                <label for="cover" class="form-label">Cover Image:</label>
                <input type="file" class="form-control" id="cover" name="cover">
            </div>

            <button type="submit" class="btn btn-primary">Add Game</button>
        </form>
    </div>
</body>

</html>