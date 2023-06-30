document.addEventListener("DOMContentLoaded", function(){
    const form = document.querySelector("#form-atualiza")
    const inputEmail = document.querySelector("#email");
    inputEmail.disabled = true;

    form.onsubmit = function(e){
        enviaForm(form);
        e.preventDefault();
    }

    buscaInformacoes(form);
});

function buscaInformacoes(form){
    let xhr = new XMLHttpRequest();
    let email = form.email[1].value;

    xhr.open("GET", "buscaInformacoes.php?email=" + `${email}`);

    xhr.onreadystatechange = function(){
        if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200){
            // Converte a resposta json em objeto JS.
            var informacoes = JSON.parse(xhr.responseText);
            /**
             * Faz um forEach no array recebido pelo servidor
             * E atualiza os campos, o email é o identificante da sessão.
             */
            informacoes.forEach(function(informacao){
                form.nome.value = informacao.nome;
                form.email.value = informacao.email;
                form.cpf.value = informacao.cpf;
                form.telefone.value = informacao.telefone;
            });
        }
    };
    xhr.send()
}

function enviaForm(form){
    let formData = new FormData(form);
    let xhr = new XMLHttpRequest();

    xhr.open("POST", form.getAttribute("action"));
    xhr.responseType = 'json';

    xhr.onload = function () {
        let resposta = xhr.response;
        
        if (resposta.success){
            window.location = resposta.detail;
        }
        else {
            console.log(resposta.email);
            document.querySelector("#att-fail").style.display = 'inline';
            form.senhaAntiga.value = "";
            form.senhaNova.value = "";
            form.senhaAntiga.focus();
        }
    }      

    xhr.send(formData);
}