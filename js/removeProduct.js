document.querySelectorAll('.close').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();

        const id = this.dataset.id.trim(); // get id from button

        fetch('remove_product.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `id=${encodeURIComponent(id)}`
        })
        .then(res => res.text())
        .then(data => {
            console.log(data); // Optional: show response
            location.reload(); // refresh to update cart
        });
    });
});
