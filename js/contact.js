document.getElementById("contactForm").addEventListener("submit",function(e){
    e.preventDefault();
    let formData=new FormData(this);
    fetch("contact.php",{
    method:"POST",
    body:formData
    })
    .then(response=>response.text())
    .then(data=>{
          document.getElementById("formMessage").innerHTML='<p style="color:green;">'+data+'</p>';
             document.getElementById("contactForm").reset();
            
    })
    .catch(error=>{
        document.getElementById("formMessage").innerHTML='<p style="color:red;">Error sending this message</p>';
    })


    });