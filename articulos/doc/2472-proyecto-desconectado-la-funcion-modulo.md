---
id: 2472
title: "Proyecto desconectado: La función módulo"
date: 2019-02-28
tags: matematicas,scratch,pensamiento computacional,abstraccion,
category: Proyectos de Matemáticas
author: CS Unplugged
---

# Proyecto desconectado: La función módulo

> Proyecto de aula en el que se trabaja la función matemática de Módulo. Promueve la conexión con el pensamiento computacional mediante abstracción en secuencias tales como "lunes, martes, miércoles..." o "enero, febrero, marzo..." o "C, C #, D, D #, E, F..." se pueden representar mediante un ciclo de números (0, 1, 2, 3...).

Proyecto de aula en el que se trabaja la función matemática de Módulo. Promueve la conexión con el pensamiento computacional mediante abstracción en secuencias tales como "lunes, martes, miércoles..." o "enero, febrero, marzo..." o "C, C #, D, D #, E, F..." se pueden representar mediante un ciclo de números (0, 1, 2, 3...).

---

# **PROYECTO DESCONECTADO: LA FUNCIÓN MÓDULO**






- **![](http://eduteka.icesi.edu.co/img/general/cs-modulo1.png)**Duración: 30 minutos

- Edades 8 a 10: Lección 3




**Imprimibles**






- [Módulo del reloj](https://csunplugged.org/en/resources/modulo-clock/): 2 caras de reloj con espacio en blanco y 10 combinaciones de dígitos.

- [Teclado del piano](https://csunplugged.org/en/resources/piano-keys/): Teclas del piano con teclas resaltadas (opcional).

- [Estaciones del tren](https://csunplugged.org/en/resources/train-stations/): Estaciones del tren con carriles circulares uondulados.




**Preguntas clave**






- ¿Qué mes va primero, Enero o Diciembre? ¿Cuál es el mes que va después de Diciembre?

- ¿Cuál es el día de la semana que va después del viernes? ¿Cuál es el día de la semana que está 4 días antes del viernes?

- Si llegas a las 5 pm a un parqueadero (17:00 si usas hora militar – 24 horas), y pagas por 8 horas de parqueo, ¿cuándo expira tu tarifa?




**Las respuestas potenciales pueden incluir: **






- Con los meses, ambos están antes y después de cada uno. Si es Enero, hay un Diciembre antes y también hay otro en 11 meses.

- Con los días, es igual. El lunes va después del viernes, pero también va antes.

- El tiquete del parqueadero expira a la 1 am (1:00). Al sumarle 8 horas a las 5 (o 17), obtienes 1.




**Iniciar la lección**




Imprima el recurso del [reloj con el módulo 10](https://csunplugged.org/en/resources/modulo-clock/) para los estudiantes, o dibújelo en el tablero (esta secuencia de preguntas también se puede hacer usando el módulo del tren o el de la rueda de la fortuna; revise la siguiente sección).




![](http://eduteka.icesi.edu.co/img/general/cs-modulo-10-clock.png)




Pregunte que es inusual en este “reloj” (solo tiene 10 puntos en el marcador, y tiene un cero en la parte superior en vez de un 10) Explique que, a pesar de ser un poco extraño, es en realidad muy útil.




**Actividades de la lección**




Pregunte ¿qué número corresponde a 2 horas (2 tics) después de las 3? (La respuesta es 5, es decir: solo cuente 2 unidades con las manecillas del reloj). Si los estudiantes tienen sus propias copias del reloj, ellos pueden poner un contador en el 3, y moverse dos lugares.




¿Qué es 3 horas después de las 9? (2) Nos referiremos a esto como 9+3.




¿Qué es 2 + 10 (es decir, 10 tics después de 2)? (Es 2, dado que se suma 10 hacia la derecha del reloj). ¿Qué es 2 + 20? (También es 2; le das la vuelta dos veces al reloj). ¿Qué es 5 + 100? (5, ya que un múltiplo de 10 te hace volver al mismo lugar).




Ahora compare las siguientes sumas en el reloj con la suma normal:






- 8+3 (1, comparado con 11).

- 9+8+7 (4, comparado con 24).

- 6+6+6 (8, comparado con 18).




Pregunte a los estudiantes si pueden ver el patrón; con suficientes ejemplos, se darán cuenta de que mantiene el último dígito de la suma, por ej. el último dígito de 11 es 1, de 24 es 4 y de 18 es 8. Si han realizado la actividad del código de producto, pueden reconocerlo como la función que utilizamos para elaborar el dígito de verificación en los códigos de producto.




El ultimo cálculo es 3 veces 6 (puede comenzar en 0 y contar 6 tics 3 veces).




Pregunte qué resultado daría en el reloj, 4 veces 6 (es 4, que es el último digito de 24). Ahora que tal ¿8 veces 5? (es 0, ya que es el último digito de 40).




Este extraño tipo de reloj aritmético que estamos trabajando tiene un nombre: El módulo. Este reloj tiene 10 tics, por lo que la forma correcta de escribir un cálculo sería: (8 + 3) módulo 10. 




Esto significa que sume el 8 y el 3, pero reinicie a cero cada vez que pase por el 10.




(Esta función también se llama el “residuo” después de la división, que para los números positivos es el mismo que el módulo.)




**Rueda de la fortuna y rutas del tren**




El ejercicio anterior también se podría hacer con los folletos del tren o el de la rueda de la fortuna, que también realizan un ciclo cada 10 pasos. Los estudiantes pueden usar contadores para rastrear sus movimientos alrededor de las estaciones. Hemos proporcionado recursos para las rutas del tren anteriores.




![](http://eduteka.icesi.edu.co/img/general/cs-modulo-train-stations-tracks-circular.png)




**Hora de rebobinar**




El conteo de módulo es como una recta numérica circular. Además de seguir adelante, experimentemos retrocediendo (es decir, restar o sumar números negativos). En el reloj estaría devolviendo el tiempo atrás; En el tren y la rueda de la fortuna iría en reversa.




Pregunte a la clase qué piensan que es 2 - 4 en este sistema (es decir, 4 pasos antes de 2). Esto te lleva a la ubicación 8.




Ahora que tal, ¿2 – 10? (sigue siendo 2). O ¿2 – 100? (nuevamente vamos a parar en 2).




¿Qué es 0 – 7? (3)




Pero ¿qué pasa con -8 es decir, 8 negativo? (eso es 8 unidades antes de 0, que es 2). Otros números que caen en 2 son -18, -28 y así sucesivamente.




(Esta idea viene de calcular algunos dígitos de verificación; para varios sistemas necesitamos preguntar "¿qué agregas a x para llegar a 0 en el módulo 10?", La forma corta de calcular eso en un programa de computador es "(0-x) mod 10 ")




**Relojes de otros tamaños**




Ahora ponga a los estudiantes a imaginarse (o dibujar) un reloj con 11 tics, enumerado de 0 a 10.




¿Qué es 9+3? en dicho reloj (es 1).




¿Qué es 7+11? (7, ya que le da la vuelta completa al reloj).




Puede experimentar con otras sumas y con otros tamaños de relojes. El módulo 11 se menciona porque es usado por los [números ISBN-10](https://es.wikipedia.org/wiki/ISBN) (International Standard Book Number).




Si logra que los estudiantes consideren el módulo de 12 o 24, ellos deberán ver que se relacionan con relojes normales (relojes de 12 o 24 horas respectivamente). Por ejemplo: (8 + 5) módulo 12




Es lo mismo que calcular qué hora es 5 horas después de las 8 en punto; o (22 + 4) módulo 24 es lo mismo que calcular el tiempo 4 horas después de las 22:00. En el sistema de módulo, el número en la parte superior del reloj sería 0 en lugar de 12 o 24, pero todos los demás valores funcionan como es de esperarse.




Uno interesante para experimentar es el módulo 360, es decir, un reloj con 360 tics, numerado de 0 a 359. Este tipo de cálculo ocurre cuando agregamos ángulos, ya que girar 360 grados termina en el mismo lugar que la posición inicial.




Haga que los estudiantes se pongan de pie y miren hacia el frente. Ahora pídales que giren a la derecha 90 grados (un ángulo recto). Ahora haga que giren otros 90 grados. Pregúnteles qué ángulo han girado en total (180 grados).




Continuando, haga que giren 90 grados dos veces más. Ahora deberían estar mirando hacia el frente. ¿Cuánto han girado en total? (90 + 90 + 90 + 90 = 360 grados) ¡Los estudiantes pueden estar familiarizados con la idea de que un "360" representa una rotación completa!




Ahora pregúnteles qué dirección estarían mirando si hubieran girado otros 360 grados. (Volverían a estar mirando al frente). ¿Qué sucede si giran 360 grados 10 veces (es decir, 3600 grados)? (Todavía estarían mirando hacia el frente).




Usando preguntas similares, ayude a los estudiantes a darse cuenta de que el total de los ángulos que giran en módulo 360 da el resultado final. Por ejemplo, girar 3690 grados a la derecha es lo mismo que girar 90 grados a la derecha (¡y esto último es mucho más fácil!) Hacer rotaciones es otro tipo de módulo aritmético.




Hacer estos cálculos en el módulo 360 se relaciona con la programación en lenguajes basados en tortugas como Scratch y Logo, donde los gráficos giran en ángulos. Girar a la izquierda 90 grados seis veces es lo mismo que girar 540 grados, es decir que ubica el gráfico en la misma dirección como si hubiera girado 180 grados. De la misma manera, ¡girar 3600 (o 360, o 720) grados es lo mismo que girar 0 grados! Y 365 grados es lo mismo que girar 5 grados. Girar -10 grados es lo mismo que 350 grados.




Yendo a otro extremo, pida a los estudiantes que consideren un reloj con solo dos marcas. ¿Cuáles serían? (Serían 0 y 1).




![](http://eduteka.icesi.edu.co/img/general/cs-modulo-2-clock.png)




En este sistema de módulo 2, ¿qué es 1 + 1? (0).




¿Qué es 1+1+1? (1).




Cuente sobre los números. Por ejemplo, 4 es 4 tics más allá del 0, lo que te lleva de vuelta a 0. Mover 5 tics te lleva a 1, 6 te hace volver a 0. Con algunas sugerencias o pistas, los estudiantes deberían darse cuenta de que un número de módulo 2 te dice si es par o impar.¡Esto se usa para el código de paridad! Si cuenta el negro como 1 y el blanco como 0, entonces cada línea con paridad uniforme suma hasta 0 módulo 2. Hay un error de paridad si agrega hasta 1 módulo 2; y el bit adicional que se agregó se puede considerar como el valor necesario para hacer que la línea sume 0, que es la misma idea que se usa para la mayoría de las otras sumas de comprobación, ¡solo con un módulo diferente!




**¿Importa el orden?**




Volviendo al reloj de módulo 10, pregunte a los alumnos si el número 7 + 8 es diferente al número 8 + 7 (No lo son, ya que ambos son 5 tics después de 0). Si es necesario, use más ejemplos, para que los estudiantes reconozcan que esta nueva forma de sumar no se ve afectada por el orden.




Del mismo modo, haga que experimenten aplicando la función mod en cada paso de la suma de tres números, en comparación con sumarlos primero. Por ejemplo, 7 + 8 + 6 llega a 1 en el reloj. Ahora pídales que consideren 7 + 8 (que llega a 5), y luego le agreguen el 6. Resulta que puedes aplicar el módulo en cada paso, o solo al final; ambos dan el mismo resultado.




Esto significa que puedes sumar números rápidamente agrupándolos. Por ejemplo, suponga que está sumando: 3 + 7 + 2 + 9 + 1 + 3




Podría calcular el total (25), que es 5 en el módulo 10. O podría sumar los dígitos en pares. En este ejemplo, los dos primeros dígitos suman 0 mod 10 (3 + 7 = 0)




Esto significa que el 3 y el 7 se anulan entre sí, no afectarán el total. También hay un 9 + 1 en la suma, por lo que tampoco harán ninguna diferencia al total. Lo cual deja solo el 2 y 3 para sumar, que llegan a 5 para el gran total.




Al agrupar números que suman 10 (o 20, etc.), generalmente podrá sumar varios dígitos rápidamente en su cabeza. Esto no es útil en los computadores, pero significa que posiblemente podría hacer una suma de comprobación de código de producto sin siquiera usar lápiz y papel.




**Enchúfate**




La función del “modulo” o “mod” es fácil de probar en la mayoría de los lenguajes de programación.




En Scratch, puedes elegir el bloque "mod" de los Operadores, escribir dos números y hacer doble clic para ver el resultado:




![](http://eduteka.icesi.edu.co/img/general/cs-modulo-scratch.png)




En varios lenguajes de programación, el signo **%** se usa para representar el "módulo", ¡lo cual es una opción muy confusa! Tendrías que escribir **37%10** para hacer el cálculo anterior en Python, Java, C, C ++, C # y JavaScript. Otros lenguajes usan **mod**, incluidas las hojas de cálculo; por ejemplo, puede escribir **= mod(37,10)** en una fórmula de hoja de cálculo.




Podría proyectar un programa en la pantalla y pedir a los alumnos que predigan el valor cuando escribas dos números. Por ejemplo, en Scratch puede poner **(8+5) mód 12** en el área de trabajo y hacer doble clic una vez que los alumnos realicen sus predicciones. En Python usaría **(8+5)%12**. Ahora pruebe **8 + 4** en lugar de 8 + 5.  ¿**8 + 12**? ¿**8 + 24**?  ¿**8 + 25**?




**Datos divertidos**




Los meses del año son modulo 12; 4 meses después de Noviembre se encuentra Marzo, 13 meses después de Enero esta Febrero, y así sucesivamente (si quiere usar la función del módulo, deberá numerar los meses de 0 a 11, lo cual es poco convencional).




![](http://eduteka.icesi.edu.co/img/general/cs-modulo-keyboard.png)




El nombramiento de notas musicales es módulo 7; si empiezas en la nota "D" de un piano y cuentas hasta 7 notas blancas, terminarás nuevamente en la nota "D", que es la misma nota, una octava más alta. Esto plantea el hecho interesante de que hay 7 notas diferentes en una octava; recibe este nombre porque si cuentas las notas al principio y al final ("D" en este caso, hay 8 notas).




En realidad, hay 12 notas diferentes en una octava si cuentas las teclas negras (si subes 12 semitonos de la nota "D", terminarás en D nuevamente), así que en este sentido las 12 notas diferentes de un piano son muy parecidas a los 12 meses o a los 12 tics en un reloj de 12 horas; pero sería realmente confuso si nos referimos a las notas como "enero, febrero, ...".




La relación de números cíclicos surge cuando se tocan notas en programas de un computador, que a menudo usan una representación digital de notas de piano llamadas MIDI (por ejemplo, estos números se usan en Scratch). En este sistema, el centro C es el número de nota 60, C# es 61 y D es 62. Por lo tanto, una octava por encima de 62 es 62 + 12 = 74. Cada 12º número corresponde al mismo nombre de nota.




**Aplicando lo que acabamos de aprender**




Los conceptos matemáticos son parte de nuestra vida cotidiana. La aritmética modular (que utiliza la función de módulo) es el término formal para la aritmética de reloj. A medida que exponemos a los estudiantes a los términos técnicos de las cosas que usamos todos los días, como cuando aprendemos a contar la hora, los meses del año y durante nuestras lecciones de música, las relaciones matemáticas se vuelven más obvias. La función de módulo también está estrechamente relacionada con la búsqueda del residuo.




**Reflexión de la lección**






- ¿Quiénes fueron los estudiantes que lograron hacer las conexiones rápidamente?

- ¿Hubo estudiantes que encontraron esto como una forma lógica de calcular el residuo de un número?

- ¿Qué le sorprendió a usted y a sus alumnos al aprender sobre la aritmética de módulo?




**Viendo las conexiones del pensamiento computacional**




A lo largo de las lecciones hay enlaces sobre [pensamiento computacional](http://eduteka.icesi.edu.co/articulos/PensamientoComputacional1). A continuación, hemos señalado algunas conexiones generales que se aplican a este contenido.




La enseñanza del pensamiento computacional a través de las actividades de “[CSUnplugged](https://csunplugged.org/)” ayuda a los estudiantes a aprender cómo describir un problema, identificar cuáles son los detalles importantes que necesitan para resolver ese problema, dividirlo en pequeños pasos lógicos para que luego puedan crear un proceso que resuelva el problema, y finalmente evaluar este proceso. Estas habilidades son transferibles a cualquier otra área curricular, pero son particularmente relevantes para desarrollar sistemas digitales y resolver problemas mediante las capacidades de los computadores.




Estos conceptos de [pensamiento computacional](http://eduteka.icesi.edu.co/articulos/PensamientoComputacional1) (pensamiento algorítmico, abstracción, descomposición, generalización, patrones, etc) están todos conectados y se apoyan entre ellos, pero es importante tener en cuenta que no todos los aspectos del pensamiento computacional ocurren en cada unidad o lección. Hemos resaltado las conexiones importantes para que usted observe a sus estudiantes en acción. Para obtener más información sobre nuestra definición de pensamiento computacional, consulte [nuestras notas sobre el pensamiento computacional](https://csunplugged.org/en/computational-thinking/).




Este tema es principalmente uno de matemáticas, que admite actividades posteriores para el pensamiento computacional. Las conexiones con el pensamiento computacional son, por lo tanto, más indirectas, ya que el pensamiento computacional no es el propósito principal del tema, por lo que no hemos proporcionado la lista habitual de conexiones.




Sin embargo, hay algunas conexiones que vale la pena destacar. Una conexión con el pensamiento computacional es un ejemplo de abstracción: secuencias como "lunes, martes, miércoles..." o "enero, febrero, marzo..." o "C, C #, D, D #, E, F..." se pueden representar mediante un ciclo de números (0, 1, 2, 3...) También hay muchos patrones para que los estudiantes exploren; por ejemplo, al usar el módulo 10, el resultado es siempre el último dígito, y al usar el módulo 2 el patrón para los números ascendentes es 0, 1, 0, 1, 0, 1 ..., es decir, se alterna para los números pares e impares; y también corresponde al bit derecho de la representación binaria.




**CRÉDITOS:**

Traducción al español realizada por Eduteka del proyecto de clase "[The Modulo operator Unplugged](https://csunplugged.org/en/topics/kidbots/unit-plan/modulo/)" publicado por [CS Unplugged](https://csunplugged.org/). El material de [CS Unplugged](https://csunplugged.org/) es una fuente abierta en GitHub, y el contenido de este sitio web se comparte bajo una licencia internacional Creative Commons Attribution-ShareAlike 4.0. Por lo tanto, uste puede compartir (copiar y redistribuir este material en cualquier medio o formato) y adaptar (remezclar, transformar y construir sobre el material con cualquier propósito, incluso comercial) siempre y cuando otorgue el crédito correspondiente tanto al auto como al traductor, proporcione un enlace a la licencia Crerative Commons e indique si se realizaron cambios. Si remezcla, transforma o construye sobre el material, debe distribuir sus contribuciones bajo la misma licencia que el original. CS Unplugged es un proyecto del Grupo de Investigación de [Educación en Ciencias de la Computación](https://www.canterbury.ac.nz/engineering/schools/csse/research/cse/) de la Universidad de Canterbury, Nueva Zelanda.




Publicación de este documento en EDUTEKA: Febrero 28 de 2019.

Última actualización de este documento: Febrero 28 de 2019.
