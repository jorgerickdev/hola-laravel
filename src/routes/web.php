<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laravel 11 en Docker | UNIR - DAR Grupo C</title>
  <style>
    :root {
      --unir-blue: #0198cc;
      --unir-navy: #0f172a;
      --laravel-red: #ff2d20;
      --docker-blue: #2496ed;
      --bg-gradient: radial-gradient(circle at 50% 20%, #1e293b 0%, #0a0f1d 100%);
      --card-bg: rgba(30, 41, 59, 0.7);
      --card-border: rgba(255, 255, 255, 0.1);
      --text-main: #f8fafc;
      --text-muted: #94a3b8;
      --accent-glow: rgba(1, 152, 204, 0.35);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    body {
      background: var(--bg-gradient);
      color: var(--text-main);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      position: relative;
      overflow-x: hidden;
    }

    /* Luces de fondo decorativas */
    body::before {
      content: "";
      position: absolute;
      top: -100px;
      left: 50%;
      transform: translateX(-50%);
      width: 500px;
      height: 350px;
      background: var(--accent-glow);
      filter: blur(120px);
      border-radius: 50%;
      pointer-events: none;
      z-index: 0;
    }

    /* Contenedor Principal Glassmorphism */
    .container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 780px;
      background: var(--card-bg);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid var(--card-border);
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 30px rgba(1, 152, 204, 0.15);
      overflow: hidden;
      transition: all 0.3s ease;
    }

    /* Barra Superior / Selector de Pestañas */
    .nav-bar {
      display: flex;
      background: rgba(15, 23, 42, 0.85);
      border-bottom: 1px solid var(--card-border);
      padding: 0.5rem;
      gap: 0.5rem;
    }

    .nav-btn {
      flex: 1;
      background: transparent;
      border: none;
      color: var(--text-muted);
      font-size: 0.92rem;
      font-weight: 700;
      padding: 0.85rem 1rem;
      border-radius: 12px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      transition: all 0.25s ease;
    }

    .nav-btn:hover {
      color: var(--text-main);
      background: rgba(255, 255, 255, 0.05);
    }

    .nav-btn.active {
      color: #ffffff;
      background: linear-gradient(135deg, rgba(1, 152, 204, 0.3) 0%, rgba(36, 150, 237, 0.2) 100%);
      border: 1px solid rgba(1, 152, 204, 0.5);
      box-shadow: 0 4px 15px rgba(1, 152, 204, 0.2);
    }

    /* Contenido de las Páginas */
    .page-section {
      display: none;
      padding: 2.8rem 2.2rem;
      animation: fadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .page-section.active {
      display: block;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(8px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Badges de Estado */
    .status-badge-container {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }

    .badge {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.35rem 0.8rem;
      border-radius: 9999px;
      font-size: 0.78rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .badge-status {
      background: rgba(34, 197, 94, 0.15);
      color: #4ade80;
      border: 1px solid rgba(34, 197, 94, 0.35);
    }

    .pulse-dot {
      width: 8px;
      height: 8px;
      background-color: #22c55e;
      border-radius: 50%;
      box-shadow: 0 0 8px #22c55e;
      animation: pulse 1.8s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
      70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }

    .badge-laravel {
      background: rgba(255, 45, 32, 0.15);
      color: #ff6b61;
      border: 1px solid rgba(255, 45, 32, 0.35);
    }

    .badge-docker {
      background: rgba(36, 150, 237, 0.15);
      color: #60a5fa;
      border: 1px solid rgba(36, 150, 237, 0.35);
    }

    /* Tipografía y Hero */
    .hero-title {
      font-size: 2.1rem;
      font-weight: 800;
      text-align: center;
      margin-bottom: 0.8rem;
      background: linear-gradient(135deg, #ffffff 0%, #94a3b8 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      line-height: 1.25;
    }

    .hero-subtitle {
      text-align: center;
      color: var(--text-muted);
      font-size: 1.05rem;
      line-height: 1.6;
      max-width: 620px;
      margin: 0 auto 2.2rem auto;
    }

    /* Cuadrícula de Especificaciones Técnicas */
    .tech-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
      gap: 1.2rem;
      margin-bottom: 2.2rem;
    }

    .tech-card {
      background: rgba(15, 23, 42, 0.6);
      border: 1px solid var(--card-border);
      border-radius: 14px;
      padding: 1.2rem;
      text-align: center;
      transition: all 0.25s ease;
    }

    .tech-card:hover {
      border-color: var(--unir-blue);
      transform: translateY(-3px);
      box-shadow: 0 6px 18px rgba(1, 152, 204, 0.2);
    }

    .tech-icon {
      font-size: 1.8rem;
      margin-bottom: 0.5rem;
    }

    .tech-card h4 {
      font-size: 0.82rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0.3rem;
    }

    .tech-card p {
      font-size: 1.05rem;
      font-weight: 700;
      color: #ffffff;
    }

    /* Barra de Progreso */
    .progress-box {
      background: rgba(15, 23, 42, 0.6);
      border: 1px solid var(--card-border);
      border-radius: 14px;
      padding: 1.3rem;
      margin-bottom: 2rem;
    }

    .progress-header {
      display: flex;
      justify-content: space-between;
      font-size: 0.88rem;
      font-weight: 600;
      margin-bottom: 0.6rem;
    }

    .progress-track {
      width: 100%;
      height: 10px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 9999px;
      overflow: hidden;
    }

    .progress-fill {
      width: 85%;
      height: 100%;
      background: linear-gradient(90deg, var(--unir-blue) 0%, var(--docker-blue) 50%, var(--laravel-red) 100%);
      border-radius: 9999px;
      transition: width 1s ease-in-out;
    }

    /* Botón de Acción Principal */
    .cta-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      width: 100%;
      background: linear-gradient(135deg, var(--unir-blue) 0%, #1967b7 100%);
      color: #ffffff;
      text-decoration: none;
      font-size: 0.98rem;
      font-weight: 700;
      padding: 1rem 1.6rem;
      border-radius: 12px;
      border: none;
      cursor: pointer;
      box-shadow: 0 4px 18px rgba(1, 152, 204, 0.35);
      transition: all 0.25s ease;
    }

    .cta-btn:hover {
      filter: brightness(1.15);
      transform: translateY(-2px);
      box-shadow: 0 6px 24px rgba(1, 152, 204, 0.5);
    }

    /* Contenido de Página 2: Ficha Técnica */
    .info-list {
      display: flex;
      flex-direction: column;
      gap: 1.1rem;
      margin-bottom: 2.2rem;
    }

    .info-item {
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      background: rgba(15, 23, 42, 0.6);
      border: 1px solid var(--card-border);
      border-radius: 12px;
      padding: 1.1rem 1.3rem;
    }

    .info-bullet {
      width: 32px;
      height: 32px;
      background: rgba(1, 152, 204, 0.2);
      color: var(--unir-blue);
      border: 1px solid var(--unir-blue);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      flex-shrink: 0;
    }

    .info-item h4 {
      font-size: 0.98rem;
      color: #ffffff;
      margin-bottom: 0.2rem;
    }

    .info-item p {
      font-size: 0.88rem;
      color: var(--text-muted);
      line-height: 1.5;
    }

    .team-chips {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 0.4rem;
    }

    .chip {
      background: rgba(255, 255, 255, 0.08);
      color: #f1f5f9;
      font-size: 0.8rem;
      font-weight: 600;
      padding: 0.25rem 0.65rem;
      border-radius: 6px;
      border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .chip-lead {
      background: rgba(1, 152, 204, 0.25);
      color: #38bdf8;
      border-color: var(--unir-blue);
    }

    /* Footer discreto */
    .card-footer {
      text-align: center;
      padding: 1.2rem;
      background: rgba(10, 15, 29, 0.6);
      border-top: 1px solid var(--card-border);
      font-size: 0.82rem;
      color: #64748b;
    }

    /* Responsive */
    @media (max-width: 600px) {
      .page-section { padding: 2rem 1.4rem; }
      .hero-title { font-size: 1.7rem; }
      .tech-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <div class="container">
    <!-- Selector de las 2 Páginas -->
    <nav class="nav-bar">
      <button class="nav-btn active" onclick="cambiarPagina('pagina-construccion', this)">
        <span>🚧</span> Estado del Despliegue
      </button>
      <button class="nav-btn" onclick="cambiarPagina('pagina-actividad', this)">
        <span>📋</span> Ficha de la Actividad
      </button>
    </nav>

    <!-- ================= PÁGINA 1: SITIO EN CONSTRUCCIÓN / ESTADO ================= -->
    <section id="pagina-construccion" class="page-section active">
      <div class="status-badge-container">
        <span class="badge badge-status">
          <span class="pulse-dot"></span> Contenedor Activo en Producción
        </span>
        <span class="badge badge-laravel">Laravel 11 MVC</span>
        <span class="badge badge-docker">Docker Engine</span>
      </div>

      <h1 class="hero-title">Sitio en Construcción</h1>
      <p class="hero-subtitle">
        El entorno base ha sido aprovisionado y desplegado con éxito en la nube. Actualmente nos encontramos orquestando las vistas dinámicas y la grabación de la evidencia técnica final.
      </p>

      <!-- Cuadrícula Técnica -->
      <div class="tech-grid">
        <div class="tech-card">
          <div class="tech-icon">🐳</div>
          <h4>Contenedor</h4>
          <p>Docker Container</p>
        </div>
        <div class="tech-card">
          <div class="tech-icon">☁️</div>
          <h4>Infraestructura</h4>
          <p>Render Cloud (PaaS)</p>
        </div>
        <div class="tech-card">
          <div class="tech-icon">⚡</div>
          <h4>Backend Framework</h4>
          <p>Laravel 11 / PHP 8.x</p>
        </div>
      </div>

      <!-- Barra de Progreso -->
      <div class="progress-box">
        <div class="progress-header">
          <span style="color: #cbd5e1;">Fase 2: Integración y Despliegue</span>
          <span style="color: var(--unir-blue);">85% Completado</span>
        </div>
        <div class="progress-track">
          <div class="progress-fill"></div>
        </div>
      </div>

      <button class="cta-btn" onclick="cambiarPagina('pagina-actividad', document.querySelectorAll('.nav-btn')[1])">
        <span>Conocer Detalles de la Actividad UNIR</span>
        <span>→</span>
      </button>
    </section>

    <!-- ================= PÁGINA 2: FICHA DE LA ACTIVIDAD ================= -->
    <section id="pagina-actividad" class="page-section">
      <div class="status-badge-container">
        <span class="badge badge-status" style="border-color: var(--unir-blue); color: #38bdf8; background: rgba(1, 152, 204, 0.15);">
          UNIR México · DAR
        </span>
        <span class="badge badge-laravel">Actividad 2</span>
      </div>

      <h2 class="hero-title" style="font-size: 1.85rem;">Laboratorio de Despliegue de Red</h2>
      <p class="hero-subtitle">
        Documentación del aprovisionamiento, resolución de dependencias y puesta en marcha de aplicaciones web desacopladas bajo el patrón Modelo-Vista-Controlador.
      </p>

      <div class="info-list">
        <div class="info-item">
          <div class="info-bullet">🏛️</div>
          <div>
            <h4>Programa Académico</h4>
            <p>Universidad Internacional de La Rioja en México · Licenciatura en Ingeniería en Sistemas Computacionales · Asignatura: <em>Desarrollo de Aplicaciones en Red</em>.</p>
          </div>
        </div>

        <div class="info-item">
          <div class="info-bullet">👥</div>
          <div>
            <h4>Equipo de Trabajo (Grupo C)</h4>
            <div class="team-chips">
              <span class="chip chip-lead">Jorge Rick (@jorgerickdev)</span>
              <span class="chip">Jperaltaa</span>
              <span class="chip">Vic</span>
              <span class="chip">Borbolla</span>
            </div>
          </div>
        </div>

        <div class="info-item">
          <div class="info-bullet">🎯</div>
          <div>
            <h4>Alcance y Objetivos de la Actividad</h4>
            <p>Configuración multiplataforma (Windows 11 y Linux), contenerización con Dockerfile y despliegue continuo de un servicio web HTTP accesible en Internet.</p>
          </div>
        </div>
      </div>

      <button class="cta-btn" style="background: rgba(255, 255, 255, 0.1); border: 1px solid var(--card-border); box-shadow: none;" onclick="cambiarPagina('pagina-construccion', document.querySelectorAll('.nav-btn')[0])">
        <span>← Volver al Estado del Despliegue</span>
      </button>
    </section>

    <footer class="card-footer">
      UNIR México · Octubre 2026 · Desplegado en vivo sobre Contenedores Docker
    </footer>
  </div>

  <script>
    // Lógica para alternar entre las 2 páginas dentro del mismo HTML
    function cambiarPagina(paginaId, botonElemento) {
      // Ocultar todas las páginas
      document.querySelectorAll('.page-section').forEach(seccion => {
        seccion.classList.remove('active');
      });

      // Desactivar todos los botones del menú
      document.querySelectorAll('.nav-btn').forEach(btn => {
        btn.classList.remove('active');
      });

      // Activar la página seleccionada y su botón correspondiente
      const paginaActiva = document.getElementById(paginaId);
      if (paginaActiva) {
        paginaActiva.classList.add('active');
      }

      if (botonElemento) {
        botonElemento.classList.add('active');
      }

      // Desplazamiento suave al inicio de la tarjeta en móviles
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  </script>
</body>
</html>
