---
id: 1005
title: "Lección 5: Manejo de variables y cronómetro"
date: 2009-11-06
tags: scratch,programacion,solucion problemas,actividades,guias uso,videos
category: Herramientas
author: Francisco Martínez
---

# Lección 5: Manejo de variables y cronómetro

> Quinto video, de una serie de 9, en el que se aprende cómo manejar variables, cronometro, así como a enviar y recibir eventos. A estas alturas, el juego ya suma puntos cuando Mario toca las monedas y resta cuando toca rocas, martillos o balas.

Quinto video, de una serie de 9, en el que se aprende cómo manejar variables, cronometro, así como a enviar y recibir eventos. A estas alturas, el juego ya suma puntos cuando Mario toca las monedas y resta cuando toca rocas, martillos o balas.

---

# TALLER DE SCRATCH

## Lección 5

*

Descargue imágenes y sonidos necesarios para que usted realice este juego. [Haga clic aquí.](http://eduteka.org/pdfdir/Scratch_Lecciones_Insumos.php)

Descargue archivo ejecutable con la versión final del [juego Mario](https://eduteka.icesi.edu.co/Scratch/JuegoMario.exe) (EXE, 3.304 MB)*

Hola, bienvenido a los tutoriales de Scratch, esta es la lección 5. Aprenderemos a manejar variables, cronometro, así como a enviar y recibir eventos. Antes de comenzar, si tienes alguna duda sobre un tema previo te recomendamos que veas las lecciones anteriores.

Comience hoy abriendo el programa que realizó en la lección 4 y guárdelo con otro nombre. Para ello, hacemos clic en el botón ***Guardar como*** y escribimos L5 como nombre de archivo en la parte inferior de la ventana y luego hacemos clic en el botón ***Aceptar***. Con esto podremos trabajar en esta lección sin modificar el programa de la lección anterior. 

Para poder realizar esta lección, primero debemos entender el término variable, que nos ayudará a mejorar nuestro juego.

![1](https://eduteka.icesi.edu.co/imgbd/23/23-09/ima2.gif)

Recuerde que ya ha trabajado con variables en la Lección 4. ***Posición X ***es una variable que almacena la posición en el eje x de un Objeto y que cambia por ejemplo, cuando Mario camina. La característica de este tipo de variables es que los usuarios (nosotros), no podemos modificarlas, por eso se llaman variables del sistema. Afortunadamente, en Scratch usted puede crear sus propias variables y darles el valor que quiera.

Genere una; haga clic en Escenario (en la parte inferior del área de Objetos) y vaya a Variables. Allí, haga clic en el botón ***Nueva variable, ***al hacerlo el programa pregunta el nombre de la Variable, escriba “**Nivel**” y seleccione ***Para todos los objetos***, concluya haciendo clic en el botón Aceptar (en Scratch las variables, como la que acabamos de crear, las pueden utilizar todos los Objetos; también se pueden crear variables que utilice un solo Objeto. Para esto último seleccione ***Para este objeto*** en lugar de ***Para todos los objetos***). Siguiendo este orden de ideas, ¿cree usted que la variable ***Posición X***, es visible para todos los Objetos o solo para uno solo Objeto?

Si quiere establecer un valor determinado para la Variable, puede usar la instrucción: ***fijar Nivel a *** y seleccionar además, un numero positivo o negativo (enteros o decimales).

![51](https://eduteka.icesi.edu.co/imgbd/23/23-09/51.gif)

De otro lado, si se quiere agregar un valor a la variable, se puede utilizar la instrucción: ***cambiar Nivel por*** y digitar el valor del incremento. De esta  manera,  si la variable, antes de llamar a la instrucción vale 1, después de llamarla valdrá 2.

![52](https://eduteka.icesi.edu.co/imgbd/23/23-09/52.gif)

Por último, si necesita ver el valor actual de la variable puede usar la siguiente instrucción: 

![53](https://eduteka.icesi.edu.co/imgbd/23/23-09/53.gif)

Ahora que usted ha credo esta variable, úsela, haciendo clic en Escenario, ubicado en la parte inferior del área de diseño y, agregue en, Variables, fijar nivel a y le escribimos el valor 1. Arrastre esta instrucción y póngala debajo de una nueva instrucción*** al presionar (Bandera verde).***

![54](https://eduteka.icesi.edu.co/imgbd/23/23-09/54.gif)

De esta manera, el nivel inicial del juego siempre será 1. A continuación va usted a aumentar el nivel cada 30 segundos.  Para ello, haga uso  del cronómetro, que es una Variable del Sistema, que cuenta un determinado período de tiempo. Diríjase a Sensores y agregue, debajo de ***fijar nivel a, ***la instrucción: ***reiniciar cronometro***; con esta acción garantizará***, ***que su cronometro empiece en 0. Ahora adicione el código que le permite aumentar el nivel. Vaya a Control y agregue por siempre debajo de ***reiniciar cronometro* **Enseguida, agregue ***Si ***en el interior de por siempre.****Vaya a Números y adicione ***>***, dentro del condicional. Luego, haga clic en ***Sensores ***y agregue*** cronómetro, ***en el primer círculo de la comparación y, en el segundo, escriba 30. De esta manera, el programa revisará constantemente el valor del cronometro.

![55](https://eduteka.icesi.edu.co/imgbd/23/23-09/55.gif)

Dentro del Si, adicione la instrucción ***cambiar Nivel por,  ***ubicada en ***Variables ***y por último, de  Sensores agregue debajo la instrucción ***reiniciar cronometro***. De esta manera, la variable Nivel aumentará cada 30 segundos hasta que el juego termine.

![57](https://eduteka.icesi.edu.co/imgbd/23/23-09/57.gif)

Ya ha creado usted la variable Nivel, sin embargo la dificultad del juego no depende del nivel sino del tiempo transcurrido desde su inicio. . Para modificar el tiempo de caída de la roca, seleccione el Objeto Roca (ubicado en la parte inferior del área de trabajo) modificando los segundos  que tarda en ***deslizar en seg. a x: y:, ***Esto se puede hacer creando una fórmula matemática que disminuya el tiempo a medida que aumenta el nivel del juego. Trate de generar su propia fórmula de dificultad, por el momento aquí le ejemplificamos una:

![55](https://eduteka.icesi.edu.co/imgbd/23/23-09/58.gif)

Ahora, utilizando las funciones matemáticas podrá implementarla, recuerde que se puede insertar una función dentro de otra, algunos valores los debe digitar y ***Nivel,*** lo puede sacar de la sección ***Variables. ***

![59](https://eduteka.icesi.edu.co/imgbd/23/23-09/59.gif)

El siguiente es un ejemplo de código final para la Roca, con la velocidad ajustada en función del nivel de dificultad del juego:

![60](https://eduteka.icesi.edu.co/imgbd/23/23-09/60.gif)

Trate ahora de aumentar todavía más la dificultad del juego, agregando ahora nuevos enemigos. Para proceder, vaya al área de diseño donde están los Objetos y haga clic derecho en el de la roca. Seleccione la opción duplicar, para copiar en el nuevo Objeto el código y los disfraces. Párese sobre el nuevo Objeto, vaya a la pestaña ***Disfraces ***y haga clic en el botón ***importar; ***escoja algún enemigo de los que vienen en la carpeta de este tutorial, en el siguiente ejemplo se importará el disfraz del Martillo, eliminando los otros disfraces de la roca y cambiando el nombre del Objeto por Martillo (parte superior, en las pestañas).

El disfraz se edita para combinar el fondo y se agrega el programa que se quiera. Además, se pueden modificar: el tiempo de espera, el rango de movimiento y la velocidad con la que cae el martillo. Este es el ejemplo. 

![61](https://eduteka.icesi.edu.co/imgbd/23/23-09/61.gif)

Recuerde que se han cambiado la velocidad de caída (mediante una fórmula) y el tiempo de espera. Ahora pruebe los conocimientos que ha adquirido y agregue nuevos personajes que caigan sobre Mario.

Después de adicionar la cantidad de enemigos que considere necesaria para que el juego sea más interesante, agregue algunas monedas para que caigan también; en este caso, las monedas sumaran puntos. Lo primero que se debe hacer es crear una nueva Variable; para ello, de clic en Escenario (debajo de los Objetos) y vaya a Variables y haga clic en el botón ***Nueva Variable***, escriba el nombre Puntos y haga clic en el botón ***aceptar, ***esta nueva variable le ayudará a sumar los puntos. Agregue ***Fijar Puntos a 0, ***debajo de ***Fijar Nivel a. ***

Ahora abra un nuevo Objeto ![25](https://eduteka.icesi.edu.co/imgbd/23/23-09/25.gif), y seleccione una de las monedas que viene con este tutorial; a continuación edite el fondo y adicione el código correspondiente para que esta caiga desde una posición aleatoria en la parte superior de la pantalla. El siguiente es un ejemplo:

![62](https://eduteka.icesi.edu.co/imgbd/23/23-09/62.gif)

A diferencia de los enemigos, las monedas deben sumar al entrar en contacto con Mario. Para lograrlo, agregue un condicional con el que establezca que cuando la moneda toque a Mario, el puntaje aumente. Vaya a ***Control ***y adicione Si y ubíquelo debajo de ***repetir hasta que, ***luego,*en **Sensores ***agregue ***Tocando Mario ***dentro del condicional y por ultimo adjunte el código que permite aumentar el puntaje, dentro del ***Si, ***seguidamente, agregue ***cambiar Puntos a  ***y ponga el valor que quiera aumentar por cada moneda recolectada. 

![63](https://eduteka.icesi.edu.co/imgbd/23/23-09/63.gif)

A estas alturas, su juego debe verse más o menos así.

![64](https://eduteka.icesi.edu.co/imgbd/23/23-09/64.gif)

Muy bien, eso ha sido todo, gracias por acompañarnos, nos vemos en la siguiente lección.  

 

**CRÉDITOS:***![23](https://eduteka.icesi.edu.co/imgbd/23/23-05/icesi.gif)*

*Tutorial de Scratch elaborado por Francisco Martínez, como parte de su proyecto de grado para optar por el título de Ingeniero de Sistemas de la Universidad Icesi. En la validación de este tutorial participaron docentes de Informática de las siguientes Instituciones educativas de Cali: Colegio “Miraflores” de Comfandi, Instituto Nuestra Señora de la Asunción (INSA) y Corporación Educativa Popular (CEP). *

 

 

*Publicación de este documento en EDUTEKA: Noviembre 01 de 2009.

Última modificación de este documento: Noviembre 01 de 2009.*
