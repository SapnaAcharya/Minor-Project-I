// //get the file input and image preview elements
// const imageInput = document.getElementById('image');
// const imagePreview = document.getElementById('image-preview');

// //add an event listener to the file input
// imageInput.addEventListener('change', function() {
//     //check if any file is selected
//     if (imageInput.files && imageInput.files[0]) {
//         //create a filereader object
//         const reader = new FileReader();

//         //set the image preview source when the FileReader has finished loading the image
//         reader.onload = function(e) {
//             imagePreview.src = e.target.result;
//             imagePreview.style.display = 'block';
//         };

//         //Read the selected file as a data URL
//         reader.readAsDataURL(imageInput.files[0]);
//     } else {
//         //hide the image preview if no file is selected
//         imagePreview.src = "#";
//         imagePreview.style.display = 'none';
//     }
// });

document.getElementById("image").addEventListener("change", function() {
    var reader = new FileReader();
    reader.onload = function(e) {
        var imagePreview = document.getElementById("image-preview");
        var clearIcon = document.getElementById("clear-icon");
        imagePreview.style.display = "block";
        imagePreview.src = e.target.result;
        clearIcon.style.display = "block";
    };
    reader.readAsDataURL(this.files[0]);
});

document.getElementById("clear-icon").addEventListener("click", function() {
    var imagePreview = document.getElementById("image-preview");
    var imageInput = document.getElementById("image");
    imagePreview.style.display = "none";
    imagePreview.src = "#";
    imageInput.value = "";
    this.style.display = "none";
});
 
// document.getElementById("clear-icon").addEventListener("click", function() {
//     imageInput.value= "";
// });