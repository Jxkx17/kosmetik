document.addEventListener('DOMContentLoaded', () => {
  // Menu mobile
  const toggle = document.querySelector('.nav-toggle');
  const menu = document.getElementById('nav-menu');
  if (toggle && menu) {
    toggle.addEventListener('click', () => {
      const open = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!open));
      menu.classList.toggle('is-open', !open);
    });
  }

  // Fermeture des alertes (auto après 6 s pour les succès)
  document.querySelectorAll('.alert').forEach((a) => {
    a.querySelector('.alert-close')?.addEventListener('click', () => a.remove());
    if (a.classList.contains('alert-success')) setTimeout(() => a.remove(), 6000);
  });

  // Afficher / masquer le mot de passe
  document.querySelectorAll('.toggle-password').forEach((btn) => {
    btn.addEventListener('click', () => {
      const input = btn.parentElement.querySelector('input');
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.textContent = show ? 'Masquer' : 'Afficher';
      btn.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
    });
  });

  // Confirmation des actions sensibles + anti double soumission
  document.querySelectorAll('form').forEach((f) => {
    f.addEventListener('submit', (ev) => {
      const msg = f.dataset.confirm;
      if (msg && !window.confirm(msg)) { ev.preventDefault(); return; }
      f.querySelectorAll('button[type=submit]').forEach((b) => { b.disabled = true; });
    });
  });

  // Carrousel d'accueil
  const slides = document.querySelectorAll('.slide');
  const dots = document.querySelectorAll('.dot');
  if (slides.length > 1) {
    let i = 0;
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const show = (n) => {
      i = (n + slides.length) % slides.length;
      slides.forEach((s, k) => s.classList.toggle('is-active', k === i));
      dots.forEach((d, k) => { d.classList.toggle('is-active', k === i); d.setAttribute('aria-selected', String(k === i)); });
    };
    let timer = reduce ? null : setInterval(() => show(i + 1), 5000);
    dots.forEach((d, k) => d.addEventListener('click', () => {
      show(k);
      if (timer) { clearInterval(timer); timer = setInterval(() => show(i + 1), 5000); }
    }));
  }

  // Images manquantes : masquer proprement
  document.querySelectorAll('img').forEach((img) => {
    img.addEventListener('error', () => img.classList.add('img-missing'), { once: true });
  });
});
