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
                <img src="{{ asset('storage/logo-ivars.png') }}" alt="IVARSCOM" style="max-width: 200px; margin-bottom: 20px;">
            </td>
        </tr>

        <tr>
            <td style="text-align: justify;">
                <p style="font-size: 16px; color: #333;">
                    Estimado/a cliente,
                </p>

                <p style="font-size: 16px; color: #333;">
                    Le informamos que el dominio <strong>{{ $dominio }}</strong> está programado para renovarse el día <strong>{{ $fecha->format('d/m/Y') }}</strong>.
                </p>
                <p style="font-size: 16px; color: #333;">Cualquier duda por favor avisen 20 días antes del día de renovación..</p>

                <p style="font-size: 16px; color: #333;">
                    Si no recibimos una respuesta o contacto por su parte antes de esa fecha,
                    procederemos automáticamente con la renovación del dominio según lo establecido en nuestros términos y condiciones.
                </p>

                <p style="font-size: 16px; color: #333;">
                    Si desea realizar alguna modificación o no desea continuar con la renovación, por favor, póngase en contacto con nosotros.
                </p>
                <p style="font-size: 15px; color: #333;">
                    <strong>Nota:</strong> En caso de no comunicar la baja del dominio, IVARSCOM AGENCIA DE PUBLICIDAD, generarà la factura automáticamente.
                </p>

                <p style="font-size: 16px; color: #333;">
                    Gracias por confiar en nuestros servicios.
                </p>

                <p style="font-size: 14px; color: #333;">
                    Atentamente, <strong>Ivarscom Agencia de Publicidad S.L.U</strong><br>
                    Departamento web - <a href="mailto:web@ivarscomagenciadepublicidad.com" style="text-decoration: none; color: #333">web@ivarscomagenciadepublicidad.com</a><br>
                    Departamento de administración - <a href="mailto:admin@ivarscomagenciadepublicidad.com" style="text-decoration: none; color: #333">admin@ivarscomagenciadepublicidad.com</a>
                    <br>Tel. <a href="tel:620 72 54 60" style="text-decoration: none; color: #333">620 72 54 60</a>
                </p>
                <hr />
                <small style="color: #7d7d7d">AVISO LEGAL
                    Este mensaje y sus ficheros adjuntos tienen carácter privado y confidencial y van dirigidos exclusivamente a sus destinatarios.
                    Si ha recibido este mensaje por error, no debe revelarlo, copiarlo o distribuirlo en ningún sentido sin previo consentimiento por escrito de Ivarscom Agencia de Publicidad S.L.U.
                    Rogamos lo comunique al remitente y elimine dicho mensaje y cualquier documento adjunto que pudiera contener. De no hacerlo así puede vulnerar la legislación vigente.
                </small>
            </td>
        </tr>
    </table>
</body>
</html>
