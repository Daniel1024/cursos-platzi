Aplicacion en descanso
==========
 
Este es el README de mi nueva aplicación MyNewApp.
 
Este fichero se usa de ejemplo dentro del blog de aprendegit.com para crear un primer repositorio.
 
Ejemplos de markdown
--------------------
 
Así se crean secciones y subsecciones. Para crear una enumeración utilizamos:
1. Elemento 1
# Probando los titulos h1... #
## Probando los titulos h2... ##
### Probando los titulos h3... ###
**hola mundo**

```
#!javascript

function sortObject(Object, type, name){
	var array1 = new Array();
	var array2 = new Array();
	Object.forEach(function(obj){
		if (obj[type]==name) {
			array1.push(obj);
		} else{
			array2.push(obj);
		};
	});
	return array1.concat(array2);
}
```