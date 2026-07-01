---
id: 1002
title: "Lección 2: Primeros pasos en Scratch"
date: 2009-11-09
tags: scratch,programacion,solucion problemas,actividades,guias uso,videos
category: Herramientas
author: Francisco Martínez
---

# Lección 2: Primeros pasos en Scratch

> Segundo video, de una serie de 9, en el que se inicia la elaboración de un juego retador inspirado en Super Mario Bros. En este video se muestran los elementos básicos de Scratch y se enseña a mover por la pantalla al personaje del juego que se entrega con el material que acompaña este video.

Segundo video, de una serie de 9, en el que se inicia la elaboración de un juego retador inspirado en Super Mario Bros. En este video se muestran los elementos básicos de Scratch y se enseña a mover por la pantalla al personaje del juego que se entrega con el material que acompaña este video.

---

# TALLER DE SCRATCH




## Lección 2




 


















*

Descargue imágenes y sonidos necesarios para que usted realice este juego. [Haga clic aquí](../pdfdir/Scratch_Lecciones_Insumos.php).


Descargue archivo ejecutable con la versión final del [juego Mario](https://eduteka.icesi.edu.co/Scratch/JuegoMario.exe) (EXE, 3.304 MB)

*




 




Bienvenido a los tutoriales del lenguaje de programación Scratch, esta es la segunda lección. En ella conoceremos los elementos básicos de la herramienta y aprenderemos a mover por la pantalla al personaje que les entregamos con el material que acompaña este video.




Antes de comenzar debe tener instalado Scratch en su computador y saber abrirlo. Si no ha realizado lo anterior, revise la lección 1 que explica, paso a paso, cómo hacerlo.




Para comenzar a trabajar primero debe familiarizarse con algunos términos.




![1](https://eduteka.icesi.edu.co/imgbd/23/23-09/ima1.gif)




Una vez abierto Scratch, encontramos diferentes paneles. Vamos a conocer las opciones básicas que ofrecen




![2](https://eduteka.icesi.edu.co/imgbd/23/23-05/scrat.jpg)




**Área de Diseño:** a la derecha de la pantalla, encontrará un recuadro con fondo blanco en el que aparece un gato, esta es el área de diseño. En ella podrá insertar personajes, ubicarlos en la pantalla y ver el avance de los programas. Arriba se ubican una serie de herramientas para manipular los Objetos; en lecciones siguientes, aprenderá un poco más sobre ellas. También hay un botón con una bandera verde que permite iniciar la ejecución del programa y además, un hexágono rojo para detenerlo. 


En el centro de la pantalla está el área de trabajo, aquí podrá generar sus programas, adicionar y crear disfraces, fondos o sonidos.


A la izquierda se ubican las instrucciones del programa agrupadas en ocho categorías (movimiento, apariencia, sonido, lápiz, control, sensores, números, variables), podemos seleccionar una categoría y arrastrar y pegar alguna línea de instrucción; no se preocupe si no las entiende todas, en las siguientes lecciones las irá conociendo. 


En la parte superior del programa se ubican las opciones de este, veamos que hace cada una de ellas:




1. **Nuevo: **Crear un proyecto nuevo, vacío
2. **Abrir:** Permite abrir algún proyecto que se haya realizado previamente




1. **Guardar: **Con esta opción se pueden guardar los proyecto que se van haciendo.
2. **Guardar como: **Se utiliza cuando se quiere guardar una copia de alguno de los proyectos previamente guardados, sin modificar la versión original.
3. **¡Compartir!: **Esta opción permite subir el proyecto que estamos trabajando, a la página oficial de Scratch y compartirlo con otras personas.
4. **Deshacer: **Nos regresa el último elemento borrado.
5. **Lenguaje:** Permite seleccionar el idioma en que aparecen las instrucciones del proyecto que puede cambiarse, inclusive, en plena ejecución de este, lo que facilita usarlo en diferentes entornos lingüísticos.
6. **Extras:** Ofrece otras herramientas para incluirlas en el proyecto.
7. **¿Ayuda?: **Ofrece ayuda sobre la herramienta




Muy bien, ahora que conoce los componentes básicos de la herramienta, puede comenzar a desarrollar su juego. En esta lección, se adiciona el Objeto Mario y se hace que camine sobre la pantalla. 




 




Para ello, haga clic en **nuevo** y luego en la pestaña del medio (**Disfraces**) con lo que abrirá un nuevo Objeto. A continuación haga clic en el botón “**Importar**” y busque, en la carpeta adjunta a este video, la imagen “**Mario1**”; de clic en Aceptar y cambie en la parte superior el nombre Objeto1 por  Mario. Luego haga clic sobre las X entre círculos para borrar los disfraces del gato.




![1](https://eduteka.icesi.edu.co/imgbd/23/23-09/1.gif)




Ahora haga clic en la pestaña ***Programas*** y seleccione ***Control;*** con clic sostenido arrastre al área de programas la instrucción ***al presionar**** (bandera verde*), con esta instrucción le estamos diciendo al programa que todo lo que pongamos debajo de ella se ejecutará cada vez que presionemos el botón ***bandera verde***, ubicado en la parte superior derecha de la pantalla. Ahora vaya a Movimiento y agregue mover 10 pasos ubicándoladebajo de la instrucción anterior de forma que encaje; así, cada vez que se presiona la ***Bandera verde ***Mario camina 10 pasos al frente. Crear programas consiste pues, en ir encajando bloques gráficos formando una pila de instrucciones, que se ejecutarán secuencialmente. Cuando se arrastra un bloque al Área de guiones, una marca blanca indica dónde se puede soltar el bloque y formar una conexión válida con otro bloque.




![2](https://eduteka.icesi.edu.co/imgbd/23/23-09/2.gif)




Ahora que Mario aprendió a dar pasos, podemos definir cuantos pasos da modificando “***mover x pasos***”, cambie el valor 10 por 5 y pruebe nuevamente; como vera, ahora Mario se mueve una distancia menor. Para lograr que camine continuamente debe decirle que repita la instrucción indefinidamente, para ello, separe la instrucción “***mover 5 pasos***” de la instrucción “***al presionar***”, vaya a ***Control*** y agregue ***por siempre,***justo debajo de la bandera. Luego, ubique la instrucción ***mover 5 pasos*** dentro de la instrucción **por siempre**, tal y como se ve en la siguiente imagen:




![3](https://eduteka.icesi.edu.co/imgbd/23/23-09/3.gif)




Al hacer clic en la bandera verde vemos como Mario camina constantemente por la pantalla, puede hacer clic en el botón rojo, ubicado en la parte superior derecha de esta, para detener la ejecución del programa. Para acomodar a Mario en un punto fijo de la pantalla, que sirva de posición inicial, arrástrelo mediante clic sostenido, hasta el punto en el cual lo quiere ubicar y tome las posiciones X y Y del área de información del Objeto. Por ejemplo, para acomodar a Mario en la posición X=-200 y Y=-70 agregue bajo la bandera, la instrucción ***ir a x: -200 y: -70***que se encuentra en ***Movimiento***. El personaje puede moverse a cualquier lugar de la pantalla, pero siempre que haga clic en la **bandera verde** regresará a la esquina izquierda. 




![4](https://eduteka.icesi.edu.co/imgbd/23/23-09/4.gif)




Ahora  mejore su programa. Vaya a la pestaña Disfraces, haga clic en el botón importar y seleccione *Mario2*. Esta segunda imagen muestra a Mario moviendo una pierna. Si analiza las imágenes se puede dar cuenta que al intercambiar rápidamente una con otra parece como si el Objeto se estuviera moviendo. Regrese a la pestaña Programas, vaya a Apariencia y agregue **siguiente disfraz, **debajo de ***mover 5 pasos***, pero debe ubicarla adentro de la instrucción ***por siempre***. Con esta instrucción el muñeco cambiará de disfraz cada 5 pasos.




![5](https://eduteka.icesi.edu.co/imgbd/23/23-09/5.gif)




 




Muy bien, ya logró que Mario camine por la pantalla, el problema ahora es que lo hace muy rápido, busque la manera de que cambie de disfraz más lentamente. Para ello, vaya a Control y seleccione la instrucción  esperar 1 segundo,****encájela debajo de ***siguiente disfraz*** luego, haga clic en la bandera verde y observe qué pasa.




![6](https://eduteka.icesi.edu.co/imgbd/23/23-09/6.gif)




Como puede observar, Scratch utiliza bloques autoencajables que sólo ajustan si son sintácticamente correctos, permitiendo que usted centre su atención en los algoritmos lógicos de programación, en lugar de perder tiempo intentando desentrañar el esotérico código de los lenguajes de programación tradicionales.




Ahora que usted ha avanzado lo suficiente, aprenda a grabar los cambios. Diríjase a la parte superior del programa y haga clic en Guardar. Después, seleccione el sitio donde quiere guardar su proyecto, este puede ser en el botón izquierdo donde dice proyectos o en otro directorio que desee. Vaya ahora a donde dice ***Nuevo nombre de archivo:*** escriba un nombre para el proyecto, por ejemplo, leccion1 y haga clic en aceptar. Al hacerlo, el proyecto queda guardado. Recuerde guardar cada vez que haga cambios en su proyecto.


Perfecto, usted logró con 6 instrucciones sencillas que su personaje camine por la pantalla. Ahora puede agregar otros personajes para que caminen junto a Mario. ¿Qué puede  hacer para que Mario camine más rápido? o ¿cómo lograr que sus pasos sean más largos?. Como ya sabe mover Objetos por qué no intenta poner otros personajes a caminar por la pantalla y vea quién llega primero, ¡anímese y haga carreras con sus amigos!


Muy bien, eso ha sido todo por hoy, gracias por su compañía y nos vemos en la lección 3.






**CRÉDITOS:***![imagen](https://eduteka.icesi.edu.co/imgbd/23/23-05/icesi.gif)*


*Tutorial de Scratch elaborado por Francisco Martínez, como parte de su proyecto de grado para optar por el título de Ingeniero de Sistemas de la Universidad Icesi , con asesoría de la Fundación Gabriel Piedrahita Uribe y validado por docentes de Informática de las siguientes Instituciones educativas de Cali: Colegio “Miraflores” de Comfandi, Instituto Nuestra Señora de la Asunción (INSA) y Corporación Educativa Popular (CEP).*




 




*Publicación de este documento en EDUTEKA: Noviembre 01 de 2009.


Última modificación de este documento: Noviembre 01 de 2009. *
