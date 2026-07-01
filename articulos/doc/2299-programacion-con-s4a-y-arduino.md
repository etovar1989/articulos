---
id: 2299
title: "Programación con S4A y Arduino"
date: 2014-11-01
tags: scratch,programacion,robotica,ciencias naturales
category: Herramientas
author: Álvaro Contreras
---

# Programación con S4A y Arduino

> El entorno Scratch para Arduino (S4A), en asocio con la tarjeta Arduino Uno, permite generar actividades de clase, con diferentes grados de dificultad, en las cuales los estudiantes pueden construir simulaciones o juegos a partir de la lectura de datos del mundo físico. Este documento presenta tanto la tarjeta Arduino como el entorno de programación S4A e igualmente muestra como ejemplo tres actividades de este tipo: funcionamiento de un semáforo, lanzamiento de un dado digital y juego tradicional de ping-pong.

El entorno Scratch para Arduino (S4A), en asocio con la tarjeta Arduino Uno, permite generar actividades de clase, con diferentes grados de dificultad, en las cuales los estudiantes pueden construir simulaciones o juegos a partir de la lectura de datos del mundo físico. Este documento presenta tanto la tarjeta Arduino como el entorno de programación S4A e igualmente muestra como ejemplo tres actividades de este tipo: funcionamiento de un semáforo, lanzamiento de un dado digital y juego tradicional de ping-pong.

---

- 



 



# PROGRAMACIÓN CON ARDUINO







Scratch es un entorno gráfico de programación de computadores ampliamente utilizado en educación escolar. Entre sus características, este entorno admite la conexión de dispositivos externos equipados con sensores, tales como: http://www.picocricket.com/picoboard.html “[PicoBoard](http://www.picocricket.com/picoboard.html)”, la Tarjeta de Sensores de Eduteka ([TDS](https://eduteka.icesi.edu.co/modulos/9/285/2089/1)), [Makey Makey](http://www.makeymakey.com/), [Lego WeDo](http://education.lego.com/es-es/preschool-and-school/lower-primary/7plus-education-wedo/introducing-wedo), [Teléfonos celulares inteligentes o Tabletas](https://play.google.com/store/apps/details?id=com.khanning.scratchercontrol&hl=es_419) (Android) y la tarjeta/placa [Arduino](http://www.arduino.cc/). Estos dispositivos, mediante sensores, captan estímulos del mundo físico y Scratch puede leer los datos que estos arrojan. Con excepción de Arduino, todos los dispositivos anteriores funcionan con la versión 1.4 de Scratch. En el caso de Arduino, el [Citilab](http://citilab.eu/) [Centro de Innovación Social y Digital de Barcelona, España] creó una variante de Scratch que denominaron [Scratch Para Arduino](http://s4a.cat/index_es.html) (S4A) que permite controlar la tarjeta con ese entorno. Todos estos dispositivos posibilitan enriquecer los ambientes de aprendizaje estimulando  el desarrollo del pensamiento computacional mediante la programación de computadores.



![imagen](../imgbd/27/27-09/S4A.jpg) El entorno S4A en asocio con la tarjeta Arduino, permite generar actividades de clase, con diferentes grados de dificultad, en las cuales los estudiantes pueden construir simulaciones o juegos a partir de la lectura de datos del mundo físico. Por ejemplo, simular el funcionamiento de un semáforo o generar valores aleatorios que simulen tanto el lanzamiento de un dado como los resultados que acción arroja.




**TARJETA ARDUINO**

Según sus creadores, Arduino es una tarjeta/placa electrónica de código abierto basada en hardware y software fácil de usar. Está dirigida a quienes deseen realizar proyectos interactivos y es muy utilizada hoy en procesos educativos. Existen varias versiones de esa tarjeta, sin embargo, para los proyectos de clase presentados en este artículo,  Eduteka trabajó con la versión conocida como “[Arduino Uno](http://arduino.cc/en/Main/arduinoBoardUno)”, por ser la diseñada para trabajar con el entorno de programación S4A y por encontrar que permite realizar actividades de aula con estudiantes de secundaría con las que se busca complementar temas ya vistos en clase; por ejemplo, el de  circuitos eléctricos.  

A continuación presentamos una tabla con algunos modelos de tarjetas Arduino y sus características:




https://eduteka.icesi.edu.co/imgbd/27/27-09/TablaComparativaArduino.jpg



Fuente: [El Boy](http://www.elboby.com/2011/09/%C2%A1la-familia-de-arduino-crece/)



 



La tarjeta “Uno” de Arduino consiste en una placa electrónica que tiene un microprocesador Atmega328; 14 pines digitales de entrada/salida, de los cuales 6 pueden utilizarse como salidas PWM (modulación de ancho de pulsos); 6 entradas analógicas; un resonador cerámico de 16 MHz; una conexión USB; un conector de alimentación; un microcontrolador (circuito) ICSP y, un botón de reinicio. La alimentación de corriente de esta tarjeta es dual, se puede conectar al puerto USB de un computador o a un adaptador de Corriente Alterna (CA) o de Corriente Contínua (CC).



 



![imagen](../imgbd/27/27-09/ArduinoUno.jpg)



[Arduino Uno](http://hacedores.com/arduino-o-raspberry-pi-cual-es-la-mejor-herramienta-para-ti/) 



 



**SCRATCH PARA ARDUINO (S4A)**

Arduino cuenta con su propio entorno de programación (basado en Wiring), pero dado que este es textual y poco atractivo para los estudiantes, se han adaptado otros entornos de programación gráficos para que puedan leer las señales que genera la tarjeta Arduino. Entre estos entornos gráficos que facilitan la programación de la tarjeta, tenemos: [Mindplus](http://www.mindplus.cc/), [Minibloq](http://blog.minibloq.org/p/download.html), [Modkit](http://www.modkit.com/), [Ardublock](http://blog.ardublock.com/engetting-started-ardublockzhardublock/) y [Scratch para Arduino](http://s4a.cat/index_es.html) (S4A).




![imagen](../imgbd/27/27-09/ScratchS4A.jpg)



Interfaz gráfica del entorno S4A



 



La versión de [Scratch para Arduino](http://s4a.cat/index_es.html) se descarga según  el sistema operativo del equipo en el que se vaya a trabajar; en este caso, Windows. Los archivos que se necesitan son [S4A](http://vps34736.ovh.net/S4A/S4A15.zip) y su respectivo [Firmware](http://vps34736.ovh.net/S4A/S4AFirmware15.ino). Este último es un programa que permite reconocer la Tarjeta Ardunio y comunicarse con ella desde S4A. Una vez instalado el entorno S4A y el Firmware, se abre el programa que luce muy similar a la versión 1.4 de Scrach, pero que ofrece unos bloques adicionales que permiten controlar los sensores conectados a Arduino. En el menú **Movimiento** del programa se puede verificar el estado de los sensores.




![imagen](../imgbd/27/27-09/ComandosScratchS4A.jpg)



 



El entorno S4A cuenta también con una tabla de sensores en la cual se puede observar el estado, tanto de las entradas digitales como de las análogas. Esta tabla aparece en el momento en que se abre el programa y se conecta la tarjeta Arduino al puerto USB del computador



![imagen](../imgbd/27/27-09/ReconocerPlaca.jpg) A continuación, citamos un conjunto de aspectos a tener en cuenta y algunos consejos que permiten trabajar mejor con Arduino tomados de la siguiente fuente: “Herramientas graficas para la programación de Arduino, de José Manuel Ruiz Gutiérrez”.



Para que el programa reconozca la placa/tarjeta se siguen los siguientes pasos:





1. Localizamos donde quedó almacenado el firmware que permite, no solo comunicarnos con el Arduino de manera serial, sino configurar las entradas y las salidas de la placa.
2. Abrimos el programa Arduino y copiamos en él el código para cargar el firmware.
3. Volvemos al entorno Scratch para buscar la tarjeta Arduino y seleccionamos el puerto, en este caso, para Windows se reconocen los puertos COM, lo cual verificamos en la administración de dispositivos del equipo.




Trabajamos los montajes con un Protoboard en el que se conectan los dispositivos electrónicos (resistencias, switches, potenciómetros, leds, etc) mediante cables que permiten establecer puentes de conexión con la Tarjeta Arduino.

- Recomendamos usar cables con conector en el extremo macho, a manera de conectores jumper. El cable a usar puede ser de los mismos utilizados para hacer cableado de red (UTP). Cada uno de sus hilos puede cortarse en pequeños trozos dejando libre los extremos; esto es, retirando de estos el recubrimiento de caucho para lograr un mejor contacto.





**

EJEMPLOS DE PROYECTOS DE CLASE CON ARDUINO**

A continuación presentamos dos simulaciones y un juego construidos con la tarjeta Arduino y el programa S4A: Funcionamiento de un semáforo, lanzamiento de un dado digital y un juego tradicional de ping-pong. Consulte en el Gestor de Proyectos de Eduteka los montajes de estos, en [Arduino y S4A](https://eduteka.icesi.edu.co/proyectos.php/2/24877).



Para elaborar la [**simulación del funcionamiento de un semáforo**](https://eduteka.icesi.edu.co/proyectos.php/2/24877), los estudiantes deben realizar un esquema eléctrico del montaje sobre la protoboard. Para ello, deben utilizar tres diodos led de diferente color (rojo, amarillo y verde), usar tres resistencias de 330 ohmios, la Tarjeta Arduino UNO y cables de conexión. Posteriormente, deben analizar cómo programar la secuencia de encendido/apagado de los tres diodos de manera que funcionen como lo hace un semáforo. Se debe tener en cuenta con cuál color se inicia y cuánto tiempo debe durar este encendido; tener en cuenta además, qué sucede en este tiempo con los otros dos diodos.




![imagen](../imgbd/27/27-09/Circuito1.jpg)



Foto tomada al ensamble de la Actividad que simula un semáforo



 



![imagen](../imgbd/27/27-09/Circuito2.jpg)

Semáforo en Protoboard y conexión a la tarjeta Arduino UNO







Para simular los resultados arrojados por un [**Dado Digital**](https://eduteka.icesi.edu.co/proyectos.php/2/24877), los estudiantes deben lograr que cada vez que se pulse el botón *pulsador* de la tarjeta Arduino, se generen números aleatorios entre 1 y 6 y se enciendan los diodos led correspondientes. Estos diodos deben permanecer encendidos durante periodos de tiempo muy cortos para mostrar los valores aleatorios que se van generando simulando que el dado está rodando. Luego de un tiempo determinado, se genera un número aleatorio definitivo (resultado) y deben quedar encendidos, durante un tiempo más largo, la cantidad de diodos led equivalente a dicho número.




![imagen](../imgbd/27/27-09/Circuito3.jpg)

Simulación de lanzamiento de dados y su conexión a la tarjeta Arduino UNO



 



En el caso del [**juego de Ping-Pong**](https://eduteka.icesi.edu.co/proyectos.php/2/24877), los estudiantes deben utilizar dos potenciómetros de 2K ohmios o de 5K ohmios que cumplan la función de mandos del juego. Asimismo, deben usar la Tarjeta Arduino UNO, cables de conexión y una tarjeta Protoboard. 

Cada uno de los potenciómetros debe controlar el movimiento de una raqueta en el S4A y el objeto “bola” debe moverse con trayectorias rectas, pero con direcciones aleatorias. Los estudiantes deben programar el juego para que al girar los potenciómetros, cada objeto “raqueta” se desplace en el eje Y (hacia arriba o hacia abajo), de manera que pueda tocar el objeto “bola”. Si cualquiera de las dos raquetas toca la bola, esta, la bola, debe rebotar en una dirección aleatoria.




![imagen](../imgbd/27/27-09/Circuito4.jpg)

Juego de Ping-Pong y su conexión a la tarjeta Arduino UNO



 



**CRÉDITOS**:

Los desarrolladores del entorno de programación S4A  fueron los españoles Marina Conde, Víctor Casado, Joan Güell, José García y Jordi Delgado con la ayuda del Grupo de Programación Smalltalk del Citilab. 

Articulo elaborado por Eduteka con información preveniente de las siguientes fuentes:





- 

[Arduino](http://arduino.cc/en/Main/arduinoBoardUno)




- 

[Dado con leds Arduino y Scratch (S4A)](http://softwareybarralibre.org/?q=content/e-9-dado-con-leds-arduino-y-scratch-s4a).




- 

[Juego de ping pong con Scratch (S4A) y Arduino para los mandos](http://softwareybarralibre.org/?q=content/e-8-juego-de-ping-pong-con-scratch-s4a-y-arduino-para-los-mandos).




- 

[Programación de un semáforo con S4A y Arduino](http://softwareybarralibre.org/?q=content/e-1-programaci%C3%B3n-de-un-sem%C3%A1foro-con-s4a-y-arduino).




- 

Curso Miriada X: Scratch y Arduino para Profesores.




- 

Herramientas graficas para la programación de Arduino, José Manuel Ruiz Gutiérrez.




- 

Libro Arduino: Tiendaderobotica.com




- 

[Solo Robótica](http://solorobotica.blogspot.com/2012/04/s4a-scratch-para-arduino.html).




- 

[Guía de Arduino, TD Robótica](http://tdrobotica.co/tutoriales/81-arduino-2/345-guia-arduino)






 



*Publicación de este documento en EDUTEKA: Noviembre 01 de 2014.

Última modificación de este documento: Noviembre 01 de 2014.*



 
