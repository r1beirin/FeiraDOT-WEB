var paginaAtual = 1;
var carregando = false;
var fimResultados = false;

document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("#form-search");
  const btnForm = document.querySelector("#btn-search");

  buscaAvancada();
  buscaCategorias();

  
  btnForm.addEventListener("click", function(e){
    busca(form);
    e.preventDefault();
  })

});

window.onscroll = function () {
  if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
    const form = document.querySelector("#form-search");
    busca(form);
  }
};

async function busca(form){
  if (!carregando && !fimResultados) {
    carregando = true;

    try{
      var formData = new FormData(form);
      formData.append("pagina", paginaAtual);

      const obj = {
        method: "POST",
        body: formData
      }

      let resposta  = await fetch(form.getAttribute("action"), obj);
      if(!resposta.ok) throw new Error(resposta.statusText);
      var anuncios = await resposta.json();

      if(anuncios.length > 0){
        renderAnuncios(anuncios);

        paginaAtual++;
      }
      else{
        fimResultados = true;
      }
    }
    catch(e){
      console.error(e);
    }

    carregando = false;
  }
}

function buscaAvancada() {
  const search = document.querySelector("#btn-advanced");
  const divAdvanced = document.getElementById("search-advanced");
  let foo = false;

  search.onclick = function () {
    divAdvanced.style.display = "block";
    if (foo) {
      divAdvanced.style.display = "none";
      foo = false;
    } else {
      divAdvanced.style.display = "block";
      foo = true;
    }
  };
}

function buscaCategorias() {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "buscaCategoria.php");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      // Converte a resposta json em objeto JS.
      var categorias = JSON.parse(xhr.responseText);
      const selectCategorias = document.getElementById("categoria");

      /**
       * Faz um forEach no array recebido pelo servidor
       * E cria uma nova div para cada categoria.
       *
       * OBS: .toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "")
       * serve para retirar a acentuação e transformar em minúsculo.
       */
      categorias.forEach(function (categoria) {
        var option = document.createElement("option");
        option.setAttribute("type", "option");
        option.setAttribute("value", categoria.nome);
        option.textContent = categoria.nome;

        selectCategorias.appendChild(option);
      });
    }
  };

  xhr.send();
}

function renderAnuncios(anuncios) {

  const anuncioSection = document.querySelector(".results_search");
  const template = document.querySelector("#template");

  for (let anuncio of anuncios) {
    let html = template.innerHTML
      .replace("{{anuncio-image}}", anuncio.imagem)
      .replace("{{anuncio-titulo}}", anuncio.nome)
      .replace("{{anuncio-descricao}}", anuncio.descricao)
      .replace("{{anuncio-preco}}", anuncio.preco)
      .replace("{{anuncio-codigo}}", anuncio.codigo);

      anuncioSection.insertAdjacentHTML("beforeend", html);
  };
}