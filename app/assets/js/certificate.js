import '../styles/certificate.css';

document.addEventListener('DOMContentLoaded', function() {
    
  // Vérifie qu'on est bien sur la page du formulaire de certificat
  const form = document.getElementById('certificate-form');
  if (!form) return;

  // ── Mise en évidence des lignes NC ─────────────────────
  document.querySelectorAll('.rating-group input[type="radio"]').forEach(function (radio) {
    radio.addEventListener('change', function() {
      const row = this.closest('.criterion-row');
      row.classList.remove('table-danger', 'table-success', 'table-secondary');

      if (this.value === 'NC') {
        row.classList.add('table-danger');
      } else if (this.value === 'C') {
          row.classList.add('table-success');
      } else {
          row.classList.add('table-secondary');
      }
      
      updateNcCounter();
    });
  });

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
    
    // Scrolle vers le premier critère non coté
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