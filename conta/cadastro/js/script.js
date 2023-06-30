document.addEventListener("DOMContentLoaded", function(){
    const form = document.querySelector("#form-cadastro");

    form.onsubmit = function (e) {
        enviaForm(form);
        e.preventDefault();
    }
});

function enviaForm(form){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", form.getAttribute("action"));
    xhr.responseType = 'json';

    xhr.onload = function () {
        let resposta = xhr.response;
        console.log(resposta);
        if (resposta.success){
            window.location = resposta.detail;
        }
        else {
            alert("Verifique os campos e tente novamente.")
        }
    }      

    xhr.send(new FormData(form));

}