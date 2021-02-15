function addNumber(){
    var weight = document.getElementById("dynamic-list");
    var number = document.getElementById("name");
    var li = document.createElement("li");
    li.setAttribute('id',number.value);
    li.appendChild(document.createTextNode(number.value));
    ul.appendChild(li);
}

/*function removeItem(){
    var ul = document.getElementById("dynamic-list");
    var candidate = document.getElementById("candidate");
    var item = document.getElementById(candidate.value);
    ul.removeChild(item);
} */