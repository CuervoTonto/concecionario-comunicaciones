<?php $this->layout(fromViews('layouts/admin.php')); ?>
<?php $this->section('title', 'Vehículos'); ?>

<?php $this->section('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-car-front me-2 text-danger"></i>Vehículos registrados</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary"><?= count($vehiculos) ?></span>
            <a href="/admin/vehiculos/crear" class="btn btn-danger btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Nuevo
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Marca / Modelo</th>
                    <th>Año</th>
                    <th>Color</th>
                    <th>VIN</th>
                    <th class="text-end">Precio</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vehiculos)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No hay vehículos registrados.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($vehiculos as $v): ?>
                <tr>
                    <td class="text-muted small"><?= $v->vehiculo_id ?></td>
                    <td>
                        <span class="fw-semibold"><?= htmlspecialchars($v->marca) ?></span>
                        <span class="text-muted ms-1"><?= htmlspecialchars($v->modelo) ?></span>
                    </td>
                    <td><?= $v->anio ?></td>
                    <td><?= htmlspecialchars($v->color ?? '—') ?></td>
                    <td class="font-monospace small"><?= htmlspecialchars($v->numero_serie) ?></td>
                    <td class="text-end fw-semibold text-danger">$<?= number_format($v->precio, 0, ',', '.') ?></td>
                    <td class="text-center">
                        <a href="/admin/vehiculos/<?= $v->vehiculo_id ?>/editar"
                           class="btn btn-sm btn-outline-secondary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="/admin/vehiculos/<?= $v->vehiculo_id ?>/eliminar"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar el vehículo #<?= $v->vehiculo_id ?>?')">
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
