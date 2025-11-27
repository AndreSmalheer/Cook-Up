</body>

<script src="js/header.js"></script>
<script>
    const searchButtons = document.querySelectorAll('.search-btn');

    searchButtons.forEach(btn => {
        const container = btn.closest('.search');
        const input = container.querySelector(".search-input");

        console.log(input)
    
        btn.addEventListener('click', () => {
            container.classList.toggle('active');
            input.focus();
        });
    });

    function goToUrl(url) {
    window.location.href = url;
    }
</script>

<script src="../js/header.js"></script>
<script src="../js/admin.js"></script>

</html>
