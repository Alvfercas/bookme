# BookMe
Pequeña prueba de gestión de mesas, disponibilidad y reservas para un restaurante.

## Construido con 🛠️
Para el desarrollo de esta pequeña prueba se ha usado:
*  [Codeigniter](https://codeigniter.com/userguide3/index.html) - Framework PHP
*  [CodeIgniter RestServer](https://github.com/chriskacerguis/codeigniter-restserver) - Librería para desarrollo de API REST

## Despliegue 📦
Se sobreentiende que para poder usar la aplicación, tenemos un servidor local para ejecutar PHP. Si no, podemos crear uno rápidamente con **WAMPP** (Windows), **MAMPP** (Mac OS), **LAMPP** (Distro LINUX) o **XAMPP** (Cualquiera).

Una vez clonado el repositorio, necesitamos importar la base de datos, instalar algunas dependencias (opcional) y terminar de configurar el proyecto para nuestro entorno.
### Base de datos
_Para este proyecto se ha usado **MySQL** con ayuda de **phpMyAdmin**._ 

Empezaremos con la creación de la base de datos, llamándola por ejemplo **bookme**. Una vez creada, pasaremos a importar la base de datos con la estructura y datos iniciales.

Se puede hacer con la herramienta de importación o ejecutando el script, siempre con la base de datos anteriormente creada seleccionada.

El script para importar o ejecutar está en **db/bookme.sql**.

### Dependencias
_NOTA: Estas dependencias son necesarias para poder usar el servicio REST de la aplicación. Si no se quiere usar la API, se puede saltar todo el bloque de dependencias._

Antes debemos asegurarnos de que el archivo **composer.json** contiene:
```
{
	"require":  {
		"chriskacerguis/codeigniter-restserver":  "^3.1"
	}
}
```
Una vez comprobado, iremos al directorio **root** del proyecto desde la consola y instalaremos las dependencias con:
``` console
$ composer install
```
### Configuración del proyecto
Hay algunos archivos que, al depender del entorno en el que se despliegue y privacidad de algunas variables, son necesarios crearlos y hacer unos cambios en algunos parámetros.
* **application/config/config.php**:
Se puede usar como base el archivo que está en el mismo directorio, **config_sample.php**. Una vez creado el archivo, cambiaremos los valores:
	* **```$config['base_url']```**: _Pondremos la URL en la que el proyecto está alojado. Ej: http://localhost/bookme_
	* **```$config['encryption_key']```**: _Pondremos una cadena de 32 caracteres para poder cifrar y descifrar datos. Ej: abb90ae2724543a4161120c315ccve2d_
	* **```$config['composer_autoload']```**: _NOTA: No será necesario si no se han instalado las dependencias. Indicaremos en qué directorio debe buscar el autoload de composer. Ej: FCPATH.'vendor/autoload.php'_
* **application/config/database.php**:
Al igual que el archivo **config.php**, se puede usar como base el archivo que está en el mismo directorio **database_sample.php**. Una vez creado, cambiaremos los valores:
	* **```username```**: _Nombre del usuario que accederá a la base de datos. Ej: root_
	* **```password```**: _Contraseña, si la hay, del usuario que accederá a la base de datos. Ej: (vacía)_
	* **```database```**: _Nombre de la base de datos creada anteriormente. Ej: bookme_

Tenemos el archivo **.htaccess** en el raíz del proyecto con varias reglas para quitar de la URL `index.php`, quedando una URL más amigable. Para que funcione, debemos tener activo el `mod_rewrite` en el servidor. 
([Activar mod_rewrite](https://www.google.com/search?q=enable+mod_rewrite))

Una vez terminado con la configuración estaremos listos para empezar a probar.

## Pruebas 📋
Ahora se puede empezar a probar el proyecto que dividiremos en 3 partes. Cada una de ellas puede usarse mediante los controladores de Codeigniter o bien con la API (_si se han instalado previamente las dependencias necesarias_).

Para los controladores, al no haber realmente una interfaz que ayude a insertar los datos necesarios, en cada una de las funciones están definidos los parámetros que se van a usar. Para mayor comodidad, cambiando y probando valores, se aconseja usar la API.

Para la API, se puede usar **Postman**. En el directorio raiz del proyecto, hay un archivo para importar la colección directamente en el programa y no tener que crearlas desde 0: **Bookme.postman_collection.json**. Al importar la colección tendremos acceso a todas las llamadas implementadas.

### Mesas
### ```GET```
Para obtener todas o sólo una mesa, cuando se pasa el identificador.
### Parametros
* **opcional** `idMesa`: _Identificador de la mesa_
### Endpoints
* #### Controlador
	* `url/mesa/get`
	* `url/mesa/get/{idMesa}`
* #### API
	* `url/api/mesa`
	* `url/api/mesa/{idMesa}`
### Respuestas
Ejemplo de respuesta correcta:
``` json
{
	"status": 200,
	"message": "Mesa encontrada",
	"response": {
		"idMesa": "1",
		"aforoMin": "1",
		"aforoMax": "2"
	}
}
```
Ejemplo de respuesta errónea:
``` json
{
	"status": 404,
	"message": "Mesa no encontrada",
	"response": null
}
```
---
### ```POST```
Para añadir una nueva mesa.
### Parametros
* **required** `aforoMin`:  _Número mínimo de personas para la mesa_
* **required** `aforoMax`:  _Número máximo de personas para la mesa_
### Endpoints
* #### Controlador
	* `url/mesa/post`
* #### API
	* `url/api/mesa`
### Respuestas
Ejemplo de respuesta correcta:
``` json
{
	"status": 200,
	"message": "Mesa creada correctamente",
	"response": {
		"idMesa": "2",
		"aforoMin": "1",
		"aforoMax": "2"
	}
}
```
Ejemplo de respuesta errónea:
``` json
{
	"status": 400,
	"message": "Parámetro aforoMax no enviado",
	"response": null
}
```
---
### ```PUT```
Para editar la mesa del identificador indicado en la URL.
### Parametros
* **required** `idMesa`: _Identificador de la mesa_
* **required** `aforoMin`:  _Número mínimo de personas para la mesa_
* **required** `aforoMax`:  _Número máximo de personas para la mesa_
### Endpoints
* #### Controlador
	* `url/mesa/put/{idMesa}`
* #### API
	* `url/api/mesa/{idMesa}`
### Respuestas
Ejemplo de respuesta correcta:
``` json
{
	"status": 200,
	"message": "Mesa actualizada correctamente",
	"response": {
		"idMesa": "1",
		"aforoMin": "3",
		"aforoMax": "4"
	}
}
```
Ejemplo de respuesta errónea:
``` json
{
	"status": 400,
	"message": "Parámetro aforoMax no enviado",
	"response": null
}
```
---
### ```DELETE```
Para eliminar una mesa.
### Parametros
* **required** `idMesa`: _Identificador de la mesa_
### Endpoints
* #### Controlador
	* `url/mesa/del/{idMesa}`
* #### API
	* `url/api/mesa/{idMesa}`
### Respuestas
Ejemplo de respuesta correcta:
``` json
{
	"status": 200,
	"message": "Mesa eliminada correctamente",
	"response": null
}
```
Ejemplo de respuesta errónea:
``` json
{
	"status": 400,
	"message": "Parámetro id no enviado",
	"response": null
}
```
---
---
### Reservas
### `GET`
Para obtener todas o sólo una reserva, cuando se pasa el identificador. También se le puede pasar como parámetro adicional `mesa/{idMesa}` para obtener las reservas de una mesa en concreto.
### Parametros
* **opcional** `idReserva`: _Identificador de la reserva_
* **opcional** `idMesa`: _Identificador de la mesa_
### Endpoints
* #### Controlador
	* `url/reserva/get`
	* `url/reserva/get/{idReserva}`
	* `url/reserva/get/mesa/{idMesa}`
* #### API
	* `url/api/reserva`
	* `url/api/reserva/{idReserva}`
	* `url/api/reserva/mesa/{idMesa}`
### Respuestas
Ejemplo de respuesta correcta:
``` json
{
	"status": 200,
	"message": "Reserva encontrada",
	"response": {
		"idReserva": "1",
		"idMesa": "1",
		"fecha": "01-01-2020",
		"comensales": "2",
		"nombreReserva": "John Doe",
		"codigoReserva": "W597RV"
	}
}
```
Ejemplo de respuesta errónea:
``` json
{
	"status": 404,
	"message": "Reserva no encontrada",
	"response": null
}
```
---
### `POST`
Para crear una nueva reserva.
### Parametros
* **required** `idMesa`:  _Identificador de la mesa a la que se va a hacer reserva_
* **required** `fecha`:  _Fecha de la reserva (dd-mm-yyyy)_
* **required** `comensales`:  _Número de personas para la reserva_
* **required** `nombreReserva`:  _Nombre del titular de la reserva_
### Endpoints
* #### Controlador
	* `url/reserva/post`
* #### API
	* `url/api/reserva`
### Respuestas
Ejemplo de respuesta correcta:
``` json
{
	"status": 200,
	"message": "Reserva creada correctamente",
	"response": {
		"idMesa": "2",
		"aforoMin": "1",
		"aforoMax": "2"
	}
}
```
Ejemplo de respuesta errónea:
``` json
{
	"status": 400,
	"message": "Parámetro aforoMax no enviado",
	"response": null
}
```
---
### `PUT`
Para editar una reserva del identificador indicado en la URL.
### Parametros
* **required** `idReserva`: _Identificador de la reserva_
* **required** `idMesa`:  _Identificador de la mesa a la que se va a hacer reserva_
* **required** `fecha`:  _Fecha de la reserva (dd-mm-yyyy)_
* **required** `comensales`:  _Número de personas para la reserva_
* **required** `nombreReserva`:  _Nombre del titular de la reserva_
### Endpoints
* #### Controlador
	* `url/reserva/put/{idReserva}`
* #### API
	* `url/api/reserva/{idReserva}`
### Respuestas
Ejemplo de respuesta correcta:
``` json
{
	"status": 200,
	"message": "Mesa actualizada correctamente",
	"response": {
		"idMesa": "1",
		"aforoMin": "3",
		"aforoMax": "4"
	}
}
```
Ejemplo de respuesta errónea:
``` json
{
	"status": 400,
	"message": "Parámetro aforoMax no enviado",
	"response": null
}
```
---
### ```DELETE```
Para eliminar una reserva.
### Parametros
* **required** `idReserva`: _Identificador de la reserva_
### Endpoints
* #### Controlador
	* `url/reserva/del/{idReserva}`
* #### API
	* `url/api/reserva/{idReserva}`
### Respuestas
Ejemplo de respuesta correcta:
``` json
{
	"status": 200,
	"message": "Reserva eliminada correctamente",
	"response": null
}
```
Ejemplo de respuesta errónea:
``` json
{
	"status": 400,
	"message": "Parámetro id no enviado",
	"response": null
}
```
---
---
### Disponibilidad
### `GET`
Para saber qué mesas están disponibles dada una fecha y número de comensales.
### Parametros
* **required** `fecha`: _Día elegido para ver la disponibilidad (dd-mm-yyyy)_
* **required** `comensales`: _Número de personas para ver la disponibilidad_
### Endpoints
* #### Controlador
	* `url/reserva/disponibilidad/{fecha}/comensales/{comensales}`
* #### API
	* `url/api/disponibilidad/{fecha}/comensales/{comensales}`
### Respuestas
Ejemplo de respuesta correcta:
``` json
{
	"status":  200,
	"message":  "Mesas disponibles",
	"response":  [
		{
			"idMesa":  "1",
			"aforoMin":  "1",
			"aforoMax":  "2"
		},
		{
			"idMesa":  "2",
			"aforoMin":  "1",
			"aforoMax":  "2"
		},
		{
			"idMesa":  "3",
			"aforoMin":  "3",
			"aforoMax":  "4"
		},
		{
			"idMesa":  "4",
			"aforoMin":  "3",
			"aforoMax":  "4"
		}
	]
}
```
Ejemplo de respuesta errónea:
``` json
{
	"status": 400,
	"message": "Parámetro comensales no enviado",
	"response": null
}
```