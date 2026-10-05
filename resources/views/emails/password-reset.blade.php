<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Restablecer contraseña — Sukha</title>
<style>
  body { margin:0; padding:0; background:#F5F0E8; font-family: Georgia, serif; }
  .wrap { max-width:560px; margin:40px auto; background:#FAF7F0; border-radius:24px; overflow:hidden; border:1px solid #D4E5D2; }
  .header { background:linear-gradient(135deg,#2C3E35,#3D5247); padding:36px 40px; text-align:center; }
  .header-logo { display:flex; align-items:center; justify-content:center; gap:12px; margin-bottom:6px; }
  .logo-text { font-size:28px; font-weight:700; color:white; letter-spacing:-0.5px; }
  .logo-sub { font-size:11px; letter-spacing:3px; text-transform:uppercase; color:#B5C9B3; }
  .body { padding:40px; }
  h2 { font-size:22px; font-weight:700; color:#2C3E35; margin:0 0 12px; }
  p { font-size:14px; color:#4A5A52; line-height:1.7; margin:0 0 16px; }
  .btn { display:block; width:fit-content; margin:28px auto; padding:14px 36px; background:linear-gradient(135deg,#7D9B76,#8FAF88); color:white !important; text-decoration:none; border-radius:14px; font-size:15px; font-weight:700; letter-spacing:0.3px; }
  .link-backup { font-size:12px; color:#6B7B6E; word-break:break-all; }
  .divider { border:none; border-top:1px solid #E8EDE6; margin:24px 0; }
  .notice { font-size:12px; color:#9BA8A0; line-height:1.6; }
  .footer { background:#EAF2E9; padding:20px 40px; text-align:center; font-size:11px; color:#7D9B76; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <div class="header-logo">
      <svg width="32" height="32" viewBox="0 0 64 64" fill="none">
        <path d="M32 44 C22 40 16 28 20 16 C24 24 28 34 32 44Z" fill="#8FAF88"/>
        <path d="M32 44 C42 40 48 28 44 16 C40 24 36 34 32 44Z" fill="#8FAF88"/>
        <path d="M32 44 C26 32 26 18 32 8 C38 18 38 32 32 44Z" fill="#7D9B76"/>
      </svg>
      <span class="logo-text">Sukha</span>
    </div>
    <p class="logo-sub">Tu espacio de calma</p>
  </div>

  <div class="body">
    <h2>Restablecer tu contraseña</h2>
    <p>Hola{{ $name ? ', ' . explode(' ', $name)[0] : '' }}.</p>
    <p>Recibimos una solicitud para restablecer la contraseña de tu cuenta en Sukha. Si fuiste tú, usa el siguiente botón para crear una nueva contraseña.</p>

    <a href="{{ $url }}" class="btn">Restablecer contraseña</a>

    <p>Si el botón no funciona, copia y pega este enlace en tu navegador:</p>
    <p class="link-backup">{{ $url }}</p>

    <hr class="divider">

    <p class="notice">
      Este enlace expirará en <strong>60 minutos</strong>. Si no solicitaste este cambio, puedes ignorar este correo. Tu contraseña no cambiará a menos que hagas clic en el enlace anterior.<br><br>
      Por seguridad, nunca compartimos contraseñas por correo electrónico. Sukha jamás te pedirá tu contraseña actual.
    </p>
  </div>

  <div class="footer">
    Sukha &middot; Tu espacio de calma &middot; Este correo fue generado automáticamente.
  </div>
</div>
</body>
</html>
