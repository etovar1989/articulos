---
id: 1010
title: "Instalación y configuración de Scratch en una Red"
date: 2009-10-29
tags: scratch,programacion,guias uso
category: Herramientas
author: Grupo de investigación del MIT Media Lab
---

# Instalación y configuración de Scratch en una Red

> Este documento tiene como propósito principal ayudar a los administradores de sistemas a instalar y configurar Scratch en un entorno de red. Incluye indicaciones precisas para ocultar unidades de disco, inhabilitar los botones de Compartir y utilizar el instalador MSI. Además, muestra cómo personalizar, la carpeta de inicio predeterminada, las notas del proyecto predeterminadas, el objeto predeterminado y el idioma predeterminado.

Este documento tiene como propósito principal ayudar a los administradores de sistemas a instalar y configurar Scratch en un entorno de red. Incluye indicaciones precisas para ocultar unidades de disco, inhabilitar los botones de Compartir y utilizar el instalador MSI. Además, muestra cómo personalizar, la carpeta de inicio predeterminada, las notas del proyecto predeterminadas, el objeto predeterminado y el idioma predeterminado.

---

## INSTALACIÓN Y CONFIGURACIÓN DE SCRATCH 1.4

Este documento tiene como propósito principal ayudar a los administradores de sistemas a instalar y configurar Scratch en un entorno de red.

# REQUISITOS DEL SISTEMA

1. Sistema Operativo (S.O.): Windows 2000, Windows XP, Windows Vista, Mac OS X 10.4 o más reciente
2. Pantalla:  800x480 o más grande, miles o millones de colores (16-bits de color o más)
3. Disco: Como mínimo 120  megabytes de espacio libre para instalar Scratch
4. Sonido (opcional): Parlantes o audífonos; micrófono para grabación

 

# INSTALACIÓN Y PERSONALIZACIÓN EN UNA RED 

Usted puede personalizar la instalación de  Scratch 1.4 para que satisfaga sus necesidades particulares.  Por ejemplo, usted puede estar instalando Scratch en la red de una Institución Educativa (IE) y necesita controlar dónde se archivarán los proyectos de los usuarios de Scratch o qué unidades (drives) deberían ser visibles. Puede que usted necesite cambiar configuraciones para un servidor Proxy o que quiera cambiar el Objeto (sprite), el idioma, o las notas predeterminadas del proyecto.

Si usted desea personalizar su instalación de Scratch, primero descargue Scratch, instálelo y navegue por la carpeta Scratch en “c:\Archivos de programa”. Allí encontrará el archivo: Scratch.ini. que contiene los archivos principales que usted modificará.

 

# DÓNDE INSTALAR SCRATCH

Scratch busca varios archivos y folders dentro de la carpeta de Scratch. La carpeta de Scratch es la que contiene la aplicación Scratch (Scratch.exe o Scratch.app) y el archivo Scratch.image (aun cuando el nombre de la carpeta  sea diferente). Algunos de los fólderes que Scratch busca en la carpeta de Scratch son los de Ayuda, Medios y Proyectos. Scratch guarda sus configuraciones en el archivo Scratch.ini en la carpeta de Scratch.

Por tal motivo, es mejor conservar la estructura de la carpeta  de Scratch intacta y no cambiarle el nombre a ninguna de sus carpetas. 

 

**UNIDAD COMPARTIDA O INSTALADOR MSI**

En algunas instalaciones que están en red usted puede querer instalar una copia de Scratch solo en una unidad de la red.  En otros casos, puede desear instalar Scratch en todos los equipos.  En  este último caso, el [instalador MSI](http://download.scratch.mit.edu/Scratch1.4.msi), disponible en la página de descarga puede ayudarle a automatizar el proceso de instalación. 

 

# PERSONALIZAR LA CARPETA DE INICIO PREDETERMINADA

Por defecto, Scratch asume que la carpeta del usuario está en la unidad de disco local C:  

Sin embargo, en las configuraciones de redes, las carpetas de los usuarios se guardan con frecuencia en una unidad de la red. Si adiciona esta línea al archivo Scratch.ini:

         Home=J:\MiEscuela\Estudiantes\Grado5\*

le dirá a Scratch que las carpetas de los usuarios se guardan en la carpeta J:\MiEscuela\Estudiantes\Grado5\. Tenga en cuenta que el asterisco se reemplaza por el nombre del usuario registrado. Usted puede omitir el asterisco si quiere que todos los usuarios compartan la misma carpeta para guardar sus proyectos de Scratch. Esto podría hacerse para facilitar el trabajo colaborativo de los estudiantes.

 

# OCULTAR UNIDADES

NOTA: Únicamente se pueden ocultar unidades si se trabaja con Windows.

En las configuraciones de redes que usan Windows, a veces es útil limitar las unidades visibles para el usuario. Esto se puede hacer adicionando una línea como esta al archivo Scratch.ini:

 VisibleDrives=J:,M:

 Si se configuran unidades visibles, los usuarios no podrán ver ninguna otra unidad (incluyendo las unidades USB), y tampoco podrán navegar en forma ascendente en la jerarquía del archivo hacia partes del disco que estén ubicadas fuera de la carpeta de Scratch y de la carpeta de inicio del usuario.

 

# INHABILITAR LOS BOTONES “COMPARTIR”

En algunos casos, usted no quiere que los usuarios compartan en línea sus proyectos de Scratch.  Si adiciona la siguiente línea a Scratch.ini ocultará el menú y el botón “Compartir”:

 Share=0 

 

# PERSONALIZAR EL IDIOMA PREDETERMINADO

Scratch se inicia usando el idioma especificado en la “configuración regional y de idioma” del computador, pero esto se puede anular. En el archivo de Scratch.ini adicione la línea: 

 Language = [ISO-639-2 code] 

Tenga en cuenta que esta configuración cambiará cada que el usuario modifique la configuración del idioma (si puede escribirse en el archivo Scratch.ini) de manera que Scratch se iniciará en el idioma utilizado por última vez.

 

# SOBRE SERVIDORES PROXY

Las configuraciones de los servidores Proxy se pueden especificar en el archivo ini. Usando las siguientes entradas: 

ProxyServer=[nombre del servidor o dirección IP]

ProxyPort=[número del puerto]

 

# PERSONALIZAR EL OBJETO PREDETERMINADO

Usted puede cambiar la configuración predeterminada del objeto gato, y reemplazarlo por su propio objeto (sprite). Su objeto predeterminado (por defecto) puede incluir múltiples disfraces, sonidos, e inclusive programas (script). Para hacerlo, simplemente cree su objeto y expórtelo. (Para exportarlo, haga clic derecho sobre el objeto y seleccione “exportar este objeto”). Luego renombrelo como “default.sprite” y ubíquelo en la carpeta Costumes (Disfraces).  

 

# PERSONALIZAR LAS NOTAS DEL PROYECTO PREDETERMINADAS (POR DEFECTO)

Algunas personas han solicitado dar a los usuarios ejemplos de preguntas guía o de instrucciones cuando editan por primera vez las notas para un proyecto.  Simplemente haga un archivo de texto con sus notas predeterminadas, guárdelas con codificación UTF8, denomínela defaultNotes.txt y póngala en la carpeta c:\Archivos de Programa\Scratch

Si el usuario no edita el texto de las notas predeterminadas, nada se guardará en las notas del proyecto. Esto con el propósito de prevenir que muchos proyectos con notas de texto predeterminadas aparezcan el sitio Web de Scratch. El usuario tiene que cambiar por lo menos un carácter para que se guarden las notas en el proyecto. 

# PARA MÁS INFORMACIÓN

Para información adicional, favor consultar [http://scratch.mit.edu](http://scratch.mit.edu/) 

 

**CRÉDITOS:**

Traducción al español por Eduteka del documento “Network Installation and Customization”, elaborado por el grupo de investigación del MIT Media Lab, “Lifelong Kindergarten Group” (Jardín Infantil para toda la vida). http://scratch.mit.edu

![m](https://eduteka.icesi.edu.co/imgbd/23/motorola_foundation_logo.gif)

Este documento se tradujo con el apoyo de Motorola Foundation, Motorola de Colombia Ltda. y la gestión de la ONG Give to Colombia.

 

*Fecha de publicación en EDUTEKA: Noviembre 1 de 2009.

Fecha de la última actualización: Noviembre 1 de 2009.*
