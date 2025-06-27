<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Renovación de dominio</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">

    <table width="100%" style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px;">
        <tr>
            <td style="text-align: center;">
                <img src="{{ asset('images/logo_agencia.png') }}" alt="Agencia Creativa" style="max-width: 200px; margin-bottom: 20px;">
            </td>
        </tr>

        <tr>
            <td>
                <p style="font-size: 16px; color: #333;">
                    Estimado/a cliente,
                </p>

                <p style="font-size: 16px; color: #333;">
                    Le informamos que el dominio <strong>{{ $dominio }}</strong> está programado para renovarse el día <strong>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</strong>.
                </p>

                <p style="font-size: 16px; color: #333;">
                    Si no recibimos una respuesta o contacto por su parte antes de esa fecha, procederemos automáticamente con la renovación del dominio según lo establecido en nuestros términos y condiciones.
                </p>

                <p style="font-size: 16px; color: #333;">
                    Si desea realizar alguna modificación o no desea continuar con la renovación, por favor, póngase en contacto con nuestro equipo antes del <strong>{{ \Carbon\Carbon::parse($fecha)->subDays(2)->format('d/m/Y') }}</strong>.
                </p>

                <p style="font-size: 16px; color: #333;">
                    Gracias por confiar en nuestros servicios.
                </p>

                <p style="font-size: 16px; color: #333;">
                    Atentamente,<br>
                    <strong>Ivarscom Agencia de Publicidad S.L.U</strong><br>
                    <a href="mailto:info@ivarscom.com">info@ivarscom.com</a><br>
                    Tel. 644 28 06 05
                </p>
            </td>
        </tr>
    </table>
</body>
</html>

