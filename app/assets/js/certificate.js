import '../styles/certificate.css';

document.addEventListener('DOMContentLoaded', function() {
    
  // Vérifie qu'on est bien sur la page du formulaire de certificat
  const form = document.getElementById('certificate-form');
  if (!form) return;

  // ── Mise en évidence des lignes NC + affichage upload photo ──
  document.querySelectorAll('.rating-group input[type="radio"]').forEach(function (radio) {
    radio.addEventListener('change', function() {
      const row = this.closest('.criterion-row');
      row.classList.remove('table-danger', 'table-success', 'table-secondary');

      if (this.value === 'NC') {
        row.classList.add('table-danger');
        showPhotoUpload(row);
      } else if (this.value === 'C') {
        row.classList.add('table-success');
        hidePhotoUpload(row);
      } else {
        row.classList.add('table-secondary');
        hidePhotoUpload(row);
      }
      
      updateNcCounter();
    });
  });

  // ── Affiche le bloc upload photo sur une ligne NC ──────
  function showPhotoUpload(row) {
    let uploadZone = row.querySelector('.nc-photo-upload');
    if (uploadZone) {
      uploadZone.style.display = 'block';
      return;
    }

    const criterionId = row.dataset.criterionId;

    uploadZone = document.createElement('div');
    uploadZone.className = 'nc-photo-upload mt-2';
    uploadZone.innerHTML = `
      <label class="form-label text-danger small mb-1">
        <i class="bi bi-camera me-1"></i>Photos NC (20 max)
      </label>
      <input type="file"
            name="nc_photos[${criterionId}][]"
            class="form-control form-control-sm bg-dark text-light border-danger"
            accept="image/jpeg,image/png,image/webp"
            multiple
            data-criterion-id="${criterionId}">
      <div class="nc-photo-preview d-flex flex-wrap gap-1 mt-1"></div>
    `;

    // Aperçu des photos sélectionnées
    const fileInput = uploadZone.querySelector('input[type="file"]');
    fileInput.addEventListener('change', function() {
      console.log('Fichiers sélectionnés:', this.files.length);
      console.log('Nom input:', this.name);
      
      const preview = uploadZone.querySelector('.nc-photo-preview');
      preview.innerHTML = '';

      if (this.files.length > 20) {
        alert('Maximum 20 photos par critère NC.');
        this.value = '';
        return;
      }

      Array.from(this.files).forEach(function(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.style.cssText = 'width:60px;height:60px;object-fit:cover;border-radius:4px;border:1px solid #dc3545;';
          preview.appendChild(img);
        };
        reader.readAsDataURL(file);
      });
    });

    // Insère le bloc upload dans la cellule du libellé
    const labelCell = row.querySelector('td:nth-child(2)');
    labelCell.appendChild(uploadZone);
  }

  // ── Masque le bloc upload si NC déscoché ───────────────
  function hidePhotoUpload(row) {
    const uploadZone = row.querySelector('.nc-photo-upload');
    if (uploadZone) {
      uploadZone.style.display = 'none';
      // Vide l'input pour ne pas envoyer de fichiers
      const fileInput = uploadZone.querySelector('input[type="file"]');
      if (fileInput) fileInput.value = '';
      const preview = uploadZone.querySelector('.nc-photo-preview');
      if (preview) preview.innerHTML = '';
    }
  }

  // ── Compteur NC ────────────────────────────────────────
  function updateNcCounter() {
    const ncCount = document.querySelectorAll('input[value="NC"]:checked').length;
    const counter = document.getElementById('nc-counter');
    const countEL = document.getElementById('nc-count');

    countEL.textContent = ncCount;
    counter.style.display = ncCount > 0 ? 'inline-block' : 'none';
  }

  // ── Bouton Tout Conforme ───────────────────────────────
  document.getElementById('btn-all-c').addEventListener('click', function () {
    document.querySelectorAll('input[value="C"]').forEach(function (radio) {
      radio.checked = true;
      const row = radio.closest('.criterion-row');
      row.classList.remove('table-danger', 'table-secondary');
      row.classList.add('table-success');
      hidePhotoUpload(row);
    });
    updateNcCounter();
  });

  // ── Validation avant soumission ────────────────────────
  form.addEventListener('submit', function (e) {
    const totalCriterions = document.querySelectorAll('.criterion-row').length;
    const ratedCriterions = document.querySelectorAll('.rating-group input:checked').length;
    
    if (ratedCriterions < totalCriterions) {
      e.preventDefault();
      const missing = totalCriterions - ratedCriterions;
      alert(missing + " critere(s) n'ont pas été cotés. Veuillez cocher SO, C ou NC pour chaque item.");
      
      const rows = document.querySelectorAll('.criterion-row');
      for (let row of rows) {
        if (!row.querySelector('input:checked')) {
          row.scrollIntoView({ behavior: 'smooth', block: 'center' });
          row.classList.add('table-warning');
          break;
        }
      }
    }
  });

});