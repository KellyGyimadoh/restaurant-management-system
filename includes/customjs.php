<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll('.nav-item .dropdown-toggle').forEach(dropdown => {
            dropdown.addEventListener('click', function(event) {
                event.preventDefault();
                const dropdownMenu = this.nextElementSibling;
                dropdownMenu.classList.toggle('show');
            });
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
