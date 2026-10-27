# DESIGN.md — AgentQL Daylight Institutional (Ingeniería Informática UNSXX)

> **Referencia rápida sincronizada.** La fuente de verdad completa es [`design-system/MASTER.md`](design-system/MASTER.md).  
> **Versión:** 1.0 · **Fecha:** 2026-10-25 · **Estado:** activo.  
> **Alcance:** portal público multipágina. Esta referencia no autoriza todavía modificar vistas PHP.

## 1. Norte de diseño

**Idea:** “AgentQL Daylight Institutional”. Precisión técnica de una herramienta para desarrolladores, claridad editorial académica y gravedad institucional, sobre un lienzo claro, luminoso y de alta gama.

**Calibración visual:** AgentQL para composición y ventanas técnicas; Linear Light, Vercel Light y Raycast para limpieza, contraste y sensación premium del tema claro.

**Diales:**

| Dial | Valor | Interpretación |
| --- | --- | --- |
| `DESIGN_VARIANCE` | `4/10` | Bento controlado, asimetría dentro de una retícula estricta. |
| `MOTION_INTENSITY` | `7/10` | Entradas de hero, revelados de sección, microinteracciones y shimmer. Sin scroll hijacking. |
| `VISUAL_DENSITY` | `4/10` | Aire generoso, contenedor de 1200 px, secciones de 80 px. |

**Principios:** precisión técnica, gravedad institucional, evidencia sobre decoración, multipágina completa, movimiento con propósito y accesibilidad por defecto.

**No es:** tema oscuro, landing de una sola pantalla, glassmorphism, catálogo de tarjetas idénticas ni página de startup con gradientes por todas partes.

## 2. Color

### 2.1 Primitivos

| Token | Valor | Uso |
| --- | --- | --- |
| `--color-abyss` | `#ffffff` | Lienzo principal |
| `--color-void` | `#f8fafc` | Secciones alternas, footer |
| `--color-deep-sea` | `#f1f5fb` | Superficie elevada, hover, inputs deshabilitados |
| `--color-cobalt-panel` | `#eff6ff` | Tarjeta spotlight, badges informativos |
| `--color-terminal` | `#0d172b` | Código, terminal, superficie ejecutiva |
| `--color-terminal-header` | `#0b0c0e` | Cabecera de ventanas de código |
| `--color-quartz` | `#0b132b` | Texto primario |
| `--color-ash` | `#475569` | Texto secundario |
| `--color-mist` | `#64748b` | Metadatos sobre blanco o `#f8fafc` |
| `--color-pulse-navy` | `#1e3a8a` | Botón primario, navy institucional |
| `--color-signal-blue` | `#2862d7` | Acento principal, enlaces, foco |
| `--color-signal-strong` | `#1d4ed8` | Enlaces de énfasis |
| `--color-signal-soft` | `#3b82f6` | Acento secundario |
| `--color-unsxx-gold` | `#eab308` | Acreditación y logros |
| `--color-unsxx-gold-bright` | `#facc15` | Dorado sobre oscuro |
| `--color-obsidian-edge` | `#dbe3f0` | Hairline de tarjetas |
| `--color-inkline` | `#e2e8f0` | Divisores internos |
| `--color-sapphire-hairline` | `#93c5fd` | Borde de tarjeta spotlight |
| `--color-border-strong` | `#94a3b8` | Borde de botón ghost |
| `--color-success` | `#047857` | Éxito sobre claro |
| `--color-warning` | `#b45309` | Advertencia sobre claro |
| `--color-danger` | `#b91c1c` | Error sobre claro |
| `--color-info` | `#0369a1` | Información sobre claro |
| `--color-focus` | `#2862d7` | Foco en claro |
| `--color-focus-dark` | `#facc15` | Foco en oscuro |

### 2.2 Estados y código

| Token | Valor | Uso |
| --- | --- | --- |
| `--color-success-soft` | `#ecfdf5` | Fondo de éxito |
| `--color-warning-soft` | `#fffbeb` | Fondo de advertencia |
| `--color-danger-soft` | `#fef2f2` | Fondo de error |
| `--color-info-soft` | `#f0f9ff` | Fondo informativo |
| `--color-unsxx-gold-deep` | `#ca8a04` | Borde de badge dorado |
| `--color-code-key` | `#ff7dda` | Claves en código |
| `--color-code-value` | `#28b6ff` | Valores en código |
| `--color-code-comment` | `#8798c1` | Comentarios |
| `--color-code-accent` | `#85a6e9` | Acentos técnicos |
| `--color-code-success` | `#34d399` | Estado correcto |

### 2.3 Semánticos

| Token | Referencia | Rol |
| --- | --- | --- |
| `--surface-canvas` | `--color-abyss` | Fondo principal |
| `--surface-subtle` | `--color-void` | Sección alterna |
| `--surface-raised` | `--color-deep-sea` | Tarjeta elevada |
| `--surface-spotlight` | `--color-cobalt-panel` | Tarjeta destacada |
| `--surface-terminal` | `--color-terminal` | Código |
| `--text-primary` | `--color-quartz` | Texto principal |
| `--text-secondary` | `--color-ash` | Párrafos |
| `--text-tertiary` | `--color-mist` | Metadatos |
| `--border-hairline` | `--color-obsidian-edge` | Borde exterior |
| `--border-inner` | `--color-inkline` | Divisor interno |
| `--border-spotlight` | `--color-sapphire-hairline` | Tarjeta destacada |
| `--border-focus` | `--color-focus` | Foco |
| `--action-primary` | `--color-pulse-navy` | Botón primario |
| `--action-primary-hover` | `#172554` | Hover primario |
| `--action-signal` | `--color-signal-blue` | Acción de acento |
| `--action-signal-hover` | `--color-signal-strong` | Hover de acento |
| `--accent-gold` | `--color-unsxx-gold` | Acreditación y logros |

### 2.4 Proporción y contraste

- 70 % lienzo y superficies neutras; 20 % tintas; 7 % azul; 3 % dorado.
- El dorado `#eab308` no se usa como texto sobre blanco (1.92:1). Solo relleno, borde o icono decorativo con texto oscuro encima.
- `#64748b` no se usa sobre `#f1f5fb` ni `#eff6ff` para texto normal. Usar `#475569`.
- Contraste mínimo: 4.5:1 para cuerpo; 3:1 para texto grande y elementos no textuales.
- El estado no se comunica solo por color.

### 2.5 Auroras

```css
--gradient-aurora-blue: radial-gradient(75% 90% at 38% -45%, rgba(37, 99, 235, 0.14) 0, rgba(255, 255, 255, 0) 100%);
--gradient-aurora-gold: radial-gradient(32% 26% at 74% 100%, rgba(234, 179, 8, 0.15) 0, rgba(255, 255, 255, 0) 100%);
```

Máximo dos auroras por página, solo en hero o cabecera ejecutiva. Prohibido el texto con gradiente y los fondos de tarjeta con gradiente.

> El conjunto completo de tokens —estados suaves, código, espacio y z-index— está en `design-system/MASTER.md`.

## 3. Tipografía

| Rol | Familia | Uso |
| --- | --- | --- |
| Display | **Figtree** | Titulares, secciones, cifras grandes |
| UI y cuerpo | **Inter** | Navegación, párrafos, botones, formularios |
| Técnica | **IBM Plex Mono** | Siglas, cargas, fechas, metadatos, código |

### 3.1 Escala

| Token | Familia | Tamaño | Interlineado | Tracking | Peso | Uso |
| --- | --- | --- | --- | --- | --- | --- |
| `--type-display-xl` | Figtree | `clamp(2.75rem, 6vw, 4rem)` | `1.02` | `-0.025em` | 600 | Hero de Home |
| `--type-display-lg` | Figtree | `clamp(2.25rem, 4.5vw, 3.25rem)` | `1.05` | `-0.025em` | 600 | Cabecera de página |
| `--type-h2` | Figtree | `clamp(1.75rem, 3vw, 2.5rem)` | `1.10` | `-0.020em` | 600 | Título de sección |
| `--type-h3` | Figtree | `clamp(1.25rem, 2vw, 1.625rem)` | `1.20` | `-0.015em` | 600 | Título de tarjeta |
| `--type-h4` | Figtree | `1.125rem` | `1.30` | `-0.010em` | 600 | Subtítulo |
| `--type-body-lg` | Inter | `1.125rem` | `1.65` | `-0.010em` | 400 | Bajada de hero |
| `--type-body` | Inter | `1rem` | `1.60` | `-0.015em` | 400 | Cuerpo |
| `--type-body-sm` | Inter | `0.875rem` | `1.55` | `-0.010em` | 400 | Metadatos y navegación |
| `--type-caption` | Inter | `0.75rem` | `1.40` | `0` | 400 | Pie de foto |
| `--type-eyebrow` | Inter | `0.75rem` | `1.40` | `0.04em` | 600 | Etiqueta corta |
| `--type-mono` | IBM Plex Mono | `0.8125rem` | `1.50` | `0` | 400 | Código y tablas |
| `--type-mono-sm` | IBM Plex Mono | `0.6875rem` | `1.45` | `0.02em` | 500 | Siglas y cargas |
| `--type-mono-xs` | IBM Plex Mono | `0.625rem` | `1.40` | `0.04em` | 500 | Microetiqueta |

Reglas: un `<h1>` por página; titulares de dos líneas máximo en desktop; 60–75 caracteres por línea; no más de cinco tamaños visibles; sin texto con gradiente, sombra o cursiva decorativa; siglas siempre en mono.

## 4. Espacio, retícula y layout

- Contenedor máximo: **1200 px**.
- Gutters: 16 px móvil, 24 px tablet, 32 px desktop.
- Retícula: 12 columnas, gap 24 px desktop / 16 px móvil.
- Escala: `4, 8, 12, 16, 20, 24, 32, 40, 48, 56, 64, 80, 96, 120` px.
- Secciones: 80 px desktop / 56 px tablet / 40 px móvil.
- Hero desktop: split 5/7. Móvil: una columna, texto y CTA primero.
- Bento: spans `4/8`, `5/7`, `7/5`, `8/4`, `12`; máximo dos piezas dominantes.
- Cada sección tiene título y un elemento dominante; no hay media pantalla vacía.
- Dos secciones consecutivas no repiten composición.
- Breakpoints de prueba: 375, 768, 1024 y 1440 px.
- Header sticky de 64 px; footer de cuatro columnas.

## 5. Forma, borde y sombra

| Token | Valor | Uso |
| --- | --- | --- |
| `--radius-xs` | `4px` | Detalles internos |
| `--radius-sm` | `8px` | Inputs compactos, imágenes internas |
| `--radius-md` | `12px` | Tarjetas, dropdowns, código |
| `--radius-lg` | `16px` | Tarjetas grandes, modales |
| `--radius-pill` | `9999px` | Botones, badges, tabs |
| `--radius-circle` | `50%` | Avatares, botones de icono |

Regla concéntrica: 16 px exterior con 4 px de padding → 12 px interior; 12 px exterior con 4 px → 8 px interior. No anidar 16 px dentro de 12 px.

Bordes: hairline de 1 px `#dbe3f0`; divisor `#e2e8f0`; spotlight `#93c5fd`; foco 2 px `#2862d7` con offset 2 px.

Sombras:

```css
--shadow-card: 0 1px 2px rgba(11, 19, 43, 0.03), 0 8px 24px -4px rgba(11, 19, 43, 0.04);
--shadow-dropdown: 0 4px 12px rgba(11, 19, 43, 0.06), 0 16px 32px -8px rgba(11, 19, 43, 0.10);
--shadow-floating: 0 20px 35px rgba(13, 23, 43, 0.16), 0 4px 13px rgba(13, 23, 43, 0.08);
--shadow-modal: 0 24px 64px rgba(11, 19, 43, 0.18);
```

Solo flotan dropdown, modal, lightbox y ventana de código. Hover de tarjeta: elevar 2 px y cambiar borde, sin sombra dramática.

Z-index: header `40`, dropdown `50`, overlay `60`, modal `70`, lightbox `80`, toast `90`.

## 6. Movimiento

```css
--duration-press: 120ms;
--duration-hover: 180ms;
--duration-dropdown: 200ms;
--duration-modal: 240ms;
--duration-reveal: 480ms;
--duration-hero: 600ms;
--duration-shimmer: 1500ms;

--ease-out-expo: cubic-bezier(0.22, 1, 0.36, 1);
--ease-in-out: cubic-bezier(0.77, 0, 0.175, 1);
--ease-standard: cubic-bezier(0.2, 0, 0, 1);
```

Patrones: hero con `translateY(12px)` + fade 600 ms y stagger 80 ms; secciones con `translateY(16px)` + fade 480 ms y stagger 70 ms; botones con `scale(0.98)` 120 ms; dropdowns con fade + `translateY(-4px)` 180–200 ms; modales con fade + `translateY(8px)` 240 ms; acordeón con `grid-template-rows` 220 ms; shimmer de 1.5 s.

Reglas: solo `transform`, `opacity` y `filter`; prohibido `transition: all`; hover solo en `(hover: hover) and (pointer: fine)`; no scroll hijacking; no parallax en móvil; no autoplay con sonido; el contenido es visible sin JS; no GSAP ni librerías de animación; salida más rápida que entrada; `prefers-reduced-motion` obligatorio.

## 7. Componentes

- **Botones:** primario navy, señal azul, ghost, silencioso, peligro, enlace. Alturas 40/44/52 px; icono 44 × 44 px. Press `scale(0.98)`; foco visible; un solo primario por viewport. Texto con verbo.
- **Badges:** neutral, informativo, dorado, éxito, advertencia, peligro. Píldora, 12 px, padding `4px 12px`; dorado máximo una vez por viewport; no son botones.
- **Tarjetas:** `aql-card` 12 px/24 px; `aql-card-lg` 16 px/32 px; `aql-card-elevated`; `aql-card-spotlight`; `aql-card-terminal`. Título, descripción y metadatos; sin filas de tres tarjetas idénticas con icono.
- **Ventana de código:** terminal `#0d172b`, cabecera `#0b0c0e`, borde `#1e293b`, radio 12 px, mono 12–13 px, sombra flotante; máximo dos visibles; solo datos reales.
- **Header:** sticky 64 px, blanco 90 % + blur 12 px, hairline; logo 32 px; links Inter 14 px; dropdown 224 px, radio 12 px; nav móvil de dos columnas con ítems de 44 px; foco y teclado.
- **Footer:** fondo `#f8fafc`, borde superior, cuatro columnas, contacto real, sin newsletter.
- **Formularios:** label arriba; input 44 px, radio 10 px; foco con anillo; error con texto; checkbox/radio 18 px; resumen de errores; sin formularios falsos.
- **Tablas:** wrapper con scroll, caption, cabecera navy, filas 44–56 px, números en mono, hover `#f1f5fb`; en móvil scroll o ficha alternativa.
- **Acordeón:** borde hairline, radio 12 px, cabecera 56–64 px, activo `#2862d7`, `aria-expanded`, animación 220 ms; primer año abierto.
- **Tabs:** píldoras, máximo cuatro, activo navy, roles ARIA, flechas, fade 180 ms.
- **Breadcrumbs:** 13 px, separador `/`, `aria-current="page"`.
- **Paginación:** obligatoria en listados; 44 × 44 px; “Página X de Y”; sin scroll infinito.
- **Búsqueda/filtros:** input con label, máximo cuatro filtros, contador, limpiar, estado vacío, URL compartible.
- **Alertas/toasts:** info, éxito, advertencia, peligro; `role="status"`/`alert`; errores sin autodestrucción.
- **Modal/lightbox/PDF:** overlay `rgba(11,12,14,.75)` + blur; diálogo 12–16 px; cierre 44 px; focus trap; `Escape`; devolver foco; PDF con título/tipo/tamaño.
- **Skeletons:** reservar aspect ratio; shimmer 1.5 s; sin layout shift; con movimiento reducido, estático.
- **Estados vacíos:** titular, frase y acción; icono lineal; sin datos inventados.
- **Medios:** ratios `16/9`, `16/10`, `4/3`, `1/1`, `4/5`; `width`/`height`; lazy; alt; sin texto sobre imagen sin scrim; solo fotos reales.

## 8. Blueprints por ruta

El portal es **100 % multipágina**. La navegación principal no usa anclas de una sola página. Cero scroll infinito; paginación o enlace a archivo.

| Ruta | Trabajo | Secciones | Acción principal |
| --- | --- | --- | --- |
| `/` | Presentar la carrera y llevar a admisión o plan | Hero 5/7, prueba institucional, pilares, menciones, labs, comunicados/eventos, logros, CTA | Admisión |
| `/nosotros` | Identidad, historia y dirección | Cabecera, presentación, historia, misión/visión, autoridades, acreditación | Contacto |
| `/admision` | Convertir al aspirante | Cabecera con año, modalidades, requisitos, calendario, pasos, FAQ | WhatsApp / inscripción |
| `/titulacion` | Explicar la titulación | Cabecera, opciones, modalidades, requisitos, proceso, reglamentos, FAQ | Descargar reglamento |
| `/becas` | Mostrar beneficios y requisitos | Cabecera, tipos, elegibilidad, pasos, calendario, documentos, FAQ | Consultar / postular |
| `/contacto` | Facilitar contacto real | Cabecera, canales, dirección, horarios, mapa, soporte | WhatsApp / correo |
| `/plan-estudios` | Entregar la currícula completa | Cabecera, resumen, acordeón por año, tablas, electivas, PDF | Descargar plan |
| `/menciones` | Diferenciar menciones e IIDAI | Cabecera, dos paneles, líneas de investigación, labs, perfil | Conocer mención |
| `/plantel` | Presentar autoridades y docentes | Cabecera, autoridades, grilla docente, áreas | Contacto |
| `/documentos` | Encontrar y abrir documentos | Cabecera, búsqueda/filtros, categorías, listado, visor PDF, vacío | Abrir / descargar PDF |
| `/laboratorios` | Mostrar infraestructura real | Cabecera, tarjetas de labs, equipos, seguridad, galería | Conocer laboratorio |
| `/comunicados` | Publicar novedades oficiales | Cabecera, destacado, filtros, listado, paginación, archivo | Leer comunicado |
| `/eventos` | Difundir actividades | Cabecera, próximos, pasados, calendario, galería | Ver evento |
| `/logros` | Celebrar resultados verificables | Cabecera, filtros, timeline/grilla, detalle | Ver logro |

`/carrera` es alias de `/nosotros`; `/comunidad` es alias de `/comunicados`.

## 9. Contenido y voz

- Institucional, claro, cálido y preciso; “tú” para el aspirante, “la Carrera” en tercera persona para lo institucional.
- Sin “estimado usuario”, “bienvenido a nuestro sitio”, superlativos sin prueba ni relleno de marketing.
- Titulares de 5–10 palabras, dos líneas máximo en desktop, sin signos de exclamación.
- CTA con verbo + objeto: “Descargar el reglamento”, “Conoce el plan”, “Escríbenos”.
- Fechas: `25 de octubre de 2026` o `25/10/2026`; horas `08:30–12:30`; siglas `INF1100`, `CEUB`, `IIDAI`.
- PDF: título + tipo + tamaño.
- SEO: `<title>` único, meta description 150–160 caracteres, canonical, Open Graph, un `<h1>`, URLs limpias y datos estructurados.

## 10. Iconografía e imágenes

- SVG inline, trazo 1.8–2 px, 20/24 px, una sola familia; iconos decorativos `aria-hidden`; botones de icono con `aria-label` y 44 × 44 px.
- Sin emojis como iconos estructurales.
- Prioridad de imagen: fotografía institucional real > archivo con crédito > composición tipográfica.
- Sin fotos de stock que simulen campus, laboratorios o estudiantes que no existen.
- WebP/JPEG optimizado; máximo 300 KB tarjetas, 500 KB hero; `width`, `height`, `alt`, `loading`, `decoding`.
- Logo oficial sin recolorear, deformar ni recortar.
- Dorado de laureles solo para acreditación y logros.

## 11. Accesibilidad

- WCAG 2.2 AA; `lang="es"`; skip link; landmarks; un `<h1>`; jerarquía sin saltos.
- Enlaces y botones reales; foco visible; objetivos táctiles 44 × 44 px.
- Teclado: dropdowns, tabs, acordeón, modal y lightbox; `Escape` cierra; focus trap; foco vuelve al disparador.
- Formularios con label, ayuda, error y resumen; validación en servidor.
- `prefers-reduced-motion`; contenido visible sin JS; sin scroll hijacking.
- Alt text; PDFs con título/tipo/tamaño; tablas con `caption` y `th`.
- Sin color como único indicador; sin autoplay con sonido.

## 12. Implementación

- Tokens en `public/assets/css/agentql-unsxx.css`; fuentes y Tailwind en `src/Views/layouts/public.php`; navegación en `partials/navbar.php`; cierre en `partials/footer.php`; interacciones en `public/assets/js/portal.js`.
- Vistas nuevas usan clases `.aql-*` y tokens CSS; no repetir hex arbitrarios.
- Tailwind: extender `fontFamily`, `colors`, `borderRadius`, `boxShadow`, `transitionTimingFunction` cuando se configure; mientras tanto, tokens CSS.
- Reemplazar `transition: all` por propiedades específicas.
- Añadir `prefers-reduced-motion` a toda animación nueva.
- No agregar dependencias nuevas para UI pública; no GSAP.
- Cambiar anclas heredadas (`/carrera#...`, `/comunidad#...`) a rutas multipágina al reemplazar vistas.
- No usar `dark:` como tema activo; las clases existentes se conservan por compatibilidad.
- No sobrescribir `home.php` ni `carrera.php` en la fase documental.

## 13. Anti-patrones

Hero centrado con tres tarjetas de iconos; filas de tarjetas idénticas; texto gris claro; dorado como texto sobre blanco; gradientes en texto/tarjetas/fondos; glassmorphism en tarjetas; sombras dramáticas en reposo; bordes gruesos decorativos; emojis estructurales; navegación solo con iconos; scroll infinito; carrusel automático; datos o testimonios inventados; “Lorem ipsum”; texto sobre imagen sin scrim; más de un CTA primario o un dorado por viewport; más de dos ventanas de código; `transition: all`; parallax en móvil; modal sin focus trap; tabla sin caption; PDF sin metadatos; página sin `<h1>`; “haz clic aquí”; fotos de stock que simulan la carrera; modo oscuro público no aprobado.

## 14. QA mínimo

- [ ] Lienzo blanco; secciones alternas `#f8fafc`.
- [ ] Dorado máximo una vez por viewport.
- [ ] Tarjetas 12–16 px con hairline; botones y badges píldora.
- [ ] Titulares de dos líneas máximo en desktop.
- [ ] Cada sección tiene título; no hay media pantalla vacía; márgenes coinciden.
- [ ] Probado a 375, 768, 1024 y 1440 px; sin overflow no intencional.
- [ ] Skip link, un `<h1>`, foco visible, teclado, `Escape`, focus trap.
- [ ] Contraste AA; `prefers-reduced-motion`; formularios accesibles; imágenes con alt.
- [ ] Sin `transition: all`; UI bajo 300 ms; contenido visible sin JS.
- [ ] Sin datos inventados; fechas/siglas con formato; PDFs con título/tipo/tamaño.
- [ ] Sin dependencias nuevas; rutas multipágina; sin anclas heredadas.
- [ ] `php -l` pasa; sin warnings PHP; `DESIGN.md` sincronizado con `design-system/MASTER.md`.

## 15. Gobernanza

- `design-system/MASTER.md` es la fuente de verdad; este archivo es la referencia rápida sincronizada.
- Todo cambio de token, componente o regla se actualiza en ambos archivos.
- `agentql-unsxx.css` implementa los tokens; si cambia un token, se actualiza el CSS.
- Las excepciones se documentan con una razón verificable.
- Revisión recomendada antes de cada fase de vistas y al cerrar una migración.

**Última sincronización:** 2026-10-25 · **Versión:** 1.0.
