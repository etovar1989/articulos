---
id: 1006
title: "Lección 6: Eventos y menú de inicio"
date: 2009-11-05
tags: scratch,programacion,solucion problemas,actividades,guias uso,videos
category: Herramientas
author: Francisco Martínez
---

# Lección 6: Eventos y menú de inicio

> Sexto video, de una serie de 9, en el que se aprende cómo manejar eventos, contar vidas y agregar nuevos personajes. Además, se creará un menú de inicio para el juego. Con este video se concluye la elaboración del juego Mario que utiliza las instrucciones y opciones disponible en la versión 1.3.1 de Scratch. Todo lo indicado en este y los videos anteriores, funciona en la versión 1.4 de Scratch.

Sexto video, de una serie de 9, en el que se aprende cómo manejar eventos, contar vidas y agregar nuevos personajes. Además, se creará un menú de inicio para el juego. Con este video se concluye la elaboración del juego Mario que utiliza las instrucciones y opciones disponible en la versión 1.3.1 de Scratch. Todo lo indicado en este y los videos anteriores, funciona en la versión 1.4 de Scratch.

---

# TALLER DE SCRATCH



## Lección 6



*

Descargue imágenes y sonidos necesarios para que usted realice este juego. Haga clic aquí.

Descargue archivo ejecutable con la versión final del juego Mario (EXE, 3.304 MB)*


 

 



Hola, bienvenido a los tutoriales de Scratch. Esta es la lección 6, en ella aprenderá a manejar eventos, contar vidas y agregar nuevos personajes además de, crear un menú de inicio. 



Antes de continuar, si tiene alguna duda sobre alguno de los temas previos le recomendamos revisar las lecciones anteriores, en las que se explica, paso a paso, cada uno de ellos.



Iniciemos, agregándole vidas a Mario, mediante un clic en el botón abrir ![1](https://eduteka.icesi.edu.co/imgbd/23/23-09/25.gif), ubicado en el centro del área de diseño. Escoja allí, de la carpeta Objetos, Hongo Verde. Edite el disfraz, y cambie el nombre por Hongo.Ahora agregue el código para que el hongo caiga desde el cielo, como lo han hecho los demás objetos, solo que esta vez, caerá indefinidamente. 



![65](https://eduteka.icesi.edu.co/imgbd/23/23-09/65.gif)



En seguida, vaya a variables y haga clic en el botón nueva variable, escriba vidas, seleccione **para todos los objetos** y haga clic en el botón aceptar. Esta nueva variable es la que llevará la cuenta del número de vidas de Mario.



Haga clic en el Objeto Mario, para comenzar inserte la instrucción ***al presionar (Bandera Verde), ***luego agregue fijar vidas a y seleccione 1. Con esa instrucción, Mario comienza cada juego con una vida. Ahora va a adicionarle una vida, cada vez que Mario atrape un Hongo. Para hacerlo, seleccione el Objeto Hongo y agregue un nuevo hilo (adjuntando una instrucción ***al presionar (Bandera Verde) ***y agregue luego una instrucción repetitiva que evalué indefinidamente (por siempre) si el hongo está tocando a Mario; si lo anterior es cierto, agregue una vida a la variable vidas. 



![66](https://eduteka.icesi.edu.co/imgbd/23/23-09/66.gif)



Ahora, debe usted modificar a los enemigos, para que en lugar de detener el juego cuando toquen a Mario le reste una vida y si esta resta llega a cero el juego se para. Seleccione un enemigo y cambie la condición dentro de la instrucción, por ***vidas < 1***, Para hacer esto, desencaje la condición anterior y ubíquela en un lugar vacio; posteriormente, haga clic derecho y seleccione borrar,. Luego diríjase a ***Números ***y adicione **<**. Posteriormente, vaya a variables y agregue ***vidas ***dentro del primer cajón de ***<***; por último, escriba 1 en el segundo cajón. 



![67](https://eduteka.icesi.edu.co/imgbd/23/23-09/67.gif)



Con las acciones anteriores usted garantiza que el juego solo se detenga cuando las vidas llegan a 0. Ahora agregue el código necesario para que disminuyan las vidas cada vez que Mario choque con un enemigo. Vaya a ***Control ***y adicione ***al presionar (Bandera Verde)***; luego, agregue debajo ***por siempre***, y dentro de este un bloque*** Si, ***y, en*** Sensores,*** escoja ***¿tocando Mario?, ***debajo otro *** Si, ***y, como condición, vidas>0 y después adentro, agregue ***cambiar vidas por ***y escoja –1.  Por último, vaya a ***Control*** y adicione ***esperar 1 segundo.*** El programa debe quedar como se muestra en la siguiente figura:



![68](https://eduteka.icesi.edu.co/imgbd/23/23-09/68.gif)



Agregue estas dos modificaciones en todos los enemigos y ensaye nuevamente su juego. Se debe poder jugar mientras se tengan vidas disponibles que se pierden cada vez que Mario se choca contra un enemigo.



Ahora vaya al Objeto Mario y agregue una condición ***Si ***en cada uno de los movimientos (flecha derecha, izquierda, abajo y arriba), para que solo se mueva cuando tenga vidas disponibles. 



![69](https://eduteka.icesi.edu.co/imgbd/23/23-09/69.gif)



Solo le falta cambiar la Pantalla cuando Mario Pierda, vaya a Escenario (Debajo de los Objetos) y haga clic en el botón pintar. Edite su fondo como le guste, de tal manera que se vea claramente que perdió el juego y haga clic en el botón Aceptar.



![70](https://eduteka.icesi.edu.co/imgbd/23/23-09/70.gif)



Ahora en la pestaña programas, debe usted agregar el código para cambiar de fondo cuando se llega a 0 vidas. Agregue una instrucción ***Si ***debajo del** *por siempre* **que adicionó anteriormente. Como condición ponga ***vidas <1 ***y adentro del ***Si ***agregue  ***cambiar disfraz a fondo 4.***



![71](https://eduteka.icesi.edu.co/imgbd/23/23-09/71.gif)



Para terminar, debe agregar un menú al principio del juego, para poder escoger su personaje. Primero debe generar una variable global para decirle al juego que personaje va a utilizar.  Para lograrlo, haga clic en ***Variables *** y  luego en el botón ***Nueva Variable, ***escriba un nombre y presione  el botón aceptar. Después, vaya a Escenario (ubicado debajo de los Objetos) y agregue la instrucción ***fijar personaje a 0***. Con esta instrucción usted indica que mientras no se seleccione un personaje la variable estará en 0. Vaya ahora a fondos y de clic en copiar sobre el fondo3 (Fondo Principal); edite el fondo y agregue un texto que diga ***“Seleccione un personaje”. ***Regrese ahora al programa y edite la instrucción ***cambiar fondo a ***y cambia del fondo3 al fondo que acaba de crear. 



Para continuar, seleccione el Objeto Mario y debajo de: ***al presionar (Bandera Verde), ***agregue la instrucción: ***mostrar ***y cambie la posición en ***ir a x y, ***por una en el centro de la pantalla, como se ve en la siguiente figura:



![72](https://eduteka.icesi.edu.co/imgbd/23/23-09/72.gif)



Ahora vaya a Control y agregue una nueva instrucción: ***al presionar Mario, ***En seguida***,  ***agregue el código para escoger a Mario como personaje; en este caso, su código será 1, por lo que la variable personaje, deberá cambiar a 1, cuando lo escoja.  Adicione debajo de: ***al presionar Mario ***y, como condición escoja: ***Personaje = 0, ***Fije en 1 el personaje con la instrucción: ***fijar personaje a 1 ***y, por último, diríjase a ***Control ***y agregue: **enviar a todos**** **Ahora, genere un nuevo evento haciendo clic en nuevo y escogiendo como nombre Inicio. Esto le permite crear eventos para que los Objeto se comuniquen entre si; en este caso, Mario le dice a todos los demás Personajes que el juego va a comenzar.



![73](https://eduteka.icesi.edu.co/imgbd/23/23-09/73.gif)



 



Ahora adicione el código cuando reciba Inicio. Vaya a ***Control ***y agregue la instrucción: ***al recibir ***y luego, seleccione de la pestaña Inicio debajo de este, agregue una intrusión*** Si Si No, ***Como condición escoja ***Personaje=0 *** y adentro del ***Si, ***agregue ***mostrar ***y diríjase  a ir a x y, con las coordenadas de inicio del juego (coordenadas anteriores), debajo del Si No agregamos ***esconder.***



![74](https://eduteka.icesi.edu.co/imgbd/23/23-09/74.gif)



Muy bien, ya configuró a Mario, ahora agréguele nuevos personajes: Para esto, haga  clic derecho en el Objeto de Mario (abajo del área de diseño) y seleccione duplicar; a continuación cambie el nombre del Objeto, agregue los Nuevos Disfraces, edítelos y borre los de Mario. Luego, configure el cambio de disfraces para que coincidan con el nuevo Personaje. [https://instalikesfast.com/](https://instalikesfast.com) Cambie la posición inicial para que no tape a Mario y edite fijar personaje y el condicional ***Personaje = 1 *** por un código consecutivo. En este caso sería Personaje = 2. 



Después de adicionar todos los personajes, vaya a editar los enemigos,  seleccione el Objeto de uno de ellos y donde dice: ***Si ¿tocando Mario? ***Cambie por una instrucción con el condicional “o” agregando cada Personaje,  EJ: ***¿tocando Mario? O ¿tocando Luigi? O ¿tocando Princesa?, ***de esta manera todos los personajes del juego reaccionan al manejo del teclado. En seguida adicione la instrucción ***Al recibir Inicio ***y agregue debajo ***mostrar. *** El código debajo de ***al presionar (Bandera Verde) ***lo debe ubicar debajo de ***Al recibir Inicia*** que acaba de agregar y, en ***al presionar (Bandera Verde), ***adicione la instrucción ***esconder. ***Aplique estos cambios a cada uno de los enemigos y de los objetos que caen (Monedas y Hongos).



![75](https://eduteka.icesi.edu.co/imgbd/23/23-09/75.gif)



Por ultimo, vaya a Escenario y agregue la instrucción ***al recibir, ***y seleccione Inicio;  debajo de ella agregue, ***cambiar el fondo a ***y seleccione el fondo principal, fondo3.



Muy bien, eso ha sido todo, gracias por acompañarnos, nos vemos en la siguiente lección.  



 



**CRÉDITOS:***![23](https://eduteka.icesi.edu.co/imgbd/23/23-05/icesi.gif)*

*Tutorial de Scratch elaborado por Francisco Martínez, como parte de su proyecto de grado para optar por el título de Ingeniero de Sistemas de la Universidad Icesi. En la validación de este tutorial participaron docentes de Informática de las siguientes Instituciones educativas de Cali: Colegio “Miraflores” de Comfandi, Instituto Nuestra Señora de la Asunción (INSA) y Corporación Educativa Popular (CEP). *



 



 



*Publicación de este documento en EDUTEKA: Noviembre 01 de 2009.

Última modificación de este documento: Noviembre 01 de 2009.*
