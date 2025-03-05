function mudaSublist(element) {
    let sublist = element.querySelector(".sub-lista");
    let seta = element.querySelector(".seta");

    if (sublist.style.display === "block") {
        sublist.style.display = "none";
        seta.classList.remove("setaVirada");
    } else {
        sublist.style.display = "block";
        seta.classList.add("setaVirada");
    }
}

function pesquisar() {
    let pesquisa = document.querySelector("#search").value.trim().toLowerCase();
    let itens = document.querySelectorAll(".sub-lista li");
    let section = document.querySelector(".setores");
    let resultado = document.querySelector(".resultado");
    let contResultados = 0;

    resultado.innerHTML = "";
    section.style.display = "block";

    if (pesquisa === '') {
        pesquisa = "*";
    } else {
        itens.forEach(item => {
            let nome = item.textContent.trim().toLowerCase();
            let link = item.querySelector("a").getAttribute("href");
            let isVideo = link.includes(".mp4") || link.includes("youtube") || link.includes("vimeo");
            let icone = isVideo ? "video.png" : "doc.webp";
            let target = isVideo ? "target='_blank'" : "";

            if (nome.includes(pesquisa)) {
                resultado.innerHTML += `<li><img src="assets/img/${icone}" id="doc"> 
                <a href="${link}" ${target}>${item.textContent}</a></li>`;
                resultado.style.display = "block";
                section.style.display = "none";
                contResultados += 1;
            }
        });

        if (contResultados === 0) {
            resultado.style.display = "block";
            resultado.innerHTML = "<p id='nResultado'>Nenhum resultado encontrado!</p>";
        }
    }

    if (pesquisa === "*") {
        resultado.style.display = "none";
        section.style.display = "block";
    }
}