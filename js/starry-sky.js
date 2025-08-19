const canvas = document.getElementById("starry-sky");
const ctx = canvas.getContext("2d");

canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

const stars = [];
const starCount = 100;
const shootingStars = [];

function createStars() {
    for (let i = 0; i < starCount; i++) {
        stars.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            size: Math.random() * 2,
            speed: Math.random() * 0.5
        });
    }
}

function createShootingStar() {
    shootingStars.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height / 2,
        size: Math.random() * 3 + 1,
        speedX: Math.random() * 4 + 2,
        speedY: Math.random() * 2 + 1,
    });
}

function drawStars() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "white";
    stars.forEach(star => {
        ctx.beginPath();
        ctx.arc(star.x, star.y, star.size, 0, Math.PI * 2);
        ctx.fill();
        star.y += star.speed;
        if (star.y > canvas.height) star.y = 0;
    });
}

function drawShootingStars() {
    ctx.fillStyle = "white";
    shootingStars.forEach((star, index) => {
        ctx.beginPath();
        ctx.arc(star.x, star.y, star.size, 0, Math.PI * 2);
        ctx.fill();
        star.x -= star.speedX;
        star.y += star.speedY;

        if (star.x < 0 || star.y > canvas.height) {
            shootingStars.splice(index, 1);
        }
    });
}

function animate() {
    drawStars();
    drawShootingStars();
    if (Math.random() < 0.01) createShootingStar();
    requestAnimationFrame(animate);
}

createStars();
animate();