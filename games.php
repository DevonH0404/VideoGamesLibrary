<?php
$filename = __DIR__ . '/games.csv';
$games = [];

if (file_exists($filename)) {
    $file = fopen($filename, 'r');

    if ($file !== false) {
        while (($row = fgetcsv($file)) !== false) {
            $games[] = $row;
        }

        fclose($file);
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

    <style>
        .game-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <?php require './includes/navigation.php'; ?>

    <div class="container mt-4">
        <h1>Games</h1>

        <a href="index.php" class="btn btn-primary mb-3">Add Another Game</a>

        <?php if (empty($games)): ?>
            <div class="alert alert-info">
                No games added. Click "Add Another Game" to add one.
            </div>
        <?php else: ?>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Cover Image</th>
                        <th>Inventory Code</th>
                        <th>Game Name</th>
                        <th>Console</th>
                        <th>Price</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($games as $row): ?>
                        <tr>
                            <td>
                                <img
                                    src="uploads/<?= htmlspecialchars($row[4]) ?>"
                                    alt="<?= htmlspecialchars($row[1]) ?> cover"
                                    class="game-image">
                            </td>
                            <td><?= htmlspecialchars($row[0]) ?></td>
                            <td><?= htmlspecialchars($row[1]) ?></td>
                            <td><?= htmlspecialchars($row[2]) ?></td>
                            <td>$<?= number_format((float) $row[3], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>
</body>

</html>
