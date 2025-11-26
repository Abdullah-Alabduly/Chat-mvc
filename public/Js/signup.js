const form = document.querySelector(".signup form"),
signBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-txt");
console.log('hi');
form.onsubmit = (e)=>{
    e.preventDefault();
}
signBtn.onclick = ()=>{

    let xhr = new XMLHttpRequest();
    xhr.open('POST','reg',true);
    xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                data = xhr.response;
                if(data === "Success"){
                    location.href = "users.php";
                }else{
                     errorText.textContent = data;
                     errorText.style.display = "block";
                }
            }
        }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}
// signBtn.onclick = ()=>{

//     let formData = new FormData(form);

//     fetch("login",{
//         method: "POST",
//         body: formData
//     }).then(
//         response => response.text()
//         ).then(
//             data =>{             
//                 if(data === "Success"){
//                     location.href = "users.php";
//                 }else{
//                      errorText.textContent = data;
//                      errorText.style.display = "block";
//                 }
//             }).catch(
//                     error =>{
//                         errorText.textContent = "somthing went wrong";
//                         errorText.style.display = "block";
//                         console.log(error);
//                     }
//                 );
// }




