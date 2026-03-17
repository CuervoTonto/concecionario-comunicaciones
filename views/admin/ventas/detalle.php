<?php $this->layout(fromViews('layouts/admin.php')); ?>
<?php $this->section('title', 'Detalle de venta #' . $venta->venta_id); ?>

<?php $this->section('styles'); ?>
<style>
    .detalle-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; color: #6c757d; }
    .detalle-valor { font-size: 1rem; font-weight: 500; }
    .precio-grande { font-size: 2rem; font-weight: 700; color: #e94560; }
</style>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h5 class="fw-bold mb-0">Venta <span class="text-danger">#<?= $venta->venta_id ?></span></h5>
        <small class="text-muted">Registrada el <?= date('d \d\e F \d\e Y', strtotime($venta->fecha_venta)) ?></small>
    </div>
    <div class="d-flex gap-2">
        <a href="/admin/ventas/<?= $venta->venta_id ?>/editar" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-pencil me-1"></i>Editar
        </a>
        <a href="/admin/ventas" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<div class="row g-4">

    <!-- Precio destacado -->
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 d-flex flex-row align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="detalle-label mb-1">Precio final de venta</div>
                <div class="precio-grande">$<?= number_format($venta->precio_final, 0, ',', '.') ?></div>
            </div>
            <div class="d-flex flex-column align-items-end gap-2">
                <?php if ($venta->metodo_pago): ?>
                <span class="badge bg-dark fs-6 px-3 py-2">
                    <i class="bi bi-credit-card me-1"></i><?= htmlspecialchars($venta->metodo_pago) ?>
                </span>
                <?php endif; ?>
                <?php
                $descuento = $venta->precio_vehiculo - $venta->precio_final;
                if ($descuento > 0): ?>
                <span class="text-success small">
                    <i class="bi bi-tag me-1"></i>Descuento aplicado: $<?= number_format($descuento, 0, ',', '.') ?>
                </span>
                <?php elseif ($descuento < 0): ?>
                <span class="text-warning small">
                    <i class="bi bi-exclamation-circle me-1"></i>Sobreprecio: $<?= number_format(abs($descuento), 0, ',', '.') ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Cliente -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold mb-4"><i class="bi bi-person me-2 text-danger"></i>Datos del cliente</h6>
            <div class="row g-3">
                <div class="col-12">
                    <div class="detalle-label">Nombre completo</div>
                    <div class="detalle-valor"><?= htmlspecialchars($venta->nombre . ' ' . $venta->apellido) ?></div>
                </div>
                <div class="col-sm-6">
                    <div class="detalle-label">Documento</div>
                    <div class="detalle-valor"><?= htmlspecialchars($venta->documento_identidad) ?></div>
                </div>
                <div class="col-sm-6">
                    <div class="detalle-label">Teléfono</div>
                    <div class="detalle-valor"><?= htmlspecialchars($venta->telefono ?? '—') ?></div>
                </div>
                <div class="col-12">
                    <div class="detalle-label">Correo electrónico</div>
                    <div class="detalle-valor">
                        <?php if ($venta->email): ?>
                        <a href="mailto:<?= htmlspecialchars($venta->email) ?>" class="text-decoration-none">
                            <?= htmlspecialchars($venta->email) ?>
                        </a>
                        <?php else: ?>—<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vehículo -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h6 class="fw-bold mb-4"><i class="bi bi-car-front me-2 text-danger"></i>Datos del vehículo</h6>
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="detalle-label">Marca</div>
                    <div class="detalle-valor"><?= htmlspecialchars($venta->marca) ?></div>
                </div>
                <div class="col-sm-6">
                    <div class="detalle-label">Modelo</div>
                    <div class="detalle-valor"><?= htmlspecialchars($venta->nombre_modelo) ?></div>
                </div>
                <div class="col-sm-6">
                    <div class="detalle-label">Año</div>
                    <div class="detalle-valor"><?= $venta->anio ?></div>
                </div>
                <div class="col-sm-6">
                    <div class="detalle-label">Color</div>
                    <div class="detalle-valor"><?= htmlspecialchars($venta->color ?? '—') ?></div>
                </div>
                <div class="col-12">
                    <div class="detalle-label">Número de serie (VIN)</div>
                    <div class="detalle-valor font-monospace"><?= htmlspecialchars($venta->numero_serie) ?></div>
                </div>
                <div class="col-12">
                    <div class="detalle-label">Precio de catálogo</div>
                    <div class="detalle-valor">$<?= number_format($venta->precio_vehiculo, 0, ',', '.') ?></div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $this->endSection(); ?>
