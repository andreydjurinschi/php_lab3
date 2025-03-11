<?php 
    $dir = 'images/';
    $images = scandir($dir);
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Image Gallery</title>
</head>
<body>
    <header style="background-color: #333; color: white; padding: 10px 0; text-align: center;">
        <h1>Image Gallery</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Transactions</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
        </ul>
    </nav>
    <main>
        <div class="gallery">
            <?php foreach($images as $image): ?>
                <?php if($image !== '.' && $image !== '..'): ?>
                    <img src="<?php echo $dir . $image; ?>" alt="Image">
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2023 Image Gallery. All rights reserved.</p>
    </footer>
</body>
</html>