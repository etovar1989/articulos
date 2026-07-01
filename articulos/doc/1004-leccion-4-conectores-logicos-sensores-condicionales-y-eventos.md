---
id: 1004
title: "Lección 4: Conectores lógicos, sensores, condicionales y eventos"
date: 2009-11-07
tags: scratch,programacion,solucion problemas,actividades,guias uso,videos
category: Herramientas
author: Francisco Martínez
---

# Lección 4: Conectores lógicos, sensores, condicionales y eventos

> Cuarto video, de una serie de 9, en el que se muestra cómo manejar conectores lógicos (y, o, no), manejo de sensores, condicionales (si, si no), así como envió y recepción de eventos.

Cuarto video, de una serie de 9, en el que se muestra cómo manejar conectores lógicos (y, o, no), manejo de sensores, condicionales (si, si no), así como envió y recepción de eventos.

---

# TALLER DE SCRATCH

## Lección 4

*

Descargue imágenes y sonidos necesarios para que usted realice este juego. [Haga clic aquí](http://eduteka.org/pdfdir/Scratch_Lecciones_Insumos.php).

Descargue archivo ejecutable con la versión final del [juego Mario](https://eduteka.icesi.edu.co/Scratch/JuegoMario.exe) (EXE, 3.304 MB)*

 

Bienvenido a los tutoriales de Scratch.

Esta es la lección 4. Aprenderemos a manejar conectores lógicos (y, o, no), manejo de sensores, condicionales (si, si no), así como envió y recepción de eventos. Antes de comenzar debes tener instalado la herramienta, conocer las funciones básicas y cómo mover a Mario por la pantalla, si no sabes o tienes alguna duda, puedes ver las lecciones 0, 1 y 2 donde se explica detalladamente cada uno de estos temas.

Inicie, abriendo el programa que realizó en la lección anterior y guárdelo con otro nombre. Para ello, haga clic en el botón “**Guardar como**” y escriba L4, en la parte de abajo, como nombre de archivo. Luego haga clic en el botón Aceptar.

Al terminara ya puede trabajar en esta nueva lección sin modificar el programa anterior. Comience por agregar un nuevo personaje al juego, haciendo clic en el botón de abrir ubicado en la mitad del área de diseño ![25](https://eduteka.icesi.edu.co/imgbd/23/23-09/25.gif) y allí seleccione Roca1, ubicado en la carpeta Roca, adjunta a este tutorial. Importe luego Roca2 y edite los dos Objetos para que combinen con un fondo determinado, tal y como lo hizo en la lección 3. 

Ahora, buscaremos que la roca caiga desde arriba hasta la tierra donde se encuentra ubicado Mario, haciendo clic en la pestaña programas y agregamos la instrucción de Control ***al presionar (bandera verde)***, agregamos el bloque ***por siempre, ***debajo de este y luego ubicamos dentro del bloque por siempre la instrucción de Apariencia *cambiar el disfracez *a; escoja Roca1. 

Después, sitúe la roca en la parte superior del juego, y agregue la instrucción ir a x: y: que se encuentran en ***Movimiento***. Como puede ver las coordenadas coinciden con la posición de la Roca; si quiere, para comenzar, puede escoger manualmente otra posición. Ahora, agregue ***deslizar en 1 segs a x: y: ***y configure las coordenadas para que coincidan con el borde de la tierra donde está ubicado Mario. En este caso como se quiere que la roca baje en línea recta, en x agregamos posición x. Haga clic en la ***bandera verde***, y si todo le funciona bien la roca debe caer, de manera continua, hacia el piso.

![26](https://eduteka.icesi.edu.co/imgbd/23/23-09/26.gif)

Ahora, va usted a mejorar el movimiento de la roca. Para lo anterior, vaya a: Apariencia cambiar el disfraz a y escoja Roca2. Además adicione en ***Control, esperar 1 segundos***. Este tiempo se puede modificar, haciendo que la rocas se demore en la tierra más o menos tiempo Ensaye, seleccione 0.5 y haga clic en ***bandera verde, ***y observe cómo *funciona* el* programa.*

![27](https://eduteka.icesi.edu.co/imgbd/23/23-09/27.gif)

Usted ya agregó un enemigo, la roca. Sin embargo, como el juego puede ser muy fácil si la roca cayera siempre en el mismo lugar, dificúltelo, modificando el comportamiento de la roca para que aparezca siempre en un lugar diferente de la parte superior de la pantalla. Para esto, vaya a: Números y escoja ***números al azar entre 1 y 10; ***agregue* esta instrucción en el campo x de *ir a x: y: de su programa. Sitúe la roca en la esquina superior izquierda y copie el valor de x en el primer campo de la instrucción: ***números al azar entre 1 y 10. ***después, sitúelo en el otro extremo y copie el valor de x en el segundo campo. Debe obtener una instrucción así:

![28](https://eduteka.icesi.edu.co/imgbd/23/23-09/28.gif)

Como puede observar, la Roca cae ahora desde cualquier lugar; sin embargo, nada pasa cuando entra en contacto con Mario. A continuación usted va a modificar el código para que detenga el juego cada vez que la  Roca caiga sobre Mario. Lo primero que hará es cambiar la instrucción por siempre por un bloque que repita, pero que tenga una condición de parada. Es decir que repita un conjunto de acciones hasta que pase algo. Para lograrlo, desencajamos el bloque por siempre y, luego, en Control agregamos repetir hasta que, debajo de ***al presionar (bandera verde), ***y adicionamos dentro del bloque las instrucciones contenidas en el bloque desencajado anteriormente; finalmente, borramos la instrucción ***por siempre***,****que debe estar sola, con clic derecho sobre ella, borrar.

![29](https://eduteka.icesi.edu.co/imgbd/23/23-09/29.gif)

 

Si hace clic en la ***bandera verde, ***verá que el programa se comporta exactamente igual al anterior, esto se debe a que no ha puesto sobre el hexágono una instrucción que detenga la repetición de la caída.

**En Scratch existen cuatro tipos de instrucciones:**

| 

Tipo

| 

Función

| 

Ejemplo

|

| 

**Instrucciones de Inicio**

| 

Instrucciones que se ejecutan cuando sucede un evento (Ej. Presionar Bandera Verde), si en un programa hay varias instrucciones de este tipo se ejecutarán en paralelo.

| 

* *![30](https://eduteka.icesi.edu.co/imgbd/23/23-09/30.gif)

|

| 

**Instrucciones de Programa**

| 

Instrucciones que ejecutan una acción particular; deben estar relacionadas con alguna instrucción de Inicio para que se ejecuten en algún momento. En la mayoría de los casos, se pueden ubicar instrucciones debajo y encima de ellas.

| 

*![31](https://eduteka.icesi.edu.co/imgbd/23/23-09/31.gif) *

|

| 

**Condiciones**

| 

Intrusiones que evalúan una pregunta de la forma SI o No, y se utilizan para tomar decisiones uniéndolas con Instrucciones de Programa

| 

* *![32](https://eduteka.icesi.edu.co/imgbd/23/23-09/32.gif)

|

| 

**Variable**

| 

Sirven para guardar un número, por ejemplo el número de vidas de un personaje.

| 

* *![33](https://eduteka.icesi.edu.co/imgbd/23/23-09/33.gif)

|

Muy bien, diríjase ahora a Sensores y agregue la instrucción ***¿tocando?, ***y escogemos seguidamente el Objeto Mario, y arrastre luego esta instrucción adentro de: repetir hasta que

![34](https://eduteka.icesi.edu.co/imgbd/23/23-09/34.gif)

*Muy bien, pero ¿Qué* pasa con el sonido del juego y con Mario, cuando este choca contra la roca?.... se sigue oyendo. Solucione esta situación agregando algunas instrucciones para detener el programa. Si va a ***Control******, ***puede ver dos instrucciones que lo ayudarán a ejecutar esta función. La primera de ellas es ***detener programa ***y sirve para suspender una determinada secuencia de un programa, ejemplo, detener una instrucción ***por siempre***; en cambio, la segunda instrucción detener todo, sirve para parar todos las instrucciones que se estén ejecutando de todos los programas. Su función es igual a la de presionar el botón rojo de pare, con la única diferencia que esta instrucción se realiza programadamente y no requiere una acción manual. ¿Cuál de las dos instrucciones le puede servir para solucionar el problema?  reflexione un momento.

Si pensó en: ***detener todo, ***está en lo correcto; la otra opción, aunque también le podría ayudar, sería mucho más complicada. Escoja esta instrucción y ubíquela debajo y por fuera, de la instrucción ***repetir hasta que***

Ahora el juego se detiene cuando se choca con Mario; pero ¿qué pasa cuando usted vuelve a presionar la*** bandera verde***?, nada. Lo anterior obedece a que la condición de tocar a Mario se cumple y el juego queda “congelado” si el jugador pierde. Esto se soluciona agregando una instrucción que ubique la roca en otra posición, de manera que no se cumpla la condición y el juego reinicie cada vez que se presiona la ***bandera verde.  ***Para realizar lo anterior, se agrega *ir a x: y: *del grupo *Movimiento *y se ubica debajo de ***al presionar (Bandera Verde),*** seguido de ***repetir hasta que.***

![35](https://eduteka.icesi.edu.co/imgbd/23/23-09/35.gif)

Logro usted detener la música, sin embargo, Mario sigue caminando. En las siguiente lección aprenderá a detener totalmente el juego; por ahora, continúe trabajándolo. 

Ahora, Mario va a saltar hacia adelante cada vez que se presionen, simultáneamente, las teclas de arriba y derecha. Para hacer esto, primero debe familiarizarse con el concepto Si, conocido en ingles como **if:** 

![3](https://eduteka.icesi.edu.co/imgbd/23/23-09/36.gif)

 

La función de esta instrucción, es evaluar una condición lógica (preguntas con respuesta de la forma sí o no), si la respuesta es sí, realiza las instrucciones contenidas en el bloque, pero, si la respuesta es no, salta a la siguiente instrucción por fuera del bloque. Vea un ejemplo.

![37](https://eduteka.icesi.edu.co/imgbd/23/23-09/37.gif)

Copie este código en un nuevo programa de Scratch y observe qué pasa. Se dará cuenta que el Objeto dirá Si (1 es igual a 1) y luego dirá No; lo anterior obedece a que primero ejecuta el contenido que está dentro del bloque Si y luego, ejecuta el resto del programa, si hace la prueba con una condición falsa (1 igual a 2) verá que sólo dice No.

Si, dependiendo de la respuesta, quiere, realizar una u otra acción,  pero no las dos como en el caso anterior, 

![38](https://eduteka.icesi.edu.co/imgbd/23/23-09/38.gif)

tiene que utilizar la condición Si Si No.

Funciona igual que el bloque anterior con la única diferencia que en esta oportunidad, si la condición del Si es falsa, salta al segundo bloque; pero si la condición es verdadera, hace el primer bloque e ignora el segundo. Un ejemplo:

![39](https://eduteka.icesi.edu.co/imgbd/23/23-09/39.gif)

Esta vez, solo dirá Si o No, dependiendo de la evaluación de la condición. Utilícela ahora para saltar hacia los lados. La razón por la cuál se requieren condicionales para realizar el salto, es que con una misma tecla se realizan dos acciones distintas y es necesario diferenciarlas para hacer solamente una, al presionar la combinación correcta.

![40](https://eduteka.icesi.edu.co/imgbd/23/23-09/40.gif)

Agregue ahora una condición ***Si Si No ***debajo del bloque ***al presionar tecla flecha arriba*** Vaya a Sensores y adicione a  la pregunta del Si la instrucción: ¿tecla espacio presionada? y cambie espacio por ***flecha derecha***.

![41](https://eduteka.icesi.edu.co/imgbd/23/23-09/41.gif)

Ahora agregue, en cada bloque, el código correspondiente a la acción de salto hacia arriba y salto diagonal; recuerde que puede utilizar la instrucción: ***+ ****en Números,* para sumar dos valores y crear el efecto de salto elíptico.

![43](https://eduteka.icesi.edu.co/imgbd/23/23-09/43.gif)

Solo le falta que Mario salte hacia la izquierda al  presionar flecha izquierda. Lógrelo agregando otro condicional (***SI SI No***) dentro del Si No, de esta forma:

![44](https://eduteka.icesi.edu.co/imgbd/23/23-09/44.gif)

Vamos a agregar un código parecido al salto derecho, pero ahora incluyéndole valores negativos; el código final, seria algo así:

![45](https://eduteka.icesi.edu.co/imgbd/23/23-09/45.gif)

Prosiga, adicionando un nuevo contrincante, que esta vez será controlado por un segundo jugador. Haga clic derecho sobre el Objeto Roca y dele duplicar, luego vaya a la pestaña Disfraces y haga clic en el botón importar, allí seleccione Bala1 de la carpeta Bala. Borre los otros dos disfraces (Roca1 y Roca2) y luego edite el fondo de Bala para que combine con el fondo del juego. Regrese a Programas y cómo podrá ver, tiene el mismo programa que Roca, pero con una Bala en sentido contrario. Para corregir su dirección, agregue la instrucción de Movimiento *** apuntar en dirección ***y seleccione -90, adicione esta instrucción justo debajo de ***Al presionar (Bandera verde). ***Ahora corrija las posiciones en X y Y para que la bala caiga sobre la tierra, de la misma forma que cae la Roca.

![46](https://eduteka.icesi.edu.co/imgbd/23/23-09/46.gif)

Muy bien, ahora desencaje el bloque ***repetir hasta que ***y agregue debajo la instrucción ***ir a x: y:*. **Seguidamente *y dentro de esta, adicione ***ir a x: y:**Vayaahora a Sensores * y, en el eje x: de la instrucción *que agrego antes, ponga: x del ratón, en y: ponga 152 y agregue : ¿ratón presionado? dentro del hexágono de ***repetir hasta que.*** Con este código la Bala se dirigirá  a la posición X que tenga el ratón, siempre y cuando, no haya presionado el ratón (mouse). Para probar, haga Clic en la ***Bandera Verde***.

![48](https://eduteka.icesi.edu.co/imgbd/23/23-09/48.gif)

Muy bien ahora agregue el código que desencajó antes, ubicado debajo de ***ir a x: y: ***Asegúrese que el segundo ***repetir hasta que ¿ratón presionado? ****quede adentro del primer **repetir hasta que ¿tocando a Mario? ***como muestra la figura.

![49](https://eduteka.icesi.edu.co/imgbd/23/23-09/49.gif)

 

Ahora elimine las intrusiones de ***Apariencia, ***pues este Objeto solo tiene un disfraz y también, la instrucción ***ir a x: y: ***Modificamos los segundos en ***deslizar en 1 segs a x: y:,*** cambiando por 2 segundos, para darle algo de ventaja a Mario, lo que se obtiene finalmente es :

![50](https://eduteka.icesi.edu.co/imgbd/23/23-09/50.gif)

Muy bien, ahora en el juego, Mario pierde cuando se choca contra la Roca o contra la Bala. Usted puede adicionar otros enemigos controlados por el computador o por otro oponente. Además de oponentes que vengan en diferentes direcciones y muchas otras cosa más; anímese a seguir aprendiendo.

Muy bien, eso ha sido todo, gracias por acompañarnos, nos vemos en la siguiente lección.

 

 

**CRÉDITOS:***![23](https://eduteka.icesi.edu.co/imgbd/23/23-05/icesi.gif)*

*Tutorial de Scratch elaborado por Francisco Martínez, como parte de su proyecto de grado para optar por el título de Ingeniero de Sistemas de la Universidad Icesi. En la validación de este tutorial participaron docentes de Informática de las siguientes Instituciones educativas de Cali: Colegio “Miraflores” de Comfandi, Instituto Nuestra Señora de la Asunción (INSA) y Corporación Educativa Popular (CEP). *

 

*Publicación de este documento en EDUTEKA: Noviembre 01 de 2009.

Última modificación de este documento: Noviembre 01 de 2009.*
