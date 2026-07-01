---
id: 1003
title: "Lección 3: Crear y editar objetos y sonidos"
date: 2009-11-08
tags: scratch,programacion,solucion problemas,actividades,guias uso,videos
category: Herramientas
author: Francisco Martínez
---

# Lección 3: Crear y editar objetos y sonidos

> Tercer video, de una serie de 9, en el que se muestra cómo crear y editar Objetos y Sonidos. Además, se aprenderá cómo mover al personaje del juego adelante y atrás, hacerlo saltar y agacharse.

Tercer video, de una serie de 9, en el que se muestra cómo crear y editar Objetos y Sonidos. Además, se aprenderá cómo mover al personaje del juego adelante y atrás, hacerlo saltar y agacharse.

---

# TALLER DE SCRATCH

## Lección 3

*

Descargue imágenes y sonidos necesarios para que usted realice este juego. [Haga clic aquí](https://eduteka.icesi.edu.co/pdfdir/Scratch_Lecciones_Insumos.zip).

Descargue archivo ejecutable con la versión final del [juego Mario](https://eduteka.icesi.edu.co/Scratch/JuegoMario.exe) (EXE, 3.304 MB)*

 

 

Hola, bienvenido a los tutoriales de Scratch. 

Hoy aprenderá cómo crear y editar Objetos y Sonidos. Además, a mover a Mario adelante y atrás, hacerlo saltar y agacharse. 

Antes de comenzar debe tener instalado el programa y conocer los componentes básicos de este, en caso contrario, puede ver la lección 1 y 2 donde se explica paso a paso como hacer esto. 

En la segunda lección el programa quedó más o menos así: 

![7](https://eduteka.icesi.edu.co/imgbd/23/23-09/7.gif)

Mario camina por la pantalla, ahora le va a agregar tanto un fondo como una tierra para que el juego comience a mejorar su aspecto. Para hacer esto haga clic en escenario y luego vaya a fondos. Ahí podrá ver un fondo blanco que puede editar o copiar. En este  caso haga clic en importar y escoja en la carpeta el archivo fondo2. Al hacer esto el fondo cambia, ahora tiene un bosque y un cielo detrás de Mario.

![8](https://eduteka.icesi.edu.co/imgbd/23/23-09/8.gif)

El problema es que este nuevo fondo no cubre toda la pantalla, pero esto no importa, porque en Scratch se pueden editar fácilmente los fondos. Para hacerlo,  seleccione el fondo y haga clic en editar; después, escoja la ![9](https://eduteka.icesi.edu.co/imgbd/23/23-09/9.gif) y haga clic hasta que el fondo cubra la totalidad de la pantalla que se verá así .

![10](https://eduteka.icesi.edu.co/imgbd/23/23-09/10.gif)

 

Muy bien, ahora abra nuevamente el editor del fondo y personalícelo con las herramientas como si estuviera usando el programa “Paint” de Microsoft. En este caso va a agregar un camino por donde Mario correrá pero, usted  puede modificarlo como quiera. 

![11](https://eduteka.icesi.edu.co/imgbd/23/23-09/11.gif)

Ahora que ya tiene el fondo, debe editar cada disfraz de Mario para que no quede con fondo blanco. Para lo anterior, haga clic en el Objeto Mario y luego, en la pestaña Disfraces. Elija  el primer disfraz y haga clic en el botón editar. En la parte inferior se puede presionar la lupa +, para agrandar el personaje. Si mira con cuidado notará que el fondo es blanco, pero el fondo del juego cambia de colores. Para solucionar esto, tendremos que poner un fondo transparente, que se simboliza en Scratch con cuadros blancos y grises ![12](https://eduteka.icesi.edu.co/imgbd/23/23-09/12.gif) Para hacerlo  seleccione el color transparente de la paleta y pinte cada uno de los cuadros blancos, teniendo cuidado de no pintar a Mario; repetimos esta acción con cada uno de los disfraces. Para acomodar mejor a Mario, vaya al área de programación y ubíquelo en las nuevas coordenadas de inicio de tal forma que camine sobre la tierra. El resultado final debe ser algo como esto:

![13](https://eduteka.icesi.edu.co/imgbd/23/23-09/13.gif)

Muy bien, ahora le adicionará algo de movimiento con el teclado. Para ello, borramos todo lo que esta dentro del por siempre, incluyéndolo. Inicie desencajando las instrucciones, moviéndolas hacia fuera del área de programación, haga  luego clic derecho y escoja borrar.  En Control seleccione al presionar tecla espacio y agréguela en cualquier parte del área de programación. Con esta tecla, Mario puede reaccionar cuando el usuario presione una tecla determinada, en este caso, todo lo que se ponga debajo de ella, se ejecutará cada vez que se presione la tecla espacio, ensaye. Escoja flecha derecha y agregue algunas instrucciones, en Movimiento, como mover 10 pasos. Si hace clic en la Bandera Verde nada pasa, pero cada vez que presione la flecha derecha, Mario camina 10 pasos hacia delante. Se puede agregar el código que hizo la lección pasada para que camine con más fluidez. Al finalizar tendrá algo como esto:

![14](https://eduteka.icesi.edu.co/imgbd/23/23-09/14.gif)

 

Esta vez, seleccionó manualmente qué disfraz va a poner; más adelante agregará otros disfraces para que salte y se agache.  

Ahora que Mario camina hacia delante, veamos como hacer para que cambie de dirección:  Para ello, lo primero que se debe hacer, es decirle que gire solamente hacia delante y hacia atrás. Para que lo haga, presione clic en el Objeto Mario y arriba de programas, escoja *solo mirar a izquierda y derecha *

![15](https://eduteka.icesi.edu.co/imgbd/23/23-09/15.gif)

Vaya ahora a Movimiento y en: **apuntar en dirección, **escoja la dirección deseada. Puede agregar esta instrucción debajo de al presionar tecla para que Mario mire hacia la derecha; debe quedar más o menos así: 

![15](https://eduteka.icesi.edu.co/imgbd/23/23-09/16.gif)

Muy bien, ahora ¿qué puede usted hacer para que Mario camine hacia la izquierda cada vez que presione la tecla correspondiente? Inténtelo y descubrirá que es muy fácil… ¿listo? Esta es una propuesta, pero también puede hacerse  de otra manera. Recuerde que en programación siempre hay diferentes formas de hacer las cosas y todas son correctas siempre y cuando, cumplan el objetivo propuesto.

![17](https://eduteka.icesi.edu.co/imgbd/23/23-09/17.gif)

Muy bien, ahora piense qué hacer para que Mario salte y se agache. Logramos lo anterior agregándole dos disfraces más, Mario3 y Mario4; no olvide editarlos para que los fondos coincidan. A continuación, agregue nuevamente: Control al presionar tecla y escoja, flecha arriba. Así, cada vez que la presionen Mario debe saltar. El movimiento saltar no es tan simple porque hay que decirle al Objeto que suba una determinada posición en Y, en un tiempo determinado, respetando el lugar donde este ubicado, y que luego regrese a  la posición inicial, Este es un ejemplo:

Si Mario esta ubicado en X= -69 y Y= -135, le debe decir que se deslice, en un tiempo dado, a ocupar la posición X=69 y Y=-100; es decir, que mantenga la posición de X, pero que suba respecto al eje Y. Para esto puede utilizar la instrucción deslizar en 1 seg a x = -200 y: -135; también puede: modificar el tiempo para variar la velocidad con la que Mario sube, modificar Y para ver que tan alto lo hace y, por último, busque la manera para que X siempre apunte a la posición X de Mario. Se consigue lo anterior  con: ***Movimiento,*** allí se escoge ***posición X***, que le dará siempre el valor actual de la ubicación de Mario en el eje X. La instrucción debe quedar así: 

![18](https://eduteka.icesi.edu.co/imgbd/23/23-09/18.gif)

Agregue una instrucción similar, pero con posición y = -135, para que este regrese nuevamente al piso.

![19](https://eduteka.icesi.edu.co/imgbd/23/23-09/19.gif)

Al final puede agregar cambio de disfraz entre las instrucciones para que el salto se vea más real. La instrucción queda así:

![20](https://eduteka.icesi.edu.co/imgbd/23/23-09/20.gif)

Ahora, puede usted  agregar la instrucción para que se agache cuando se presione la flecha abajo. En este caso no tiene que desplazar a Mario para ningún lado, solo debe simular que se agacha cambiando de disfraz por un tiempo, piénselo. A continuación, una propuesta:

![21](https://eduteka.icesi.edu.co/imgbd/23/23-09/21.gif)

 

Piense ahora cómo puede agregarle sonido al juego. Vaya a Escenario y, en Sonido, haga clic en importar. Escoja luego, de la lista de sonidos disponibles, Overworld; será este la música de fondo que se escuche mientras el juego Mario esté en ejecución. Vaya a programas y en ***Sonido***, ***agregue tocar sonido Overworld y espere****. *Si hace clic sobre esta, escuchamos la música. Esta instrucción se utiliza para tocar un determinado sonido y el programa espera, hasta que la música termine, para realizar la siguiente instrucción. Se ha preguntado ¿qué puede hacer para que se escuche esta canción cada vez que presione la Bandera Verde? y ¿qué puede hacer para que la música se repita continuamente?, ¿Adivina? Claro que lo sabe, solo tiene que usar las instrucciones vistas en la lección anterior y se dará cuenta de que es fácil.

![22](https://eduteka.icesi.edu.co/imgbd/23/23-09/22.gif)

 

A continuación, piense cómo agregar sonidos cada vez que Mario salte o se agache. Para ello, seleccione el Objeto Mario y en sonidos, haga clic en importar, seleccione *Mario Jump. *Vaya a Sonido y agregue: tocar sonido Mario Jump,debajo de: ***al presionar tecla flecha arriba***. Con esta acción se oirá el sonido cada vez que salte. En este caso, a diferencia del sonido anterior, no tiene que esperar a que termine el sonido para ejecutar la siguiente instrucción. Esta deben ejecutarse casi al mismo tiempo para que parezca lo más real posible. 

![23](https://eduteka.icesi.edu.co/imgbd/23/23-09/23.gif)

 

Por último, puede agregar sonidos predeterminados en el programa, utilizando la instrucción: tocar nota 60 durante 0.2 pulsos*, *instrucción esta que permite tocar una nota del piano durante 0.2 pulsos. Esta instrucción puede agregarse donde se desee,  para simular el sonido de un paso o de una agachada. A continuación un ejemplo:

![24](https://eduteka.icesi.edu.co/imgbd/23/23-09/24.gif)

Usted ha mejorado el juego. Ahora que ya sabe como mover el Objeto además de, editar y crear sonidos e imágenes. También sabe cómo agregar nuevos sonidos, editar los personajes, adicionar otros nuevos y hacer que estos salten hacia los lados. 

Muy bien, esto ha sido todo por hoy. Gracias por acompañarnos, lo esperamos en la lección 4.

**CRÉDITOS:***![23](https://eduteka.icesi.edu.co/imgbd/23/23-05/icesi.gif)*

*Tutorial de Scratch elaborado por Francisco Martínez, como parte de su proyecto de grado para optar por el título de Ingeniero de Sistemas de la Universidad Icesi. En la validación de este tutorial participaron docentes de Informática de las siguientes Instituciones educativas de Cali: Colegio “Miraflores” de Comfandi, Instituto Nuestra Señora de la Asunción (INSA) y Corporación Educativa Popular (CEP). *

 

*Publicación de este documento en EDUTEKA: Noviembre 01 de 2009.

Última modificación de este documento: Noviembre 01 de 2009.*
