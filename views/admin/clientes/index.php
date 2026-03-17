<?php $this->layout(fromViews('layouts/admin.php')); ?>
<?php $this->section('title', 'Clientes'); ?>

<?php $this->section('content'); ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-people me-2 text-danger"></i>Clientes registrados</h6>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary"><?= count($clientes) ?></span>
            <a href="/admin/clientes/crear" class="btn btn-danger btn-sm">
                <i class="bi bi-plus-lg me-1"></i>Nuevo
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clientes)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No hay clientes registrados.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($clientes as $c): ?>
                <tr>
                    <td class="text-muted small"><?= $c->cliente_id ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($c->nombre . ' ' . $c->apellido) ?></td>
                    <td><?= htmlspecialchars($c->documento_identidad) ?></td>
                    <td><?= htmlspecialchars($c->telefono ?? '—') ?></td>
                    <td><?= htmlspecialchars($c->email ?? '—') ?></td>
                    <td><?= htmlspecialchars($c->direccion ?? '—') ?></td>
                    <td class="text-center">
                        <a href="/admin/clientes/<?= $c->cliente_id ?>/editar"
                           class="btn btn-sm btn-outline-secondary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form method="POST" action="/admin/clientes/<?= $c->cliente_id ?>/eliminar"
                              class="d-inline"
                              onsubmit="return confirm('¿Eliminar a <?= htmlspecialchars(addslashes($c->nombre . ' ' . $c->apellido)) ?>?')">
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
