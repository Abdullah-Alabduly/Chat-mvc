const form = document.querySelector(".signup form"),
signBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-txt");
console.log('hi');
form.onsubmit = (e)=>{
    e.preventDefault();
}

signBtn.onclick = ()=>{

    let formData = new FormData(form);

    fetch("register",{
        method: "POST",
        body: formData
    }).then(
        response => response.text()
        ).then(
            data =>{             
                if(data === "Success"){
                    location.href = "chatlist";
                    console.log(data);
                }else{
                    location.href = "users";
                    // console.log(data);
                    //  errorText.textContent = data;
                    //  errorText.style.display = "block";
                }
            }).catch(
                    error =>{
                        errorText.textContent = "somthing went wrong";
                        errorText.style.display = "block";
                        console.log(error);
                    }
                );
}




