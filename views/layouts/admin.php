<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoElite | <?= $this->useSection('title') ?? 'Admin' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 240px; }
        body { background: #f4f6fb; }
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: #1a1a2e;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
        }
        .sidebar .brand span { color: #e94560; }
        .sidebar .nav-link {
            color: rgba(255,255,255,.6);
            border-radius: 8px;
            margin-bottom: 2px;
            transition: all .2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(233,69,96,.15);
            color: #fff;
        }
        .sidebar .nav-link.active { border-left: 3px solid #e94560; }
        .sidebar .nav-link i { width: 20px; }
        .main { margin-left: var(--sidebar-width); }
        .topbar { background: #fff; border-bottom: 1px solid #e9ecef; }
        .stat-card { border-left: 4px solid #e94560; }
    </style>
    <?= $this->useSection('styles') ?>
</head>
<body>

<aside class="sidebar d-flex flex-column p-3">
    <a href="/" class="brand text-decoration-none text-white fw-bold fs-5 mb-4 d-block px-2 pt-2">
        Auto<span>Elite</span>
    </a>
    <small class="text-white-50 text-uppercase px-2 mb-2" style="font-size:.7rem;">Gestión</small>
    <nav class="nav flex-column gap-1">
        <a href="/admin" class="nav-link px-3 py-2 <?= (($_SERVER['REQUEST_URI'] ?? '') === '/admin') ? 'active' : '' ?>">
            <i class="bi bi-grid me-2"></i>Dashboard
        </a>
        <a href="/admin/clientes" class="nav-link px-3 py-2 <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/clientes') ? 'active' : '' ?>">
            <i class="bi bi-people me-2"></i>Clientes
        </a>
        <a href="/admin/vehiculos" class="nav-link px-3 py-2 <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/vehiculos') ? 'active' : '' ?>">
            <i class="bi bi-car-front me-2"></i>Vehículos
        </a>
        <a href="/admin/ventas" class="nav-link px-3 py-2 <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/ventas') ? 'active' : '' ?>">
            <i class="bi bi-receipt me-2"></i>Ventas
        </a>
    </nav>
    <div class="mt-auto px-2 pb-2">
        <a href="/" class="nav-link text-white-50 px-3 py-2">
            <i class="bi bi-box-arrow-left me-2"></i>Volver al sitio
        </a>
    </div>
</aside>

<div class="main">
    <div class="topbar px-4 py-3 d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-semibold"><?= $this->useSection('title') ?? 'Admin' ?></h6>
        <span class="text-muted small"><i class="bi bi-person-circle me-1"></i>Administrador</span>
    </div>

    <div class="p-4">
        <?php
        // Flash success
        $flashSuccess = session()->pull('_flash_success');
        if ($flashSuccess): ?>
        <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <span><?= htmlspecialchars($flashSuccess) ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php
        // Validation errors from Validator::validateRedirect
        $errors = session()->pull('errors');
        if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible mb-4" role="alert">
            <div class="d-flex align-items-center gap-2 mb-1">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <strong>Corrige los siguientes errores:</strong>
            </div>
            <ul class="mb-0 ps-3">
                <?php foreach ($errors as $field => $messages): ?>
                <?php foreach ((array) $messages as $msg): ?>
                <li><?= htmlspecialchars($msg) ?></li>
                <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?= $this->useSection('content') ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->useStack('scripts') ?>
</body>
</html>
