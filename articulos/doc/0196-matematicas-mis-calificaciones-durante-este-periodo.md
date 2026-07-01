---
id: 196
title: "Matemáticas: Mis calificaciones durante este período"
date: 2003-10-01
tags: actividades,matematicas,algebra,iste
category: Proyectos de Matemáticas
author: Profesor
---

# Matemáticas: Mis calificaciones durante este período

> En este proyecto cada estudiante utiliza la Hoja de Cálculo para registrar sus calificaciones en las materias de un período. De esta manera, pueden inferir qué notas necesitan en los trabajos, pruebas, tareas, etc. para lograr la calificación que desean alcanzar al final del período.

En este proyecto cada estudiante utiliza la Hoja de Cálculo para registrar sus calificaciones en las materias de un período. De esta manera, pueden inferir qué notas necesitan en los trabajos, pruebas, tareas, etc. para lograr la calificación que desean alcanzar al final del período.

---

## HOJAS DE CÁLCULO




Aprender a utilizar todo el potencial que tienen las Hojas de Cálculo (H. de C.) para enseñar distintas materias del currículo entre los grados de Kindergarten y 11º es la propuesta que plantea en su libro “La Magia de las Hojas de Cálculo” (Spreadsheet Magic) la maestra Pam Lewis, Surafricana, Psicóloga de la Universidad de Sur África, quién posteriormente en los EEUU realizó un magíster en ciencias concentrándose en el área de computadores en educación.

Actualmente es profesora y coordinadora de informática en el Colegio “St. Luke”, de Brookfield, Winsconsin en el que enseña por un lado a estudiantes entre kindergarten y 8º grados y por la otra a maestros en la forma de integrar la Tecnología al currículo.




Ella considera que las H. de C. son herramientas de aprendizaje poderosas y que si los estudiantes tienen acceso a computadores, deben utilizarlas. Argumenta que ellas desarrollan en los estudiantes habilidades cómo: a) organizar datos, haciendo diferentes tipos de gráficas con los datos de las H. de C. que ayudan grandemente en la presentación de información a una audiencia y; b) utilizar elementos visuales concretos para explorar conceptos matemáticos abstractos lo que de paso, ayuda a los estudiantes que aprenden en forma visual (utilizar color y matices o sombras en las celdas para visualizar por ejemplo sumas y restas) a comprender mejor estos conceptos; c) estimula el desarrollo de las habilidades intelectuales de orden superior mediante el uso de formulas para manipular números con lo que pueden responderse preguntas a fórmulas condicionales del tipo “si….entonces”; d) promueve el desarrollo de habilidades para solucionar problemas y e) motivación para sostener el interés del estudiante en completar tareas que son muy tediosas si se llevan a cabo con lápiz y papel.




 




## PROYECTO: MIS CALIFICACIONES DURANTE ESTE PERÍODO




### **Descripción General**




Los estudiantes abren la plantilla “Mis calificaciones durante este período” [1] y la alimentan con los nombres de las materias que han visto en ese lapso de tiempo. A continuación, en la columna S, entran las calificaciones que en ellas esperan obtener, e imprimen una copia. Utilizan esa copia para anotar en lápiz las calificaciones de los exámenes, pruebas rápidas (quices), trabajos, etc. que obtienen durante el período.




Las calificaciones deben convertirse a porcentajes antes de ingresarse en el archivo de la H. de C. Antes de que finalice el período, cada estudiante registra los porcentajes en la H. de C. y utiliza las herramientas de esta para promediar las calificaciones. Los estudiantes pueden inferir cuanto necesitan sacar en el próximo trabajo o proyecto para alcanzar la calificación que habían anticipado. Con anterioridad se han ingresado fórmulas a las matrices que permitan generar una letra que indique la calificación (A; B; C etc..) obtenida, en la columna de promedios.




Los maestros que se sientan cómodos trabajando con fórmulas pueden cambiar la escala de calificaciones, alterándolas. (Si lo anterior es muy complicado, el maestro puede simplemente eliminar la columna de letras de calificación de la H. de C.).




Si el valor de una calificación es diferente del valor de la que sigue, entonces las celdas M5 a R15 deben cambiarse o eliminarse. Las fórmulas en la plantilla reflejan las siguientes letras de calificación [2]:




*E=90-100; B=80-89; A=60-79; I=40-59; D=menor a 40*




Las fórmulas para la fila 5 de la plantilla se muestran en la tabla siguiente. (Para ver todas las fórmulas para las columnas M a R, abra la plantilla, haga clic en el menú de **Opciones**, seleccione **Mostrar** (Display) y coloque una señal enseguida de **Fórmulas**. (Se necesita ensanchar las columnas para ver las fórmulas)








| **Celda** 
| **Fórmula** 
| **Qué hace** 
|


| M5 
| =SI(L5>=90;"E";" ") 
| Si el valor en L5 es mayor o igual que 90, aparece “E” en esta celda 
|


| N5 
| =SI(Y(L5>=80;L5<90);"B";" ") 
| Si el valor en L5 es mayor o igual que 80 y menor que 90, aparece “B” en esta celda 
|


| O5 
| =SI(Y(L5>=60;L5<80);"A";" ") 
| Si el valor en L5 es mayor o igual que 60 y menor que 80, aparece “A” en esta celda 
|


| P5 
| =SI(Y(L5>=40;L5<60);"I";" ") 
| Si el valor en L5 es mayor o igual que 40 y menor que 60, aparece “I” en esta celda 
|


| Q5 
|   
|   
|


| R5 
| =SI(Y(L5>0;N5<40);"D";" ") 
| Si el valor en L5 es mayor que 0 y menor que 40, aparece “D” en esta celda 
|







### **HABILIDADES DE HOJA DE CALCULO QUE SE PRCTICAN**






- Edición de Texto

- Borrado de columnas

- Cálculo de promedios utilizando fórmulas

- Establecer el promedio ponderado de las calificaciones utilizando fórmulas

- Utilizar y modificar fórmulas condicionales del tipo “si…..entonces” (siempre y cuando el maestro se sienta cómodo trabajando con estas fórmulas; si no, se pueden eliminar)




### **ESTÁNDARES MATEMÁTICOS [3]**




**Estándares NCTM [4]**

**(1: Números y Operaciones)**

Los estudiantes utilizan las funciones de la Hoja de Cálculo para calcular promedios.

**(2: Patrones, Funciones y Álgebra)**

Los estudiantes utilizan fórmulas algebraicas y las modifican.

**(6: Solución de Problemas)**

Los estudiantes hacen cálculos matemáticos para resolver problemas y crear una calculadora que funcione.




### **ACTIVIDADES CON EL COMPUTADOR [5]**




1. Los estudiantes abren la Plantilla “Mis calificaciones durante este período” [1], que se muestra abajo [EDUTEKA cree que sería muy valioso que los estudiantes construyeran la plantilla en la clase de Informática]
2. Ingresan nombres, fecha, materias sobre las que esperan calificación, así como la calificación que pretenden obtener en cada materia, en la columna S.
3. El maestro puede pedir a los estudiantes que cambien el encabezado en la fila 4 o que eliminen las columnas que son irrelevantes. Para eliminar una columna, se seleccionan las celdas, se hace clic en el menú **Edición**, y se selecciona “eliminar...” Luego se selecciona “Toda la columna” y se hace clic en el botón “Aceptar” (El **Área de Impresión** se ha definido previamente en la plantilla. Si se desea aumentar columnas, se debe modificar primero el **Área de Impresión**).
4. Los estudiantes guardan el documento y lo imprimen. Utilizan esta impresión para llevar en lápiz, el record de sus pruebas rápidas (quices), trabajos, exámenes etc.
5. Después de que los estudiantes han recibido algunas calificaciones de cada materia, abren el archivo guardado e ingresan las calificaciones bajo los encabezamientos adecuados. Las calificaciones deben convertirse a porcentajes antes de registrarse en la hoja.
6. Los estudiantes utilizan ahora una función preestablecida para promediar las columnas B a J. Para ingresar una función, hacen clic en la celda donde se desea ubicar el porcentaje (comenzando con la celda K5), despliegan el menú “Insertar”, y se escoge “**Función**”, se selecciona “**Porcentaje**”. Así se inserta la fórmula =Porcentaje(número 1, número 2, …) en la celda. Se eliminan las palabras entre paréntesis y se arrastra el ratón sobre las celdas que se van a promediar, se presiona la tecla “**Entre**”, y la H. de C. realiza los cálculos. Por ejemplo, =PROMEDIO(B5:D5) promedia las calificaciones en las celdas B5, C5, y D5.
7. Si la fórmula se inserta de manera equivocada, aparece un mensaje de error. Los estudiantes pueden hacer clic en **OK** y presionar la tecla **Esc** para salir de la celda, luego borran la fórmula incorrecta y escriben la correcta sin hacer uso de las funciones preestablecidas. **Nota**: La fórmulas se deben escribir con exactitud. Para que funcionen no deben tener caracteres o espacios suplementarios.
8. Después de que los estudiantes han promediado las calificaciones que han recibido a la fecha, guardan el archivo y pueden hacer algunas preguntas, cómo:
a) ¿Qué calificaciones necesito sacar en los siguientes tres exámenes para alcanzar la nota que me he propuesto?
b) Si saco las mismas calificaciones que tengo hasta ahora en los próximos tres exámenes, ¿Cuál será mi promedio?
Los estudiantes pueden experimentar ingresando las mismas calificaciones y ver como se afectan sus promedios. Para ver las calificaciones en letras, tendrán que llenar la columna de promedio ponderado. Todas las “calificaciones proyectadas” se deben borrar y la H. de C. debe contener solamente las calificaciones reales.
9. Al final del período, los estudiantes ingresan el balance de sus calificaciones. Calculan su promedio y, si el profesor desea compartir como pondera las calificaciones, pueden trabajar las notas reales para el período. Para hacer esto necesitan ingresar una fórmula para calcular el promedio ponderado en cada materia. Por ejemplo, si cada ítem vale 100 puntos y el examen de la unidad o capítulo vale 400 puntos, la fórmula para el promedio ponderado se verá así:

![](https://eduteka.icesi.edu.co/imgbd/18/temas/cuadro1.gif)

10. Los estudiantes imprimen una copia de sus calificaciones.




 




#### **NOTAS DEL EDITOR:**




**[1]** Esta plantilla se encuentra en el archivo “MisCalificaciones.php” Descárguela de [https://eduteka.icesi.edu.co/pdfdir/MisCalificaciones.php](https://eduteka.icesi.edu.co/pdfdir/MisCalificaciones.php)

**[2] **Hemos adaptado este ejemplo al sistema de letras que para calificar se emplea en el sistema educativo colombiano. La conversión de letras a números puede cambiar de una institución a otra. En INSA ([http://www.insa-col.org](http://www.insa-col.org)) se utiliza la siguiente escala: “D” entre 1 y 3.9; “I” entre 4.0 y 5.9; “A” entre 6.0 y 7.9; “B” entre 8.0 y 8.9; y “E” entre 9.0 y 10.0

**[3]** Estándares de Matemáticas (Ministerio de Educación Nacional de Colombia): “Pensar con Variaciones y con Álgebra: El estudiante descubre los valores que puede tomar una variable en una situación concreta de cambio” (grados 6º a 7º)

**[4]** Estándares en Matemáticas de NCTM [http://standards.nctm.org/](http://standards.nctm.org/)

**[5]** Hemos adaptado este proyecto para realizarlo con la Hoja de Cálculo Excel 2000.




 




#### **CRÉDITOS:**




Traducción realizada por EDUTEKA del proyecto “My Grades This Quarter” publicado en el libro “Spreadsheet Magic” escrito por Pamela Lewis. El libro se puede comprar directamente en el sitio de ISTE: [http://www.iste.org/bookstore](http://www.iste.org/bookstore)





*Publicación de este documento en EDUTEKA: Octubre 04 de 2003.

Última modificación de este documento: Octubre 04 de 2003.*
