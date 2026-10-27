document.addEventListener('DOMContentLoaded', () => {
    // 1. Menú Móvil
    const btnMenu = document.getElementById('mobile-menu-btn');
    const menu = document.getElementById('mobile-menu');
    if (btnMenu && menu) {
        btnMenu.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    }

    // 2. Detección exacta de textos truncados con line-clamp-3 para mostrar el botón "Ver más"
    const verificarTextosTruncados = () => {
        const tarjetas = document.querySelectorAll('.tarjeta-docente');
        tarjetas.forEach((card) => {
            if (card.classList.contains('hidden')) return;
            const parrafo = card.querySelector('.texto-resumen-docente');
            const contenedorVerMas = card.querySelector('.contenedor-ver-mas');
            if (!parrafo || !contenedorVerMas) return;

            const estaTruncado = parrafo.scrollHeight > (parrafo.clientHeight + 2);
            contenedorVerMas.classList.toggle('hidden', !estaTruncado);
        });
    };

    verificarTextosTruncados();
    window.addEventListener('load', verificarTextosTruncados);
    window.addEventListener('resize', verificarTextosTruncados);

    // 3. Modal de Perfil Académico (Soporta HTML enriquecido de Quill.js)
    const modalDocente = document.getElementById('modal-perfil-docente');
    const modalCard = document.getElementById('modal-perfil-card');
    const btnsAbrirModal = document.querySelectorAll('.btn-abrir-modal-docente');
    const btnCerrarSup = document.getElementById('btn-cerrar-modal-docente');
    const btnCerrarInf = document.getElementById('btn-cerrar-modal-inferior');

    if (modalDocente && modalCard) {
        const fotoEl = document.getElementById('modal-docente-foto');
        const inicialesEl = document.getElementById('modal-docente-iniciales');
        const nombreEl = document.getElementById('modal-docente-nombre');
        const catEl = document.getElementById('modal-docente-categoria');
        const descEl = document.getElementById('modal-docente-descripcion');
        const linksEl = document.getElementById('modal-docente-links');

        const abrirModal = (btn) => {
            const nombre = btn.getAttribute('data-nombre') || '';
            const categoria = btn.getAttribute('data-categoria') || '';
            const descripcion = btn.getAttribute('data-descripcion') || '';
            const foto = btn.getAttribute('data-foto') || '';
            const iniciales = btn.getAttribute('data-iniciales') || '';
            const email = btn.getAttribute('data-email') || '';
            const whatsapp = btn.getAttribute('data-whatsapp') || '';
            const cel = btn.getAttribute('data-cel') || '';
            const web = btn.getAttribute('data-web') || '';

            nombreEl.textContent = nombre;
            catEl.textContent = categoria;
            // Renderizamos HTML limpio de Quill.js en lugar de texto plano
            descEl.innerHTML = descripcion;

            if (foto !== '') {
                fotoEl.src = foto;
                fotoEl.onerror = () => {
                    fotoEl.classList.add('hidden');
                    inicialesEl.textContent = iniciales;
                    inicialesEl.classList.remove('hidden');
                };
                fotoEl.classList.remove('hidden');
                inicialesEl.classList.add('hidden');
            } else {
                fotoEl.src = '';
                fotoEl.classList.add('hidden');
                inicialesEl.textContent = iniciales;
                inicialesEl.classList.remove('hidden');
            }

            linksEl.innerHTML = '';
            if (email !== '') {
                linksEl.innerHTML += `<a href="mailto:${email}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-slate-100 hover:bg-institucional-navy text-slate-700 hover:text-white text-xs font-medium transition-colors"><i class="fas fa-envelope"></i> <span>${email}</span></a>`;
            }
            if (whatsapp !== '' && cel !== '') {
                linksEl.innerHTML += `<a href="${whatsapp}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-50 hover:bg-emerald-600 text-emerald-800 hover:text-white border border-emerald-200 text-xs font-mono font-medium transition-colors"><i class="fab fa-whatsapp"></i> <span>${cel}</span></a>`;
            }
            if (web !== '') {
                linksEl.innerHTML += `<a href="${web}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-institucional-navy text-institucional-gold text-xs font-medium transition-colors"><i class="fas fa-globe"></i> <span>Sitio Web</span></a>`;
            }

            modalDocente.classList.remove('hidden');
            void modalDocente.offsetWidth;
            modalDocente.classList.remove('opacity-0');
            modalCard.classList.remove('scale-95');
        };

        const cerrarModal = () => {
            modalDocente.classList.add('opacity-0');
            modalCard.classList.add('scale-95');
            setTimeout(() => {
                modalDocente.classList.add('hidden');
            }, 200);
        };

        btnsAbrirModal.forEach((btn) => {
            btn.addEventListener('click', () => abrirModal(btn));
        });

        btnCerrarSup?.addEventListener('click', cerrarModal);
        btnCerrarInf?.addEventListener('click', cerrarModal);
        modalDocente.addEventListener('click', (e) => {
            if (e.target === modalDocente) cerrarModal();
        });
    }
});