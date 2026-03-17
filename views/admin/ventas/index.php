<?php $this->layout(fromViews('layouts/admin.php')); ?>
<?php $this->section('title', 'Ventas'); ?>

<?php $this->section('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-receipt me-2 text-danger"></i>Ventas registradas</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary"><?= count($ventas) ?></span>
            <a href="/admin/ventas/crear" class="btn btn-danger btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Nueva
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>VIN</th>
                    <th>Fecha</th>
                    <th>Método de pago</th>
                    <th class="text-end">Precio final</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($ventas)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No hay ventas registradas.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($ventas as $v): ?>
                <tr>
                    <td class="text-muted small"><?= $v->venta_id ?></td>
                    <td><?= htmlspecialchars($v->nombre . ' ' . $v->apellido) ?></td>
                    <td><?= htmlspecialchars($v->marca . ' ' . $v->nombre_modelo) ?></td>
                    <td class="font-monospace small text-muted"><?= htmlspecialchars($v->numero_serie) ?></td>
                    <td><?= $v->fecha_venta ?></td>
                    <td>
                        <?php if ($v->metodo_pago): ?>
                        <span class="badge bg-light text-dark border"><?= htmlspecialchars($v->metodo_pago) ?></span>
                        <?php else: ?>
                        <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end fw-semibold text-danger">$<?= number_format($v->precio_final, 0, ',', '.') ?></td>
                    <td class="text-center">
                        <a href="/admin/ventas/<?= $v->venta_id ?>"
                           class="btn btn-sm btn-outline-primary" title="Ver detalle">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="/admin/ventas/<?= $v->venta_id ?>/editar"
                           class="btn btn-sm btn-outline-secondary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="/admin/ventas/<?= $v->venta_id ?>/eliminar"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar la venta #<?= $v->venta_id ?>?')">
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $this->endSection(); ?>
