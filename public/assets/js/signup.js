// var ch = document.getElementById("sel");

// ch.onchange = function(){
//     var div1 = document.getElementById("customerf");
//     var frm = document.getElementById("sig");
//     // frm.appendChild(div1);
    
//     while (div1.firstChild) {
//         div1.removeChild(div1.firstChild);
//     }

//     if(ch.value == "c"){
//         var la = document.createElement("label");
//         la.innerHTML = "Your city:";

//         la.classList.add("form-label");
//         div1.appendChild(la);

//         div1.appendChild(document.createElement("br"));
//         var city = document.createElement("input");
//         city.classList.add("form-control");

//         city.name = "city";
//         city.required = true;

//         city.maxLength = 65;
//         div1.appendChild(city);

//         div1.appendChild(document.createElement("br"));
//         var la2 = document.createElement("label");
//         la2.innerHTML = "Your street:";

//         la2.classList.add("form-label");
//         div1.appendChild(la2);

//         var strret = document.createElement("input");
//         strret.classList.add("form-control");

//         strret.name = "street";
//         strret.required = true;

//         strret.maxLength = 65;
//         div1.appendChild(strret);

//         div1.appendChild(document.createElement("br"));
//         var la3 = document.createElement("label");
//         la3.innerHTML = "Your country:";

//         la3.classList.add("form-label");
//         div1.appendChild(la3);

//         var country = document.createElement("input");
//         country.classList.add("form-control");

//         country.name = "country";
//         country.required = true;

//         country.maxLength = 65;
//         div1.appendChild(country);

//     }
// }