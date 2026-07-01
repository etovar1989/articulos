---
id: 375
title: "La Aplicación de Access de Microsoft: Preguntas y Respuestas"
date: 2004-08-28
tags: base datos,guias uso,resenas,matematicas,ciencias naturales,biologia
category: Apoyo Didáctico
author: David M. Marcovitz
---

# La Aplicación de Access de Microsoft: Preguntas y Respuestas

> Artículo que muestra, mediante un ejemplo sencillo, la utilización de Bases de Datos para manejar gran cantidad de información. Con ellas, se pueden responder preguntas creando Consultas con las que se ordenan y/o seleccionan datos. El ejemplo utilizado es de Ciencias Naturales.

Artículo que muestra, mediante un ejemplo sencillo, la utilización de Bases de Datos para manejar gran cantidad de información. Con ellas, se pueden responder preguntas creando Consultas con las que se ordenan y/o seleccionan datos. El ejemplo utilizado es de Ciencias Naturales.

---

# **LA APLICACIÓN ACCESS® DE MICROSOFT:

PREGUNTAS Y RESPUESTAS**




![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/isteLine.gif)








| **![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/revistaIste2.jpg)** 
| 

Traducción realizada por EDUTEKA del Artículo original “Q&A With Microsoft Access" escrito por David M. Marcovitz y publicado en el Número 3 del Volumen 30 de la revista Learning & Leading with Technology (http://www.iste.org/LL/30/3/index.cfm).

David Marcovitz es profesor asistente y coordinador del programa de graduados en tecnología educativa en la universidad de Loyola, Maryland. Recibió su doctorado en tecnología educativa de la Universidad de Illinois, Estados Unidos.



|







Una Base de Datos puede ser una herramienta extremadamente versátil para el aula de clase, en especial si usted puede aprovechar el poder de las opciones de consulta que ofrece la aplicación Access de Microsoft. El proyecto que a continuación describimos utiliza un ejemplo de alimentos y nutrición que puede adaptar para utilizarlo en su aula.




Uno de los objetivos de la instrucción de las ciencias naturales y de las ciencias sociales en todos los grados escolares es enseñar a los estudiantes a formular y responder preguntas. Necesitamos una herramienta que nos ayude a encontrar las respuestas cuando las preguntas incluyen gran cantidad de datos. En algunos casos, la mejor herramienta es una Base de Datos, ya que le permite, basándose en sus datos, realizar cálculos; ordenarlos y seleccionar algunos de acuerdo a los criterios que usted establezca.




Este artículo en el que se utiliza Access de Microsoft, sirve para hacer una introducción a algunas de la funciones poderosas que ofrecen las Bases de Datos. Aunque se usa un ejemplo de ciencias para mostrar cómo se hacen consultas para calcular, ordenar y seleccionar datos, esta herramienta se puede usar con muchas materias del currículo.




Si necesita ayuda para comenzar a usar Access, vaya al final de este artículo, allí encontrará enlaces a varios manuales en castellano con información paso a paso de cómo iniciar su primera Base de Datos, además de ejercicios y lecciones más extensas. [Nota del Editor: Además, puede descargar la Base de Datos con el ejemplo desarrollado en este artículo en versión Microsoft Access 2000, haciendo clic aquí ].




Los estudiantes pueden construir una base de datos con los valores nutritivos de una variedad de alimentos [1]. Pueden usar sus Bases de Datos tanto para formular preguntas sobre alimentos que son y no son saludables como sobre sus propios hábitos alimenticios. El ejemplo que presentamos en este artículo se limita a mostrar cómo se formulan preguntas en Access, pero usted puede expandir su Base de Datos y explorar nuevas formas de relacionar esta y otras, a su currículo.




Comenzamos creando una Base de Datos limitada de los alimentos y su valor nutritivo (Figura 1). Una vez está lista la Tabla usted puede comenzar a formular algunas preguntas interesantes.




![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/TACCESS1.gif)

*Figura 1. Tabla de Alimentos*




## **CONSULTAR**




El poder de las Bases de Datos verdaderamente se manifiesta cuando usted comienza a hacer preguntas (Consultas). La primera pregunta que queremos hacer es sobre cada uno de los registros de nuestra Base de Datos. Si queremos saber comparando gramo a gramo cuál es el alimento que contiene más proteínas, debemos saber que tanta proteína por gramo tiene cada alimento. Vamos a necesitar entonces pidir nuestro campo de Proteínas por nuestro campo de Peso.




Mirando su tabla “Alimentos”, haga clic en “cerrar ventana” {?- 1} para regresar a la ventana principal de la Base de Datos. Haga clic en el botón de consultas {2}. Debe aparecer una ventan que tenga al menos un par de opciones para crear consultas {12} pero ninguna consulta creada.

[Nota del Editor: Los números que aparecen entre llaves {x} permiten establecer una relación entre las opciones descritas en el texto y las imágenes. La Base de Datos con el ejemplo (Microsoft Access 2000) puede ser descargada haciendo clic aquí].




*TACCESS2.BMP ![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/TACCESS2.gif)

Figura 2. Ventana de Consultas*




Haga clic en “Nuevo” {3} para crear una consulta. Haga clic en “Vista Diseño” (Design View) y luego clic en “Aceptar” para comenzar a crear una consulta. Fíjese que las Consultas tienen como las Tablas “Vista Diseño” y “Vista Hoja de datos” (Datasheet View) y, esta última, luce casi idéntica, tanto para Consultas como para Tablas {11}. Para continuar, necesitamos notificarle a Access que requerimos de los datos almacenados en la Tabla “Alimentos” que acabamos de crear. Aparece una ventana emergente (pop-up) que muestra las Tablas creadas (solo debe estar la Tabla “Alimentos”) {4}. Haga clic en la Tabla “Alimentos” {5} para seleccionarla y a continuación haga clic en el botón “Agregar” {6}. Cierre la Ventana emergente para mostrar Tablas haciendo clic en el botón “Cerrar” {7}. Su pantalla debe asemejarse a la Figura 3.




 




*![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/TACCESS3.gif)

Figura 3. Inicio de la creación de una Consulta*




En el borde superior de la ventana existe una caja de desplazamiento {8} con la lista de todos los campos de la Tabla “Alimentos”. En la parte inferior de la pantalla hay varias columnas {9}. Arrastre cada uno de los campos, de la lista ubicada en la parte superior {8}, y suéltelos en las columnas ubicadas en la parte inferior {9}. Usted puede utilizar la barra de desplazamiento de la parte inferior {10} para aumentar el recorrido y ver un mayor número de columnas cuando haya llenado las que puede ver. Cuando haya hecho lo anterior su pantalla debe verse como la Figura 4. Cada columna tiene el nombre del campo y la Tabla de la que proviene ese campo. En nuestro caso, todos los campos vienen de la Tabla “Alimentos”.





*![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/TACCESS4.gif)

Figura 4. Campos adicionados a la Consulta*




En el menú “Ver” {11}, seleccione “Vista Hoja de datos”. Su consulta debe verse igual a la “Vista Hoja de datos” de la Tabla porque usted simplemente solicitó que se mostraran todos los campos en la tabla “Alimentos”. Seleccione “Vista Diseño” del menú “Ver” {11} para regresar a la consulta y poder formular una pregunta más interesante.




## **CALCULAR**




Access tiene varias formas de realizar cálculos. Los usuarios avanzados pueden querer realizar cálculos en sus “Informes” {13}, pero lo primero que vamos a hacer, es realizar cálculos en una Consulta. La ventaja de utilizar una consulta es que usted fácilmente puede basar otras consultas en los cálculos que realiza y si usted quiere tener un resultado sofisticado de un “Informe”, usted también puede basar el “Informe” en la consulta.




En la “Vista Diseño” de la consulta, desplácese hacia la derecha {10} hasta que pueda ver la primera columna vacía. Haga clic en el botón derecho del ratón (Mouse) en la caja “campo” {14} y seleccione “Zoom...” (acercar) del menú. Esta operación abre una ventana mayor que facilita escribir expresiones más largas. Escriba lo siguiente: Proteína por Gramo: [Proteínas] / [Peso].



“Proteína por Gramo” va a ser el nombre de la columna (actúa como si fuera el nombre de un campo). Lo que viene después de los dos puntos (:) es una fórmula para calcular la proteína por gramo. Esta fórmula puede tener cualquier expresión matemática, incluyendo algunas que utilizan funciones matemáticas poderosas que vienen con Access. Su pantalla debe verse como la Figura 5.





*![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/TACCESS5.gif)

Figura 5. Zoom para digitar fórmulas.*




Asegúrese de escribir los nombres de los campos (Proteínas y Peso) en la forma exacta en que los escribió cuando creó los campos. Access es exigente con el deletreo correcto. Asegúrese además, de que los nombres de sus campos están dentro de corchetes [] ya que estos le indican a Access que se trata de nombres de campos. Cuando finalice lo anterior, haga clic en “Aceptar” {15}. Ahora corra nuevamente su consulta escogiendo “Vista Hoja de datos” del menú “Ver” {11}.




Su consulta debe verse igual a la de la primera vez que usted la corrió, excepto que usted tiene ahora una columna nueva. Esta columna se titula “Proteína por Gramo” {16} y contiene el resultado se sus cálculos para cada uno de los alimentos. Su consulta debe verse como la Figura 6.

*![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/TACCESS6.gif)

Figura 6. Consulta con el resultado de sus cálculos.*




A continuación, se limita el número de posiciones decimales que se muestra en el campo “Proteína por Gramo”. Regrese a la “Vista Diseño” (seleccionando “Vista Diseño” del menú “Ver” {11}. Oprima el botón derecho del ratón en la columna “Proteína por Gramo” y escoja “Propiedades” en el menú que aparece. Haga clic en la caja de “Formato” y cámbiela a “Fijo”. Luego haga clic en la caja “Lugares Decimales” y cámbiela a 2 posiciones decimales (ver Figura 7). Esto hace que la columna “Proteína por Gramo” se vea como en la Figura 8.





*![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/TACCESS7.gif)

Figura 7. Propiedades del campo calculado*




Haga clic en la caja “Cerrar” {17} de la ventana que aparece en la Figura 7 y regrese a la “Vista Hoja de datos”. Debe verse exactamente igual que en la Figura 6, excepto que todos los registros de la columna “Proteína por Gramo” {16} muestran dos dígitos después del punto decimal.




Haga clic en la caja “Cerrar” {18} de la ventana (Figura 6) y clic en “Sí” cuando le pregunte “¿Desea guardar los cambios en el diseño de la consulta ‘consulta 1’?”. Dele un nombre que haga sentido a la consulta tal como “Cálculo de Proteínas”. Usted debe ver ahora “Cálculo de Proteínas” listado como una consulta en la Base de Datos. Para verificar que su Tabla “Alimentos” todavía existe, haga clic en el botón “Tabla” {19} y verá que la Tabla se encuentra listada allí.




Ahora que ha añadido un campo calculado, puede crear una nueva Consulta o añadir ese campo a la Consulta que acaba de hacer. Cree un índice de nutrición, sumando “Vitamina C”, “Calcio” y “Proteínas”, y restando las “Calorías” y “Grasas”. Entre más alto el número, más saludable la comida. Claro está que este número no sería un índice de nutrición real, así que puede ajustar estos números, por ejemplo multiplicando las grasas por 10 y pidiendo el calcio por 5, o multiplicando otros números por algún factor. Haga que su clase cree una fórmula para representar todos los elementos nutricionales en su base de datos, para que puedan decidir cuales son las comidas más saludables.




## **ORDENAMIENTO**




No se debe pensar que los registros en su Base de Datos están organizados de un modo particular. Están en el orden en que fueron adicionados, que posiblemente no será el más útil. La opción “ordenar” le permite organizarlos del modo que desee. En el caso de una Base de Datos de 10 comidas, puede que no sea necesario, pero imagine que su Base de Datos incluye cientos o miles de comidas. De repente, el ordenamiento automático se vuelve crucial.




Access le ofrece varias maneras de organizar un documento. La más sencilla es en la “Vista Hoja de datos”. Pulse el botón derecho del ratón sobre el campo que quiera ordenar, y escoja en el menú que aparece: “Orden ascendente” {20} (del valor más bajo al más alto) o “Orden descendente” {20}(del valor más alto al más bajo).




Para responder nuestra pregunta de cual comida contiene la mayor cantidad de proteína en proporción a su masa en gramos, podemos simplemente organizar los registros de acuerdo al campo “Proteína por Gramo”. Viendo la Consulta en la “Vista Hoja de datos”, pulse el botón derecho en “Proteína por Gramo” y elija “Orden descendente”. Así encontrará que el Pollo frito tiene la mayor cantidad de proteína por gramo entre todas las comidas en nuestra Base de Datos (Figura 8).




*![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/TACCESS8.gif)

Figura 8. Orden descendente*




Este modo de organización funciona muy bien cuando solamente se quieren ordenar los registros de acuerdo a un campo, pero no cuando se requiere una organización más compleja, basada en dos o más campos. Por ejemplo, puede que al ordenar los registros de acuerdo a un campo le salgan varios en el mismo lugar, que sería el caso si el campo es el nombre, y varias personas en la Base de Datos tienen el mismo nombre. Así, resulta útil organizarlos también de acuerdo al apellido (todos los José están juntos, pero va antes José Pérez que José Rodríguez).




Para llevar a cabo un ordenamiento complejo, necesitamos otra Consulta. Vaya a la ventana principal de su Base de Datos (Figura 2), haga clic sobre “Consulta” {2}y luego pulse sobre el botón “Nuevo” {3} para obtener una nueva Consulta. Seleccione “Vista Diseño” y pulse sobre “Aceptar”. Access le preguntara en cuáles Tablas o Consultas quiere basar esta nueva Consulta. Puesto que queremos ver todos nuestros datos, incluyendo nuestro cálculo de proteína por gramo, podemos basarla en la Consulta ”Cálculo de Proteínas” que creamos anteriormente. En la ventana emergente “Mostrar Tabla” {21}, pulse sobre “Consultas” {22}, luego sobre “Cálculo de Proteínas” y por último “Agregar” {6}. Ahora cierre la ventana emergente “Mostrar Tabla” {7}. Hasta ahora, el proceso es el mismo que seguimos para crear la Consulta “Cálculo de Proteínas”, sólo que nos estamos basando en aquella Consulta en vez de basarnos en la Tabla “Alimentos”.




Es posible que usted quiera llevar campos de la mitad superior de la pantalla {23} a una columna en la mitad inferior {24}. No tiene que incluir todos los campos. Para mostrar solamente algunos, arrastre los campos que desea a las columnas en la parte inferior. Cuando cambie a “Vista Hoja de datos” solamente aparecerán en esta Consulta los campos que añadió en las columnas de la parte inferior.




La tercera fila de las columnas en la parte inferior se llama “Orden” {25}. Si oprime en la línea “Orden”, para cualquier campo, le aparecerá un menú {26}. Del menú podrá elegir “Ascendente”, “Descendente” o “(sin ordenar)” (Figura 9). Escoja “Ascendente” para el campo “Medida Común”. Regrese a “Vista Hoja de datos” eligiendo “Vista Hoja de datos” en el menú “Ver”. Ahora debe poder ver todos los registros organizados por Medida Común (Figura 10).




![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/TACCESS9.gif)

*Figura 9. Ordenar información con una Consulta*




*![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/TACCESS10.gif)

Figura 10. Resultado de un ordenamiento simple*




Desafortunadamente, aún tenemos un problema con el brócoli, el jugo de naranja, la leche entera, y la leche descremada (2% de grasa). Todos tienen la misma medida común. Añadiremos un segundo criterio para el orden: Peso. Regrese a “Vista Diseño” y pulse en la fila “Orden” para el campo “Peso”. Access lo usará como el criterio principal. Sin embargo, sólo queremos usar los valores de peso para definir el orden en el caso de los registros con igual “Medida Común”. El campo que aparezca primero en “Vista Diseño” será el primer criterio de la organización. Si queremos usar “Medida Común” como primer criterio y “Peso” como segundo, debemos ubicar el campo “Medida Común” antes del campo ”Peso” en “Vista Diseño”. Para lograrlo, vaya a “Vista Diseño” y pulse sobre la columna ”Medida Común” para seleccionarla. Arrástrela hacia la izquierda de la columna ”Peso”, y suéltela ahí.




Aún con estos dos criterios, tenemos un empate entre los dos tipos de leche. Si el orden en que aparecen es importante, necesitamos un tercer criterio.




Si quiere que ”Medida Común” aparezca después de ”Peso”, pero siga siendo el criterio principal para la organización, puede arrastrar sus campos en “Vista Hoja de datos”; el orden en esta vista determina cómo se ven los campos. El orden en que están en “Vista Diseño” es el que determina el orden de importancia de los criterios.




Oprima la caja “Cerrar” {18} de la Consulta, y elija “Si” cuando le pregunte si quiere guardar los cambios. Grábelo bajo un nombre razonable, como “Orden por Medida Común”. Ahora debe ver dos Consultas en la ventana principal de su base de datos: “Orden por Medida Común” y “Cálculo de Proteínas”. Ambas Consultas tienen los mismos datos, pero “Cálculo de Proteínas” no tiene los datos ordenados por “Medida Común”. Puede definir varias Consultas diferentes para ordenamientos diferentes. Por ejemplo, puede que quiera ver cuáles son las comidas con más grasa, o cuáles tienen el mayor contenido de Vitamina C.




**SELECCIÓN**




En ocasiones, usted solo desea ver algunos registros. Por ejemplo, puede que solo quiera considerar las comidas con menos de 1 gramo de grasa por porción. Con sólo 10 comidas, esto es fácil sin una base de datos. Pero con un número grande se hace evidente el poder de ésta herramienta.




En Access, las selecciones también se hacen a través de una Consulta. Abra una nueva Consulta en “Vista Diseño” y use todos los campos de la Consulta “Cálculo de Proteínas”. Observe que bajo cada campo, esta la fila “Orden” {25} (que utilizamos para cuadrar los criterios de orden). Bajo la fila Orden está la fila “Mostrar” (ignórela). Bajo la fila “Mostrar esta la fila “Criterios” {27}. Es aquí donde ubicamos nuestros criterios de selección.




En la fila “Criterios” , se pueden introducir fórmulas para limitar los registros que mostrará la Consulta. Estas fórmulas pueden ser sencillas o muy complejas. Aquí sugerimos algunos ejemplos (cuando pruebe uno, asegúrese de borrar los criterios de selección anteriores).




Escriba “=Broccoli” (sin las comillas) en el campo “Nombre Alimento”. Esta le dará todos los registros con el nombre de comida “Broccoli”.

Escriba “=leche” en el campo “Nombre Alimento”. Esta no le dará resultado alguno, pues = busca un ítem exacto, y ningún alimento se llama sólo “leche”.

Escriba “Leche*” en el campo “Nombre Alimento”. Este criterio mostrará todos los alimentos cuyo nombre comienza con “Leche”. El asterisco (*) es un comodín, así que Leche* quiere decir Leche más cualquier otra palabra.

Escriba “>100” en el campo “Vitamina C”. Esta seleccionará todos los alimentos con más de 100mg de vitamina C.

Escriba “<=2.5” en el campo “Calcio”. Esta le dará los alimentos con 2,5mg o menos de calcio.

Escriba “*a*” en el campo “Nombre Alimento”. Esta mostrará todos los alimentos cuyo nombre incluye la letra a.




Es posible que tras introducir su criterio, Access lo modifique levemente. El programa requiere que sus criterios de selección estén en un formato exacto (con comillas, espacios y caracteres especiales en los lugares exactos), pero normalmente puede descifrar lo que usted desea, así que tras introducir su criterio, lo alterará al formato que necesita.




Hagamos una prueba, creando una selección paso por paso. Si probó algunas de las formulas de arriba, borre los criterios de selección (arrastre el ratón por encima de la formula y presione la tecla Suprimir). Vamos a elegir aquellos alimentos que tienen más de 0,1 gramos de proteína por gramo (según el campo “Proteína por Gramo”). Si no se encuentra en “Vista Diseño” escójala en el menú “Ver”. Si su ventana no es suficientemente amplia, avance hacia la derecha {10} para ver el campo “Proteína por Gramo”. Para este campo, en la fila “Criterios” , teclee >.1 (Figura 11).





*![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/TACCESS11.gif)

Figura 11. Consulta de selección*




Cambie a “Ver Hoja de datos”, y verá tres registros: “Huevos”, “Hamburguesa (comida rápida)” y “Pollo frito (comida rápida, pechuga)”. Estas son las únicas comidas en nuestra base de datos con más de 0,1g de proteína por gramo (Figura 12).





*![](https://eduteka.icesi.edu.co/imgbd/19/entrega12/TACCESS12.gif)

Figura 12. Resultado de la selección*




Cierre su Consulta y grábela bajo un nombre lógico, como “Selección por Proteína por Gramo”. Ahora debe poder ver tres Consultas en la ventana principal de la base de datos {12}: “Cálculo de Proteínas”, “Orden por Medida Común”, y “Selección por Proteína por Gramo”.




El uso de las Consultas para hacer selecciones es una herramienta muy poderosa, pues se pueden elegir registros a partir de cualquier criterio que se escoja. Para selecciones sencillas basta hacer un ordenamiento y buscarlas en el lugar apropiado de la lista, pero en el caso de selecciones más complejas (especialmente aquellas con dos o más criterios), esto no funcionará.




Por último, puede incluir criterios para varios campos a la vez y puede también añadir criterios de orden para crear una Consulta muy compleja. Por ejemplo, podría encontrar alimentos con alto contenido de proteína y baja grasa buscando aquellas con más de 2 gramos de proteína y menos de 5 de grasa, y puede ordenarlas alfabéticamente mediante un ordenamiento.




## **CONCLUSIÓN**




Las Bases de Datos son herramientas extremadamente útiles para organizar grandes cantidades de información; en este artículo sólo hemos rallado la superficie de lo que pueden lograr. El ejemplo que utilizamos aquí le explica como hacer una sencilla Base de Datos de alimentos, pero usted puede usarlas para todo tipo de información. Fácilmente puede crear una Base de Datos para reemplazar su libro de notas. Si sus estudiantes están recolectando información sobre insectos o rocas, pueden introducirla en una Base de Datos y encontrar con facilidad cuáles insectos tienen alas o se alimentan de frutas. Para organizar grandes cantidades de información, no hay mejor herramienta que una Base de Datos.




 




## **MANUALES/TUTORIALES DE ACCESS**






- 

**AulaClic**. Curso de Introducción a Access 2000 (formato HTML)

http://www.aulaclic.es/access2000/f_acces2000.htm




- 

Universia. Manual rápido para usuarios de Access (formato DOC, comprimido ZIP, 854KB)

http://www.universia.net.mx/contenidos/tecnologia/tutoriales/ms/access.zip




- 

La Base de Datos con el ejemplo desarrollado en este artículo (Microsoft Access 2000)

https://eduteka.icesi.edu.co/descargas/FAQAccess.mdb







 




 




### **NOTAS DEL EDITOR:**




**[1]** El ejemplo de este artículo utiliza datos obtenidos del Departamento de Agricultura de Estados Unidos http://www.nal.usda.gov/fnic/foodcomp/Data/.




Consulte la tabla de composición de alimentos de América Latina

http://www.rlc.fao.org/bases/alimento/default.htm




Consulte la Red Latinoamericana de Composición de Alimentos

http://www.inta.cl/latinfoods/




Consulte las Bases de Datos más importantes que ofrece la FAO para la región de América Latina y el Caribe http://www.rlc.fao.org/bases/ Se debe tener en cuenta que algunas de estas Bases de Datos pueden contener información sobre bebidas alcohólicas.




Consulte Las tablas de composición de alimentos en América Latina (Infoods)

http://www.fao.org/infoods/tables_latin_en.stm




 




### **CRÉDITOS**:




Traducción realizada por EDUTEKA del Artículo original “Q&A With Microsoft Access" escrito por David M. Marcovitz y publicado en el Número 3 del Volumen 30 de la revista Learning & Leading with Technology (http://www.iste.org/LL/30/3/index.cfm).




*Publicación de este documento en EDUTEKA: Agosto 28 de 2004.

Última modificación de este documento: Agosto 28 de 2004.*
