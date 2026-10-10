---
name: sdd-auditor
description: Analiza el directorio actual del proyecto para extraer el stack tecnológico, la arquitectura general, e identificar las 5 vulnerabilidades de seguridad y problemas de UI más críticos para preparar la fase 0 del Spec-Driven Development.
---

# SDD Auditor: Fase 0 - Análisis Técnico y Arquitectura

Esta skill guía el análisis integral del directorio actual del proyecto para extraer el stack tecnológico, resumir la arquitectura y detectar los principales riesgos de seguridad y deficiencias de UI/UX, estableciendo los cimientos para la fase 0 del Spec-Driven Development (SDD).

## Instrucciones de Ejecución

1. **Determinación del Stack Tecnológico:**
   - Inspecciona y lee los archivos de manifiesto y configuración en la raíz del proyecto (tales como `package.json`, `composer.json`, `requirements.txt`, `pom.xml`, `Gemfile`, `go.mod`, etc.).
   - Identifica versiones clave de lenguajes, frameworks, librerías frontend/backend y gestores de base de datos.

2. **Revisión de Arquitectura y Patrones:**
   - Recorre la estructura de directorios del proyecto.
   - Resume el patrón arquitectónico (ej. MVC, Hexagonal, Clean Architecture, Monolito por capas, API REST desacoplada).
   - Documenta la organización y flujo entre vistas/frontend, controladores/enrutadores y modelos/servicios de datos.

3. **Auditoría de Vulnerabilidades y Código Inseguro:**
   - Realiza búsquedas activas de patrones vulnerables comunes:
     - Inyección SQL (consultas directas o interpoladas sin sentencias preparadas).
     - Endpoints o scripts expuestos sin validación de sesión/rol.
     - Vulnerabilidades Cross-Site Scripting (XSS) y falta de sanitización de entradas/salidas.
     - Credenciales, tokens o configuraciones sensibles hardcodeadas.
     - Carga insegura de archivos o desbordamiento de privilegios.

4. **Auditoría de UI/UX:**
   - Detecta malas prácticas en el diseño y frontend:
     - Uso excesivo de estilos CSS en línea (`style="..."`) o duplicación masiva de estilos.
     - Falta de modularización o ausencia de componentes reutilizables.
     - Problemas de accesibilidad, diseño no responsivo o inconsistencias de layout y diseño visual.

5. **Generación del Reporte:**
   - Estructura y presenta un informe final con las siguientes secciones obligatorias:
     1. **Stack y Arquitectura**: Detalle técnico exacto, herramientas y organización de carpetas.
     2. **Top 5 Vulnerabilidades y Riesgos**: Descripción detallada, archivo/línea de impacto, severidad y riesgo asociado para la fase 0.
     3. **Top 3 Problemas de UI/UX**: Inconsistencias, código de interfaz redundante y recomendaciones inmediatas.
