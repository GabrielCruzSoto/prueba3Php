


document.getElementById('customFile').addEventListener('change', function() {
    //img.src = "";
    debugger
    let img = document.getElementById('img-preview');
    let file = document.getElementById('customFile').files[0];
    const reader = new FileReader();
    reader.onload = function(event){
        const base64String = event.target.result;
        img.src = base64String;
        img.width=150;
        img.height=85;
    }
    reader.readAsDataURL(file);
});
if(document.getElementById('btn-edit')){

    document.getElementById('btn-edit').addEventListener('click', function() {
        document.getElementById("nombre_area").disabled = false;
        tinymce.get(0).mode.set('design');
        document.getElementById("custom-file").disabled = false;
        
    
        document.getElementById("inputRadioPublicoSi").disabled = false;
        document.getElementById("inputRadioPublicoNo").disabled = false;
    
    
        document.getElementById("btn-edit").disabled = true;
        document.getElementById("btn-edit").style.display = "none";
    });
}

