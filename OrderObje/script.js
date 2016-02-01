/*
for(i=0; i < elArray.length; i++){

}*/
/*
var people = [
{
    name: 'a75',
    item1: false,
    item2: false
},
{
    name: 'z32',
    item1: true,
    item2: false
},
{
    name: 'e77',
    item1: false,
    item2: false
}];

function sortByKey(array, key) {
    return array.sort(function(a, b) {
        var x = a[key]; var y = b[key];
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
}

people = sortByKey(people, 'name');
*/

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
//---------------------------------------------------------------------------
/*Codigo de relleno*/
var elArray = [
  {
    "title": "Data Driven Retail Success",
    "type": "Whitepaper",
    "synopsis": "learn how you can maximise your sell thru data from retailers to gain better visibility of your product's performance down to the store floor.",
    "region": "ANZ",
    "industry": "Retail",
    "data_source": "SAP",
    "role": "any"
  },
  {
    "title": "How Phocas Business Intelligence Helps Your IT Team",
    "type": "Whitepaper",
    "synopsis": "The benefits of business intelligence can make things a lot better for your IT team & copany",
    "region": "ALL",
    "industry": "Retail",
    "data_source": "Eclipse",
    "role": "any"
  },
  {
    "title": "Medical Industry: Keep your Finger on the Pulse",
    "type": "Whitepaper",
    "synopsis": "Learn how suppliers and manufacturers are using operational data to overcome the challenges facing their industry.",
    "region": "ALL",
    "industry": "Medical",
    "data_source": "Eclipse",
    "role": "any"
  }
];
//--------------------------------------------
/*Codigo para probar la funcion sortObject*/
var az = sortObject(elArray, 'data_source', 'SAP');
console.log(JSON.stringify(az));
