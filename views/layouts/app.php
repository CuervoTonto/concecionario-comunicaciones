<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->useSection('title') ?? 'AutoElite' ?></title>
    <link rel="stylesheet" href="/css/normalize.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .navbar-brand span { color: #e94560; }
        .btn-primary { background-color: #e94560; border-color: #e94560; }
        .btn-primary:hover { background-color: #c73652; border-color: #c73652; }
        .btn-outline-primary { color: #e94560; border-color: #e94560; }
        .btn-outline-primary:hover { background-color: #e94560; border-color: #e94560; color: #fff; }
        .section-title::after { content: ''; display: block; width: 60px; height: 3px; background: #e94560; margin: 10px auto 0; }
        <?= $this->useSection('styles') ?>
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="/">Auto<span>Elite</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto gap-2">
                <li class="nav-item"><a class="nav-link" href="/">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="/#catalogo">Catálogo</a></li>
                <li class="nav-item"><a class="nav-link" href="/#servicios">Servicios</a></li>
                <li class="nav-item"><a class="nav-link" href="/#contacto">Contacto</a></li>
            </ul>
            <a href="/#contacto" class="btn btn-primary ms-3">Cotizar ahora</a>
        </div>
    </div>
</nav>

<?= $this->useSection('content') ?>

<footer class="bg-dark text-white-50 py-4 text-center">
    <div class="container">
        <p class="mb-1 fw-bold text-white">Auto<span class="text-danger">Elite</span></p>
        <small>&copy; <?= date('Y') ?> AutoElite. Todos los derechos reservados.</small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->useStack('scripts') ?>
</body>
</html>
