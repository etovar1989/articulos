---
id: 2096
title: "Configuración de la Tarjeta de Sensores (TDS)"
date: 2012-06-01
tags: scratch,programacion,robotica,ciencias naturales,guias uso,videos
category: Herramientas
author: Carlos Andrés Ávila
---

# Configuración de la Tarjeta de Sensores (TDS)

> Eduteka presentó hace poco su Tarjeta de Sensores (TDS). Esta permite obtener mediante sensores datos provenientes del mundo físico y utilizarlos para que objetos de proyectos Scratch respondan a diferentes estímulos. Para que Scratch pueda leer esos datos provenientes de la Tarjeta, esta necesita una configuración previa. Queremos facilitar esta acción con este documento.

Eduteka presentó hace poco su Tarjeta de Sensores (TDS). Esta permite obtener mediante sensores datos provenientes del mundo físico y utilizarlos para que objetos de proyectos Scratch respondan a diferentes estímulos. Para que Scratch pueda leer esos datos provenientes de la Tarjeta, esta necesita una configuración previa. Queremos facilitar esta acción con este documento.

---

 



# **CONFIGURACIÓN DE LA**

**TARJETA DE SENSORES (TDS)**



 



**![imagen](http://eduteka.org/img/portadas/tds2.jpg) **



 



El entorno gráfico de programación de computadores Scratch ofrece muchas posibilidades educativas y una de ellas consiste en leer datos provenientes de sensores que capturan estímulos del mundo físico. Esto es posible mediante la utilización de dispositivos como la[Tarjeta de Sensores](https://eduteka.icesi.edu.co/modulos/9/373/2089/1) (TDS) [1] que permiten a los objetos de los proyectos Scratch responder a diferentes estímulos externos.



Sin embargo, para que Scratch pueda leer los datos provenientes de la TDS, es necesario configurarla previamente, operación que se muestra detalladamente en este documento. Esto nos asegura que los proyectos Scratch respondan a los estímulos provenientes tanto de los sensores que vienen con la TDS (Luz, Sonido, Botón y Deslizador), como de aquellos que se conecten por medio de caimanes (pinzas) a cualquiera de los cuatro puertos de entrada (A, B, C, D).



 



![imagen](https://www.eduteka.org/imgbd/25/25-04/TDS.jpg)



TDS fabricada por A&S Design [1]



 















 



**1. INSTALAR CONTROLADORES/DRIVERS**

Para que el sistema operativo del computador reconozca la TDS, se debe instalar el controlador o driver [2] correspondiente (Win XP, Win 7, Linux, Mac).

En la siguiente tabla encontrará los enlaces de descarga de los controladores para diferentes sistemas operativos:





| 

**x86 (32-bit)**


| 

**x64 (64-bit)**


|


| 

[Windows XP](https://eduteka.icesi.edu.co/pdfdir/WinXP_CDM_2_06_00.exe)


| 

 


|


| 

[Windows 7 / Vista](https://eduteka.icesi.edu.co/pdfdir/Win7_CDM_2_06_00.exe)


| 

[Windows XP / 7 / Vista](https://eduteka.icesi.edu.co/pdfdir/CDM_2_08_24_WHQL_Certified.zip)


|


| 

[Linux](https://eduteka.icesi.edu.co/pdfdir/x86_ftdi_sio.tar.gz)


| 

[Linux](https://eduteka.icesi.edu.co/pdfdir/x64_ftdi_sio.tar.gz)


|


| 

[Mac](https://eduteka.icesi.edu.co/pdfdir/x86_FTDIUSBSerialDriver_v2_2_17.dmg)


| 

[Mac](https://eduteka.icesi.edu.co/pdfdir/x64_FTDIUSBSerialDriver_v2_2_17.dmg)


|




 



Después de descargar el controlador, haga doble clic en el archivo y siga los pasos que el instalador le indique.



 



**2. CONECTAR LA TDS**

Después de instalar los controladores, conecte la TDS a uno de los puertos USB [3] del computador.Después de instalar los controladores, conecte la TDS a uno de los puertos USB [3] del computador. Espere a que el computador reconozca el dispositivo y muestre el siguiente mensaje en pantalla:



![imagen](https://eduteka.icesi.edu.co/imgbd/25/25-05/HardwareEncontrado.png)



 



**3. HABILITAR EL SCRATCHBOARD EN SCRATCH**

En este paso es necesario tener [Scratch instalado](https://eduteka.icesi.edu.co/modulos/9/299/935/1) en su computador. Una vez abierto Scratch, seleccione la categoría Sensores.



![imagen](https://eduteka.icesi.edu.co/imgbd/25/25-05/SensoresScratch.png)



 



Haga clic derecho con el mouse en el comando ![imagen](https://eduteka.icesi.edu.co/imgbd/25/25-05/ValorSensorScratch.png) y seleccione la opción “Mostrar variables ScratchBoard”. Esto permitirá ver los datos provenientes de los sensores de la TDS (Deslizador, Luz, Sonido, Botón, A, B, C, D). 



![imagen](https://eduteka.icesi.edu.co/imgbd/25/25-05/ScratchBoardScratch.png)



 



Los valores de la “ScratchBoard” varían en función de los estímulos provenientes del mundo físico que detecte la TDS por medio de sus diferentes sensores. Si la “ScratchBoard” muestra todos los valores en cero, esto significa que el ambiente de programación Scratch no está detectando el puerto USB por donde ingresan al computador los datos desde la TDS. En este caso se debe configurar el puerto USB por el cual la “ScratchBoard” recibe los datos.



 



**4. IDENTIFICAR EL PUERTO USB EN EL COMPUTADOR**

Para configurar el Puerto USB por el cual la “ScratchBoard” recibe los datos de la TDS, primero se debe identificar el puerto COM al que está conectado la TDS. Para reconocer esta información siga los siguientes pasos, según el sistema operativo de su computador:



 



Windows XP



a. Seleccione el menú “Inicio”



b. Haga clic derecho en “MI PC” y seleccione la opción “Propiedades”




![imagen](https://eduteka.icesi.edu.co/imgbd/25/25-05/PropiedadesMIPC.png)



 



c. Seleccione la pestaña “Hardware” y haga clic en el botón “Administrador de dispositivos”



![imagen](https://eduteka.icesi.edu.co/imgbd/25/25-05/AdministradorDispositivos.png)



 



d. En la ventana “Administrador de dispositivos” desplegue (+) la opción **Puertos (COM & LPT)**.** **Posteriormente en la opción **USB Serial Port** mire el puerto que aparece entre paréntesis. Por ejemplo, en la siguiente imagen el puerto es** (COM 4)**, lo cual indica que la TDS está conectada al computador por el puerto COM4. Por lo tanto, en la “ScratchBoard” se debe seleccionar el puerto COM4 para que Scratch pueda recibir los datos provenientes de la TDS (Vea el paso 5 de esta guía). 



![imagen](https://eduteka.icesi.edu.co/imgbd/25/25-05/PuertosCOM.png)



 



Windows 7



a. Seleccione el menú “Inicio”



b. Haga clic derecho en “Equipo” y seleccione la opción “Propiedades”



![imagen](https://eduteka.icesi.edu.co/imgbd/25/25-05/EquipoPropiedades.png)



 



c. Seleccione la opción “Administrador de dispositivos” 



![imagen](https://eduteka.icesi.edu.co/imgbd/25/25-05/AdminDispositivos_Win7.png)



 



d. En la ventana “Administrador de dispositivos” desplegue la opción **Puertos (COM y LPT)**.** **Posteriormente en la opción **USB Serial Port** mire el valor que aparece entre paréntesis. Por ejemplo, en la siguiente imagen el puerto es** (COM 5)**, lo cual indica que la TDS está conectada al computador por el puerto COM5. Por lo tanto, en la “ScratchBoard” se debe seleccionar el puerto COM5 para que Scratch pueda recibir los datos provenientes de la TDS (Vea el paso 5 de esta guía). 



![imagen](https://eduteka.icesi.edu.co/imgbd/25/25-05/Win7PuertoCOM.png)



 



**5. CONFIGURAR EL PUERTO USB EN SCRATCH**

Después de identificar el puerto COM del computador por donde ingresan los datos provenientes de la TDS (paso 4 de esta Guía), en el ambiente de programación Scratch haga clic derecho en la “ScratchBoard” y seleccione la opción “Puerto Serial/USB”. Debe aparecer una lista de puertos COM, seleccione el puerto identificado en el punto “d” del paso 4.



![imagen](https://eduteka.icesi.edu.co/imgbd/25/25-05/ScratchBoardScratch_PuertoCOM.png)



 



Después de seleccionar el puerto COM correspondiente, las variables de la “ScratchBoard” deben empezar a cambiar en función de los datos provenientes de la TDS.



![imagen](https://eduteka.icesi.edu.co/imgbd/25/25-05/ScratchBoardPrendido.png)



 



**NOTAS DEL EDITOR**:

[1] El desarrollo de la Tarjeta de Sensores (TDS) estuvo a cargo de la empresa A&S Design de Cali. Las Instituciones educativas interesadas pueden contactar esta empresa para cotizar y comprar las TDS que requieran:





| [**A&S Design**](http://www.aysdesign.net/) 

Cali, Colombia.

Contacto: Alexis Moreno Martínez

Celular (móvil): 316-255-2389 / 312-758-1972

Correo: alexis.moreno@aysdesign.net 
|




 



Recomendamos consultar el documento https://eduteka.icesi.edu.co/modulos/9/285/2089/1 “[Eduteka presenta su Tarjeta de Sensores (TDS)](https://eduteka.icesi.edu.co/modulos/9/285/2089/1)”. Aunque la TDS tiene las mismas funcionalidades de la [Tarjeta PicoBoard](http://www.picocricket.com/picoboard.html), incorpora mejoras tales como un selector de alcance del sensor de sonido, una salida de 5 voltios y una protección en acrílico para aumentar su durabilidad.



[2] Un controlador de dispositivo, llamado normalmente controlador (en inglés, *device driver*) es un programa informático que permite al sistema operativo interactuar con un periférico ([http://es.wikipedia.org/wiki/Controladores](http://es.wikipedia.org/wiki/Controladores))



[3] Un puerto USB es una entrada o acceso a un computador que permite compartir información almacenada en o proveniente de diferentes dispositivos (cámara fotográfica, pendrive (memoria USB), sensores, etc.. La sigla USB quiere decir Bus Universal en Serie (Universal Serial Bus, en inglés). ([http://es.wikipedia.org/wiki/Universal_Serial_Bus](http://es.wikipedia.org/wiki/Universal_Serial_Bus)) 



 



**CRÉDITOS**:

Documento elaborado por Eduteka.





| 

![imagen](https://eduteka.icesi.edu.co/imgbd/24/24-06/MSFoundation_Small.jpg)


| 

Eduteka elaboró este documento con el apoyo de Motorola Solutions Foundation y la gestión de la ONG Give to Colombia.


|




 



 



*Publicación de este documento en EDUTEKA: Junio 01 de 2012.

Última modificación de este documento: Junio 01 de 2012.*
