document.addEventListener("DOMContentLoaded", () => {
    const cards = document.querySelectorAll(".course-card");

    cards.forEach(card => {
        const img = card.querySelector("img");
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");

        img.onload = () => {
            canvas.width = img.naturalWidth;
            canvas.height = img.naturalHeight;
            ctx.drawImage(img, 0, 0);

            try {
                const data = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
                let r = 0, g = 0, b = 0, count = 0;

                // Calcula el color promedio
                for (let i = 0; i < data.length; i += 4 * 100) {
                    r += data[i];
                    g += data[i + 1];
                    b += data[i + 2];
                    count++;
                }
                r = Math.round(r / count);
                g = Math.round(g / count);
                b = Math.round(b / count);

                card.style.backgroundColor = `rgba(${r}, ${g}, ${b}, 0.15)`;
            } catch (e) {
                console.warn("No se pudo obtener el color de la imagen", e);
            }
        };
    });
});
