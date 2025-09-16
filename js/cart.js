
const count = document.getElementById('cart-count');
const total = document.getElementById('cart-total');
const cartSum = document.getElementById('cartSum');
const cartSubtotal = document.getElementById('cartSubtotal');

function updateCartPrice(id, productPrice) {
    const section = document.getElementById(id);
    if (section) {
        const currentTotal = parseFloat(section.textContent.replace(',', '')) || 0; 
        const newTotal = currentTotal + productPrice;
        section.textContent = newTotal.toFixed(2);
    }
}

document.querySelectorAll('.add-to-cart-form').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);
        fetch(form.action, {
            method: form.method,
            body: formData
        })
            .then(res => res.text())
            .then(data => {
                const box = document.createElement('div');
                const productName = form.querySelector('input[name="name"]').value;
                const productPrice = parseFloat(form.querySelector('input[name="price"]').value)||0;
                updateCartPrice('cartSum', productPrice);
                updateCartPrice('cartSubtotal', productPrice);
                updateCartPrice('cart-total', productPrice);

                if (count) {
                    const currentCount = parseInt(count.textContent) || 0;
                    count.textContent = currentCount + 1;
                }
                const icon = document.createElement('img');
                icon.src = "img/icon/check-mark.png";
                icon.style.width = '70px';
                icon.style.marginBottom = '15px';
                icon.style.display = 'block';
                icon.style.marginLeft = 'auto';
                icon.style.marginRight = 'auto';
                const message = document.createElement('p');
                message.textContent = `${productName} added successfully`;
                message.style.margin = '0';
                message.style.fontSize = '22px';
                message.style.fontWeight = 'bold';
                box.style.width = '400px';
                box.style.position = 'fixed';
                box.style.top = '50%';
                box.style.left = '50%';
                box.style.transform = 'translate(-50%, -50%)';
                box.style.background = '#dff0d8';
                box.style.color = '#3c763d';
                box.style.padding = '30px';
                box.style.borderRadius = '15px';
                box.style.boxShadow = '0 0 15px rgba(0,0,0,0.4)';
                box.style.zIndex = '9999';
                box.style.textAlign = 'center';
                box.appendChild(icon);
                box.appendChild(message);
                document.body.appendChild(box);
                setTimeout(() => {
                    box.remove();
                }, 2000);
            })
            
            .catch(() => alert('an error occurred'));
 

    });


});

const applayCoupon = document.getElementById('applayCoupon');
if (applayCoupon) {
    applayCoupon.addEventListener('click', function () {
        const couponValue = document.getElementById('couponInput').value;

        fetch('coupon.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'coupon=' + encodeURIComponent(couponValue)
        }).then(res => res.text())
            .then(data => {
                console.log('Sending coupon:', couponValue);
                if (data.trim() == 'valid') {
                    location.reload();
                }
                else {
                    alert('invalid coupon code!');
                }
            })
            .catch(() => alert('an error occurred'));
    });

}
