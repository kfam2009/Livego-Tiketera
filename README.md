# Livego Ticketera (versión rescatada)

Sistema para vender/entregar acceso a transmisiones: el admin carga un evento con el link del stream,
genera usuarios y claves, y cada espectador entra con los suyos y ve el show embebido.
Un acceso no se puede compartir: si se usa en otro dispositivo, se cierra la sesión anterior.

## Cómo se usa (admin)

1. Entrá a `/users/login` con el usuario admin.
2. **Eventos → Agregar Evento**: título, descripción, imagen, **link** (la URL *embed* del stream:
   YouTube `https://www.youtube.com/embed/ID`, Vimeo, o tu página de reproductor), fecha de inicio y de fin.
   El show se puede ver solo entre esas dos fechas (hora de Argentina).
3. En la lista de eventos tocá **Accesos**:
   - **Generar accesos nuevos**: elegís cantidad (1 a 500), el comienzo del usuario y una referencia.
     Se crean usuario y clave al azar (ej. `final4827` / `nq6938`) ya habilitados para ese evento.
     En la pantalla siguiente podés **mandarlos por WhatsApp**, **copiar** o **descargar la planilla CSV**.
     ⚠️ Las claves se ven una sola vez. Si alguien la pierde: **Usuarios → 🔑** genera una nueva.
   - **Agregar un espectador existente**: para alguien que ya tenía usuario.
   - 🗑 quita a un espectador del evento (no borra al usuario ni el evento).

El espectador entra al mismo link de login con su usuario (o email) y clave, y ve "Mis Eventos".

## Venta online, transmisión protegida y repeticiones

- **Cartelera pública** (`/`): partidos con venta activa. **Comprar** → Mercado Pago (Checkout Pro) →
  al aprobarse el pago se crean solos los usuarios y claves, se muestran en pantalla y se mandan por email.
  Aviso de pagos en `/pagos/mercadopago` (se verifica siempre contra la API de Mercado Pago).
  Pagos devueltos o desconocidos → los usuarios quedan inactivos.
- **Transmisión propia** (evento con "Propia y protegida"): se crea una entrada en Cloudflare Stream;
  en **Eventos → vMix/OBS** están el servidor RTMPS/SRT y la clave. El espectador ve el video con un
  token firmado que vence (no se puede compartir el link). Se graba solo.
- **Repetición**: después del final, los que compraron pueden ver la grabación los días que diga el evento.
  Desde la misma página se baja el MP4.
- **Partidos anteriores** (`/partidos-anteriores`): eventos con link de YouTube cargado.
- **Ventas** (menú del admin): compras, estados, reenviar email, revisar pagos pendientes, ver accesos.

## Configuración (variables de entorno)

| Variable | Para qué |
|---|---|
| `DATABASE_URL` | `postgres://...` o `mysql://...` (Render la completa sola) |
| `DB_DRIVER`, `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS` | Alternativa a `DATABASE_URL` (`DB_DRIVER`: mysql o postgres) |
| `ADMIN_USER`, `ADMIN_PASSWORD` | Al arrancar crea o actualiza ese admin (así se recupera la clave) |
| `SECURITY_SALT` | Clave interna para cifrar contraseñas (Render la genera). Si cambia, las claves existentes dejan de andar |
| `CF_ACCOUNT_ID`, `CF_API_TOKEN` | Cloudflare Stream (token con permiso Account → Stream → Edit) |
| `CF_CUSTOMER_CODE` | El `xxxx` de `customer-xxxx.cloudflarestream.com` (opcional, se detecta solo) |
| `MP_ACCESS_TOKEN` | Mercado Pago: Access Token (de prueba `TEST-…` o de producción `APP_USR-…`) |
| `BREVO_API_KEY`, `MAIL_FROM`, `MAIL_FROM_NAME` | Emails con los accesos (Brevo). `MAIL_FROM` tiene que estar verificado en Brevo |
| `APP_URL` | URL pública si no es la de Render (dominio propio) |
| `APP_DEBUG` | `0` en producción (por defecto). `2` muestra errores para depurar |
| `APP_TIMEZONE` | Por defecto `America/Argentina/Buenos_Aires` |
| `IMPORTAR_DATOS` | `0` para NO cargar los usuarios viejos en una base nueva |

Al arrancar con Docker, `bin/init.php` crea las tablas si la base está vacía (`db/schema.pgsql.sql` o `db/schema.sql`),
carga los datos viejos si está `db/datos-originales.sql`, y aplica el admin.

## Opción A — Render (recomendada)

Todo queda en Render: la web (Docker) y la base **PostgreSQL**. Las imágenes de los eventos se guardan
dentro de la base, así que no hace falta disco.

1. En Render: **New + → Blueprint** → elegí este repo. Lee `render.yaml` y crea:
   - `livego-ticketera` (web, plan Free)
   - `livego-db` (Postgres, plan Free)
2. Dale **Apply**. Render genera sola la clave del admin: la ves en el servicio web → **Environment → ADMIN_PASSWORD** (ojito para mostrarla). Si querés otra, la cambiás ahí y redeployás.
3. Esperá el deploy (5–10 min la primera vez). La URL queda tipo `https://livego-ticketera.onrender.com`.
   Las tablas se crean solas en el primer arranque.
4. Entrá a `/users/login` con `admin` y tu clave.

**Plan gratis — tené en cuenta:**
- La web se "duerme" después de 15 min sin visitas y tarda ~1 minuto en despertar.
  **Antes de cada partido entrá vos un rato antes** para que esté despierta.
- La base gratis **vence a los 30 días** (hay 14 días más de gracia, después se borra).
- Para pasar a pago: en Render, entrá a cada servicio → **Settings → Instance Type**
  (web: *Starter*; base: *Basic*). Hacelo antes de que venza la base.
- ¿Olvidaste la clave del admin? Cambiá `ADMIN_PASSWORD` en *Environment* y redeployá.

## Opción B — Hosting con cPanel (MySQL)

Requisito: **PHP 8.0 o superior** con `pdo_mysql`, `intl` y `mbstring` (en cPanel: *Select PHP Version*).

1. Creá una base MySQL y un usuario en cPanel.
2. En phpMyAdmin importá `db/schema.sql` y, si querés los usuarios viejos, `db/datos-originales.sql`
   (ese archivo no está en GitHub: está en el zip del rescate).
3. Subí todo el contenido a la raíz del dominio/subdominio.
4. Agregá arriba de `app/webroot/.htaccess`:
   ```
   SetEnv DB_HOST localhost
   SetEnv DB_NAME tu_base
   SetEnv DB_USER tu_usuario
   SetEnv DB_PASS tu_clave
   ```
5. Para crear el admin, desde la terminal de cPanel:
   `ADMIN_USER=admin ADMIN_PASSWORD='tu-clave' DB_HOST=localhost DB_NAME=... DB_USER=... DB_PASS=... php bin/init.php`
6. Permisos de escritura en `app/tmp` y subcarpetas.

## Opción C — Probar en tu PC

Con Docker Desktop: `docker compose up` y entrá a <http://localhost:8080>
(admin: `admin` / `cambiar-esta-clave`, cambialo en `docker-compose.yml`).

## Qué se cambió respecto del sistema viejo

- CakePHP 2.10.16 → fork compatible con PHP 8 ([kamilwylegala/cakephp2-php8](https://github.com/kamilwylegala/cakephp2-php8), 2.10.24)
  + polyfill de `strftime` en `vendors/strftime`. El original solo andaba con PHP 7.4.
- **Bug corregido:** el tachito en "Espectadores del evento" borraba un *evento* en lugar de la suscripción.
- Nuevo: generador de accesos (usuario + clave) con envío por WhatsApp, copia y CSV.
- Nuevo: soporte PostgreSQL (Render) y las imágenes de eventos se guardan en la base.
- Nuevo: regenerar la clave de un espectador desde Usuarios (🔑).
- Editar un usuario ya no rompe su clave si no se escribe una nueva. No se permiten usuarios repetidos.
- Borrar usuarios desde la lista funciona (antes daba error).
- El login acepta usuario o email. Se respeta también la fecha de **fin** del evento al entrar al show.
- Claves de la base fuera del código (variables de entorno). Modo debug apagado por defecto.
- Se quitaron logs viejos, archivos del panel de cPanel y el `test.php` público.

## Pendientes conocidos

- Las claves se guardan con SHA1 + salt (como el sistema original, para que las claves viejas sigan andando).
  No cambies `SECURITY_SALT` una vez en uso o dejan de funcionar todas.
- En los datos viejos hay emails repetidos (ej. dos usuarios con el mismo email): si alguno no puede
  entrar, revisá en Usuarios y borrá el duplicado.
- "Olvidé mi contraseña" no envía mails: la clave se regenera desde el admin.
