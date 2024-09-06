// Obtener referencias a los elementos
const toggleButton = document.getElementById('cambioTema'); // Usa el nuevo ID

// Función para aplicar el tema
function applyTheme(theme) {
    if (theme === 'nocturno') {
        document.getElementById('diaNoche').setAttribute('href', 'nocturno.css');
        toggleButton.textContent = 'Cambiar a Modo Diurno';
        localStorage.setItem('theme', 'nocturno');
    } else {
        document.getElementById('diaNoche').setAttribute('href', 'diurno.css');
        toggleButton.textContent = 'Cambiar a Modo Nocturno';
        localStorage.setItem('theme', 'diurno');
    }
}

// Cargar el tema guardado en localStorage al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'diurno';
    applyTheme(savedTheme);
});

// Cambiar el tema al hacer clic en el botón
toggleButton.addEventListener('click', () => {
    const currentTheme = document.getElementById('diaNoche').getAttribute('href') === 'diurno.css' ? 'diurno' : 'nocturno';
    applyTheme(currentTheme === 'diurno' ? 'nocturno' : 'diurno');
});


// Funcion para que el h1 lleve a la pagina inicial:

document.getElementById("enlace").addEventListener("click", function () {
    // Redirige a la página principal
    window.location.href = "/index.php";
});