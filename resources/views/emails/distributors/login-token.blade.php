<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Código de acceso</title>
</head>

<body style="margin:0; padding:0; background-color:#f5f5f5; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f5f5; padding: 20px;">
        <tr>
            <td align="center">
                <table width="500" cellpadding="0" cellspacing="0"
                    style="background-color:#ffffff; border-radius:8px; padding:24px;">
                    <tr>
                        <td style="text-align:left;">

                            <h2 style="margin-top:0; color:#333;">
                                Inicio de sesión
                            </h2>

                            <p style="color:#555; font-size:14px;">
                                Has solicitado iniciar sesión en el panel de distribuidores.
                            </p>

                            <p style="color:#555; font-size:14px;">
                                Tu código de acceso es:
                            </p>

                            <div style="
                            font-size: 28px;
                            font-weight: bold;
                            letter-spacing: 6px;
                            text-align: center;
                            margin: 20px 0;
                            color: #000;
                        ">
                                {{ $token }}
                            </div>

                            <p style="color:#555; font-size:14px;">
                                Este código expira en <strong>5 minutos</strong>.
                            </p>

                            <p style="color:#999; font-size:12px; margin-top:30px;">
                                Si no solicitaste este acceso, puedes ignorar este correo.
                            </p>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>