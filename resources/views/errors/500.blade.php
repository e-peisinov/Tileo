<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error del servidor — Tileo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Raleway:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Raleway', sans-serif; background-color: #faf6f0; color: #2c1a0e; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .container { text-align: center; max-width: 480px; }
        .codigo { font-size: 7rem; font-family: 'DM Serif Display', serif; color: #d4b896; line-height: 1; }
        h1 { font-family: 'DM Serif Display', serif; font-size: 2rem; color: #2c1a0e; margin: 1rem 0 0.75rem; }
        p { color: #8b5e3c; font-size: 0.9rem; line-height: 1.7; margin-bottom: 2rem; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; background-color: #386641; color: #faf6f0; padding: 0.75rem 2rem; font-size: 0.8rem; letter-spacing: 0.1em; font-weight: 600; text-decoration: none; transition: background-color 0.2s; }
        .btn:hover { background-color: #2d5534; }
        .divider { width: 48px; height: 2px; background-color: #d4b896; margin: 1.5rem auto; }
    </style>
</head>
<body>
    <div class="container">
        <p class="codigo">500</p>
        <div class="divider"></div>
        <h1>Algo salió mal</h1>
        <p>Hubo un error interno en el servidor.<br>Ya estamos trabajando para solucionarlo. Por favor intentá de nuevo en unos minutos.</p>
        <a href="/" class="btn"><i class="fa-solid fa-house"></i> Volver al inicio</a>
    </div>
</body>
</html>
