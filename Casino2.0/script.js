/**
 * The JavaScript code includes functions to toggle between day and night themes, load the saved theme
 * from localStorage, change the theme on button click, and redirect to the initial page when clicking
 * on a specific element.
 * The `theme` parameter in the code refers to the theme that can be applied to the
 * webpage. It can have two possible values: 'diurno' (day theme) or 'nocturno' (night theme). The
 * `applyTheme` function is responsible for applying the selected theme
 */
// Obtener referencias a los elementos
const toggleButton = document.getElementById('cambioTema'); // Usa el nuevo ID


// Función para aplicar el tema

function applyTheme(theme) {
    if (theme === 'nocturno') {
        document.getElementById('diaNoche').setAttribute('href', 'nocturno.css');
        toggleButton.textContent = '-> Diurno';
        localStorage.setItem('theme', 'nocturno');
    } else {
        document.getElementById('diaNoche').setAttribute('href', 'diurno.css');
        toggleButton.textContent = '-> Nocturno';
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
    window.location.href = "index.php";
});


// Obtener el año actual

const currentYear = new Date().getFullYear();


// Asignar el año al span con id="year"

document.getElementById("year").textContent = currentYear;
