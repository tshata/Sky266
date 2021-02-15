function calculateCubic() {
  //get length and replace comma with point, then turn it into a floating point number
  var total=document.forms["cbm"].value;
  if(total==" ") {
    var total = document.getElementById("cbm").value;
            display_total.innerHTML= total;

  }
  else{
  var length = document.getElementById("length").value.replace(",", ".");
  length = length.replace(/,/g, ".");
  length = parseFloat(length);

  //get width and replace comma with point, then turn it into a floating point number
  var width = document.getElementById("width").value.replace(",", ".");
  width = width.replace(/,/g, ".");
  width = parseFloat(width);

  //get thickness, replace comma with point, turn it into a floating point number and divide by 100
  var height = document.getElementById("height").value;
  height = height.replace(/,/g, ".");
  height = parseFloat(height) ;
  //Calculate volume, then limit to 2 decimals after point
  var cubic_meters = length * width * height;
  document.getElementById("result").innerHTML = cubic_meters.toFixed(2);
  }
}
