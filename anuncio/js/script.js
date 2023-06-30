var index = 0;

document.addEventListener("DOMContentLoaded", function(){
    const form = document.querySelector(".interesse-form");
    const btnNext = document.querySelector(".btn-div-anuncio");
    
    btnNext.onclick = function(){
        nextImage();
    }

    form.onsubmit = function (e) {
        enviaForm(form);
        e.preventDefault();
    }
});

function nextImage(){
    const imgAnuncioDetalhado = document.querySelector(".img-div-anuncio");
    const fotosDataDiv = document.getElementById('fotos-data');
    // Pego os valores das fotos do php pelo atributo data.
    const fotosData = fotosDataDiv.dataset.fotos;
    const fotosArray = JSON.parse(fotosData);
    
    index += 1;
    index = index % fotosArray.length;
    
    imgAnuncioDetalhado.setAttribute('src', '/anuncio/img/' + fotosArray[index])
}

function enviaForm(form){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", form.getAttribute("action"));
    xhr.responseType = 'json';

    xhr.onload = function () {
        let resposta = xhr.response;
        console.log(resposta);
        if (resposta.success){
            alert(resposta.detail);
        }
        else {
            alert(resposta.detail);
        }
    }      

    xhr.send(new FormData(form));

}
