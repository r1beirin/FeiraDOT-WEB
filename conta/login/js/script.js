document.addEventListener("DOMContentLoaded", function(){
    const form = document.querySelector("#form-login");

    form.onsubmit = function (e) {
        enviaForm(form);
        e.preventDefault();
    }
});

async function enviaForm(form){
    try{
        let response = await fetch(form.getAttribute("action"), { method: 'post', body: new FormData(form) });
        if (!response.ok) throw new Error(response.statusText);
        var responseJS = await response.json();

        if (responseJS.success){
            window.location = responseJS.detail;
        }
        else {
            document.querySelector("#login-fail").style.display = 'block';
            form.senha.value = "";
            form.senha.focus();
        }

    }
    catch(e){
        console.error(e);
    }
}