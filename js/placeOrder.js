document.getElementById("placeorder").addEventListener("submit",function(e){
e.preventDefault();
  this.querySelector('button[type="submit"]').disabled = true; 
let formData=new FormData(this);
fetch("place-order.php",{
    method:"POST",
    body:formData
})
.then(response=>response.json())
.then(data=>{
       const box = document.createElement('div');
        const icon = document.createElement('img');
        icon.src = data.success ? "img/icon/check-mark.png" :"img/icon/cross.png" ;
        icon.style.width = '70px';
        icon.style.marginBottom = '15px';
        icon.style.display = 'block';
        icon.style.marginLeft = 'auto';
        icon.style.marginRight = 'auto';

        const message = document.createElement('p');
        message.textContent = data.message; 
        message.style.margin = '0';
        message.style.fontSize = '22px';
        message.style.fontWeight = 'bold';

        box.style.width = '400px';
        box.style.position = 'fixed';
        box.style.top = '50%';
        box.style.left = '50%';
        box.style.transform = 'translate(-50%, -50%)';
        box.style.background = data.success ? '#dff0d8' : '#f2dede';
        box.style.color = data.success ? '#3c763d' : '#a94442';
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
            if(data.success){
                window.location.href = "profile.php"; 
            }
        }, 2000);
    })

  .catch(error => {
        document.getElementById("responseMsg").innerHTML = 
            `<p style="color:red;">⚠️ Error: ${error}</p>`;
    });
});
