---
id: 905
title: "Scratch para los futuros científicos de la computación"
date: 2009-02-01
tags: scratch,programacion,ciencia computacion,pensamiento computacional
category: Artículos
author: Dr. David Malan
---

# Scratch para los futuros científicos de la computación

> El Dr. David Malan, profesor de Harvard, introduce su curso de Ciencias de la Computación con un ejemplo de Scratch en el que de manera clara y sencilla explica elementos de programación tales como instrucciones, expresiones booleanas, condicionales, ciclos, variables, hilos y eventos.

El Dr. David Malan, profesor de Harvard, introduce su curso de Ciencias de la Computación con un ejemplo de Scratch en el que de manera clara y sencilla explica elementos de programación tales como instrucciones, expresiones booleanas, condicionales, ciclos, variables, hilos y eventos.

---

- 


**SCRATCH PARA LOS FUTUROS CIENTÍFICOS DE LA COMPUTACIÓN** [1]





| 

![m](https://eduteka.icesi.edu.co/imgbd/23/malan.jpg)


| 

Por David J. Malan [malan@post.harvard.edu](mailto:malan@post.harvard.edu)   [2]   

http://www.eecs.harvard.edu/~malan/scratch/printer.php 

Traducción al español por EDUTEKA del artículo “Scratch for Budding Computer Scientists” escrito por el Dr. David Malan para trigésimo octavo simposio técnico de ACM en Covington, Kentucky en marzo de 2007.

http://www.eecs.harvard.edu/~malan/scratch/printer.php


|




# INTRODUCCIÓN



A primera vista, muchos lenguajes de programación parecen “escritos en griego” para el ojo del neófito, son una amalgama de inglés y una sintaxis inusual. Considere por ejemplo el programa siguiente, escrito en lenguaje Java.



class Hello

{

    public static void main(String [] args)

    {

        System.out.println("hola, mundo!");

    }

}



Todo lo que hace este programa, cuando se ejecuta, es escribir “hola mundo” en la pantalla del usuario. Usted podría haberlo adivinado con solo echarle un vistazo al código e ignorar todo lo que no le hacía sentido! Pero ¿qué son todos esas llaves, paréntesis y corchetes {([])}? ¿Qué quiere decir System.out? ¿Qué significa class Hello? y ¿public static void main(String [] args)? Ni sigamos.



Basta con decir que, cuando se trata de aprender a programar, la curva de aprendizaje con lenguajes como Java es muy plana. Antes de comenzar a resolver problemas, usted tiene que aprender primero a leer y escribir en un nuevo lenguaje, aún si la tarea que tiene por delante es sencilla, por ejemplo: “Hola mundo”. Aunque usted entiende a un forastero cuando pronuncia mal alguna palabras en Inglés, este no es el caso con los computadores que son intolerantes cuando de errores se trata. Y no hablemos de un punto y coma, porque sin este el programa anterior ni siquiera va a correr!



Aprender a programar tiene que ver, en últimas, con aprender a pensar lógicamente y aprender a abordar los problemas metódicamente. Sin embargo, los bloques de construcción sobre los que se apoyan los programadores para construir soluciones, son relativamente simples.



Por ejemplo, son comunes en programación tanto los “ciclos” (loops), por medio de los cuales un programa hace algo repetidamente, como las “condicionales”, por medio de las cuales un programa hace algo solo bajo ciertas circunstancias. También son comunes las “variables”, para que un programa, al igual que un matemático, pueda recordar ciertos valores. 



Para muchos estudiantes, la que parece una sintaxis críptica de lenguajes como Java, pone obstáculos en su camino para dominar estructuras tan sencillas como estas. Por lo que antes de abordar un lenguaje como Java, con sus paréntesis y puntos y comas, dirijamos nuestra atención a Scratch, “nuevo lenguaje de programación que le permite crear sus propias animaciones, juegos y producciones artísticas interactivas”. Aunque originalmente el grupo de investigación del MIT Media Lab, “Lifelong Kindergarten” (Jardín Infantil para toda la vida), lo desarrolló para que lo usaran los niños, Scratch también es útil y divertido para los futuros Científicos de la Computación [1].



Mediante la representación de los bloques de construcción de los programas por bloques con códigos de color (similares a las piezas de un rompecabezas), Scratch le baja la “exigencia” a la programación, permitiendo a los futuros científicos de la computación enfocarse en los problemas, en lugar de hacerlo en la sintaxis. La sintaxis, por supuesto, vendrá más tarde. Pero, por ahora, nos vamos a enfocar en la programación misma. De manera que por ahora, la programación va a parecerse más a armar un rompecabezas que a escribir en griego. 



Este tutorial inicia a los futuros científicos de la computación [1] en programación mediante bloques constructivos utilizando Scratch. Se supone que usted ya esta familiarizado con el uso de Scratch y por lo tanto tiene una idea general de cómo programar con el.



Este tutorial pretende formalizar su comprensión de la programación, enmarcando algunas construcciones básicas de programación en el lenguaje de Scratch. 



Fijemos nuestra atención para empezar, en las instrucciones



**INSTRUCCIONES

**En programación, una instrucción simplemente es la indicación o directiva que le dice al computador que haga algo. Piense en ella como un comando o sentencia. En Scratch, cualquier bloque cuya etiqueta se lea como una orden, es una instrucción. 



Uno de esos bloques le da instrucciones a un objeto (sprite) para que diga algo:



![d](https://eduteka.icesi.edu.co/imgbd/23/cod_decir.gif)



Otro bloque de instrucciones le indica que vaya a algún sitio:



![i](https://eduteka.icesi.edu.co/imgbd/23/cod_ir.gif)



A veces, usted quiere que una instrucción se ejecute solo bajo ciertas condiciones. Esas condiciones se definen en términos de expresiones Boleanas, en las que nos fijaremos a continuación.



# EXPRESIONES BOOLEANAS



En programación, las expresiones Boleanas, son expresiones que son o ciertas o falsas. En Scratch, cualquiera de los bloques que tiene forma de diamante alargado es una expresión Boleana. Uno de esos bloques es:



![r](https://eduteka.icesi.edu.co/imgbd/23/cod_raton.gif)



Después de todo, es cierto o es falso que el botón del ratón (mouse) está presionado. 



Otro de esos boques es: 



![m](https://eduteka.icesi.edu.co/imgbd/23/cod_menor.gif)



Después de todo, es cierto o es falso que un número dado es menor que otro numero. Con expresiones Booleanas podemos construir estructuras *condicionales*, en las que fijamos ahora nuestra atención.



# CONDICIONALES



En programación, una condición es algo que debe ser cierto para que algo pueda pasar. Una condición es entonces decir que “se evalúa para verdadero” o “se evalúa para falso”. En Scratch, cualquier bloque cuya etiqueta diga “si”, “al presionar” o “hasta que” es un tipo de constructo condicional.


Uno de esos bloques es:

![s](https://eduteka.icesi.edu.co/imgbd/23/cod_si.gif)



El constructo anterior se conoce generalmente como un “si constructo”. Con este podemos darle instrucciones a un objeto para que por ejemplo, diga “hola” únicamente cuando el usuario presione el botón izquierdo del ratón (mouse):



![s2](https://eduteka.icesi.edu.co/imgbd/23/cod_si2.gif)



Un constructo relacionado es “si - si no” constructo



![s](https://eduteka.icesi.edu.co/imgbd/23/cod_sino.gif)



Con el constructo anterior damos instrucciones al objeto para que diga “hola” o “adiós”, dependiendo de que el usuario haya presionado el botón izquierdo del ratón (mouse):



![s2](https://eduteka.icesi.edu.co/imgbd/23/cod_sino2.gif)



Tenga en cuenta de que estos constructos pueden anidarse para permitir, por ejemplo, tres condiciones diferentes:



![s3](https://eduteka.icesi.edu.co/imgbd/23/cod_sisino.gif)



El constructo anterior podría llamerse “si-si no”, “si-si no” constructo

Otro bloque condicional es:



![a](https://eduteka.icesi.edu.co/imgbd/23/cod_alpres.gif)



Y otro más de este tipo es:



![e](https://eduteka.icesi.edu.co/imgbd/23/cod_esp.gif)



Algunas veces, usted quiere que una o varias de estas instrucciones se ejecuten repetidamente de manera continua. Para implementar esta conducta, debemos dirigir nuestra atención a los *“ciclos*” *(loops).*



**CICLOS**

En programación, un ciclo puede causar la ejecución de múltiples instrucciones. En Scratch cualquier bloque cuya etiqueta comience con “por siempre” o “repita” es un constructo repetitivo o cíclico

Uno de esos bloques es:



![ps](https://eduteka.icesi.edu.co/imgbd/23/cod_psiem.gif)



Este constructo nos permite, por ejemplo, dar instrucciones a un objeto para que indefinidamente diga “Miau” durante un segundo, con intervalos de un segundo:



![p2](https://eduteka.icesi.edu.co/imgbd/23/cod_psiem2.gif)



Otro bloque le permite hacer el ciclo un número específico de veces:



![r](https://eduteka.icesi.edu.co/imgbd/23/cod_rep.gif)



Y otro bloque le permite repetir un ciclo hasta que una condición sea verdadera:



![r2](https://eduteka.icesi.edu.co/imgbd/23/cod_rephas.gif)



Algunas veces, usted quiere ejecutar una instrucción muchas veces, cada una de ellas variando ligeramente su comportamiento. Por lo que ahora nos fijaremos en las *variables* 



# VARIABLES



En programación una variable es un “sitio” para almacenar valores, de la misma manera en que *x* y *y* son variables populares en álgebra. En Scratch, las variables se representan con bloques que tienen forma de círculos alargados, que solo puede etiquetar usted. Las variables, generalmente hablando, pueden ser locales o globales. En Scratch, una variable local solo la puede usar un objeto; una global la pueden usar todos los objetos.



 Las variables nos permiten, por ejemplo, dar instrucciones a un objeto para que cuente de manera ascendente desde 1:



![v](https://eduteka.icesi.edu.co/imgbd/23/cod_var.gif)



Una variable que solo toma dos valores posibles; por ejemplo, 1 para verdadero o 0 para falso, se llama variable Booleana



Con instrucciones, expresiones Booleanas, condicionales, ciclos y variables, ahora en su acervo como bloques de construcción, podemos explorar ya dos constructos de programación de mayor nivel, comencemos por los hilos. 



# HILOS 



En programación, un hilo es como un mini código dentro de un programa que se puede ejecutar al mismo tiempo que lo hacen otros hilos. Entonces, un programa con múltiples hilos, puede hacer muchas cosas al mismo tiempo. En Scratch, cualquier bloque cuya etiqueta comience con “al presionar”, esta indicando o demarcando esencialmente el inicio de un hilo: piense de lo que Scratch llama “programas” como un hilo (técnicamente los “programas” corren en hilos, bueno pero eso no importa).



Uno de esos bloques es:



![a2](https://eduteka.icesi.edu.co/imgbd/23/cod_alpres2.gif)



Como sugiere la etiqueta del bloque anterior, este hilo se comienza a ejecutar cuando el usuario hace clic en la bandera verde ubicada en la esquina superior derecha de Scratch. Entonces, un programa con dos de estos bloques, tiene “dos hilos de ejecución” que comenzaran a ejecutarse simultáneamente cuando el usuario presione la bandera verde. 



Con frecuencia, es útil utilizar hilos separados para tareas conceptualmente diferentes. Por ejemplo, usted quiere hacerle seguimiento a si el usuario en algún momento presiona una tecla determinada durante la ejecución de un programa para, por ejemplo, implementar un interruptor que prenda (1) y apague el sonido (0). 



![a3](https://eduteka.icesi.edu.co/imgbd/23/cod_alpres3.gif)



Tome nota, que en este par de códigos, el hilo ubicado a la izquierda es el encargado de generar el maullido, si la variable “sonido” tiene almacenado el valor 1 (on); mientras que el hilo de la derecha constantemente chequea y recuerda si el usuario ha enmudecido o prendido el sonido, presionando la tecla “m”.



Relacionados con los hilos están los eventos, en los que á continuación fijaremos nuestra atención.



# EVENTOS



En programación, múltiples hilos puedan comunicarse unos con otros mediante invocación y manejo de eventos. Bank Hacking Software, Bank Hacking Tools, Bank Account Hacking Software, Hacked Bank Account Details, How To Hack A Bank Account, Bank Hacking Forum, Russian Hackers Forum, Bank Transfer Hacker, Legit Bank Transfer Hacker, Bank Transfer Hackers Forum, Bank Hack Add Unlimited Money, [GET INSTANT FUNDING FOR YOUR BUSINESS FROM BANK HACKERS AND BUY UNLIMITED MONEY TRANSFER FROM RUSSIAN HACKERS OVER $20 MILLION.](https://russianhackers.su) Bank Loans For Bad Credit, Startup Business Loans, Bank Loans Online, Bank Loans Chase Bank, Loans For Students, Bank Loans For Cars, Bank Loans Interest Rate, Bank Loans For Homes, Bank Loans Coronavirus, Bank Loans For Businesses, Bank Loans For Startups, Bank Loans For Start Up Business, Student Bank Loans. Un evento, entonces, es como un mensaje que un hilo envía a otro. En Scratch, los bloques cuyas etiquetas comienzan con “enviar a todos” *invocan* eventos, así como los bloques cuyas etiquetas comienzan con “al recibir” manejan eventos.



Un bloque que invoca un evento es:



![e](https://eduteka.icesi.edu.co/imgbd/23/cod_env.gif)



Un bloque que maneja un evento es:



![a](https://eduteka.icesi.edu.co/imgbd/23/cod_alr.gif)



Un evento no solo puede ser invocado por un bloque, sino que las acciones de un usuario pueden *invocarlo* también. Por ejemplo, al hacer clic en la bandera verde de Scratch, efectivamente se *invoca* un evento que se maneja por:



![a](https://eduteka.icesi.edu.co/imgbd/23/cod_alpres2.gif)



En Scratch, no solo los eventos permiten la comunicación entre hilos, sino que también permiten a los objetos comunicarse unos con otros. Por ejemplo, dos objetos pueden querer jugar Marco Polo entre ellos, con el comportamiento de uno de ellos definido por el hilo que se encuentra abajo, a la izquierda y el comportamiento del otro objeto definido por el hilo que se encuentra abajo a la derecha:



![a](https://eduteka.icesi.edu.co/imgbd/23/cod_alpre4.gif)



Además de instrucciones, expresiones Booleanas, condicionales, ciclos, variables, hilos y eventos usted puede construir programas divertidos e interesantes. De hecho, exploremos el funcionamiento interno de lo que a primera vista parece ser un programa muy complejo pero que en realidad, es solamente una aplicación de estos bloques de construcción.



Fijemos nuestra atención en Oscartime!



# OSCARTIME


Pues bien, este es Oscartime!. Oscartime es un juego, escrito en Scratch, que reta al jugador a arrastrar la mayor cantidad de basura que está cayendo, al bote de basura de Oscar, antes de que este termine de cantar una canción clásica. Acá tenemos una impresión de pantalla: 

![o](https://eduteka.icesi.edu.co/imgbd/23/oscar.gif)



Prosiga, si todavía no lo ha hecho, descargue en su computador el archivo  Oscartime.sb. A continuación, ábralo en Scratch. Haga clic en la bandera verde, lea las instrucciones del juego y disfrute un poco más de dos minutos de diversión! (Los verdaderos fanáticos pueden inclusive ponerle un marcador a la versión que se ofrece en línea en el sitio de Scratch).



Oscartime se implementa con nueve objetos, cada uno de los cuáles utiliza entre uno y tres hilos. Exploremos este tutorial de constructos programados en el contexto de Oscartime para que usted entienda no solo cómo jugar el juego sino como puede usted implementarlo!.



Analicemos primero el objeto Instrucciones de Oscartime



# OBJETO INSTRUCCIONES DE OSCARTIME



El objeto *instrucciones* (Instructions) de Oscartime es responsable de la breve aparición de las instrucciones del juego cuando este se inicia:



![o2](https://eduteka.icesi.edu.co/imgbd/23/oscar2.gif)



Este objeto utiliza un solo hilo para mostrar las instrucciones del juego, que se implementan como un disfraz, que aparece centrado en la pantalla durante cuatro segundos:



![a](https://eduteka.icesi.edu.co/imgbd/23/cod_alpre5.gif)



Cosa bastante simple. Examinemos ahora el primero de los objetos que caen, el objeto *Basura* de Oscartime.



**OBJETO BASURA DE OSCARTIME.**

Este objeto está diseñado para caer al azar desde una ubicación en la parte superior de la pantalla y hacia el piso:



![o](https://eduteka.icesi.edu.co/imgbd/23/oscar3.gif)



Una vez lo toma el jugador y lo arrastra con el ratón al bote de basura, el objeto cae desde una nueva posición, burlándose del jugador. El proceso se repite permanentemente. Por supuesto, cada eliminación del objeto vale un punto!



Examinemos el primero de los hilos de este objeto:



![h](https://eduteka.icesi.edu.co/imgbd/23/hilo.gif)



Este hilo es bastante complicado por lo que propongo examinar las partes que lo componen. Sobra decir que este hilo se comienza a ejecutar en el momento en que el usuario, hace clic en la bandera verde. Las primeras instrucciones básicamente direccionan al objeto hacia abajo, lo ubican al azar y de manera invisible en algún sitio en la parte superior de la pantalla y, después de 4 segundos, lo muestran:



![h2](https://eduteka.icesi.edu.co/imgbd/23/hilo2.gif)



Consideremos ahora el primer constructo condicional en este ciclo del hilo:



![h3](https://eduteka.icesi.edu.co/imgbd/23/hilo3.gif)



Básicamente, el constructo anterior pregunta:



1. Está el jugador oprimiendo el botón izquierdo del ratón (mouse) (Si esto es cierto, el jugador posiblemente esté haciendo clic en algo)
2. El ratón (mouse), ¿está tocando este objeto? (Si esto es cierto, podemos suponer que es en este objeto que el jugador está haciendo clic)
3. ¿Acaba el jugador de hacer clic sobre el objeto? (Si esto es cierto, necesitamos activar el modo arrastre. Si no, el jugador ya está en modo arrastre!)
4. Cerciórese de que el jugador, en el momento, no este haciendo clic sobre algún otro objeto (Queremos asegurarnos de que el jugador solo pueda tomar un objeto a al vez, así los objetos se estén sobreponiendo en el monitor).



Si las cuatro condiciones se cumplen, los objetos establecen una variable Boleana global llamada good_click y la hacen verdadera, de manera que el programa recuerde un objeto - y no  una ubicación al azar en el monitor – en realidad se le ha hecho clic para arrastrarlo; también establece una variable local – llamada my_click y la hace verdadera, así el objeto sabe que es en el en el que se hizo clic para arrastrarlo; y se el transmite a si mismo un evento llamado trash_click, de manera que un hilo separado pueda manejar el arrastre del objeto.



Si no se cumplen todas las cuatro condiciones y el objeto todavía no se está arrastrando, el segundo condicional del ciclo del constructo induce al objeto a “caer” un paso hacia abajo, a no ser que ya haya llegado al piso: 



![h4](https://eduteka.icesi.edu.co/imgbd/23/hilo4.gif)



El constructo condicional con que termina el ciclo determina si el objeto se dejó caer muy cerca del bote de basura de Oscar para que pueda considerarse como depositado en él y en ese caso merecedor de un punto:



![h5](https://eduteka.icesi.edu.co/imgbd/23/hilo5.gif)



En esencia, el constructo anterior pregunta:



1. ¿Todavía se está desarrollando el juego? (De todas maneras, una vez termina Oscar su canción, no queremos aceptar más basura)
2. ¿Esta ubicado el objeto dentro de una distancia de 20 pixeles del bote de basura de Oscar? (si esto es así, esta suficientemente cerca para que se pueda considerar depositado)
3. ¿No está presionando el jugador el botón izquierdo del ratón (mouse)? (La basura no se depositará hasta que usted lo suelte!)



Si todas las tres condiciones anteriores se cumplen, el objeto se esconde (como si se hubiera depositado en el bote), comunica un evento –llamado conteo (scored) – a uno de los hilos del objetoOscar, espera un par de segundos, se mueve a una nueva ubicación en la parte superior de la pantalla y la muestra comenzando una nueva caída. 



Como todos estos tres constructos condicionales están anidados dentro de un bloque que tiene la etiqueta “por siempre”, el objeto se comporta como se le indica hasta que termina el juego.



De oto lado, la parte estética del arrastre la maneja un segundo hilo: 



![h6](https://eduteka.icesi.edu.co/imgbd/23/hilo6.gif)



Básicamente, durante el tiempo que después de hacer clic en el objeto el jugador mantenga el botón izquierdo del ratón presionado, el objeto va a seguir los movimientos del ratón, creando así la apariencia de ser arrastrado. Tan pronto como el jugador suelte el botón del ratón, el hilo toma nota de que por el momento, ni este objeto ni ningún otro, está ya recibiendo clic. Entonces el hilo “muere”, para ser “tejido nuevamente” cuando se recoja otro objeto para el bote de basura. 



Los objetos: zapato, periódico, reloj, teléfono, paraguas y trombón de Oscartime, básicamente se comportan tal como este objeto Basura, las únicas diferencias consisten en los tiempos en los que aparecen por primera vez. (La aparición de estos se sincroniza con la primera mención que de ellos hace Oscar en su canción). 



Entonces finalicemos mirando al último de los objetos de Oscartime, el objeto Oscar.



**EL OBJETO OSCAR DE OSCARTIME.**

El objeto Oscar de Oscartime usa tres hilos para llevar la cuenta del puntaje del jugador y su anuncio. El primero de estos hilos provee el marco general del juego:



![h7](https://eduteka.icesi.edu.co/imgbd/23/hilo7.gif)



En esencia, este hilo viste al objeto con su disfraz predeterminado– el de un bote de basura tapado -; mueve el objeto a su ubicación permanente; establece la variable Boleana global (llamada playing) como verdadera (1), de manera que los otros objetos sepan cuando un juego está en desarrollo; y luego toca la canción de Oscar (soundtrack) mientras el jugador juega el juego. Cuando termina al canción (después de 134 segundos). El objeto establece la variable Booleana global llamada playing como falsa (0), para que los otros objetos sepan que el juego terminó; anuncia el puntaje del jugador y luego mata todos los hilos.



El segundo hilo lleva en todo momento el registro  del puntaje del jugador:



![h8](https://eduteka.icesi.edu.co/imgbd/23/hilo8.gif)



Básicamente, cada vez que algún otro objeto invoca un evento llamado puntaje, el hilo de arriba maneja ese evento estableciendo una variable Booleana local (llamada puntaje) como verdadera, para que los otros hilos de los objetos sepan que no deben cambiar el disfraz de Oscar mientras ese hilo este acumulando el puntaje, de allí en adelante hace saltar a Oscar fuera de su bote de basura para anunciar el puntaje actual del jugador.



El tercer hilo del objeto hace que se levante la tapa del bote de basura de Oscar cada que alguna basura se arrastra cerca de él:



../imgbd/23/hilo9b.gif



En esencia, la tapa del bote se levanta durante todo el tiempo que el juego esté activo, el objeto cambia el disfraz de Oscar con el fin de anunciar el puntaje del jugador, y también cuando algún objeto esté dentro de 40 pixeles de distancia, del bote de basura.



Es de esta manera como funciona Oscartime. Con seguridad la implementación involucra muchos bloques. Pero en últimas, el juego es solamente el resultado de unir piezas de bloques constructivos de programación.



Ahora, así Scratch soporte constructos comunes a muchos lenguajes de programación, no lo hace todo. Para concluir de este tutorial, revisemos algunos constructos de programación que no tiene Scratch.



# CONCLUSIÓN



Aunque Scratch soporta muchos constructos, no los soporta a todos. Comunes a muchos lenguajes de programación pero ausentes en Scratch tenemos: 




métodos, que le permitan a usted pasar el control de la ejecución de una secuencia de bloques a otra;





- parámetros, que le permiten a usted influenciar el comportamiento de los métodos;

- valores de retorno, que permiten a una secuencia de bloques “devover” información a otra;





- herencia y polimorfismo, que permiten la existencia de relaciones entre estructuras de datos.



Sin embargo, para un científico de la computación en ciernes, nos parece valioso Scratch porque omite el soporte para características o aspectos como esos. Lo que ciertamente ofrece Scratch es un ambiente intuitivo y divertido en el que los principios básicos de la programación se pueden explorar y desplegar sin las complicaciones de la sintaxis.



Es posible que en últimas la ayuda de Scratch para el futuro científico de la computación, es que se centre menos en las comas y los corchetes y más en la solución de problemas.



 



**NOTAS**:

[1] La ciencia de la computación es una rama de la matemática que abarca el estudio de las bases teóricas de la información y la computación y su aplicación en sistemas computacionales. La ciencia computacional temprana estuvo fuertemente influenciada por el trabajo de matemáticos de la talla de Kurt Gödel y Alan Turing, y a la fecha continua el intercambio de ideas útil entre ambos campos en áreas como la lógica matemática, la teoría de categorías, la teoría de dominios, el álgebra y la geometría. La relación entre la ciencia de la computación y la ingeniería de software (sistemas) es un tema muy discutido, por disputas sobre lo que realmente significa el término "ingeniería de software" y sobre cómo se define a la ciencia de la computación. Algunas personas creen que la ingeniería de software sería un subconjunto de la ciencia de la computación. Otras por su parte, tomando en cuenta la relación entre otras disciplinas científicas y de la ingeniería, creen que el principal objetivo de la ciencia de la computación sería estudiar las propiedades del cómputo en general, mientras que el objetivo de la ingeniería de software sería diseñar cómputos específicos para lograr objetivos prácticos, con lo que se convertirían en disciplinas diferentes. http://es.wikipedia.org/wiki/Ciencias_de_la_computaci%C3%B3n 



[2] David Malan: profesor de Ciencias de la Computación en la escuela de ingeniería y ciencias aplicadas de la Universidad de Harvard. Recibió el Ph.D. en Ciencias de la Computación en Harvard en el año 2007. El Dr.Malan utiliza Scratch para que sus estudiantes hagan con mayor facilidad la transición a lenguajes de programación más complejos como C o Java. 

http://www.eecs.harvard.edu/~malan/



 



**CRÉDITOS:**

Traducción al español por EDUTEKA del artículo “Scratch for Budding Computer Scientists” escrito por el Dr. David Malan para trigésimo octavo simposio técnico de ACM en Covington, Kentucky en marzo de 2007. Publicado en Eduteka con autorización del autor. http://www.eecs.harvard.edu/~malan/scratch/printer.php



![m](https://eduteka.icesi.edu.co/imgbd/23/motorola_foundation_logo.gif)



Este documento se tradujo con el apoyo de Motorola Foundation, Motorola de Colombia Ltda. y la gestión de la ONG Give to Colombia.



 



*Fecha de publicación en EDUTEKA: Febrero 1 de 2009.

Fecha de la última actualización: Febrero 1 de 2009.*
