---
id: 1008
title: "Lección 7: Figuras geométricas"
date: 2009-11-04
tags: scratch,programacion,solucion problemas,actividades,guias uso,videos,matematicas,geometria
category: Herramientas
author: Francisco Martínez
---

# Lección 7: Figuras geométricas

> Séptimo video, de una serie de 9, en el que se aprende cómo dibujar con el lápiz divertidas figuras geométricas.

Séptimo video, de una serie de 9, en el que se aprende cómo dibujar con el lápiz divertidas figuras geométricas.

---

# TALLER DE SCRATCH

## Lección 7

*

Descargue imágenes y sonidos necesarios para que usted realice este juego. [Haga clic aquí.](http://eduteka.org/pdfdir/Scratch_Lecciones_Insumos.php)

Descargue archivo ejecutable con la versión final del [juego Mario](https://eduteka.icesi.edu.co/Scratch/JuegoMario.exe) (EXE, 3.304 MB)*

 

 

Hola, bienvenido a los tutoriales de Scratch, esta es la lección 7. con ella aprenderá a dibujar con el lápiz, divertidas figuras geométricas. 

Antes de continuar, si tiene alguna duda sobre alguno de los temas previos le recomendamos revisar las lecciones anteriores, en las que se explica, paso a paso, cada uno de ellos.

En esta ocasión dibujará figuras geométricas, para ello utilizaremos un Objeto principal donde estará el código de cada una de las figuras, y luego llamaremos el código que necesitemos, cuando hagamos clic en el nombre de la figura (otro Objeto). 

Vamos a dibujar un cuadrado con la ayuda de un Objeto. Creamos un nuevo proyecto, haciendo clic en **Nuevo** y luego elegimos un Objeto que nos guste. Para dibujar, primero agregamos la instrucción ***al presionar objeto1, ***ubicado en ***Control*** y le cambiamos el nombre por “Cuadrado”. Debajo de esta instrucción ubicamos ***bajar lápiz,*** que se encuentra en la sección ***lápiz. ***Un lápiz nos permite dibujar la trayectoria de un objeto y podemos bajarlo en el momento que queramos empezar a dibujar y subirlo cuando terminemos de hacerlo. Ahora vamos a hacer que nuestro Objeto camine 100 pasos hacia adelante y luego gire 90° a la derecha. Para formar el cuadrado, debemos repetir este par de instrucciones 4 veces, utilizando el comando repetir. 

 

![76](https://eduteka.icesi.edu.co/imgbd/23/23-09/76.gif)

En Scratch, un hilo es como un mini código dentro de un programa que se puede ejecutar al mismo tiempo que lo hacen otros hilos. El código anterior es un hilo. Un programa puede tener múltiples hilos y hacer muchas cosas al mismo tiempo. En Scratch, cualquier bloque cuya etiqueta comience con “***al presionar***”, esta indicando o demarcando esencialmente el inicio de un hilo. Por otra parte, Scratch permite que múltiples hilos puedan comunicarse unos con otros mediante invocación y manejo de eventos. Un evento, entonces, es como un mensaje que un hilo envía a otro. Los bloques cuyas etiquetas comienzan con “***enviar a todos***” invocan eventos, así como los bloques cuyas etiquetas comienzan con “***al recibir***” manejan eventos.

Ahora vamos a dibujar utilizando eventos (métodos). Un evento es un grupo de instrucciones que se ejecutan desde cualquier sitio con solo mencionar su nombre, lo que nos evita tener que repetir instrucciones en diferentes áreas.

Agregamos la instrucción al recibir y escogemos en la pestaña nuevo y como nombre ponemos triangulo y luego agregamos debajo el código que teníamos en al presionar

![77](https://eduteka.icesi.edu.co/imgbd/23/23-09/77.gif)

Ahora podemos agregar otras figuras de la misma forma, creamos el método triangulo, repitiendo 3 movimiento de ángulos 120º, y el método hexágono con 6 movimientos de 60º cada uno. ¿Qué otras figuras puedes crear repitiendo los pasos y girando ángulos?, anímate a crear más figuras y agrégalas como eventos.

![78](https://eduteka.icesi.edu.co/imgbd/23/23-09/78.gif)

 

Pintamos Objetos uniformes en forma de botones: Cuadrado, Triángulo y Hexágono. Asignamos como nombre a cada Objeto el de la figura geométrica correspondiente. En el Objeto de Cuadrado agregamos tres instrucciones: ***Al presionar Cuadrado, borrar y enviar a todos cuadrado.*** Repetimos este procedimiento para el triángulo y para el hexágono.

![79](https://eduteka.icesi.edu.co/imgbd/23/23-09/79.gif)

Ahora vamos a dibujar nuevas figuras a partir de las que creamos anteriormente, agregamos una nueva variable que llamaremos repetir y será global, la agregamos a nuestra área de diseño y haciendo clic derecho escogemos valor y fijamos min en 1 y máximo en 10. Para dibujar un cuadrado que se repite varias veces con un giro de cinco grados entre cuadrado y cuadrado, debemos agregar un nuevo objeto que podremos llamar R. Cuadrado. Y creamos un nuevo evento llamado “cuadradorepetir”. Vamos al Objeto de la figura que dibuja y agregamos un nuevo evento llamado “cuadradorepetir”,  escogemos ***repetir ***y agregamos la variable que acabamos de crear, “repetir”, luego agregamos otro ***repetir ***con valor 4 y  llamamos al método cuadrado. Para hacer esto último agregamos ***enviar a todos cuadrado y esperar, ***luego adicionamos girar 90 dentro del primer ***repetir ***y girar 5 en el segundo ***repetir.***

![80](https://eduteka.icesi.edu.co/imgbd/23/23-09/80.gif)

Ahora probemos un código más interesante. Creemos otro Objeto para dibujar varios triángulos y agregaremos el evento “triangulorepetir”. Esta vez la figura dibujara 4 triángulos e  irá aumentando el tamaño del lápiz y cambiando de color a medida que va girando. Para hacer esto tendremos que agregar las instrucciones cambiar tamaño de lápiz por y ***cambiar color del lápiz por, ***dentro del bloque ***repetir ****como se ve en la siguiente figura:*

![81](https://eduteka.icesi.edu.co/imgbd/23/23-09/81.gif)

Divierte agregando más figuras geométricas y sucesiones de las mismas. Muy bien, eso ha sido todo, gracias por acompañarnos, le esperamos en la siguiente lección

 

**CRÉDITOS:***![23](https://eduteka.icesi.edu.co/imgbd/23/23-05/icesi.gif)*

*Tutorial de Scratch elaborado por Francisco Martínez, como parte de su proyecto de grado para optar por el título de Ingeniero de Sistemas de la Universidad Icesi. En la validación de este tutorial participaron docentes de Informática de las siguientes Instituciones educativas de Cali: Colegio “Miraflores” de Comfandi, Instituto Nuestra Señora de la Asunción (INSA) y Corporación Educativa Popular (CEP). *

 

 

*Publicación de este documento en EDUTEKA: Noviembre 01 de 2009.

Última modificación de este documento: Noviembre 01 de 2009.*
