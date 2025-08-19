  //Función que crea una estrella fugaz con posición y animación aleatoria
function createShootingStar() {
    const star = document.createElement('div');
    star.classList.add('shooting-star');
    // Posición vertical inicial aleatoria
    const startY = Math.random() * window.innerHeight;
    // Destino horizontal (más allá del ancho de la ventana)
    const endX = window.innerWidth + 100;
    // Destino vertical: variamos ligeramente la posición
    const endY = startY + (Math.random() * 200 - 100);
    star.style.top = startY + "px";
    star.style.left = "-50px";
    star.style.setProperty('--end-x', endX + "px");
    star.style.setProperty('--end-y', endY + "px");
    //duración y retardo aleatorios (asi lo hago un poco pas dinamico)
    star.style.animationDuration = (0.8 + Math.random() * 0.5) + "s";
    star.style.animationDelay = (Math.random() * 0.3) + "s";
    return star;
}

const form = document.getElementById('editPostForm');
form.addEventListener('submit', function(e) {
    e.preventDefault();
    const numberOfStars = 100; //para poner mas estrellas
    for (let i = 0; i < numberOfStars; i++) {
        const star = createShootingStar();
        document.body.appendChild(star);
        setTimeout(() => {
            star.classList.add('animate');
        }, 50);
    }
    //espera 1 segundo y luego se envía el formulario
    setTimeout(() => {
        form.submit();
    }, 1000);
});