## Front/Back

<p> <img src="https://acortar.link/uVseSE" alt="TramiteSIP" width="400"></p>

### Tecnologías 📋

    * [Laravel](https://laravel.com/)
    * [VueJS](https://vuejs.org/)
    * [PosgreSQL](https://www.postgresql.org/)
    * [Nodejs](https://nodejs.org/en)
    * [PHP](https://www.php.net/)


### Instalación de dependencias de Laravel y Vue🔧 🚀

```bash
php composer.phar install
```

```bash
npm install
```

### 3. Levantar Back ⚙️
Abrir 2 consolas, ejecutar los siguientes comandos en paralelo 

```bash
php artisan serve
```

### 4. Levantar Front 📦
 
```bash
npm run watch
```


### 4. Abrir el Proyecto 📦
 
```bash
http://127.0.0.1:8000
```

<p> <img src="https://bit.ly/4h7QK8D" alt="TramiteSIP" width="400"></p>



### Configuración entornos de desarrollo 🔨
- Desarrollo / Calidad / Producción

```bash
php artisan variables
```

<p> <img src="https://bit.ly/4eL0x34" alt="TramiteSIP" width="400"></p>


<p> <img src="https://bit.ly/3A2L932" alt="TramiteSIP" width="100"></p>


### (Opcional) Configuración Makefile 🗜️
* [Makefile](https://www.gnu.org/software/make/manual/make.html)

Instalación en Linux

```bash
sudo make install
```

Instalación en Windows
 - Install the chocolatey manejador de paquetes para Windows
    * [Chocolatey](https://chocolatey.org/install)

```bash
choco install make
```

### Usar Makefile 

Levanta el proyecto

```bash
make run
```

Compartir el proyecto

```bash
make share
```

Limpiar Cache

```bash
make clear
```


### Comando de limpieza de Cache del proyecto 

Para el envio a cali - (https://cali-tramitesip.gestora.bo) utilizar el siguiente comando

```bash
php artisan clear:caches
```
En el formulario de cali mencionar tambien que se ejecute los comandos:

```bash
npm run dev
php artisan clear:caches
sudo systemctl restart httpd
```


<p> <img src="https://bit.ly/3Z17Y09" alt="TramiteSIP" width="600"></p>

Ejecución del comando

<p> <img src="https://bit.ly/44QKEGg" alt="TramiteSIP" width="600"></p>

_TramiteSIP_
