// ================================================
// THEME TOGGLE - Sistema Yustre
// Aplica dark mode a body y html (necesario para
// que el pseudo-elemento html::before funcione)
// ================================================

(function () {
    if (localStorage.getItem("theme") === "dark") {
        document.body.classList.add("dark");
        document.documentElement.classList.add("dark");
    }
})();

function toggleTheme() {
    document.body.classList.toggle("dark");
    document.documentElement.classList.toggle("dark");
    const isDark = document.body.classList.contains("dark");
    localStorage.setItem("theme", isDark ? "dark" : "light");
}