<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error del servidor — Tileo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital,wght@0,400;1,400&family=Raleway:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { font-family: 'Raleway', sans-serif; background-color: #faf6f0; color: #2c1a0e; margin: 0; min-height: 100vh; display: flex; flex-direction: column; }
        h1, h2 { font-family: 'DM Serif Display', serif; }
    </style>
</head>
<body>
    <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem; min-height: 80vh;">
        <div style="text-align: center; max-width: 400px;">
            <div style="width: 80px; height: 80px; background-color: #f0e9de; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 2rem; color: #8b5e3c;"></i>
            </div>
            <p style="color: rgba(139,94,60,0.6); letter-spacing: 0.3em; text-transform: uppercase; font-size: 11px; font-weight: 500; margin-bottom: 0.75rem;">Error 500</p>
            <h1 style="font-size: 2.5rem; color: #2c1a0e; margin-bottom: 1rem;">Algo salió mal</h1>
            <p style="color: rgba(139,94,60,0.7); font-size: 0.875rem; line-height: 1.75; margin-bottom: 2rem;">
                Ocurrió un error inesperado en el servidor. Nuestro equipo ya fue notificado. Intentá nuevamente en unos minutos.
            </p>
            <a href="/" style="display: inline-flex; align-items: center; gap: 0.5rem; background-color: #386641; color: #faf6f0; padding: 0.75rem 1.5rem; font-size: 13px; letter-spacing: 0.1em; text-decoration: none; font-weight: 500;">
                <i class="fa-solid fa-house" style="font-size: 11px;"></i> Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
