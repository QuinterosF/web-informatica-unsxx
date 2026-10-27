# Sistema de Diseño Maestro — Portal Web Público Ingeniería Informática UNSXX

> **Fuente de verdad:** este archivo.  
> **Referencia sincronizada:** `DESIGN.md` en la raíz del proyecto.  
> **Versión:** 1.0 · **Fecha:** 2026-10-25 · **Estado:** activo.  
> **Alcance:** portal público multipágina de la Carrera de Ingeniería Informática, Universidad Nacional “Siglo XX”.  
> **Stack:** PHP 8 nativo, Tailwind CSS por CDN, CSS propio y JavaScript vanilla. Sin Composer, sin npm, sin build, sin GSAP.

---

## 0. Cómo usar este documento

- Leerlo antes de crear o modificar cualquier vista pública.
- No inventar tokens, componentes, rutas ni requisitos. Si algo no está aquí, se propone y se aprueba antes de implementarlo.
- `design-system/MASTER.md` manda. `DESIGN.md` debe contener los mismos valores y reglas; si hay conflicto, gana este archivo y se corrige `DESIGN.md`.
- La implementación actual vive en `public/assets/css/agentql-unsxx.css`, `src/Views/layouts/public.php`, `src/Views/layouts/partials/` y `public/assets/js/portal.js`.
- Esta versión es documental: **no autoriza todavía modificar vistas PHP**. La migración visual se hace en una fase posterior.
- Las vistas existentes `src/Views/public/home.php` y `src/Views/public/carrera.php` son la referencia visual rica. No se sobrescriben sin autorización.

---

## 1. Norte de diseño

### 1.1 Idea rectora

**“AgentQL Daylight Institutional”.**

La precisión técnica de una herramienta para desarrolladores, la claridad editorial de un sitio académico y la gravedad institucional de una carrera universitaria, todo sobre un lienzo claro, luminoso y de alta gama. No es una plantilla universitaria genérica: es un portal técnico, sobrio y verificable.

Referencias de calibración visual: **AgentQL** para la composición y las ventanas técnicas; **Linear Light**, **Vercel Light** y **Raycast** para la limpieza, el contraste y la sensación premium del tema claro.

La página debe sentirse como un sistema, no como un collage:

- estructura visible y racional;
- jerarquía tipográfica fuerte;
- superficies limpias con hairlines de 1 px;
- ventanas técnicas oscuras como acento, nunca como tema principal;
- dorado reservado para acreditación y logros;
- movimiento contenido, rápido y funcional.

### 1.2 Principios

1. **Precisión técnica:** cada dato, sigla, tabla y documento se presenta con claridad de manual.
2. **Gravedad institucional:** la universidad y la carrera tienen 25 años de trayectoria; el diseño no se infantiliza.
3. **Evidencia sobre decoración:** fotografías reales, datos reales, documentos reales. Nada de métricas inventadas.
4. **Multipágina completa:** cada tema tiene su URL, su título, su contenido y su cierre. Cero scroll infinito.
5. **Movimiento con propósito:** el movimiento explica jerarquía, confirma acciones y evita saltos; no entretiene.
6. **Accesibilidad por defecto:** contraste, teclado, foco visible, movimiento reducido y objetivos táctiles de 44 px.

### 1.3 Diales del sistema

| Dial | Valor | Interpretación |
| --- | --- | --- |
| `DESIGN_VARIANCE` | `4/10` | Composición moderna y asimétrica dentro de una retícula estricta. Bento controlado, nunca caos. |
| `MOTION_INTENSITY` | `7/10` | Movimiento estándar-alto: entrada de hero, revelados de sección, microinteracciones, shimmer. Sin coreografías complejas ni scroll hijacking. |
| `VISUAL_DENSITY` | `4/10` | Aire generoso, contenedor de 1200 px, secciones separadas por 80 px. La densidad sube solo en tablas y documentos. |

### 1.4 Lo que este sistema no es

- No es un tema oscuro. El portal público es **light-first**.
- No es una landing page de una sola pantalla.
- No es una colección de tarjetas idénticas con iconos.
- No es una interfaz glassmorphism.
- No es una página de startup con gradientes por todas partes.
- No es un catálogo de animaciones.

---

## 2. Color

### 2.1 Tokens primitivos

| Token | Valor | Uso |
| --- | --- | --- |
| `--color-abyss` | `#ffffff` | Lienzo principal, tarjetas base |
| `--color-void` | `#f8fafc` | Secciones alternas, footer, fondos suaves |
| `--color-deep-sea` | `#f1f5fb` | Superficie elevada, hover de navegación, inputs deshabilitados |
| `--color-cobalt-panel` | `#eff6ff` | Tarjeta spotlight, badges informativos |
| `--color-terminal` | `#0d172b` | Ventanas de código, terminal, superficie ejecutiva |
| `--color-terminal-header` | `#0b0c0e` | Barra superior de ventanas de código |
| `--color-quartz` | `#0b132b` | Texto primario, tinta principal |
| `--color-ash` | `#475569` | Texto secundario, párrafos |
| `--color-mist` | `#64748b` | Metadatos, texto terciario |
| `--color-pulse-navy` | `#1e3a8a` | Botón primario, encabezados clave, navy institucional |
| `--color-signal-blue` | `#2862d7` | Acento principal, enlaces, iconos, foco |
| `--color-signal-strong` | `#1d4ed8` | Enlaces y acentos de mayor contraste |
| `--color-signal-soft` | `#3b82f6` | Acento secundario, estados hover |
| `--color-unsxx-gold` | `#eab308` | Acreditación, logros, medallas |
| `--color-unsxx-gold-bright` | `#facc15` | Dorado sobre superficies oscuras |
| `--color-obsidian-edge` | `#dbe3f0` | Hairline de tarjetas y contenedores |
| `--color-inkline` | `#e2e8f0` | Bordes internos y divisores |
| `--color-sapphire-hairline` | `#93c5fd` | Borde de tarjetas spotlight |
| `--color-border-strong` | `#94a3b8` | Borde de botón ghost y controles secundarios |
| `--color-success` | `#047857` | Texto de éxito sobre claro |
| `--color-success-soft` | `#ecfdf5` | Fondo de éxito |
| `--color-warning` | `#b45309` | Texto de advertencia sobre claro |
| `--color-warning-soft` | `#fffbeb` | Fondo de advertencia |
| `--color-danger` | `#b91c1c` | Texto de error sobre claro |
| `--color-danger-soft` | `#fef2f2` | Fondo de error |
| `--color-info` | `#0369a1` | Texto informativo sobre claro |
| `--color-info-soft` | `#f0f9ff` | Fondo informativo |
| `--color-focus` | `#2862d7` | Anillo de foco en claro |
| `--color-focus-dark` | `#facc15` | Anillo de foco en oscuro |
| `--color-code-key` | `#ff7dda` | Claves en ventanas de código |
| `--color-code-value` | `#28b6ff` | Valores y tokens |
| `--color-code-comment` | `#8798c1` | Comentarios |
| `--color-code-accent` | `#85a6e9` | Acentos técnicos |
| `--color-code-success` | `#34d399` | Estado correcto en código |

> `--color-pulse-violet` y `--color-frosted-lilac` existen en el CSS actual por herencia. Se mantienen como alias de compatibilidad, pero el nombre canónico es `--color-pulse-navy` y `--color-signal-soft`.

### 2.2 Tokens semánticos

| Token | Referencia | Rol |
| --- | --- | --- |
| `--surface-canvas` | `--color-abyss` | Fondo principal |
| `--surface-subtle` | `--color-void` | Sección alterna |
| `--surface-raised` | `--color-deep-sea` | Tarjeta elevada, hover |
| `--surface-spotlight` | `--color-cobalt-panel` | Tarjeta destacada |
| `--surface-terminal` | `--color-terminal` | Código, terminal |
| `--surface-terminal-header` | `--color-terminal-header` | Cabecera de código |
| `--surface-executive` | `--color-terminal` | Cabecera ejecutiva opcional |
| `--text-primary` | `--color-quartz` | Texto principal |
| `--text-secondary` | `--color-ash` | Párrafos y texto secundario |
| `--text-tertiary` | `--color-mist` | Metadatos sobre blanco o `--color-void` |
| `--text-on-dark` | `#ffffff` | Texto sobre terminal o navy |
| `--text-on-dark-muted` | `#c7c9d1` | Texto secundario sobre terminal |
| `--text-on-accent` | `#ffffff` | Texto sobre azul de acción |
| `--text-on-gold` | `--color-quartz` | Texto sobre dorado |
| `--border-hairline` | `--color-obsidian-edge` | Borde exterior de tarjetas |
| `--border-inner` | `--color-inkline` | Divisores internos |
| `--border-spotlight` | `--color-sapphire-hairline` | Tarjeta destacada |
| `--border-focus` | `--color-focus` | Foco visible |
| `--action-primary` | `--color-pulse-navy` | Botón primario |
| `--action-primary-hover` | `#172554` | Hover del primario |
| `--action-signal` | `--color-signal-blue` | Acción de acento |
| `--action-signal-hover` | `--color-signal-strong` | Hover de acento |
| `--action-quiet` | `transparent` | Acción silenciosa |
| `--action-quiet-hover` | `--color-deep-sea` | Hover de acción silenciosa |
| `--accent-gold` | `--color-unsxx-gold` | Acreditación y logros |
| `--accent-gold-bright` | `--color-unsxx-gold-bright` | Dorado sobre oscuro |

### 2.3 Proporción cromática

- **70 %** lienzo blanco y superficies neutras.
- **20 %** tintas y grises azulados.
- **7 %** azul institucional.
- **3 %** dorado y acentos.
- El dorado no compite con el azul: aparece en un badge, un borde, un icono o un detalle por viewport.
- El terminal oscuro ocupa como máximo una composición del hero, una cabecera ejecutiva o un bloque de código. Nunca una página completa.

### 2.4 Contraste verificado

| Combinación | Ratio | Uso permitido |
| --- | ---: | --- |
| `#0b132b` sobre `#ffffff` | 18.38:1 | Texto principal |
| `#475569` sobre `#ffffff` | 7.58:1 | Texto secundario |
| `#64748b` sobre `#ffffff` | 4.76:1 | Metadatos desde 12 px |
| `#64748b` sobre `#f8fafc` | 4.55:1 | Metadatos desde 12 px |
| `#64748b` sobre `#f1f5fb` | 4.35:1 | **No usar para texto normal**; usar `#475569` |
| `#2862d7` sobre `#ffffff` | 5.50:1 | Enlaces y acentos |
| `#1d4ed8` sobre `#ffffff` | 6.70:1 | Enlaces de énfasis |
| `#ffffff` sobre `#1e3a8a` | 10.36:1 | Botón primario |
| `#ffffff` sobre `#2862d7` | 5.50:1 | Botón de acento |
| `#ffffff` sobre `#0d172b` | 17.88:1 | Texto sobre terminal |
| `#c7c9d1` sobre `#0d172b` | 10.82:1 | Texto secundario en terminal |
| `#85a6e9` sobre `#0d172b` | 7.35:1 | Acento técnico en terminal |
| `#facc15` sobre `#0d172b` | 11.67:1 | Dorado sobre oscuro |
| `#0b132b` sobre `#eab308` | 9.32:1 | Texto sobre badge dorado |
| `#047857` sobre `#ffffff` | 5.48:1 | Éxito |
| `#b45309` sobre `#ffffff` | 5.02:1 | Advertencia |
| `#b91c1c` sobre `#ffffff` | 6.47:1 | Error |

Reglas:

- El dorado `#eab308` **no se usa como texto sobre blanco** (1.92:1). Solo como relleno, borde o icono decorativo con texto oscuro encima.
- El gris `#64748b` no se usa sobre `#f1f5fb` ni `#eff6ff` para texto normal.
- Todo texto de cuerpo debe llegar a 4.5:1; los textos grandes (≥ 24 px o ≥ 18.66 px en negrita) pueden llegar a 3:1, pero no se usa esa excepción para ahorrar contraste.
- Los estados no se comunican solo por color: siempre hay texto, icono o patrón.

### 2.5 Auroras y gradientes

Las auroras son fondo del hero, no decoración de tarjetas:

```css
--gradient-aurora-blue: radial-gradient(75% 90% at 38% -45%, rgba(37, 99, 235, 0.14) 0, rgba(255, 255, 255, 0) 100%);
--gradient-aurora-gold: radial-gradient(32% 26% at 74% 100%, rgba(234, 179, 8, 0.15) 0, rgba(255, 255, 255, 0) 100%);
```

- Máximo dos auroras por página, solo en el hero o en una cabecera ejecutiva.
- Prohibido el texto con gradiente.
- Prohibidos los fondos de tarjeta con gradiente.
- El botón primario es plano por defecto. Se permite un único CTA con gradiente navy → signal si mantiene texto blanco y no se repite en la misma pantalla.

---

## 3. Tipografía

### 3.1 Familias

| Rol | Familia | Uso |
| --- | --- | --- |
| Display | **Figtree** | Titulares, nombres de sección, cifras grandes |
| UI y cuerpo | **Inter** | Navegación, párrafos, botones, formularios |
| Técnica | **IBM Plex Mono** | Siglas, cargas horarias, fechas, metadatos, código |

Son dos familias de texto más una mono técnica. No se agrega una cuarta familia.

### 3.2 Escala

| Token | Familia | Tamaño | Interlineado | Tracking | Peso | Uso |
| --- | --- | --- | --- | --- | --- | --- |
| `--type-display-xl` | Figtree | `clamp(2.75rem, 6vw, 4rem)` | `1.02` | `-0.025em` | 600 | Hero de Home |
| `--type-display-lg` | Figtree | `clamp(2.25rem, 4.5vw, 3.25rem)` | `1.05` | `-0.025em` | 600 | Cabecera de página |
| `--type-h2` | Figtree | `clamp(1.75rem, 3vw, 2.5rem)` | `1.10` | `-0.020em` | 600 | Título de sección |
| `--type-h3` | Figtree | `clamp(1.25rem, 2vw, 1.625rem)` | `1.20` | `-0.015em` | 600 | Título de tarjeta |
| `--type-h4` | Figtree | `1.125rem` | `1.30` | `-0.010em` | 600 | Subtítulo |
| `--type-body-lg` | Inter | `1.125rem` | `1.65` | `-0.010em` | 400 | Bajada de hero |
| `--type-body` | Inter | `1rem` | `1.60` | `-0.015em` | 400 | Cuerpo |
| `--type-body-sm` | Inter | `0.875rem` | `1.55` | `-0.010em` | 400 | Metadatos, navegación |
| `--type-caption` | Inter | `0.75rem` | `1.40` | `0` | 400 | Pie de foto, notas |
| `--type-eyebrow` | Inter | `0.75rem` | `1.40` | `0.04em` | 600 | Etiqueta corta en mayúsculas |
| `--type-mono` | IBM Plex Mono | `0.8125rem` | `1.50` | `0` | 400 | Código, tablas técnicas |
| `--type-mono-sm` | IBM Plex Mono | `0.6875rem` | `1.45` | `0.02em` | 500 | Siglas, cargas, badges |
| `--type-mono-xs` | IBM Plex Mono | `0.625rem` | `1.40` | `0.04em` | 500 | Microetiqueta técnica |

### 3.3 Reglas

- Un solo `<h1>` por página. La jerarquía no salta niveles.
- Titulares de dos líneas como máximo en desktop; si no cabe, se acorta el texto antes de bajar el tamaño.
- El ancho de lectura ideal es de 60–75 caracteres. La bajada del hero no supera 58 caracteres por línea.
- No se usa mayúscula sostenida en párrafos. Solo en eyebrows y microetiquetas mono.
- No se usa texto con gradiente, sombra, contorno o cursiva decorativa.
- No se usan más de cinco tamaños visibles por página.
- Los números de estadísticas usan Figtree; las unidades y etiquetas usan mono.
- Las siglas de asignaturas (`INF1100`) siempre van en IBM Plex Mono.
- El tracking negativo se aplica solo a display y headings; el cuerpo mantiene `-0.015em`.

### 3.4 Carga de fuentes

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Figtree:wght@500;600;700&family=IBM+Plex+Mono:wght@400;500&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
```

- `display=swap` obligatorio.
- Fallbacks: `ui-sans-serif, system-ui, -apple-system, sans-serif` para Figtree/Inter; `ui-monospace, SFMono-Regular, monospace` para IBM Plex Mono.
- No se cargan pesos que no se usen.

---

## 4. Espacio, retícula y layout

### 4.1 Contenedor y retícula

- Ancho máximo: **1200 px**.
- Gutters: 16 px en móvil, 24 px en tablet, 32 px en desktop.
- Retícula: 12 columnas, gap de 24 px en desktop y 16 px en móvil.
- La retícula es estricta; la asimetría vive dentro de ella, nunca en los márgenes exteriores.
- Los márgenes izquierdo y derecho siempre coinciden.

### 4.2 Escala de espacio

`4, 8, 12, 16, 20, 24, 32, 40, 48, 56, 64, 80, 96, 120` px.

| Uso | Valor |
| --- | --- |
| Gap interno de componentes | `8–16px` |
| Padding de tarjeta | `24px` |
| Padding de tarjeta grande | `32px` |
| Separación entre bloques dentro de una sección | `24–40px` |
| Separación entre secciones | `80px` desktop / `56px` tablet / `40px` móvil |
| Separación hero → primera sección | `80–96px` |

### 4.3 Reglas de sección

- Cada sección tiene un título que la nombra. No hay secciones sin encabezado.
- Cada sección tiene un elemento dominante: titular, imagen, tabla, composición técnica o dato.
- No se deja media pantalla vacía. Si el texto ocupa la mitad izquierda, la derecha lleva imagen, dato, segunda columna o figura.
- Dos secciones consecutivas no repiten la misma composición.
- El fondo alterna entre `--surface-canvas` y `--surface-subtle`; no se alterna cada 200 px.
- La primera sección después del hero no repite el hero.

### 4.4 Hero

- Desktop: split 5/7. Texto a la izquierda, composición técnica a la derecha.
- Móvil: una columna; el titular y el CTA van primero, la composición técnica después.
- El hero incluye:
  - dos insignias máximo (acreditación y trayectoria);
  - un `<h1>` de dos líneas máximo;
  - una bajada de una o dos frases;
  - un CTA primario y uno secundario;
  - una composición de ventanas técnicas o una imagen institucional.
- Las pestañas del hero son máximo tres y cambian el panel sin recargar la página.
- En móvil, las pestañas se desplazan horizontalmente; no se ocultan.

### 4.5 Bento controlado

- Usar 12 columnas con spans `4/8`, `5/7`, `7/5`, `8/4` o `12`.
- Una composición bento tiene como máximo dos piezas dominantes y dos secundarias.
- No se usa una fila de tres tarjetas idénticas como solución por defecto.
- Las tarjetas pueden tener alturas distintas, pero comparten baseline y alineación.
- El bento no se usa para tablas ni para listados densos.

### 4.6 Responsive

| Breakpoint | Comportamiento |
| --- | --- |
| `< 640px` | Una columna, gutters 16 px, hero apilado, tablas con scroll horizontal, nav móvil de dos columnas |
| `640–767px` | Una columna con bloques de dos columnas solo para datos cortos |
| `768–1023px` | Dos columnas, hero aún apilado, bento simplificado |
| `1024–1279px` | Split 5/7 activo, nav desktop, tres columnas en listados |
| `≥ 1280px` | Contenedor 1200 px, composición completa |

Reglas:

- Probar a 375, 768, 1024 y 1440 px.
- Ningún texto esencial se oculta en móvil; se reorganiza.
- Las tablas no se comprimen hasta ser ilegibles: scroll horizontal con indicador visual o conversión a fichas.
- Los objetivos táctiles miden al menos 44 × 44 px.
- No hay hover como única vía para acceder a contenido.

### 4.7 Navegación y footer

- Header sticky de 64 px, fondo blanco translúcido, blur de 12 px y hairline inferior.
- Logo de 32 px + wordmark Figtree 16 px + sublabel mono 10 px.
- Máximo cinco grupos de navegación de primer nivel.
- El grupo activo usa tinta primaria y peso 500; no se usa subrayado grueso.
- Dropdown de 224 px, radio 12 px, padding 6 px, sombra de dropdown.
- Footer de cuatro columnas: identidad, enlaces académicos, contacto y barra inferior.
- El footer no es un sitemap completo; es un cierre diseñado.

---

## 5. Forma, borde y sombra

### 5.1 Radios

| Token | Valor | Uso |
| --- | --- | --- |
| `--radius-xs` | `4px` | Detalles internos, chips pequeños |
| `--radius-sm` | `8px` | Inputs compactos, imágenes internas |
| `--radius-md` | `12px` | Tarjetas, dropdowns, ventanas de código |
| `--radius-lg` | `16px` | Tarjetas grandes, modales, media destacada |
| `--radius-pill` | `9999px` | Botones, badges, pestañas, filtros |
| `--radius-circle` | `50%` | Avatares, botones de icono |

Regla concéntrica:

- Un contenedor de 16 px con padding de 4 px lleva un hijo de 12 px.
- Un contenedor de 12 px con padding de 4 px lleva un hijo de 8 px.
- No se anida un radio de 16 px dentro de uno de 12 px.

### 5.2 Bordes

- Hairline estándar: `1px solid var(--border-hairline)`.
- Divisor interno: `1px solid var(--border-inner)`.
- Spotlight: `1px solid var(--border-spotlight)`.
- Foco: `2px solid var(--border-focus)` con `outline-offset: 2px`.
- No se usan bordes de 2–3 px como decoración.
- En superficies oscuras, el borde es `#1e293b` o `#172540`.

### 5.3 Sombras

```css
--shadow-card:
  0 1px 2px rgba(11, 19, 43, 0.03),
  0 8px 24px -4px rgba(11, 19, 43, 0.04);

--shadow-dropdown:
  0 4px 12px rgba(11, 19, 43, 0.06),
  0 16px 32px -8px rgba(11, 19, 43, 0.10);

--shadow-floating:
  0 20px 35px rgba(13, 23, 43, 0.16),
  0 4px 13px rgba(13, 23, 43, 0.08);

--shadow-modal:
  0 24px 64px rgba(11, 19, 43, 0.18);
```

Reglas:

- La tarjeta en reposo usa `--shadow-card`, no una sombra dramática.
- El hover cambia borde y, como máximo, eleva 2 px. No se salta a una sombra grande.
- Solo flotan: dropdown, modal, lightbox, ventana de código del hero.
- Prohibidas las sombras de color, los glows y las sombras en texto.

### 5.4 Z-index

| Capa | Valor |
| --- | --- |
| Contenido base | `0` |
| Header sticky | `40` |
| Dropdown | `50` |
| Overlay | `60` |
| Modal / PDF | `70` |
| Lightbox | `80` |
| Toast | `90` |

---

## 6. Movimiento

### 6.1 Tokens

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

La curva principal es `cubic-bezier(0.22, 1, 0.36, 1)`. No se usa `ease-in` en UI.

### 6.2 Patrones

| Patrón | Especificación |
| --- | --- |
| Entrada de hero | Paneles con `opacity 0 → 1` y `translateY(12px) → 0`, 600 ms, stagger 80 ms. |
| Ventanas de código | Asentamiento de 8 px, sin zoom; la ventana de atrás no se mueve más que la de adelante. |
| Revelado de sección | `opacity 0 → 1`, `translateY(16px) → 0`, 480 ms, stagger 70 ms, una sola vez. |
| Hover de tarjeta interactiva | `translateY(-2px)`, cambio de borde, 180 ms. |
| Press de botón | `scale(0.98)`, 120 ms. |
| Dropdown | `opacity 0 → 1`, `translateY(-4px) → 0`, `scale(0.98) → 1`, 180–200 ms, origen superior. |
| Modal | `opacity 0 → 1`, `translateY(8px) → 0`, `scale(0.98) → 1`, 240 ms; salida 160 ms. |
| Acordeón | `grid-template-rows: 0fr → 1fr`, 220 ms; el chevron rota 180°. |
| Tabs | Crossfade de contenido 180 ms; no se desliza si cambia la altura. |
| Skeleton | Shimmer de 1.5 s linear infinite; sin cambio de layout. |
| Lightbox | Imagen con fade 200 ms; sin zoom mayor a 1.02. |

### 6.3 Reglas

- Solo se animan `transform`, `opacity` y `filter`. No se animan `width`, `height`, `top`, `left` ni `box-shadow` salvo el acordeón con grid.
- Prohibido `transition: all`.
- Hover solo dentro de `@media (hover: hover) and (pointer: fine)`.
- No se animan acciones de teclado repetitivas.
- No hay scroll hijacking, parallax en móvil, autoplay de video con sonido ni marquesinas infinitas.
- El contenido debe ser visible sin JavaScript. Las animaciones de entrada no pueden dejar contenido oculto si el script falla.
- El movimiento se implementa con CSS y `IntersectionObserver`; no se agrega GSAP ni una librería de animación.
- La salida es más rápida que la entrada.
- El foco visible no se anima ni se desvanece.

### 6.4 Movimiento reducido

```css
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    scroll-behavior: auto !important;
    transition-duration: 0.01ms !important;
  }
}
```

- Con movimiento reducido, las entradas se convierten en fade de 150 ms o aparecen directamente.
- El shimmer se detiene y queda un fondo estático.
- El acordeón y los tabs cambian sin desplazamiento.
- El smooth scroll del `html` se desactiva.

---

## 7. Componentes

### 7.1 Botones

| Variante | Fondo | Texto | Borde | Hover | Uso |
| --- | --- | --- | --- | --- | --- |
| Primario | `--action-primary` | blanco | navy | `--action-primary-hover` | Acción principal |
| Señal | `--action-signal` | blanco | signal | `--action-signal-hover` | Énfasis secundario |
| Ghost | transparente | `--text-primary` | `--color-border-strong` | borde navy + `--surface-raised` | Acción secundaria |
| Silencioso | transparente | `--text-secondary` | transparente | `--surface-raised` | Acción terciaria |
| Peligro | `--color-danger` | blanco | danger | `#991b1b` | Eliminar, cancelar irreversible |
| Enlace | transparente | `--color-signal-strong` | ninguno | subrayado | Navegación textual |

Tamaños:

| Tamaño | Altura mínima | Padding | Fuente |
| --- | ---: | --- | --- |
| `sm` | `40px` (44 en móvil) | `8px 16px` | `13px` |
| `md` | `44px` | `10px 22px` | `15px` |
| `lg` | `52px` | `14px 28px` | `16px` |
| Icono | `44 × 44px` | centrado | `20px` |

Estados:

- `:hover`: cambio de color o borde, 180 ms.
- `:active`: `scale(0.98)`, 120 ms.
- `:focus-visible`: anillo de 2 px + offset de 2 px.
- `:disabled`: opacidad 0.5, `cursor: not-allowed`, sin hover.
- `loading`: spinner de 16 px + texto “Guardando…” o “Enviando…”; no se cambia el ancho.
- Un solo botón primario por viewport. El resto son ghost o silenciosos.
- El texto del botón empieza con verbo: “Conoce el plan”, “Descarga el reglamento”, “Escríbenos”.

### 7.2 Badges y píldoras

| Variante | Fondo | Texto | Borde | Uso |
| --- | --- | --- | --- | --- |
| Neutral | `--surface-raised` | `--text-secondary` | `--border-hairline` | Categoría, estado neutro |
| Informativo | `--surface-spotlight` | `--color-pulse-navy` | `--border-spotlight` | Mención, tipo de documento |
| Dorado | `--accent-gold` | `--text-on-gold` | `#ca8a04` | Acreditación, logro |
| Éxito | `--color-success-soft` | `--color-success` | `#a7f3d0` | Aprobado, vigente |
| Advertencia | `--color-warning-soft` | `--color-warning` | `#fde68a` | Pendiente, atención |
| Peligro | `--color-danger-soft` | `--color-danger` | `#fecaca` | Error, vencido |

- Radio `9999px`, altura 24–28 px, padding `4px 12px`, fuente 12 px.
- La variante mono usa IBM Plex Mono 11 px para siglas y códigos.
- El dorado se usa como máximo una vez por viewport.
- No se usan badges como botones.

### 7.3 Tarjetas

| Componente | Especificación |
| --- | --- |
| `aql-card` | Blanco, borde hairline, radio 12 px, padding 24 px, sombra card. |
| `aql-card-lg` | Radio 16 px, padding 32 px. |
| `aql-card-elevated` | Fondo `--surface-raised`, borde hairline, radio 12 px. |
| `aql-card-spotlight` | Fondo `--surface-spotlight`, borde sapphire, radio 16 px, padding 32 px. |
| `aql-card-terminal` | Fondo terminal, borde `#1e293b`, texto claro, radio 12 px. |

Reglas:

- Una tarjeta tiene título, descripción breve y metadatos. No se rellena con texto de relleno.
- Las tarjetas interactivas son un solo enlace; no se anidan enlaces dentro.
- El hover eleva 2 px y cambia el borde; no cambia el tamaño del texto.
- Las tarjetas con imagen reservan el aspect ratio antes de cargar.
- Prohibidas las filas de tres tarjetas idénticas con icono arriba.
- Las tarjetas de laboratorio, docente, comunicado, evento y logro tienen contenido y composición propios.

### 7.4 Ventana de código

- Fondo `--surface-terminal`, cabecera `--surface-terminal-header`, borde `#1e293b`.
- Radio 12 px, padding 16 px, IBM Plex Mono 12–13 px, line-height 1.5.
- Cabecera con título, contexto y estado; altura 36–40 px.
- Sombra `--shadow-floating`.
- Máximo dos ventanas visibles a la vez; máximo tres capas en el hero.
- En móvil, scroll horizontal interno y máximo 8 líneas visibles.
- Solo contiene datos reales: currícula, perfil de egreso, líneas de investigación, configuración. No código falso decorativo.
- Colores de sintaxis: clave `--color-code-key`, valor `--color-code-value`, comentario `--color-code-comment`, acento `--color-code-accent`, éxito `--color-code-success`.

### 7.5 Header, dropdown y navegación móvil

- Header de 64 px, sticky, `background: rgba(255,255,255,0.90)`, `backdrop-filter: blur(12px)`, borde inferior `--border-inner`.
- Logo 32 px; no se deforma ni se recolorea.
- Wordmark Figtree 16 px; sublabel mono 10 px en mayúsculas.
- Links Inter 14 px, gap 24 px; activo con tinta primaria y peso 500.
- Dropdown: 224 px, radio 12 px, padding 6 px, sombra dropdown, ítems de 36 px con hover `--surface-raised`.
- El dropdown debe abrir con teclado, cerrar con `Escape` y devolver el foco.
- Nav móvil: panel blanco, dos columnas, ítems de 44 px, borde inferior y sombra; se cierra con `Escape` y al navegar.
- No se usa un menú hamburguesa sin etiqueta accesible.
- No se usan iconos sin texto para la navegación principal.

### 7.6 Footer

- Fondo `--surface-subtle`, borde superior hairline, padding vertical 56 px.
- Cuatro columnas: identidad (5), enlaces académicos (3), contacto (4) y barra inferior.
- Identidad: logo, nombre, dirección, correo institucional.
- Contacto: máximo tres teléfonos de soporte, cada uno con WhatsApp y número en mono.
- Barra inferior: copyright, soporte técnico, intranet.
- No se incluye newsletter, formulario de suscripción ni redes sin URL real.
- En móvil se apila en una columna; los enlaces mantienen 44 px de alto.

### 7.7 Formularios

- Label arriba, Inter 14 px, peso 500; el placeholder no sustituye al label.
- Input, select y textarea: altura mínima 44 px, radio 10 px, borde hairline, padding `12px 14px`.
- Foco: borde `--border-focus` + anillo `0 0 0 3px rgba(40, 98, 215, 0.16)`.
- Error: borde `--color-danger`, mensaje debajo con icono y texto; no solo color.
- Ayuda: 12–13 px, `--text-secondary`; se asocia con `aria-describedby`.
- Deshabilitado: fondo `--surface-raised`, texto `--text-tertiary`, cursor no permitido.
- Checkbox y radio: 18 px, acento `--color-signal-blue`.
- Espaciado entre grupos: 20–24 px.
- Errores de formulario: resumen arriba con enlaces a los campos y errores inline.
- No hay formularios falsos. Si no existe backend, el CTA usa WhatsApp o `mailto:` con asunto y cuerpo redactados.
- Si hay backend: POST, CSRF, validación en servidor, `htmlspecialchars` en la salida y mensajes claros.

### 7.8 Tablas

- Contenedor con `overflow-x: auto`, borde hairline y radio 12 px.
- `caption` visible o accesible que describa la tabla.
- Cabecera con fondo `--color-pulse-navy`, texto blanco, mono 11 px en mayúsculas.
- Filas de 44–56 px, borde inferior `--border-inner`, hover `--surface-raised`.
- Números y siglas en mono; texto en Inter.
- Zebra opcional `--surface-subtle`, no obligatoria.
- En móvil: scroll horizontal con indicador; para currícula, acordeón por año + tabla.
- No se usa tabla para layout.
- No se ocultan columnas esenciales en móvil sin una vista alternativa.

### 7.9 Acordeón

- Contenedor con borde hairline y radio 12 px.
- Cabecera de 56–64 px, padding 20 px, Figtree 16 px, chevron de 20 px.
- Cabecera activa: fondo `--action-signal`, texto blanco.
- Cuerpo: padding 24 px, borde superior hairline.
- `button` con `aria-expanded` y `aria-controls`; el panel tiene `role="region"` y `aria-labelledby`.
- Animación de 220 ms con `grid-template-rows`.
- Primer año abierto por defecto en el plan de estudios; el resto cerrado.

### 7.10 Tabs

- Grupo de píldoras, máximo cuatro.
- Activo: fondo `--action-primary`, texto blanco.
- Inactivo: texto secundario, hover `--surface-raised`.
- Altura 36–40 px; en móvil scroll horizontal.
- Roles `tablist`, `tab`, `tabpanel`; navegación con flechas, Home y End.
- El contenido cambia con fade de 180 ms; no se desplaza la página.
- No se usan tabs para contenido que debe ser indexable si no hay fallback.

### 7.11 Breadcrumbs

- 13 px, Inter, `aria-label="Ruta de navegación"`.
- Separador `/` o `›` en `--text-tertiary`.
- Enlace actual en `--text-primary` con `aria-current="page"`.
- En móvil puede hacer wrap; no se oculta el nivel actual.

### 7.12 Paginación

- Obligatoria en listados largos: comunicados, eventos, logros, documentos.
- Página actual: fondo `--action-primary`, texto blanco, 44 × 44 px.
- Anterior/Siguiente con etiqueta textual; deshabilitados cuando corresponda.
- Mostrar “Página X de Y” y el total de resultados.
- No hay scroll infinito ni carga automática.
- “Ver más” solo si lleva a una URL de archivo o a una página siguiente, no a una carga infinita.

### 7.13 Búsqueda y filtros

- Input de búsqueda con icono y label accesible.
- Filtros en píldoras o select; máximo cuatro visibles.
- Contador de resultados visible.
- Botón “Limpiar filtros”.
- Estado vacío con explicación y acción.
- Los filtros se reflejan en la URL para poder compartir la búsqueda.
- No se recarga la página en cada tecla; se usa submit o debounce de 300 ms.

### 7.14 Alertas y toasts

- Alertas inline para mensajes de página; toasts para acciones transitorias del panel.
- Variantes: info, éxito, advertencia, peligro.
- Radio 12 px, borde 1 px, icono 20 px, texto 14 px.
- `role="status"` para información; `role="alert"` para errores.
- Los errores no se autodestruyen.
- SweetAlert2 se usa en el panel administrativo, no en el portal público salvo confirmación destructiva aprobada.

### 7.15 Modal, lightbox y visor PDF

- Overlay: `rgba(11, 12, 14, 0.75)` + `backdrop-filter: blur(4px)`.
- Diálogo: blanco, radio 12–16 px, sombra modal, ancho máximo 1024 px.
- Cabecera de 56 px con título, acciones y cierre de 44 × 44 px.
- Cierre con `Escape`, clic en overlay y botón; el foco vuelve al disparador.
- Bloquear el scroll del body mientras está abierto.
- Focus trap dentro del diálogo.
- PDF: iframe, skeleton de carga, botón “Nueva pestaña” y “Descargar PDF”.
- Lightbox: fondo oscuro, contador mono, flechas de 44 px, imagen `object-contain`, caption.
- No se abre un PDF sin indicar título, categoría y tamaño.

### 7.16 Skeletons

- Reservar el aspect ratio antes de cargar.
- Shimmer de 1.5 s; sin cambiar dimensiones.
- Se usan en imágenes, PDF y listados que cargan datos.
- No se usa un skeleton de página completa.
- Con movimiento reducido, el shimmer se detiene.
- La imagen entra con fade de 400 ms; no hay salto de layout.

### 7.17 Estados vacíos

- Un titular corto, una frase y una acción.
- Icono lineal de 24–32 px, sin emoji.
- Fondo `--surface-subtle`, borde hairline, radio 12 px.
- Ejemplos: “Aún no hay comunicados”, “No encontramos documentos con ese filtro”.
- No se inventan datos para llenar el estado vacío.

### 7.18 Medios y galerías

- Aspect ratios: `16/9`, `16/10`, `4/3`, `1/1`, `4/5`.
- `object-fit: cover` para tarjetas; `object-contain` para lightbox.
- Siempre `width` y `height` para evitar layout shift.
- `loading="lazy"` salvo la imagen principal del hero.
- Alt text descriptivo; si la imagen es decorativa, `alt=""`.
- No se superpone texto sobre una imagen sin scrim.
- Galería: 3–6 imágenes, lightbox, contador y navegación por teclado.
- No se usan fotos de stock que simulen campus, estudiantes o laboratorios que no existen.

### 7.19 Patrones de contenido

| Patrón | Reglas |
| --- | --- |
| Estadística | Número Figtree 40–56 px, etiqueta mono 11 px, fuente del dato en caption. |
| Timeline | Línea vertical hairline, año en mono signal, contenido en tarjeta o bloque. |
| Docente | Retrato 4/5, nombre Figtree 18 px, cargo Inter 13 px, especialidad mono 11 px. Si no hay foto, iniciales sobre `--surface-raised`. |
| Documento | Fila de 72 px, badge PDF, título, categoría, fecha/tamaño, acción de descarga. |
| Comunicado | Fecha mono, badge de categoría, título, extracto, enlace “Leer comunicado”. |
| Evento | Bloque de fecha 64 × 64 px, título, lugar, tipo, estado. |
| Logro | Acento dorado, año, categoría, título, descripción y enlace si existe. |
| Laboratorio | Imagen 16/10, nombre, capacidad, equipos en badges, estado. |

---

## 8. Blueprints por ruta

El portal es **100 % multipágina**. La navegación principal nunca usa anclas de una sola página. Las anclas solo se permiten dentro de una página larga, como índice interno.

| Ruta | Trabajo de la página | Secciones | Acción principal |
| --- | --- | --- | --- |
| `/` | Presentar la carrera y llevar a admisión o plan | Hero 5/7, prueba institucional, pilares, menciones, labs, comunicados/eventos, logros, CTA | Admisión |
| `/nosotros` | Explicar identidad, historia y dirección | Cabecera, presentación, historia, misión/visión, autoridades, acreditación, CTA | Contacto |
| `/admision` | Convertir al aspirante en postulante | Cabecera con año, modalidades, requisitos, calendario, pasos, FAQ, CTA | WhatsApp / inscripción |
| `/titulacion` | Explicar cómo se titula | Cabecera, opciones, modalidades, requisitos, proceso, reglamentos, FAQ | Descargar reglamento |
| `/becas` | Mostrar beneficios y requisitos | Cabecera, tipos, elegibilidad, pasos, calendario, documentos, FAQ | Consultar / postular |
| `/contacto` | Facilitar contacto real | Cabecera, canales, dirección, horarios, mapa, soporte | WhatsApp / correo |
| `/plan-estudios` | Entregar la currícula completa | Cabecera, resumen, acordeón por año, tablas, electivas, PDF | Descargar plan |
| `/menciones` | Diferenciar las dos menciones y IIDAI | Cabecera, dos paneles, líneas de investigación, labs, perfil, CTA | Conocer mención |
| `/plantel` | Presentar autoridades y docentes | Cabecera, autoridades, grilla docente, áreas, CTA | Contacto |
| `/documentos` | Encontrar y abrir documentos | Cabecera, búsqueda/filtros, categorías, listado, visor PDF, vacío | Abrir / descargar PDF |
| `/laboratorios` | Mostrar infraestructura real | Cabecera, tarjetas de labs, equipos, seguridad, galería, CTA | Conocer laboratorio |
| `/comunicados` | Publicar novedades oficiales | Cabecera, destacado, filtros, listado, paginación, archivo | Leer comunicado |
| `/eventos` | Difundir actividades | Cabecera, próximos, pasados, calendario, galería, CTA | Ver evento |
| `/logros` | Celebrar resultados verificables | Cabecera, filtros, timeline/grilla, detalle, CTA | Ver logro |

### 8.1 Home

1. Hero: insignias de acreditación y 25 años; `<h1>` “Ingeniería Informática”; bajada; CTA “Admisión 2026” y “Plan de Estudios”; composición de pestañas + ventanas técnicas.
2. Prueba institucional: acreditación CEUB, 25 años, dos menciones, IIDAI. Cuatro datos, no cuatro tarjetas idénticas.
3. Pilares: formación, investigación, laboratorios, egresados. Bento 2+1 o 5/7.
4. Menciones: dos paneles, Seguridad Informática y Computación Móvil, con materias reales.
5. Laboratorios: una imagen dominante + lista de laboratorios; no seis tarjetas iguales.
6. Comunidad: un comunicado destacado + dos eventos; enlaces a `/comunicados` y `/eventos`.
7. Logros: tres logros con acento dorado; enlace a `/logros`.
8. CTA final: admisión y contacto; cierre con footer.

### 8.2 Plan de estudios

- Cabecera con “5 años”, “Sistema anual”, “2 menciones” y CTA de PDF.
- Acordeón de 1.º a 5.º año; primer año abierto.
- Tabla por año con columnas: N.º, sigla, materia, HT, HL, TH, prerequisito.
- Menciones de 3.º y 4.º año dentro del acordeón correspondiente.
- Electivas en una sección aparte, con chips o lista.
- Leyenda de cargas horarias y nota de fuente oficial.
- PDF del plan de estudios visible y descargable.
- En móvil, tabla con scroll horizontal; no se convierte en texto ilegible.

### 8.3 Documentos

- Buscador y filtros por categoría, año y tipo.
- Listado de filas, no grilla de tarjetas.
- Cada fila: badge PDF, título, categoría, fecha, tamaño, acción.
- Visor PDF integrado; nueva pestaña y descarga.
- Estado vacío con “Limpiar filtros”.
- Nota de última actualización.
- Paginación si hay más de 20 documentos.

### 8.4 Comunicados, eventos y logros

- Comunicados: destacado 2/3 + lista 1/3; filtros por categoría; paginación.
- Eventos: próximos primero, pasados en archivo; bloque de fecha; estado “Próximo/Culminado”.
- Logros: filtros por año y categoría; timeline o grilla de tres columnas; dorado solo en el detalle.
- Ninguna de estas páginas usa scroll infinito.
- Las imágenes son reales; si no hay foto, se usa composición tipográfica, no un placeholder genérico.

---

## 9. Contenido y voz

### 9.1 Tono

- Institucional, claro, cálido y preciso.
- Frases cortas; una idea por párrafo.
- “Tú” para el aspirante y el estudiante; “la Carrera” en tercera persona para lo institucional.
- No se usa “estimado usuario”, “bienvenido a nuestro sitio” ni relleno de marketing.
- No se usan superlativos sin prueba: “la mejor”, “líder”, “único”.
- Los datos institucionales se verifican antes de publicarse.

### 9.2 Titulares

- Máximo dos líneas en desktop y tres en móvil.
- 5–10 palabras.
- Sin eyebrow obligatorio; si se usa, aporta información, no decoración.
- Sin signos de exclamación.
- Sin “Descubre”, “Sumérgete”, “Revoluciona”.
- El `<h1>` dice qué es la página; no es un eslogan vacío.

### 9.3 Microcopy

| Contexto | Regla | Ejemplo |
| --- | --- | --- |
| CTA | Verbo + objeto | “Descargar el reglamento” |
| PDF | Título + tipo + tamaño | “Reglamento de titulación · PDF · 1.2 MB” |
| Error | Qué pasó + cómo resolverlo | “No pudimos enviar el mensaje. Revisa tu conexión e inténtalo otra vez.” |
| Vacío | Estado + siguiente paso | “No hay documentos con ese filtro. Limpia los filtros o busca por año.” |
| Carga | Acción en curso | “Cargando documento…” |
| Confirmación | Resultado concreto | “Mensaje enviado. Te responderemos en horario de oficina.” |

### 9.4 Formatos

- Fecha larga: `25 de octubre de 2026`; fecha corta: `25/10/2026`.
- Horas: `08:30–12:30`.
- Números: `5 años`, `1.º año`, `2.º año`.
- Siglas: `INF1100`, `CEUB`, `IIDAI`; se expanden la primera vez.
- Moneda: `Bs 150` si aplica; no se inventan costos.
- Enlaces: texto descriptivo, nunca “haz clic aquí”.

### 9.5 SEO mínimo

- `<title>` único por ruta, 50–60 caracteres.
- `meta description` de 150–160 caracteres.
- `canonical` por ruta.
- Open Graph: título, descripción e imagen 1200 × 630.
- Un `<h1>` por página y encabezados jerárquicos.
- URLs limpias y estables.
- Datos estructurados `CollegeOrUniversity` o `EducationalOrganization` cuando corresponda.
- No se indexan páginas de error ni duplicados.

---

## 10. Iconografía e imágenes

### 10.1 Iconos

- SVG inline, trazo de 1.8–2 px, esquinas redondeadas, tamaño 20/24 px.
- Una sola familia visual en todo el portal.
- Iconos decorativos con `aria-hidden="true"`; iconos con significado con texto alternativo.
- Botones de icono con `aria-label` y área de 44 × 44 px.
- No se usan emojis como iconos estructurales.
- No se usan iconos de relleno dentro de tarjetas genéricas.
- Los iconos de PDF, WhatsApp, descarga, flecha y cierre deben ser consistentes.

### 10.2 Imágenes

- Prioridad: fotografía institucional real > imagen de archivo con crédito > composición tipográfica.
- No se usan fotos de stock que simulen un campus, un laboratorio o estudiantes que no son de la carrera.
- Tratamiento: encuadre limpio, sin filtros de color fuertes, sin duotonos.
- Formatos: WebP o JPEG optimizado; máximo 300 KB para tarjetas, 500 KB para hero.
- Siempre `width`, `height`, `alt`, `loading` y `decoding`.
- Las imágenes del hero no usan lazy loading.
- El logo oficial no se recolorea, deforma ni recorta.
- El dorado de los laureles se reserva para acreditación y logros.

---

## 11. Accesibilidad

### 11.1 Base

- WCAG 2.2 AA como mínimo.
- `lang="es"` en `<html>`.
- Skip link “Saltar al contenido” como primer elemento enfocable.
- Landmarks: `header`, `nav`, `main`, `footer`.
- Un `<h1>` por página; jerarquía sin saltos.
- Enlaces y botones reales; no `div` clicables.
- Foco visible en todos los controles.
- No se elimina el outline sin reemplazo.
- Objetivos táctiles de 44 × 44 px.
- Texto de cuerpo mínimo 16 px; metadatos 12 px.
- No se usa color como único indicador.
- No hay contenido que parpadee más de tres veces por segundo.
- No hay autoplay con sonido.

### 11.2 Teclado

- Orden de tabulación igual al orden visual.
- Dropdowns, tabs, acordeones y modales operables con teclado.
- `Escape` cierra dropdown, modal y lightbox.
- Focus trap en modal y lightbox.
- Al cerrar, el foco vuelve al disparador.
- `aria-expanded`, `aria-controls`, `aria-selected`, `aria-current` según corresponda.

### 11.3 Formularios

- Label visible y asociado.
- `aria-describedby` para ayuda y error.
- Mensaje de error específico por campo.
- Resumen de errores al inicio si hay más de uno.
- No se valida solo en el cliente.
- El estado de carga se anuncia.

### 11.4 Movimiento

- `prefers-reduced-motion` respetado.
- Las animaciones no bloquean la lectura ni la interacción.
- El contenido es visible sin JavaScript.
- No hay scroll hijacking.

### 11.5 Medios

- Alt text descriptivo y breve.
- PDFs con título, tipo y tamaño.
- Tablas con `caption` y encabezados `th`.
- Videos con subtítulos si existen.
- No se usa texto dentro de imágenes para información esencial.

---

## 12. Implementación

### 12.1 Tokens CSS

```css
:root {
  --color-abyss: #ffffff;
  --color-void: #f8fafc;
  --color-deep-sea: #f1f5fb;
  --color-cobalt-panel: #eff6ff;
  --color-terminal: #0d172b;
  --color-terminal-header: #0b0c0e;

  --color-quartz: #0b132b;
  --color-ash: #475569;
  --color-mist: #64748b;

  --color-pulse-navy: #1e3a8a;
  --color-signal-blue: #2862d7;
  --color-signal-strong: #1d4ed8;
  --color-signal-soft: #3b82f6;
  --color-pulse-violet: var(--color-pulse-navy);
  --color-frosted-lilac: var(--color-signal-soft);
  --color-unsxx-gold: #eab308;
  --color-unsxx-gold-bright: #facc15;
  --color-unsxx-gold-deep: #ca8a04;

  --color-obsidian-edge: #dbe3f0;
  --color-inkline: #e2e8f0;
  --color-sapphire-hairline: #93c5fd;
  --color-border-strong: #94a3b8;

  --color-success: #047857;
  --color-success-soft: #ecfdf5;
  --color-success-border: #a7f3d0;
  --color-warning: #b45309;
  --color-warning-soft: #fffbeb;
  --color-warning-border: #fde68a;
  --color-danger: #b91c1c;
  --color-danger-soft: #fef2f2;
  --color-danger-border: #fecaca;
  --color-info: #0369a1;
  --color-info-soft: #f0f9ff;

  --color-focus: #2862d7;
  --color-focus-dark: #facc15;

  --color-code-key: #ff7dda;
  --color-code-value: #28b6ff;
  --color-code-comment: #8798c1;
  --color-code-accent: #85a6e9;
  --color-code-success: #34d399;

  --surface-canvas: var(--color-abyss);
  --surface-subtle: var(--color-void);
  --surface-raised: var(--color-deep-sea);
  --surface-spotlight: var(--color-cobalt-panel);
  --surface-terminal: var(--color-terminal);
  --surface-terminal-header: var(--color-terminal-header);
  --surface-executive: var(--color-terminal);

  --text-primary: var(--color-quartz);
  --text-secondary: var(--color-ash);
  --text-tertiary: var(--color-mist);
  --text-on-dark: #ffffff;
  --text-on-dark-muted: #c7c9d1;
  --text-on-accent: #ffffff;
  --text-on-gold: var(--color-quartz);

  --border-hairline: var(--color-obsidian-edge);
  --border-inner: var(--color-inkline);
  --border-spotlight: var(--color-sapphire-hairline);
  --border-focus: var(--color-focus);

  --action-primary: var(--color-pulse-navy);
  --action-primary-hover: #172554;
  --action-signal: var(--color-signal-blue);
  --action-signal-hover: var(--color-signal-strong);
  --action-quiet: transparent;
  --action-quiet-hover: var(--color-deep-sea);

  --font-inter: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
  --font-figtree: 'Figtree', ui-sans-serif, system-ui, -apple-system, sans-serif;
  --font-mono: 'IBM Plex Mono', ui-monospace, SFMono-Regular, monospace;

  --type-display-xl: clamp(2.75rem, 6vw, 4rem);
  --type-display-lg: clamp(2.25rem, 4.5vw, 3.25rem);
  --type-h2: clamp(1.75rem, 3vw, 2.5rem);
  --type-h3: clamp(1.25rem, 2vw, 1.625rem);
  --type-h4: 1.125rem;
  --type-body-lg: 1.125rem;
  --type-body: 1rem;
  --type-body-sm: 0.875rem;
  --type-caption: 0.75rem;
  --type-eyebrow: 0.75rem;
  --type-mono: 0.8125rem;
  --type-mono-sm: 0.6875rem;
  --type-mono-xs: 0.625rem;

  --space-1: 4px;
  --space-2: 8px;
  --space-3: 12px;
  --space-4: 16px;
  --space-5: 20px;
  --space-6: 24px;
  --space-8: 32px;
  --space-10: 40px;
  --space-12: 48px;
  --space-14: 56px;
  --space-16: 64px;
  --space-20: 80px;
  --space-24: 96px;
  --space-30: 120px;

  --radius-xs: 4px;
  --radius-sm: 8px;
  --radius-md: 12px;
  --radius-lg: 16px;
  --radius-pill: 9999px;
  --radius-circle: 50%;

  --shadow-card:
    0 1px 2px rgba(11, 19, 43, 0.03),
    0 8px 24px -4px rgba(11, 19, 43, 0.04);
  --shadow-dropdown:
    0 4px 12px rgba(11, 19, 43, 0.06),
    0 16px 32px -8px rgba(11, 19, 43, 0.10);
  --shadow-floating:
    0 20px 35px rgba(13, 23, 43, 0.16),
    0 4px 13px rgba(13, 23, 43, 0.08);
  --shadow-modal: 0 24px 64px rgba(11, 19, 43, 0.18);

  --z-nav: 40;
  --z-dropdown: 50;
  --z-overlay: 60;
  --z-modal: 70;
  --z-lightbox: 80;
  --z-toast: 90;

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
}
```

Los tokens `--type-*` guardan el tamaño; la familia, el peso, el interlineado y el tracking se aplican en las clases `.aql-*`. Los tokens `--space-*` y `--z-*` son la referencia de implementación para las vistas nuevas.

### 12.2 Mapeo Tailwind

Cuando se implemente, extender la configuración del CDN con:

- `fontFamily`: `sans` → Inter, `display` → Figtree, `mono` → IBM Plex Mono.
- `colors`: `canvas`, `surface`, `ink`, `signal`, `navy`, `gold`, `line`, `success`, `warning`, `danger`, `info`.
- `borderRadius`: `card: 12px`, `panel: 16px`, `pill: 9999px`.
- `boxShadow`: `card`, `dropdown`, `floating`, `modal`.
- `transitionTimingFunction`: `out-expo`, `in-out`, `standard`.

Mientras no exista configuración global, las vistas nuevas deben usar las clases `.aql-*` y los tokens CSS; no repetir hex arbitrarios.

### 12.3 Markup canónico

Encabezado de sección:

```html
<header class="max-w-2xl">
  <p class="aql-eyebrow">Plan de estudios</p>
  <h2 class="aql-heading text-3xl sm:text-4xl">Cinco años, dos menciones</h2>
  <p class="mt-3 text-base text-[var(--text-secondary)]">
    Formación anual con salida lateral técnica y especialización en 3.º y 4.º año.
  </p>
</header>
```

Botón:

```html
<a href="/admision" class="aql-btn-primary">Conoce la admisión</a>
<button type="button" class="aql-btn-ghost">Descargar plan</button>
```

Tarjeta:

```html
<article class="aql-card">
  <p class="font-mono text-xs text-[var(--color-signal-blue)]">INF3403</p>
  <h3 class="aql-heading mt-2 text-xl">Ciberseguridad y vulnerabilidades</h3>
  <p class="mt-2 text-sm text-[var(--text-secondary)]">
    Análisis de amenazas, pruebas de penetración y respuesta a incidentes.
  </p>
</article>
```

Ventana de código:

```html
<div class="aql-code-window">
  <div class="flex items-center justify-between border-b border-[#1e293b] bg-[var(--surface-terminal-header)] px-4 py-2">
    <span class="text-white">Perfil de egreso</span>
    <span class="font-mono text-xs text-[var(--color-code-success)]">Acreditado</span>
  </div>
  <pre class="overflow-x-auto p-4 text-xs"><code>{
  "grado": "Licenciatura en Ingeniería Informática",
  "duracion": "5 años",
  "menciones": ["Seguridad Informática", "Computación Móvil"]
}</code></pre>
</div>
```

Badge:

```html
<span class="aql-badge">Mención Seguridad Informática</span>
<span class="aql-badge aql-badge-gold">Acreditada CEUB</span>
```

### 12.4 Reglas de migración

- Reemplazar hex arbitrarios por tokens semánticos en las vistas nuevas.
- Mantener `agentql-unsxx.css` como implementación de los tokens; no duplicar valores en cada vista.
- Cambiar los enlaces de navegación de anclas (`/carrera#...`, `/comunidad#...`) a rutas multipágina cuando se reemplacen las vistas.
- Reemplazar `transition: all` por propiedades específicas.
- Añadir `prefers-reduced-motion` a toda animación nueva.
- No introducir dependencias nuevas para UI pública.
- No usar `dark:` como tema activo; las clases existentes se conservan solo por compatibilidad.
- Las vistas `home.php` y `carrera.php` no se sobrescriben en la fase de documentación.

---

## 13. Anti-patrones

- Hero centrado con tres tarjetas de iconos.
- Fila de tarjetas idénticas como única solución de contenido.
- Texto gris claro sobre blanco.
- Dorado como texto sobre blanco.
- Gradiente en texto, tarjetas o fondos completos.
- Glassmorphism en tarjetas o secciones.
- Sombra dramática en elementos en reposo.
- Bordes de 2–3 px como decoración.
- Iconos emoji como estructura.
- Navegación solo con iconos.
- Scroll infinito o carga automática.
- Carrusel automático para contenido principal.
- Contadores, testimonios o logos inventados.
- “Lorem ipsum” o datos de relleno en producción.
- Texto sobre imagen sin scrim.
- Más de un CTA primario por viewport.
- Más de un acento dorado por viewport.
- Más de dos ventanas de código visibles.
- Animación de `width`, `height` o `transition: all`.
- Animación en acciones de teclado repetitivas.
- Parallax en móvil.
- Modal sin focus trap.
- Tabla sin caption o sin encabezados.
- PDF sin título, tipo o tamaño.
- Página sin `<h1>` o con más de uno.
- Enlaces “haz clic aquí”.
- Fotos de stock que simulan la carrera.
- Modo oscuro público no aprobado.

---

## 14. QA y definición de terminado

### 14.1 Visual

- [ ] El lienzo es blanco; las secciones alternas usan `--surface-subtle`.
- [ ] El dorado aparece como máximo una vez por viewport.
- [ ] Las tarjetas usan radio 12–16 px y borde hairline.
- [ ] Los botones y badges son píldoras.
- [ ] Los titulares tienen dos líneas como máximo en desktop.
- [ ] No hay secciones sin título.
- [ ] No hay media pantalla vacía.
- [ ] Los márgenes exteriores coinciden.
- [ ] Las ventanas de código solo contienen datos reales.

### 14.2 Responsive

- [ ] Probado a 375, 768, 1024 y 1440 px.
- [ ] El hero se apila correctamente.
- [ ] Las tablas tienen scroll o vista alternativa.
- [ ] La navegación móvil es usable con una mano.
- [ ] Los objetivos táctiles miden 44 × 44 px.
- [ ] No hay overflow horizontal no intencional.

### 14.3 Accesibilidad

- [ ] Skip link presente.
- [ ] Un solo `<h1>`.
- [ ] Foco visible en todos los controles.
- [ ] Dropdown, tabs, acordeón y modal operables con teclado.
- [ ] `Escape` cierra capas.
- [ ] Contraste AA verificado.
- [ ] `prefers-reduced-motion` respetado.
- [ ] Formularios con label, ayuda y error.
- [ ] Imágenes con alt.
- [ ] PDFs con título y tamaño.

### 14.4 Movimiento

- [ ] La curva principal es `cubic-bezier(0.22, 1, 0.36, 1)`.
- [ ] No hay `transition: all`.
- [ ] Las animaciones duran menos de 300 ms en UI.
- [ ] El contenido es visible sin JavaScript.
- [ ] No hay scroll hijacking.
- [ ] El shimmer se detiene con movimiento reducido.

### 14.5 Contenido

- [ ] No hay datos inventados.
- [ ] Los enlaces tienen texto descriptivo.
- [ ] Las fechas y siglas siguen el formato definido.
- [ ] Cada página tiene un título único y una meta description.
- [ ] Los PDFs tienen título, categoría y tamaño.
- [ ] Los estados vacíos tienen acción.

### 14.6 Integración

- [ ] No se agregaron dependencias nuevas.
- [ ] Las rutas multipágina están actualizadas.
- [ ] No quedan anclas heredadas en la navegación principal.
- [ ] `php -l` pasa en los archivos modificados.
- [ ] No hay warnings PHP en la respuesta.
- [ ] `DESIGN.md` está sincronizado con este archivo.

---

## 15. Gobernanza y sincronización

- `design-system/MASTER.md` es la fuente de verdad.
- `DESIGN.md` es la referencia rápida sincronizada; debe actualizarse en el mismo cambio que este archivo.
- `public/assets/css/agentql-unsxx.css` implementa los tokens; si cambia un token, se actualiza el CSS.
- `src/Views/layouts/public.php` carga fuentes, Tailwind y el CSS del sistema.
- `src/Views/layouts/partials/navbar.php` y `footer.php` implementan la navegación y el cierre.
- `public/assets/js/portal.js` implementa tabs, modales, lightbox, PDF y skeletons.
- Cualquier excepción a una regla se documenta en el cambio con una razón verificable.
- No se agregan tokens o componentes sin actualizar este documento.
- Revisión recomendada: antes de cada fase de vistas y al cerrar una migración.

### Registro de cambios

| Versión | Fecha | Cambio |
| --- | --- | --- |
| 1.0 | 2026-10-25 | Sistema maestro inicial: tokens, tipografía, layout, movimiento, componentes, blueprints, accesibilidad e implementación. |
