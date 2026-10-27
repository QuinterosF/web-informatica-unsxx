'use strict';

const aqlMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
const aqlFinePointer = window.matchMedia('(hover: hover) and (pointer: fine)');

function aqlIsModalOpen() {
    return Boolean(document.querySelector('.aql-modal:not([hidden]), .aql-lightbox:not([hidden])'));
}

function aqlLockScroll() {
    document.body.classList.add('aql-scroll-locked');
}

function aqlUnlockScroll() {
    if (!aqlIsModalOpen()) {
        document.body.classList.remove('aql-scroll-locked');
    }
}

function aqlFocusableElements(container) {
    if (!container) return [];
    return Array.from(container.querySelectorAll(
        'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), iframe, [tabindex]:not([tabindex="-1"])'
    )).filter((element) => element.offsetParent !== null || element === document.activeElement);
}

function aqlTrapFocus(event, container) {
    if (event.key !== 'Tab') return;
    const focusable = aqlFocusableElements(container);
    if (focusable.length === 0) return;

    const first = focusable[0];
    const last = focusable[focusable.length - 1];

    if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
    }
}

function toggleMobileNav(force) {
    const panel = document.getElementById('mobileNavPanel');
    const toggle = document.getElementById('mobileNavToggle');
    if (!panel || !toggle) return;

    const shouldOpen = typeof force === 'boolean'
        ? force
        : !panel.classList.contains('is-open');

    panel.classList.toggle('is-open', shouldOpen);
    toggle.setAttribute('aria-expanded', shouldOpen ? 'true' : 'false');

    const openIcon = toggle.querySelector('[data-mobile-icon="open"]');
    const closeIcon = toggle.querySelector('[data-mobile-icon="close"]');
    if (openIcon) openIcon.hidden = shouldOpen;
    if (closeIcon) closeIcon.hidden = !shouldOpen;

    if (shouldOpen) {
        const firstLink = panel.querySelector('a');
        if (firstLink) firstLink.focus();
    }
}

function closeMobileNav() {
    toggleMobileNav(false);
}

function initDropdowns() {
    const items = Array.from(document.querySelectorAll('[data-dropdown]'));
    if (items.length === 0) return;

    let closeTimer = null;

    const closeAll = (except) => {
        items.forEach((item) => {
            if (item === except) return;
            item.classList.remove('is-open');
            const trigger = item.querySelector('[data-dropdown-trigger]');
            const panel = item.querySelector('[data-dropdown-panel]');
            if (trigger) trigger.setAttribute('aria-expanded', 'false');
            if (panel) panel.setAttribute('aria-hidden', 'true');
        });
    };

    const setOpen = (item, open) => {
        item.classList.toggle('is-open', open);
        const trigger = item.querySelector('[data-dropdown-trigger]');
        const panel = item.querySelector('[data-dropdown-panel]');
        if (trigger) trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (panel) panel.setAttribute('aria-hidden', open ? 'false' : 'true');
    };

    items.forEach((item) => {
        const trigger = item.querySelector('[data-dropdown-trigger]');
        const panel = item.querySelector('[data-dropdown-panel]');
        if (!trigger || !panel) return;

        trigger.setAttribute('aria-expanded', 'false');
        panel.setAttribute('aria-hidden', 'true');

        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            const willOpen = !item.classList.contains('is-open');
            closeAll(item);
            setOpen(item, willOpen);
        });

        trigger.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                closeAll(item);
                setOpen(item, true);
                const firstLink = panel.querySelector('a, button');
                if (firstLink) {
                    void panel.offsetWidth;
                    firstLink.focus();
                }
            }
            if (event.key === 'Escape') {
                setOpen(item, false);
                trigger.focus();
            }
        });

        item.addEventListener('mouseenter', () => {
            if (!aqlFinePointer.matches) return;
            if (closeTimer) window.clearTimeout(closeTimer);
            closeAll(item);
            setOpen(item, true);
        });

        item.addEventListener('mouseleave', () => {
            if (!aqlFinePointer.matches) return;
            closeTimer = window.setTimeout(() => setOpen(item, false), 120);
        });

        item.addEventListener('focusout', (event) => {
            if (item.contains(event.relatedTarget)) return;
            setOpen(item, false);
        });

        panel.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                setOpen(item, false);
                trigger.focus();
            }
        });
    });

    document.addEventListener('click', (event) => {
        if (event.target.closest('[data-dropdown]')) return;
        closeAll();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeAll();
    });
}

function initMobileNav() {
    const toggle = document.getElementById('mobileNavToggle');
    const panel = document.getElementById('mobileNavPanel');
    if (!toggle || !panel) return;

    toggle.addEventListener('click', () => toggleMobileNav());

    panel.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', closeMobileNav);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && panel.classList.contains('is-open')) {
            closeMobileNav();
            toggle.focus();
        }
    });
}

function switchHeroShowcase(index) {
    const tabs = Array.from(document.querySelectorAll('[data-hero-tab]'));
    const panels = Array.from(document.querySelectorAll('[data-hero-panel]'));
    if (tabs.length === 0 || panels.length === 0) return;

    const target = Math.max(0, Math.min(Number(index) || 0, tabs.length - 1));

    tabs.forEach((tab, tabIndex) => {
        const selected = tabIndex === target;
        tab.setAttribute('aria-selected', selected ? 'true' : 'false');
        tab.setAttribute('tabindex', selected ? '0' : '-1');
    });

    panels.forEach((panel, panelIndex) => {
        panel.hidden = panelIndex !== target;
        panel.classList.toggle('is-active', panelIndex === target);
    });
}

function initHeroTabs() {
    const tabs = Array.from(document.querySelectorAll('[data-hero-tab]'));
    const panels = Array.from(document.querySelectorAll('[data-hero-panel]'));
    if (tabs.length === 0 || panels.length === 0) return;

    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => switchHeroShowcase(index));

        tab.addEventListener('keydown', (event) => {
            let nextIndex = null;

            if (event.key === 'ArrowRight') nextIndex = (index + 1) % tabs.length;
            if (event.key === 'ArrowLeft') nextIndex = (index - 1 + tabs.length) % tabs.length;
            if (event.key === 'Home') nextIndex = 0;
            if (event.key === 'End') nextIndex = tabs.length - 1;

            if (nextIndex !== null) {
                event.preventDefault();
                switchHeroShowcase(nextIndex);
                tabs[nextIndex].focus();
            }
        });
    });

    switchHeroShowcase(0);
}

function initSkeletonImages() {
    document.querySelectorAll('img.lazy-skeleton').forEach((image) => {
        const finish = () => {
            image.classList.add('is-loaded');
            const wrapper = image.closest('.skeleton-shimmer');
            if (wrapper) wrapper.classList.remove('skeleton-shimmer');
        };

        if (image.complete && image.naturalWidth > 0) {
            finish();
            return;
        }

        image.addEventListener('load', finish, { once: true });
        image.addEventListener('error', finish, { once: true });
    });
}

function initReveals() {
    const elements = Array.from(document.querySelectorAll('.aql-reveal'));
    if (elements.length === 0) return;

    if (aqlMotionQuery.matches || !('IntersectionObserver' in window)) {
        elements.forEach((element) => element.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    elements.forEach((element) => observer.observe(element));
}

let aqlPdfActive = false;
let aqlPdfTrigger = null;

function openPdfModal(pdfUrl, title, category, meta) {
    if (!pdfUrl) return;

    const modal = document.getElementById('pdfViewerModal');
    const frame = document.getElementById('pdfModalIframe');
    const skeleton = document.getElementById('pdfModalSkeleton');
    if (!modal || !frame || !skeleton) return;

    aqlPdfActive = true;
    aqlPdfTrigger = document.activeElement instanceof HTMLElement ? document.activeElement : null;

    const titleNode = document.getElementById('pdfModalTitle');
    const categoryNode = document.getElementById('pdfModalCategory');
    const metaNode = document.getElementById('pdfModalMeta');
    const newTab = document.getElementById('pdfModalNewTab');
    const download = document.getElementById('pdfModalDownload');

    if (titleNode) titleNode.textContent = title || 'Documento oficial';
    if (categoryNode) categoryNode.textContent = category || 'Documento PDF';
    if (metaNode) metaNode.textContent = meta || '';
    if (newTab) newTab.href = pdfUrl;
    if (download) download.href = pdfUrl;

    skeleton.hidden = false;
    frame.src = pdfUrl;
    modal.hidden = false;
    modal.setAttribute('aria-hidden', 'false');
    aqlLockScroll();

    const closeButton = modal.querySelector('[data-modal-close]');
    if (closeButton) closeButton.focus();
}

function onPdfIframeLoad() {
    if (!aqlPdfActive) return;
    const skeleton = document.getElementById('pdfModalSkeleton');
    if (skeleton) skeleton.hidden = true;
}

function closePdfModal() {
    const modal = document.getElementById('pdfViewerModal');
    const frame = document.getElementById('pdfModalIframe');
    const skeleton = document.getElementById('pdfModalSkeleton');
    if (!modal) return;

    aqlPdfActive = false;
    modal.hidden = true;
    modal.setAttribute('aria-hidden', 'true');
    if (frame) frame.src = 'about:blank';
    if (skeleton) skeleton.hidden = false;
    aqlUnlockScroll();

    if (aqlPdfTrigger && typeof aqlPdfTrigger.focus === 'function') {
        aqlPdfTrigger.focus();
    }
    aqlPdfTrigger = null;
}

let aqlLightboxItems = [];
let aqlLightboxIndex = 0;
let aqlLightboxTrigger = null;

function openLightbox(items, startIndex) {
    if (!Array.isArray(items) || items.length === 0) return;

    const modal = document.getElementById('galleryLightbox');
    if (!modal) return;

    aqlLightboxItems = items;
    aqlLightboxIndex = Math.max(0, Math.min(Number(startIndex) || 0, items.length - 1));
    aqlLightboxTrigger = document.activeElement instanceof HTMLElement ? document.activeElement : null;

    modal.hidden = false;
    modal.setAttribute('aria-hidden', 'false');
    aqlLockScroll();
    renderLightboxSlide();

    const closeButton = modal.querySelector('[data-lightbox-close]');
    if (closeButton) closeButton.focus();
}

function openSingleImage(url, title, subtitle) {
    openLightbox([{ url: url, title: title || '', subtitle: subtitle || '' }], 0);
}

function renderLightboxSlide() {
    const item = aqlLightboxItems[aqlLightboxIndex];
    if (!item) return;

    const image = document.getElementById('lightboxMainImg');
    const skeleton = document.getElementById('lightboxSkeleton');
    const counter = document.getElementById('lightboxCounter');
    const title = document.getElementById('lightboxTitle');
    const subtitle = document.getElementById('lightboxSubtitle');
    const previous = document.getElementById('lightboxPrevBtn');
    const next = document.getElementById('lightboxNextBtn');

    if (image) {
        image.hidden = true;
        image.classList.add('hidden');
        image.src = item.url;
        image.alt = item.alt || item.title || 'Fotografía institucional';
    }

    if (skeleton) skeleton.hidden = false;
    if (counter) counter.textContent = `${aqlLightboxIndex + 1} / ${aqlLightboxItems.length}`;
    if (title) title.textContent = item.title || 'Fotografía institucional';
    if (subtitle) subtitle.textContent = item.subtitle || '';

    const hasMany = aqlLightboxItems.length > 1;
    if (previous) previous.hidden = !hasMany;
    if (next) next.hidden = !hasMany;
}

function onLightboxImgLoad() {
    const image = document.getElementById('lightboxMainImg');
    const skeleton = document.getElementById('lightboxSkeleton');
    if (image) {
        image.hidden = false;
        image.classList.remove('hidden');
    }
    if (skeleton) skeleton.hidden = true;
}

function stepLightbox(direction) {
    if (aqlLightboxItems.length <= 1) return;
    aqlLightboxIndex = (aqlLightboxIndex + direction + aqlLightboxItems.length) % aqlLightboxItems.length;
    renderLightboxSlide();
}

function closeLightbox() {
    const modal = document.getElementById('galleryLightbox');
    const image = document.getElementById('lightboxMainImg');
    if (!modal) return;

    modal.hidden = true;
    modal.setAttribute('aria-hidden', 'true');
    if (image) {
        image.removeAttribute('src');
        image.hidden = true;
    }
    aqlUnlockScroll();

    if (aqlLightboxTrigger && typeof aqlLightboxTrigger.focus === 'function') {
        aqlLightboxTrigger.focus();
    }
    aqlLightboxTrigger = null;
}

function initModals() {
    const pdfModal = document.getElementById('pdfViewerModal');
    const lightbox = document.getElementById('galleryLightbox');
    const pdfFrame = document.getElementById('pdfModalIframe');
    const lightboxImage = document.getElementById('lightboxMainImg');

    if (pdfFrame) pdfFrame.addEventListener('load', onPdfIframeLoad);
    if (lightboxImage) lightboxImage.addEventListener('load', onLightboxImgLoad);

    if (pdfModal) {
        pdfModal.addEventListener('click', (event) => {
            if (event.target === pdfModal) closePdfModal();
        });
        pdfModal.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                event.preventDefault();
                closePdfModal();
                return;
            }
            aqlTrapFocus(event, pdfModal);
        });
    }

    if (lightbox) {
        lightbox.addEventListener('click', (event) => {
            if (event.target === lightbox) closeLightbox();
        });
        lightbox.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                event.preventDefault();
                closeLightbox();
                return;
            }
            if (event.key === 'ArrowRight') {
                event.preventDefault();
                stepLightbox(1);
                return;
            }
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                stepLightbox(-1);
                return;
            }
            aqlTrapFocus(event, lightbox);
        });
    }

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;
        if (pdfModal && !pdfModal.hidden) closePdfModal();
        if (lightbox && !lightbox.hidden) closeLightbox();
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.documentElement.classList.add('js');
    initDropdowns();
    initMobileNav();
    initHeroTabs();
    initSkeletonImages();
    initReveals();
    initModals();
});

window.toggleMobileNav = toggleMobileNav;
window.switchHeroShowcase = switchHeroShowcase;
window.openPdfModal = openPdfModal;
window.closePdfModal = closePdfModal;
window.openLightbox = openLightbox;
window.openSingleImage = openSingleImage;
window.stepLightbox = stepLightbox;
window.closeLightbox = closeLightbox;
