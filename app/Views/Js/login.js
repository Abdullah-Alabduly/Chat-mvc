// [Real-time] Login is a single request/response action, so it now uses fetch()
// like the signup page instead of XMLHttpRequest.
const form = document.querySelector(".login form"),
signBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-txt");

form.onsubmit = (e)=>{
    e.preventDefault();
}

signBtn.onclick = ()=>{

    let formData = new FormData(form);

    fetch("login", {
        method: "POST",
        body: formData
    }).then(response => response.text())
      .then(data => {
          if(data === "Success"){
              location.href = "profile";
          }else{
              errorText.textContent = data;
              errorText.style.display = "block";
          }
      })
      .catch(error => {
          errorText.textContent = "somthing went wrong";
          errorText.style.display = "block";
          console.log(error);
      });
}
