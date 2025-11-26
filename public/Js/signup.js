const form = document.querySelector(".signup form"),
signBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-txt");

form.onsubmit = (e)=>{
    e.preventDefault();
}
signBtn.onclick = ()=>{

    let formData = new FormData(form);

    fetch("php/signup.php",{
        method: "POST",
        body: formData
    }).then(
        response => response.text()
        ).then(
            data =>{             
                if(data === "Success"){
                    location.href = "users.php";
                }else{
                     errorText.textContent = data;
                     errorText.style.display = "block";
                }
            }).catch(
                    error =>{
                        errorText.textContent = "somthing went wrong";
                        errorText.style.display = "block";
                        console.log(error);
                    }
                );
}


