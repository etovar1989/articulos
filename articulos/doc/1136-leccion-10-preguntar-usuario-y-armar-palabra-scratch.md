---
id: 1136
title: "Lección 10: Preguntar usuario y armar palabra Scratch"
date: 2009-11-01
tags: scratch,programacion,solucion problemas,actividades,guias uso,videos
category: Herramientas
author: Francisco Martínez
---

# Lección 10: Preguntar usuario y armar palabra Scratch

> Décimo y último video de la serie. En este se mejora el Juego Mario con el uso de dos conjuntos nuevos de instrucciones de la versión 1.4 de Scratch: Interactividad con el usuario (preguntar / respuesta) y manipulación de textos (unir palabras / extraer una letra de una cadena de texto / longitud de una cadena de texto).

Décimo y último video de la serie. En este se mejora el Juego Mario con el uso de dos conjuntos nuevos de instrucciones de la versión 1.4 de Scratch: Interactividad con el usuario (preguntar / respuesta) y manipulación de textos (unir palabras / extraer una letra de una cadena de texto / longitud de una cadena de texto).

---

# TALLER DE SCRATCH

# Lección 10

* 

Descargue imágenes y sonidos necesarios para realizar este juego.[Haga clic aquí](https://eduteka.icesi.edu.co/pdfdir/Scratch_Lecciones_Insumos.rar). 

Descargue archivo ejecutable con la versión final del [juego Mario](https://eduteka.icesi.edu.co/Scratch/JuegoMario.exe) (EXE, 3.304 MB)

Hola, bienvenido de nuevo a los tutoriales de Scratch. En esta lección 10 actualizaremos el Juego “Mario” haciendo uso de las nuevas funcionalidades de la versión 1.4 del entorno de programación Scratch.  

**ACTUALIZACIÓN DEL JUEGO “MARIO”**

Hola, bienvenido de nuevo a los tutoriales de Scratch. Ahora que conoce las nuevas características de la versión 1.4 de Scratch, es hora de mejorar con algunas de ellas nuestra versión del Juego “Mario”. En esta lección extra, adicionaremos instrucciones para que el programa pregunte al usuario su nombre y almacenaremos dicho nombre en una variable para personalizar el juego. Luego, utilizaremos la instrucción de concatenación para dar puntos adicionales al jugador si logra armar la palabra Scratch con letras que caen aleatoriamente del cielo.

Lo primero que se debe hacer es agregar en el personaje Mario una nueva instrucción “**al presionar bandera verde**” debajo de ella agregue la instrucción “**preguntar ¿cuál es su nombre?y esperar**”. 

![imagen](https://eduteka.icesi.edu.co/imgbd/23/23-15/Leccion10_1.jpg)

De esta forma, el sistema simulará conocer el nombre del usuario. Vaya ahora a variables y pulse “**nueva variable**”“**nombre**”, que sea “para todos los objetos” y pulse el botón aceptar. A continuación, vaya a la sección variables y arrastre la instrucción “fijar nombre a” debajo de **“preguntar”. **Luego, de la sección **“sensores”**, escoja **“respuesta” **y arrástrela dentro de la instrucción **“fijar”. **De esta manera, almacenará en la variable **“nombre” **la información que el jugador suministre al sistema; luego podrá usarla en diferentes mensajes a lo largo del juego.

Ahora haga que Mario salude al jugador por su nombre. Vaya a la sección “apariencia” y arrastre, debajo de **“fijar”**, la instrucción “Decir por dos segundos”. En seguida, de la sección “operadores” arrastre la instrucción “unir”. En el primer espacio escriba “hola”, y en el segundo, de la sección **“variables” **arrastre **“nombre”**. Recuerde agregar un espacio en el primer cuadro de “unir” para que el saludo no se una con el nombre del usuario.

![imagen](https://eduteka.icesi.edu.co/imgbd/23/23-15/Leccion10_2.jpg)

Realice estos mismos pasos con cada uno de los personajes, incorporando este bloque de instrucciones dentro de una condición “Si” de acuerdo a cada personaje. Para ello, adicione a este bloque, de la sección **“operadores”, **la instrucción **“igual a”. **Por ejemplo, para el personaje “Mario” la instrucción queda así:“Si Personaje igual a 1”. Realice lo mismo para los personajes Luigi y Princesa.

![imagen](https://eduteka.icesi.edu.co/imgbd/23/23-15/Leccion10_3.jpg)

Una última mejora a nuestra versión de Mario consiste, como ya dijimos, en dar puntos adicionales al jugador, si logra armar, con letras que caen aleatoriamente del cielo, la palabra Scratch.

Para determinar si el jugador puede armar la palabra Scratch, vaya a “Variables” y seleccione “Nueva variable” nómbrela “letras” y que sea para todos los objetos. Ahora vaya al personaje Mario y en el área de trabajo agregue un nuevo hilo “**al recibir”** y justo debajo de éste agregue, de variables, “Fijar letras a” y borre el contenido. Esto último para no tener ninguna letra guardada en la variable del momento de comenzar el juego.

![imagen](https://eduteka.icesi.edu.co/imgbd/23/23-15/Leccion10_4.jpg)

A continuación importe, de la carpeta “Letters”, las letras que conforman la palabra Scratch; Cada letra es un objeto. Si lo desea puede pintarlas, de la misma forma en que se pintó el camino de Mario, en la lección tres.

A cada nuevo objeto le ponemos el nombre de la letra correspondiente; esto, para no confundirnos al momento de programarlos. En cada una de las áreas de código de los objetos correspondientes a las letras agregamos: “**Al presionar bandera verde**” y debajo ubicamos la instrucción esconder. Luego, creamos otro hilo con “**al recibir**” y fijamos las posiciones X y Y de cada letra. Para la posición X de cada letra adicionamos, de la sección “**números**”, “**números al azar entre -225 y 225**” similar al código de los elementos que caen del cielo, agregados en lecciones anteriores. Se escoge la posición 151 en Y, para que empiecen a caer desde el borde superior de la pantalla. 

Ahora, dentro de un bloque “**por siempre**”, se agrega: “**cambiar Y por -10**” y “**esperar 0.2 segundos**”, para que la letra caiga lentamente. Luego, dentro de un bloque “**Si**”, agregamos un operador “**menor que**” en el que adicionamos  “**posición Y**” de la letrea correspondiente en un lado de la operación y en el otro –150; dentro del sí colocamos “**ir a X y Y**”, con valores lo suficientemente grandes para desaparecer la letra del área de diseño. 

![imagen](https://eduteka.icesi.edu.co/imgbd/23/23-15/Leccion10_5.jpg)

Luego, agregamos la instrucción “**esconder**”. Por último, dentro del Si adicionamos “**Enviar a todos**” damos un clic en nuevo, y escribimos un nombre que identifique la acción de la letra siguiente; por ejemplo, si estamos en la letra S, entonces “Enviar a todos letra_c”. Con estas acciones se garantiza que cuando la letra llega al suelo, en el área de diseño, se esconde y se le notifica a las demás letras cuál es la próxima en caer. Repetimos estas mismas instrucciones para cada una de las letras que caerán desde el cielo, cambiando el nombre en **“enviar a todos”** y si también lo desea, la velocidad a la que deben caer y su posición inicial. Recuerde que utilizar diferentes velocidades de caída hará el juego mucho más interesante.

También para cada letra agregamos un nuevo hilo de instrucciones, encabezado por la instrucción **“al recibir”, **escogemos de la lista “inicio”. Debajo de él agregamos una instrucción **“por siempre”, **y dentro de esta creamos un bloque **“Si”**. En la condición, agregamos de la sección “operadores” la instrucción **“o”, **dentro de cada casilla; de la sección “sensores” agregamos **“tocando”, **y adicionamos en cada casilla una instrucción para cada personaje. De esta forma, el programa ejecutará el hilo dependiendo del personaje seleccionado. Dentro del **“Si”**, adicionamos, de la sección **“variables”, “unir”, **en la primera casilla escogemos **“letras” **y en la segunda casilla agregamos un operador **“unir”**, dentro de la primera casilla la variable “Letras” y en la segunda casilla la letra correspondiente. De la sección **“control” **adicionamos **“enviar a todos” **y escoge la variable de la letra correspondiente. Finalizamos este bloque con la instrucción de control **“detener programa”. **Esta instrucción hace que el programa reconozca cuando el personaje logra tocar la letra. Realice este mismo bloque de instrucciones para cada una de las letras.

![imagen](https://eduteka.icesi.edu.co/imgbd/23/23-15/Leccion10_6.jpg)

Volvamos al objeto Mario. Adicionemos código nuevo para el evento de que Mario toque cualquiera de las letras que caen. En este caso, debe sumar puntos y adicionalmente evaluar si se formó correctamente la palabra Scratch. Continuemos con el hilo en el que inicializamos la variable “**letras**”. Justo debajo de esta instrucción agregue un **“por siempre”. **Adicionamos un **“Si”** y dentro de éste un operador **“igual a”**. En el primer círculo de la igualdad agregue el operador **“longitud de”** y en el círculo siguiente agregue la variable **“letras”.** En el segundo círculo de la igualdad, agregue siete, número de letras que tiene la palabra Scratch. Y ahora, dentro del **Si** adicionamos otro condicional, con un operador de igualdad que evalúe si la variable **“letras” **es igual a **“Scratch”*** (el orden de letras correcto). Ahora, dentro del último “**Si”** que agregamos, ubicamos la programación necesaria para llamar al usuario por su nombre y notificarle la ***“bonificación de 5 puntos”***.

Ahora haga que el programa ejecute primero las instrucciones de las letras que caen del cielo, y enseguida el resto del juego. Para ello, seleccione la última letra que cae para conformar la palabra **SCRATCH**, y busque la instrucción **"Enviar a todos".** Cambie la variable que está definida en esta instrucción por un nuevo mensaje **"Juego".** Vaya ahora al personaje **"Roca",** y en el hilo encabezado por **"Al recibir inicio",** cambie la instrucción por **"Juego".** Realice esta misma modificación para todos los objetos que caen del cielo. De esta manera, el usuario jugará primero con las letras que caen aleatoriamente del cielo, para después continuar con el código realizado en lecciones anteriores.

Ensáyelo, ahora ha utilizado las características más importantes de la nueva versión de Scratch. El juego luce más interesante con estas dos mejoras. Anímese a seguir mejorando su versión de Mario. Puede crear diferentes escenarios a modo de mundos o niveles; ahora sabe cómo hacerlo y sabe también que es más fácil de lo que parece. Recuerde estar siempre atento a las nuevas versiones de Scratch en la Web oficial [http://scratch.mit.edu](http://scratch.mit.edu), para beneficiarse con los nuevos desarrollos del grupo de investigación a cargo de Scratch en el MIT. 

Muy bien, esto ha sido todo, gracias por acompañarnos a lo largo de estas nueve lecciones, recuerde compartir sus proyectos, divertirse y explorar todas las opciones que ofrece Scratch.  

 

![imagen](https://eduteka.icesi.edu.co/imgbd/23/23-15/Leccion10_7.jpg)

**CRÉDITOS:**

*Tutorial de Scratch elaborado por Juan Manuel Andrade por encargo de la Fundación Gabriel Piedrahita Uribe.*

 

 

 

 

*Publicación de este documento en EDUTEKA: Abril 01 de 2010.

Última modificación de este documento: Abril 01 de 2010.*

 
