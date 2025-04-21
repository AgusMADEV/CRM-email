# CRM-email

Pequeña aplicación para listar y filtrar correos de un buzón IMAP usando PHP en el backend y Vanilla JS en el frontend.

- **back.php**: Conecta al servidor IMAP, obtiene los correos y contactos únicos, y devuelve JSON.
- **correos.js**: Consume `back.php`, genera la lista de contactos y los correos filtrados.
- **estilo.css**: Definición de estilos.
- **config_sample.json/config.json**: Parámetros de conexión.