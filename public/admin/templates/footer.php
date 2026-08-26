</main>

<!-- Boton "volver arriba" -->
<button id="btn-volver-arriba" type="button" onclick="window.scrollTo({top:0,behavior:'smooth'})"
        class="hidden fixed bottom-6 left-6 z-40 w-12 h-12 rounded-full bg-white text-marca-azul shadow-lg border border-gray-200 hover:bg-gray-50 transition items-center justify-center text-lg"
        aria-label="Volver arriba">
    <i class="fa-solid fa-arrow-up"></i>
</button>
<script>
(function () {
    var btnArriba = document.getElementById('btn-volver-arriba');
    if (!btnArriba) return;
    window.addEventListener('scroll', function () {
        if (window.scrollY > 400) {
            btnArriba.classList.remove('hidden');
            btnArriba.classList.add('flex');
        } else {
            btnArriba.classList.add('hidden');
            btnArriba.classList.remove('flex');
        }
    });
})();
</script>
</body>
</html>
