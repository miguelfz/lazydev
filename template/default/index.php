<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <link rel="stylesheet" href="<?= PATH ?>/assets/css/default.css">
    <?php $this->getHtmlHead(); ?>
    <title><?= $this->getTitle(); ?></title>
</head>

<body>
    <div class="container">

        <nav><?php require __DIR__ . '/../menu.php' ?></nav>

        <main><?php $this->getContents(); ?></main>

        <footer>Lazydev</footer>

    </div>
</body>

</html>