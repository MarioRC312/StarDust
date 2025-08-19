//función que crea una estrella fugaz con posición y animación aleatoria
function createShootingStar() {
    const star = document.createElement('div');
    star.classList.add('shooting-star');
    // Posición vertical inicial aleatoria
    const startY = Math.random() * window.innerHeight;
    star.style.top = startY + "px";
    star.style.left = "-50px";
    // Definir destino de la animación mediante variables CSS
    const endX = window.innerWidth + 100;
    const endY = startY + (Math.random() * 200 - 100);
    star.style.setProperty('--end-x', endX + "px");
    star.style.setProperty('--end-y', endY + "px");
    // Duración y retardo aleatorios para variar la animación
    star.style.animationDuration = (0.8 + Math.random() * 0.5) + "s";
    star.style.animationDelay = (Math.random() * 0.3) + "s";
    return star;
}

const newPostForm = document.getElementById('newPostForm');
newPostForm.addEventListener('submit', function(e) {
    e.preventDefault(); 

    const animationContainer = document.getElementById('animationContainer');
    //limpiar contenedor de animación
    animationContainer.innerHTML = '';

    const numberOfStars = 5; // Ajusta este número para más/menos estrellas
    for (let i = 0; i < numberOfStars; i++) {
        const star = createShootingStar();
        animationContainer.appendChild(star);
        setTimeout(() => {
            star.classList.add('animate');
        }, 50);
    }

    //espera 1 segundo y luego se envía el formulario
    setTimeout(() => {
        newPostForm.submit();
    }, 1000);
});