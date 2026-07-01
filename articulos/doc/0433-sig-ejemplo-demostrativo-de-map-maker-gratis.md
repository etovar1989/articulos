---
id: 433
title: "SIG: Ejemplo demostrativo de Map Maker Gratis"
date: 2005-06-18
tags: ciencias sociales,resenas,geografia
category: Integración Ciencias Sociales
author: Juan Carlos López García
---

# SIG: Ejemplo demostrativo de Map Maker Gratis

> Entre las alternativas gratuitas de software para SIG recomendamos MapMaker Gratis . Para facilitar su utilización, desarrollamos un ejemplo paso a paso, con los datos de población, densidad y área de los países de Suramérica, con el que se demuestra cómo funciona este software.

Entre las alternativas gratuitas de software para SIG recomendamos MapMaker Gratis . Para facilitar su utilización, desarrollamos un ejemplo paso a paso, con los datos de población, densidad y área de los países de Suramérica, con el que se demuestra cómo funciona este software.

---

# **EJEMPLO DEMOSTRATIVO

DE CÓMO UTILIZAR “MAP MAKER GRATIS”**




Puede descargar este documento completo en versión PDF (2.4 MB)

[https://eduteka.icesi.edu.co/pdfdir/SIG2.php](https://eduteka.icesi.edu.co/pdfdir/SIG2.php)




## PROYECTO




Después de descargar e instalar el software apropiado, el primer paso para utilizar un Sistema de Información Geográfica (SIG),consiste en definir el problema que los estudiantes deben resolver o la pregunta que deben responder con este. A continuación vamos a desarrollar paso a paso un ejemplo demostrativo de cómo funciona el software “Map Maker Gratis”. El tema que usaremos para el ejemplo es representar por país en el mapa de Suramérica los datos de población, área y densidad.




## ANÁLISIS DE LA TAREA




Para realizar la tarea del ejemplo, los estudiantes necesitan conocer cuáles son los países que conforman América del Sur y, de cada uno de ellos, su población, área y densidad. Esta última variable se puede calcular a partir de los datos de población y área. Los estudiantes deben localizar la información que permita responderla pregunta/tarea inicial “representar en el mapa de Suramérica, para cada uno de los países, su población, área y densidad”; para este ejemplo, se elaboró la siguiente tablacon las cifras de población, área y densidad de los países suramericanos (año 2000). Las cifras se tomaron del Centro de Datos y Aplicaciones Socioeconómicas (SEDAC por su sigla en inglés) [http://beta.sedac.ciesin.columbia.edu/gpw/global.jsp](http://beta.sedac.ciesin.columbia.edu/gpw/global.jsp), dedicado a divulgar datos e información de población a nivel mundial, datos que produce la Universidad de Columbia (Estados Unidos). Esta tabla se encuentra en la Hoja de Cálculo “DatosPaises.php” que hace parte del archivo comprimido “EjeGIS.zip” el cual se puede descargar de https://eduteka.icesi.edu.co/descargas/EjeGIS.zip




![2](https://eduteka.icesi.edu.co/imgbd/23/23-18/ta.gif)




FUENTE DATOS: SEDAC-[http://beta.sedac.ciesin.columbia.edu/gpw/global.jsp](http://beta.sedac.ciesin.columbia.edu/gpw/global.jsp)


El siguiente gráfico, realizado con Excel, representa gráficamente los datos de la tabla anterior. Nótese la dificultad para realizar cualquier análisis cuando las tres variables (densidad, población, área) se encuentran en el mismo gráfico.


![r](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/sig1.gif)




# UTILIZACIÓN DE UN SIG




Utilizar un Sistema de Información Geográfico (SIG) con el fin de resolver la tarea planteada al comienzo permite y facilita ubicar visualmente los datos de población, área y densidad sobre el área geográfica correspondiente. Luego de que han encontrado y tabulado los datos pertinentes, los estudiantes deben [localizar un mapa](https://eduteka.icesi.edu.co/ResenhaMapas.php) digital de Suramérica [4]. Para este ejemplo, se descargaron dos mapas en formato .JPG de la [Colección Perry-Castañeda](http://www.lib.utexas.edu/maps/americas.html) de la biblioteca de la Universidad de Texas (MapSur1.jpg y MapSur2.jpg).




Las imágenes (mapas de bits o matriciales [5]) que se van a utilizar como fondo para dibujar sobre ellas las áreas, líneas o puntos que se van a asociar luego con la información pertinente, se pueden descargar de sitios de Internet que ofrecen mapas de diferentes áreas geográficas del planeta. En caso de que se dificulte la consecución del mapa, los estudiantes los pueden generar con un programa como Microsoft Paint [6] o AutoCad [7]. Otra alternativa consiste en dibujar el mapa en papel o tomarlo de un libro y digitalizarlo, por medio de un escáner, en formato .TIF, .BMP o .JPG.




Con respecto a los datos, estos pueden tabularse en una Hoja de Cálculo que permita exportarlos en formato .DBF (dBase III) para poderlos utilizar en el software “[MapMaker Gratis](https://eduteka.icesi.edu.co/SIG3.php)”.




A pesar de que este es un ejemplo muy sencillo, para toda la potencialidad que ofrece un SIG, lo hemos escogido para realizar una demostración introductoria de cómo funciona un software gratuito para SIG como lo es “[MapMaker Gratis](https://eduteka.icesi.edu.co/SIG3.php)” [8].




## PASO 1




Para iniciar este ejemplo demostrativo, se debe descargar el archivo “EjeGIS.zip” y descomprimirlo para obtener tanto los datos “DatosSur.dbf” como el mapa de Suramérica “MapSur1.jpg”. Guárdelos en una carpeta destinada exclusivamente a almacenar todos los archivos relacionados con este ejemplo.




## PASO 2




Descargar e instalar el software “[Map Maker Gratis](https://eduteka.icesi.edu.co/SIG3.php)” versión 3.5; en Eduteka encontrará un documento con la secuencia de instrucciones que le permitirán realizar estas tareas.




## PASO 3




Abrir el programa “MapMaker Gratis”. Se debe tener en cuenta que el programa se debe ejecutar en el modo [**Run as Map Maker Gratis**]:




![r](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig2.gif)




La pantalla de “Map Maker Gratis” se diseñó para maximizar el área destinada a la presentación de los mapas y a la información relacionada con estos. Cuando se inicia el programa, este se encuentra en el modo “pantalla limpia” [**File / Clear – (create blank project)**]:




![r](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig3.gif)




## PASO 4




Para cargar el mapa de Suramérica en formato .JPG se accede al menú [**File/Add layer**] o **[Ctrl+A].** En la ventana que se despliega, se deben seleccionar: el tipo de archivo que se va a abrir [**JPEG Image (*.jpg)**]; el directorio en el se encuentra el mapa **[...EjeGIS];** y el nombre del archivo que se desea abrir [**MapSur1.jpg**]. Luego hacer clic en el botón [**Abrir**] de esta ventana. Debe aparecer otra ventana (Layer set up) en la cual se hace clic en el botón [**Ok**]. El mapa de Suramérica debe aparecer en pantalla.




Para visualizar el mapa de Suramérica completo, se debe hacer clic en el botón [**Zoom out 2 x**]:




 




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig4.jpg)




Ninguno de los archivos “*.BMP, *.TIF o *.JPG” pueden llevarse a la capa viva [9]. Solo se utilizan como información de base.




## PASO 5




Antes de empezar a dibujar los países, utilizando como base el mapa que se acaba de cargar, se pueden modificar las características del archivo de estilos. El archivo de estilos es la representación personalizada de polígonos, símbolos y líneas sobre un mapa y se pueden variar el tipo y color de líneas, las formas y los rellenos de los polígonos o los tipos de símbolos.




Para modificar el archivo de estilos, se escoge la opción [**File / System set up / Edit default style set**]. También se puede acceder a la modificación de estilos por la opción [**File / Poject Manager**] o **[Ctrl+M], **y luego hacer clic en las pestañas [**Components**], [**Project style set**] y [**Style edit**] :




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig5.gif)




En el cuadro de diálogo de estilos que aparece, se hace clic en el estilo [**default**] y luego en [**Empty fill**]. Este paso es necesario para que cuando se asignen colores a los países, esos colores no se distorsionen por tener otra capa coloreada debajo. También se configuran los colores que se quieren asignar a la información que estará representada en los diferentes mapas. Primero se hace clic sobre el color en la columna **[Style set], **luego sobre el color **[Translucent]** ubicado a la izquierda y a continuación se hace clic sobre el botón **[Fill colour]. **En la ventana que aparece se selecciona el color deseado. Se finaliza haciendo clic en el botón [**OK**].








| ![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig6.gif) 
| ![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig6a.gif) 
|







## PASO 6




Sobre la capa activa [9] de MapMaker se dibujan uno a uno los países, tomando como base el mapa de Suramérica que se cargó en el paso 4. Para dibujarlos se hace clic en la herramienta “polygon”. Al activar esta herramienta, se crea automáticamente un capa sobre la anterior, en la que se encuentra el archivo **[MapSur1.jpg].** Esta nueva capa va a contener los polígonos que representan cada uno de los países de Suramérica y la llamaremos **[paises.dra].** Cada país se dibuja haciendo clic para crear el nodo inicial, luego se mueve el cursor al sitio donde debe quedar el siguiente nodo y luego el siguiente y el siguiente. Y así, cuantas veces sea necesario, hasta cerrar el polígono haciendo clic en el nodo inicial (el que aparece encerrado en un circulo en la imagen 7).




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig7.jpg)




Cuando se cierra el polígono, automáticamente se abre una ventana en la cual se debe especificar el estilo que tendrá el polígono que se acaba de dibujar (default) y los datos básicos para relacionar cada polígono con la base de datos: Escribir el código ISO [1] del país tanto en [**Display label**] como en [**Unique ID**].




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig8.jpg)




Este paso se debe realizar para cada uno de los países que figuran en la tabla inicial. En cada país debe aparecer en letra negra el código ISO (ECU, COL, PER, etc)




## PASO 7




Una vez creada esta capa [9], se guarda con el nombre [**paises.dra**]. Para guardarla, se debe hacer clic sobre el circulo azul del [**Manejador de capas en pantalla**] (ver imagen 3). Debe aparecer una ventana de la cual se selecciona la opción [**Save live layer as...**]. En el cuadro de diálogo que se abre se selecciona la ubicación del archivo que se va a crear [**ejeGIS**], se escribe el nombre del archivo [**paises**] y se selecciona el tipo de archivo que se va a crear [**Map maker drawing file (*.dra)**]. Finalmente se hace clic sobre el botón [**Guardar**]. MapMaker crea automáticamente una base de datos asociada a la capa que estamos intentando guardar. Se debe hacer clic sobre el botón [**Aceptar**] para que guarde la base de datos [**paises.dbf**].




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig9.jpg)




Cuando se graba la capa viva, los objetos de esta ya no se pueden modificar. Para hacerlo hay que traer la capa guardada a la capa viva: [**File / Add layer**]. Luego se hace clic sobre la opción [Edit] en la barra de herramientas. En este momento, al lado del cursor aparece la palabra “Edit”. Se hace clic una vez sobre los objetos para editar la etiqueta, el identificador y escoger el estilo. Se hace doble clic sobre un objeto para modificar su forma y posición; para borrarlo se presiona la tecla “Del” o “Supr”.




Al grabar la capa con los polígonos de los 14 países, aparece una etiqueta [**paises**] en el [**manejador de capas en pantalla**] y en la barra de herramientas queda seleccionada la opción [**Data**] y al lado del cursor aparece la palabra “query”. Esto indica que al hacer clic sobre un país, aparece la información creada automáticamente y guardada en la base de datos **[paises.dbf].**




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig10.jpg)




Para “ocultar” el mapa de Suramérica que utilizamos como fondo para crear los polígonos, se debe ubicar el cursor sobre la capa [**MapSur1**] para que aparezca la ventana con el menú y en este se hace clic sobre [**hide layer**].




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig11.gif)




Cuando se “esconde” el mapa en formato .jpg, queda en pantalla el mapa compuesto por 14 polígonos que representan cada uno de los países de Suramérica.




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig12.gif)




## PASO 8




Para grabar un proyecto (.geo) que le indique al programa qué capas abrir y en qué orden, se debe guardar el trabajo con la opción del menú [**File / Save project as**] (ver imagen No 5). En el cuadro de diálogo que se abre se selecciona la ubicación del archivo que se va a crear [**EjeGIS**], se escribe el nombre del archivo [**proyesur**] y se selecciona el tipo de archivo que se va a crear [**Map Maker project file (*.geo)**].




Este archivo [**proyesur.geo**] no contiene ni los objetos ni las capas, solamente la ubicación de los archivos que componen el mapa (.DRA, .LEG, .TXT, .STL, etc). Contiene también los accesorios, las indicaciones de los limites geográficos del despliegue y la configuración de la página. Para que un proyecto pueda llevarse a otro computador no es suficiente llevarse el archivo .geo , este debe estar acompañado por el resto de los archivos (capas, estilos, convenciones, imágenes)que conforman el proyecto. Si no se guarda el proyecto, cuando se cierra el programa se pierde todo el trabajo realizado.




## PASO 9




Las diferentes capas de un mapa se pueden enlazar con bases de datos. Todos los objetos en una capa tienen un identificador [ID del objeto para bases de datos], que permite relacionarlos a un registro de la base de datos con la cual la capa está enlazada. Este número se asigna a cada objeto al crearlo (Paso 6, Imagen No 8). En la base de datos, este identificador siempre debe aparecer en la primera columna y debe permanecer siempre allí, ya que este es el lugar en el cual busca el programa para encontrar la fila que contiene la información relacionada con este objeto. Map Maker puede trabajar con diferentes formatos de archivos de bases de datos, como son DBF (en formato dbase III), CSV (archivo ASCII separado por comas) y CSD (separado por espacios).




Para relacionar la información de población de la base de datos obtenida en el Paso 1, que contiene la población, área y densidad de cada uno de los países, se debe crear una capa para cada una de estas variables.




Para crear la capa **[densidad]** se debe cargar la capa **[paises.dra]** a la capa viva haciendo clic sobre el circulo azul del [**Manejador de capas en pantalla**] y seleccionando la opción [**Load file into live layer**] (ver imagen 9). En el cuadro de dialogo que se abre se selecciona el archivo [**paises.dra**] y se hace clic sobre el botón [**Abrir**].




Una vez que la capa **[paises.dra]** se encuantra en la capa viva, esta se guarda con el nombre [**densidad.dra**]. Para guardarla, se debe hacer clic sobre el circulo azul del [**Manejador de capas en pantalla**] (ver imagen 3). Debe aparecer una ventana de la cual se selecciona la opción [**Save live layer as...**]. En el cuadro de diálogo que se abre se selecciona la ubicación del archivo que se va a crear [**EjeGIS**], se escribe el nombre del archivo [**densidad**] y se selecciona el tipo de archivo que se va a crear [**Map maker drawing file (*.dra)**]. Luego se hace clic sobre el botón [**Guardar**]. Finalmente se debe hacer clic sobre el botón [**Aceptar**] para que guarde la base de datos [**densidad.dbf**].




Cuando ya existe la capa [**densidad.dra**], se accede a la opción [**File / Project Manager**] (deben aparecer 3 layers). En la ventana que aparece se hace clic sobre la capa [**densidad**] en la sección [**Layers**]; luego se hace clic sobre la pestaña [**Data link**]; después sobre la pestaña [**Choose database**] y se selecciona la base de datos [**DatosSur.dbf**]. Con estas operaciones hemos enlazado la capa **[densidad.dra],** que a su vez está basada en la capa de polígonos **[paises.dra],** con la base de datos **[DatosSur.dbf].**




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig13.gif)




Luego de relacionar la capa con la base de datos se debe indicar el campo (POBLACIÓN, AREA o DENSIDAD) que se desea mostrar en el mapa. Se hace clic sobre la pestaña [**Style**], luego sobre [**Assign according to...**] y posteriormente sobre la opción [**Database**]. En este punto podemos seleccionar la columna (campo) que deseamos mostrar en el mapa, le haremos clic sobre [**DENSIDAD**]. Se finaliza haciendo clic sobre el botón [**OK**].




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig14.gif)




En este punto del proceso, cada uno de los países de Suramérica aparece automáticamente de un color (de acuerdo al valor de la variable densidad de la base de datos). Además, seleccionando **[Data]** en la barra de herramientas, cuando se hace clic sobre un país por ejemplo Brasil, aparecen los datos de ese país que están almacenados en la base de datos.




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig15.gif)




.




Periódicamente hay que grabar el proyecto para no perder datos. Esto se hace con la opción del menú [**File / Save project**] o [**Ctrl+S**].




## **PASO 10**




Para cambiar los colores y los rangos de valores se debe ubicar el cursor sobre la capa **[densidad].** En el menú que aparece se debe hacer clic sobre [**layer set up**] y en la ventana que aparece se debe hacer clic en **[Style],[Assign according to...]**, **[Filter data un DENSIDAD]** y **[New filter].** En la ventana que aparece se debe escribir el nombre del archivo de filtro **[densidad.txt].**




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig16.gif)




.




En la ventana siguiente, si se hace clic en la pestaña [**Filter**] se puede cambiar la apariencia del filtro: número de bandas [**Bands**] (en este caso se puso en 7) y los colores de cada banda. Los colores se cambian haciendo clic sobre el color y seleccionando otro entre el conjunto de colores que ofrece el estilo que se este utilizando (ver el paso 5).




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig17.gif)




En la misma ventana, si se hace clic en la pestaña [**Legend**] y luego en [**Captions**] se pueden cambiar los textos de la leyenda. Al hacer clic en el botón [**Save as EMF file**] se guarda la leyenda como una imagen llamada [**densidad.emf**] la cual se puede incluir luego, al lado del mapa, para explicar las convenciones.




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig18.gif)




Para incluir leyendas de convenciones asociadas con un mapa y con un conjunto de datos se accede al menú [**Tools / Map furniture**]. Se hace clic sobre la pestaña [**Furniture type**], luego se selecciona el tipo de archivo [**Enhanced Metafile (*.emf)**] y se escoge el archivo [**Choose file**] grabado como [**densidad.emf**]




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig19.gif)




El siguiente es el mapa resultante con la representación de la densidad de población (habitantes/Km2) de todos los países de Suramérica organizados en 7 rangos, cada uno de ellos de un color diferente.




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig20.gif)




Estos pasos se pueden repetir para representar la cantidad de población y el área de cada país. Es aconsejable utilizar un mapa en formato .jpg **[MapSur1.jpg]** que tenga unos colores muy tenues (casi blanco) para no distorsionar los colores que representan los datos o mantenerlo “escondido” para que en el proyecto no suceda lo que se aprecia en la imagen 21:




En el archivo comprimido “EjeGIS.zip” el cual se puede descargar de [https://eduteka.icesi.edu.co/descargas/EjeGIS.zip](https://eduteka.icesi.edu.co/descargas/EjeGIS.zip) puede encontrar el proyecto completo “proyesur.geo”.




El siguiente es el mapa resultante de la representación de la población (habitantes) de todos los países de Suramérica organizados en 7 rangos, cada uno de ellos de un color diferente.




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig22.gif)




El siguiente es el mapa resultante de la representación del área (Km2) de todos los países de Suramérica organizados en 7 rangos, cada uno de ellos de un color diferente.




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig23.gif)




Se pueden o ocultar todas las capas (mapa, densidad, área y población) o mostrar una o varias de ellas. Para ocultar o mostrar capas, se debe ubicar el cursor sobre la capa para que aparezca la ventana correspondiente con el menú en el cual se hace clic sobre [**hide layer**]. Si aparece un símbolo de verificación, significa que la capa está oculta. Hay que tener cuidado cuando se muestran dos capas al mismo tiempo, pues sus colores se sobreponen y cambian.




![](https://eduteka.icesi.edu.co/imgbd/21/21-5/Sig2/imgSig24.gif)




Este ejemplo incluye únicamente las etapas de: elaborar o descargar el mapa adecuado, conseguir los datos pertinentes, organizar la información en una base de datos e interrelacionar los datos con el mapa utilizando un software para SIG. No se atienden aquí las etapas de análisis y presentación de resultados, fundamentales en todo proyecto de este tipo.




 




### **NOTAS DEL EDITOR:**




**[1]** Código asignado a cada país por la Organización Internacional para la Estandarización (ISO por su sigla en inglés). Consta de tres letras y se utiliza con fines de hacer la identificación única de cada país o área.




**[2]** Población de cada país estimada para el año 2000, por las Naciones Unidas.




**[3]** Número de centros urbanos: Número de áreas clasificadas como urbanas (poblaciones de 5.000 personas o más).




**[4]** Reseña de mapas disponibles en Internet. Sitios de Internet con diversos tipos de mapas, tanto estáticos (similares a los de papel), como dinámicos (permiten aumentar ciertas zonas para lograr mayor detalle). Se Incluyen, de diversos países y regiones del mundo: mapas físicos, de división política, históricos y temáticos.[https://eduteka.icesi.edu.co/ResenhaMapas.php](https://eduteka.icesi.edu.co/ResenhaMapas.php)




**[5]** Las imágenes vectoriales se componen de contornos y rellenos definidos matemáticamente (vectorialmente) mediante ecuaciones precisas que describen perfectamente cada ilustración. Esto posibilita que cuando se reproducen en un dispositivo de salida adecuado sean perfectamente escalables sin perder su calidad. Esta característica se pone de manifiesto especialmente en ilustraciones que contienen zonas con contornos curvados.




Las imágenes de mapa de bits se construyen mediante una gran cantidad de cuadraditos, llamados píxeles, que están rellenos de color aunque este sólo sea blanco o negro. La idea es muy sencilla. Supongamos que queremos reproducir una fotografía de un paisaje en un cuaderno con hojas cuadriculadas. Podemos trazar en la foto cuadrados de igual tamaño que en el cuaderno y, a continuación, traspasar a éste los colores de cada cuadrado, esto reproducirá en el papel una imagen aproximada a la fotografía original. Fácilmente comprenderemos que esta copia será más fiel cuanto más pequeños sean los cuadraditos usados para descomponerla y copiarla, lo que nos acerca a la idea de resolución. José J. Grimaldos [http://www.grimaldos.es/cursos/imgdig/ ; consulta en línea: Junio 16 de 2005]




**[6]** Microsoft Paint: Programa que permite al usuario dibujar en pantalla especificando el color de los puntos o píxeles individuales que conforman un gráfico en mapa de bits.




**[7]** AutoCad: Programa de diseño asistido por computador (CAD) desarrollado por AutoDesk y muy utilizado para aplicaciones de diseño profesional en ingeniería y proyectos arquitectónicos.




**[8]** [MapMaker](http://www.mapmaker.com/) es un Sistema de Información Geográfica (SIG) sencillo que corre bajo ambiente Windows. Fue creado por Eric Dudley ([eric@mapmaker.com](mailto:eric@mapmaker.com)) y actualmente consta de tres versiones: dos de ellas se distribuyen gratuitamente: una en español (MapMakerPopular) y otra en inglés (MapMaker Gratis); la tercera (MapMaker Pro) es de pago pero cabe anotar que su costo es muy bajo con relación a otros programas de este tipo. Ver el artículo “Sistemas de Información Geográfica” para las instrucciones de descarga e instalación de “MapMaker Gratis” [https://eduteka.icesi.edu.co/SIG3.php](https://eduteka.icesi.edu.co/SIG3.php)




**[9]** En la pantalla, se puede sobreponer la información contenida en varios archivos de datos que correspondan a la misma región geográfica. Se llama “capas” a estos archivos por su facultad de sobre posición. El hecho de que una capa se vea en la pantalla no indica que esta información se encuentra en la capa viva, es muy común tener varias capas desplegadas en la pantalla en estado “pasivo” (no modificable) por detrás de la capa viva. Los cambios que se hacen en la capa viva no afectan los archivos pasivos desplegados. La capa viva (Live layer) es la capa en la cual se puede dibujar y editar (modificar) objetos. Todos los objetos que se encuentran activos (en la capa viva), tienen a su lado un círculo celeste como indicador. La ubicación de este círculo es la misma que la de la etiqueta.




Hay tres tipos de archivos que pueden usarse usados como capas: Los dibujos hechos con vectores (con extensión .DRA), las imágenes barridas o escaneadas (.TIF , .BMP, o .JPG), y los archivos de localización (.LOC).




Las capas (archivos) agrupan por lo general objetos geográficos del mismo tipo, es decir, una capa (*.DRA) contiene todos los objetos que representan las viviendas de una zona, otra capa (*.DRA) tendrá los ríos, otro archivo las carreteras, etc.




###  




### **CRÉDITOS:**




Ejemplo elaborado por Eduteka con la asesoría de Noelia Naranjo ([rosanohelia@hotmail.com](mailto:rosanohelia@hotmail.com)), Ingeniera Agrícola de la Universidad del Valle, Cali, Colombia.




*Publicación de este documento en EDUTEKA: Junio 18 de 2005.

Última modificación de este documento: Junio 18 de 2005.*
