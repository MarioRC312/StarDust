document.addEventListener("DOMContentLoaded", () => {
    const starContainer = document.getElementById("falling-stars");

    function createFallingStar() {
        const star = document.createElement("div");
        star.classList.add("star");

        //posición inicial aleatoria
        const startX = Math.random() * window.innerWidth;
        star.style.left = `${startX}px`;

        //velocidad y dirección aleatoria
        const duration = Math.random() * 2 + 1; //entre 1 y 3 segundos
        star.style.animationDuration = `${duration}s`;

        //añadir al contenedor
        starContainer.appendChild(star);

        //eliminar la estrella después de la animación
        star.addEventListener("animationend", () => {
            star.remove();
        });
    }

    //crear estrellas periódicamente
    setInterval(createFallingStar, 300); // Una estrella cada 300ms
});