Banners (opg-banners)
=====================

Developer: Óscar Pérez (www.oscarperez.es)
Tested up to: 6.6
Stable tag: 2.0.0
License: GPLv2 or later

== Descripción ==

Plugin de WordPress para gestionar y mostrar un conjunto de banners
(nombre + URL de destino + imagen).

- Panel de administración con alta, edición y borrado de banners.
- Selección de la imagen mediante el cargador de medios de WordPress.
- Shortcode `[banners]` para mostrar los banners en cualquier página o entrada.
- Atributo opcional `columnas` (1 a 4, por defecto 1):
  `[banners columnas="2"]`.

== Estructura de ficheros ==

- `opg_banners.php` — fichero principal del plugin.
- `uninstall.php` — elimina la tabla de base de datos al desinstalar el plugin.
- `includes/db.php` — acceso a base de datos (creación de tabla vía `dbDelta`, CRUD).
- `includes/admin.php` — menú, formulario y listado de administración.
- `includes/public.php` — shortcode y carga de assets públicos.
- `includes/views/` — plantillas PHP usadas por admin.php y public.php.
- `assets/css/`, `assets/js/` — estilos públicos y script de administración.

== Base de datos ==

Tabla `{prefijo}opg_plugin_banners` (se mantiene el nombre histórico para no
perder datos de instalaciones anteriores):

- `idBanner` INT, autoincremental.
- `name` VARCHAR(100).
- `url` VARCHAR(140).
- `image` VARCHAR(255).

== Menú compartido ==

Este plugin se engancha como submenú "Banners" del menú compartido
"Oscar Pérez Plugins". Ese menú lo crea normalmente el plugin maestro
(Enlaces de Interés / weblinks); si no está activo, este plugin lo crea para
poder funcionar de forma autónoma.

== Instalación ==

1. Copia la carpeta `banners` en `wp-content/plugins`.
2. Activa el plugin desde el panel de WordPress.
3. Ve a "Oscar Pérez Plugins → Banners" y añade los banners.
4. Inserta el shortcode `[banners]` donde quieras mostrarlos.

== Changelog ==

= 2.0.0 =
Reescritura completa del plugin:
- Nuevo listado público mediante el shortcode `[banners]` (el plugin antes
  solo tenía backend de administración).
- Reestructuración en ficheros `includes/`, `includes/views/` y `assets/`.
- Corregidas vulnerabilidades: falta de nonces/CSRF en el formulario y en el
  borrado, y falta de sanitizado/escapado de entradas y salidas.
- El selector de imagen se moderniza de la API antigua (thickbox /
  `send_to_editor`) a la API `wp.media` de WordPress.
- Los scripts y estilos ya no se cargan en todo el sitio: los de
  administración solo en la pantalla del plugin, y los públicos solo cuando se
  usa el shortcode. Antes la ruta de assets apuntaba a `/opg_banners/` (e
  incluso a `/opg_opg_banners/` en un enlace, que estaba roto); ahora se usa
  `plugin_dir_url()`.
- Creación de tabla mediante `dbDelta` (actualiza instalaciones existentes sin
  perder datos) y borrado de la tabla movido a `uninstall.php` (antes se
  ejecutaba un `DROP TABLE` al desactivar, con riesgo de pérdida de datos).
- La columna `image` se amplía de VARCHAR(140) a VARCHAR(255).
- Los iconos de modificar/borrar usan ahora dashicons de WordPress en lugar de
  imágenes propias.
- El menú deja de imponerse como maestro: se engancha al menú compartido como
  plugin hermano.

= 1.0.0 = *Release Date - 2nd January, 2015
Primera versión operativa.
