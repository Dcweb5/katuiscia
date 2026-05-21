<?php $__env->startSection('title','Détail rendez-vous'); ?>
<?php $__env->startSection('content'); ?>
<div class="dashboard-content">
  <a href="<?php echo e(route('admin.appointments.index')); ?>" style="color:var(--color-text-muted);font-size:var(--text-sm);text-decoration:none;margin-bottom:1rem;display:inline-block;">&larr; Retour à la liste</a>

  <?php if(session('success')): ?>
    <div class="alert alert-success" style="margin-bottom:1rem;"><?php echo e(session('success')); ?></div>
  <?php endif; ?>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;">
    <div>
      <div class="card" style="margin-bottom:1.5rem;">
        <h2 style="margin:0 0 1rem;font-size:var(--text-lg);">Informations du demandeur</h2>
        <table style="width:100%;border-collapse:collapse;">
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Nom</td><td style="padding:0.5rem 0;"><?php echo e($appointment->name); ?></td></tr>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Email</td><td style="padding:0.5rem 0;"><?php echo e($appointment->email); ?></td></tr>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Téléphone</td><td style="padding:0.5rem 0;"><?php echo e($appointment->phone ?? '—'); ?></td></tr>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Type</td><td style="padding:0.5rem 0;"><?php echo e($appointment->type === 'entreprise' ? '🏢 Entreprise' : '👤 Particulier'); ?></td></tr>
          <?php if($appointment->company_name): ?>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Entreprise</td><td style="padding:0.5rem 0;"><?php echo e($appointment->company_name); ?></td></tr>
          <?php endif; ?>
          <?php if($appointment->siret): ?>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">SIRET</td><td style="padding:0.5rem 0;"><?php echo e($appointment->siret); ?></td></tr>
          <?php endif; ?>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Source</td><td style="padding:0.5rem 0;"><?php echo e($appointment->source === 'formation' ? '🎓 Formation' : '🤝 Grossiste'); ?></td></tr>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Date souhaitée</td><td style="padding:0.5rem 0;"><?php echo e($appointment->preferred_date ? \Carbon\Carbon::parse($appointment->preferred_date)->format('d/m/Y') : '—'); ?></td></tr>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Créneau</td><td style="padding:0.5rem 0;"><?php echo e($appointment->preferred_time ?? '—'); ?></td></tr>
          <tr><td style="padding:0.5rem 0;color:var(--color-text-muted);font-size:var(--text-sm);">Demande le</td><td style="padding:0.5rem 0;"><?php echo e($appointment->created_at->format('d/m/Y à H:i')); ?></td></tr>
        </table>
        <?php if($appointment->message): ?>
        <div style="margin-top:1rem;padding:0.75rem;background:var(--color-bg);border-radius:8px;font-size:var(--text-sm);">
          <strong>Message :</strong><br><?php echo e($appointment->message); ?>

        </div>
        <?php endif; ?>
      </div>
    </div>

    <div>
      <div class="card">
        <h2 style="margin:0 0 1rem;font-size:var(--text-lg);">
          <?php echo e($appointment->status === 'en_attente' ? 'Gérer le rendez-vous' : 'Statut actuel'); ?>

        </h2>
        <div style="margin-bottom:1.5rem;">
          Statut :
          <span style="font-size:var(--text-xs);padding:4px 12px;border-radius:12px;margin-left:0.5rem;<?php echo e($appointment->status === 'confirme' ? 'background:#e8f5e9;color:#2e7d32;' : ($appointment->status === 'refuse' ? 'background:#ffebee;color:#c62828;' : 'background:#fff8e1;color:#f57f17;')); ?>">
            <?php echo e($appointment->status === 'confirme' ? '✅ Confirmé' : ($appointment->status === 'refuse' ? '❌ Refusé' : '⏳ En attente')); ?>

          </span>
        </div>

        <?php if($appointment->status === 'confirme'): ?>
          <div style="background:#e8f5e9;padding:1rem;border-radius:8px;margin-bottom:1rem;">
            <strong>Rendez-vous fixé :</strong><br>
            <?php echo e(\Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y')); ?> à <?php echo e($appointment->appointment_time); ?>

            <?php if($appointment->admin_instructions): ?>
            <br><br><strong>Instructions envoyées :</strong><br>
            <?php echo e($appointment->admin_instructions); ?>

            <?php endif; ?>
          </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.appointments.update', $appointment)); ?>">
          <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

          <div class="form-group">
            <label class="form-label">Statut *</label>
            <select name="status" class="form-input" style="width:100%;" onchange="toggleConfirmFields(this.value)">
              <option value="en_attente" <?php echo e($appointment->status === 'en_attente' ? 'selected' : ''); ?>>⏳ En attente</option>
              <option value="confirme" <?php echo e($appointment->status === 'confirme' ? 'selected' : ''); ?>>✅ Confirmer</option>
              <option value="refuse" <?php echo e($appointment->status === 'refuse' ? 'selected' : ''); ?>>❌ Refuser</option>
            </select>
          </div>

          <div id="confirm-fields" style="<?php echo e($appointment->status === 'confirme' ? '' : 'display:none;'); ?>">
            <div class="form-group">
              <label class="form-label">Date du rendez-vous *</label>
              <input type="date" name="appointment_date" class="form-input" style="width:100%;" value="<?php echo e($appointment->appointment_date); ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Horaire *</label>
              <input type="time" name="appointment_time" class="form-input" style="width:100%;" value="<?php echo e($appointment->appointment_time); ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Instructions (envoyées par email)</label>
              <textarea name="admin_instructions" class="form-input" style="width:100%;" rows="4" placeholder="Lieu, modalités, documents à apporter, lien visio..."><?php echo e($appointment->admin_instructions); ?></textarea>
            </div>
          </div>

          <button type="submit" class="btn-primary" style="width:100%;">Mettre à jour</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function toggleConfirmFields(status) {
  document.getElementById('confirm-fields').style.display = status === 'confirme' ? '' : 'none';
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\mes sites web\KATUISCIA LARAVEL\resources\views\admin\appointments\show.blade.php ENDPATH**/ ?>