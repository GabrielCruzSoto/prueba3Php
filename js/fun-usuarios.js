document.getElementById('btn-edit').addEventListener('click', function() {
    //document.getElementById("user").disabled = false;
    document.getElementById("nombre").disabled = false;
    document.getElementById("pwd").disabled = false;
    //document.getElementById("re-pwd").disabled = false;
    tinymce.
    document.getElementById("btn-save").disabled = false;

    document.getElementById("inputRadioPublicoSi").disabled = false;
    document.getElementById("inputRadioPublicoNo").disabled = false;


    document.getElementById("btn-edit").disabled = true;
    document.getElementById("btn-edit").style.display = "none";
});