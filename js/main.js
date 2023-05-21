document.getElementById('customFile').addEventListener('change', function() {
    
    let divImagen = document.getElementById('imagenDiv');
    let img = document.createElement('img');
    let file = document.getElementById('customFile').files[0];
    const reader = new FileReader();
    reader.onload = function(event){
        const base64String = event.target.result;
        img.src = base64String;
        img.width=150;
        img.height=85;
        divImagen.appendChild(img);
    }
    reader.readAsDataURL(file);
});
