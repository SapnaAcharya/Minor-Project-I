// function toggleProvinceOption() {
//     var provinceField = document.getElementById("province");
//     // var provinceOptions = document.getElementById("provinceOptions");

//     if(provinceField.value == "select") {
//         provinceOptions.style.display = "none";
//     } else {
//         provinceOptions.style.display = "block";
//     }
// }

function toggleProvinceOptions() {
    var provinceSelect = document.getElementById('province');
    var locationInput = document.getElementById('location');
  
    // Get the selected province value
    var selectedProvince = provinceSelect.value;
  
    // Enable or disable the location input based on the province selection
    if (selectedProvince !== 'select') {
      locationInput.disabled = false;
    } else {
      locationInput.disabled = true;
      locationInput.value = ''; // Reset the location input value
    }
  }
  
  // You can add more JavaScript code here as needed
  