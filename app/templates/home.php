<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Backend Engineer Recruitment Test">
    <meta name="author" content="Matic Jan">

    <title>Naslov strani</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">

    <?php if ($_ENV['APP_ENV'] === 'development'): ?>
        <script type="module" src="http://localhost:5173/@vite/client"></script>
    <?php elseif (file_exists($manifestPath = 'dist/.vite/manifest.json')): ?>
        <?php $manifest = json_decode(file_get_contents($manifestPath), true); ?>
        <link rel="stylesheet" href="<?php echo 'dist/' . $manifest['app/resources/js/index.js']['css'][0]; ?>">
    <?php endif; ?>
</head>
<body>
    <div class="container">
        <div id="app"></div>
    </div>
    <?php if ($_ENV['APP_ENV'] === 'development'): ?>
        <script type="module" src="http://localhost:5173/app/resources/js/index.js"></script>
    <?php elseif (isset($manifest)): ?>
        <script src="<?php echo 'dist/' . $manifest['app/resources/js/index.js']['file']; ?>"></script>
    <?php endif; ?>
</body>
</html>