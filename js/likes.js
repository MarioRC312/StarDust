document.addEventListener("DOMContentLoaded", function() {
    document.body.addEventListener("click", function(event) {
        if (event.target.classList.contains("reaction-btn")) {
            let postId = event.target.getAttribute("data-post-id");
            let reaction = event.target.getAttribute("data-reaction");

            fetch("./../controller/likePost.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `post_id=${postId}&reaction=${reaction}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar botones sin recargar la página
                    document.querySelectorAll(`[data-post-id='${postId}']`).forEach(btn => {
                        btn.classList.remove("btn-primary", "btn-warning", "btn-danger");
                        btn.classList.add("btn-outline-primary", "btn-outline-warning", "btn-outline-danger");
                    });

                    // Activar solo el botón seleccionado
                    event.target.classList.remove("btn-outline-primary", "btn-outline-warning", "btn-outline-danger");
                    if (reaction === "like") {
                        event.target.classList.add("btn-primary");
                    } else if (reaction === "meh") {
                        event.target.classList.add("btn-warning");
                    } else if (reaction === "dislike") {
                        event.target.classList.add("btn-danger");
                    }
                }
            });
        }
    });
});

// document.addEventListener("DOMContentLoaded", function () {
//     document.querySelectorAll(".reaction-btn").forEach(button => {
//         button.addEventListener("click", function () {
//             let postId = this.dataset.postId;
//             let reaction = this.dataset.reaction;

//             fetch("../controller/reaccionar.php", {
//                 method: "POST",
//                 headers: {
//                     "Content-Type": "application/x-www-form-urlencoded"
//                 },
//                 body: `post_id=${postId}&reaction=${reaction}`
//             })
//             .then(response => response.json())
//             .then(data => {
//                 if (data.success) {
//                     // Actualizar UI cambiando los colores de los botones
//                     document.querySelectorAll(`[data-post-id="${postId}"]`).forEach(btn => {
//                         btn.classList.remove("btn-primary", "btn-warning", "btn-danger", "btn-outline-primary", "btn-outline-warning", "btn-outline-danger");

//                         if (btn.dataset.reaction === reaction) {
//                             btn.classList.add(
//                                 reaction === "like" ? "btn-primary" :
//                                 reaction === "meh" ? "btn-warning" :
//                                 "btn-danger"
//                             );
//                         } else {
//                             btn.classList.add(
//                                 btn.dataset.reaction === "like" ? "btn-outline-primary" :
//                                 btn.dataset.reaction === "meh" ? "btn-outline-warning" :
//                                 "btn-outline-danger"
//                             );
//                         }
//                     });

//                     // Actualizar contadores
//                     document.querySelector(`[data-post-id="${postId}"][data-reaction="like"]`).innerHTML = `👍 Me gusta (${data.likeCount})`;
//                     document.querySelector(`[data-post-id="${postId}"][data-reaction="meh"]`).innerHTML = `😐 Meh (${data.mehCount})`;
//                     document.querySelector(`[data-post-id="${postId}"][data-reaction="dislike"]`).innerHTML = `👎 No me gusta (${data.dislikeCount})`;
//                 } else {
//                     alert("Error al registrar la reacción.");
//                 }
//             })
//             .catch(error => console.error("Error:", error));
//         });
//     });
// });
