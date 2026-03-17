<?php $this->layout(fromViews('layouts/admin.php')); ?>
<?php $this->section('title', 'Dashboard'); ?>

<?php $this->section('content'); ?>

<!-- Estadísticas principales -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Clientes</small>
                    <h4 class="fw-bold mb-0"><?= number_format($totalClientes, 0, ',', '.') ?></h4>
                </div>
                <i class="bi bi-people text-danger fs-2 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Vehículos en inventario</small>
                    <h4 class="fw-bold mb-0"><?= number_format($totalVehiculos, 0, ',', '.') ?></h4>
                </div>
                <i class="bi bi-car-front text-danger fs-2 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Ventas totales</small>
                    <h4 class="fw-bold mb-0"><?= number_format($totalVentas, 0, ',', '.') ?></h4>
                </div>
                <i class="bi bi-receipt text-danger fs-2 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm stat-card p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">Ingresos totales</small>
                    <h4 class="fw-bold mb-0">$<?= number_format($ingresos, 0, ',', '.') ?></h4>
                </div>
                <i class="bi bi-cash-stack text-danger fs-2 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas del mes -->
<div class="row g-3 mb-4">
    <div class="col-sm-6">
        <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center"
                 style="width:48px;height:48px;flex-shrink:0;">
                <i class="bi bi-calendar-check text-danger fs-5"></i>
            </div>
            <div>
                <small class="text-muted">Ventas este mes</small>
                <h5 class="fw-bold mb-0"><?= number_format($ventasMes, 0, ',', '.') ?></h5>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center gap-3">
            <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center"
                 style="width:48px;height:48px;flex-shrink:0;">
                <i class="bi bi-graph-up-arrow text-danger fs-5"></i>
            </div>
            <div>
                <small class="text-muted">Ingresos este mes</small>
                <h5 class="fw-bold mb-0">$<?= number_format($ingresosMes, 0, ',', '.') ?></h5>
            </div>
        </div>
    </div>
</div>

<!-- Accesos rápidos -->
<hr class="my-2">
<p class="text-muted small mb-3">Accesos rápidos</p>
<div class="row g-3">
    <div class="col-md-4">
        <a href="/admin/clientes/crear" class="card border-0 shadow-sm p-4 text-decoration-none text-dark h-100 d-flex flex-row align-items-center gap-3">
            <i class="bi bi-person-plus fs-2 text-danger"></i>
            <div>
                <div class="fw-semibold">Nuevo cliente</div>
                <small class="text-muted">Registrar un cliente</small>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/admin/vehiculos/crear" class="card border-0 shadow-sm p-4 text-decoration-none text-dark h-100 d-flex flex-row align-items-center gap-3">
            <i class="bi bi-plus-circle fs-2 text-danger"></i>
            <div>
                <div class="fw-semibold">Nuevo vehículo</div>
                <small class="text-muted">Agregar al inventario</small>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/admin/ventas/crear" class="card border-0 shadow-sm p-4 text-decoration-none text-dark h-100 d-flex flex-row align-items-center gap-3">
            <i class="bi bi-receipt fs-2 text-danger"></i>
            <div>
                <div class="fw-semibold">Registrar venta</div>
                <small class="text-muted">Reportar una venta</small>
            </div>
        </a>
    </div>
</div>

<?php $this->endSection(); ?>
