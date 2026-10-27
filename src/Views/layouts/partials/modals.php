<div id="pdfViewerModal" class="aql-modal" role="dialog" aria-modal="true" aria-labelledby="pdfModalTitle" aria-hidden="true" hidden>
    <div class="aql-modal-dialog">
        <div class="aql-modal-header">
            <div class="min-w-0">
                <span id="pdfModalCategory" class="aql-eyebrow">Documento oficial PDF</span>
                <h3 id="pdfModalTitle" class="aql-modal-title">Documento</h3>
                <span id="pdfModalMeta" class="aql-caption block"></span>
            </div>
            <div class="aql-modal-actions">
                <a id="pdfModalNewTab" href="#" target="_blank" rel="noopener noreferrer" class="aql-btn-ghost aql-btn-sm">Nueva pestaña ↗</a>
                <a id="pdfModalDownload" href="#" download class="aql-btn-primary aql-btn-sm">Descargar PDF</a>
                <button type="button" class="aql-modal-close" data-modal-close onclick="closePdfModal()" aria-label="Cerrar visor PDF">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="aql-modal-body">
            <div id="pdfModalSkeleton" class="skeleton-shimmer absolute inset-0 z-0 flex items-center justify-center">
                <span class="aql-mono-sm text-[#475569]">Cargando documento PDF…</span>
            </div>
            <iframe id="pdfModalIframe" src="about:blank" title="Visor de documento PDF oficial"></iframe>
        </div>
    </div>
</div>

<div id="galleryLightbox" class="aql-lightbox" role="dialog" aria-modal="true" aria-labelledby="lightboxTitle" aria-hidden="true" hidden>
    <div class="aql-lightbox-header">
        <div>
            <span id="lightboxCounter" class="aql-lightbox-counter">1 / 1</span>
            <h3 id="lightboxTitle" class="aql-lightbox-title">Galería institucional</h3>
        </div>
        <button type="button" class="aql-modal-close text-white hover:bg-white/10 hover:text-white" data-lightbox-close onclick="closeLightbox()" aria-label="Cerrar galería">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div class="aql-lightbox-stage">
        <button type="button" id="lightboxPrevBtn" class="aql-lightbox-nav" data-direction="prev" onclick="stepLightbox(-1)" aria-label="Fotografía anterior">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <div id="lightboxSkeleton" class="w-80 h-56 rounded-[12px] skeleton-shimmer" aria-hidden="true"></div>
        <img id="lightboxMainImg" src="" alt="" class="aql-lightbox-image hidden" hidden>

        <button type="button" id="lightboxNextBtn" class="aql-lightbox-nav" data-direction="next" onclick="stepLightbox(1)" aria-label="Fotografía siguiente">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
    </div>

    <p id="lightboxSubtitle" class="aql-lightbox-caption"></p>
</div>
