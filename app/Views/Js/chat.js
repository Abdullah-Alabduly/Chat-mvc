const form =document.querySelector('.typing-area'),
inputFaild = form.querySelector('.input-faild'),
sendBtn = form.querySelector('button'),
chatBox = document.querySelector('.chat-box');

form.onsubmit = (e) =>{
    e.preventDefault();
}
sendBtn.onclick = ()=>{

    let xhr = new XMLHttpRequest();
    xhr.open('POST','send_msg',true);
    xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                inputFaild.value = "";
                scrolToEnd();
            }
        }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}

chatBox.onmouseenter = ()=>{
    chatBox.classList.add('active');
}
chatBox.onmouseleave = ()=>{
    chatBox.classList.remove('active');
}

// setInterval(()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "getMsgs");
    xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;              
                    chatBox.innerHTML = data;
                    if(!chatBox.classList.contains('active'))
                        scrolToEnd();    
            }
        }
    }
    let formData = new FormData(form);
    xhr.send(formData);
    
// }, 500);

function scrolToEnd(){
    chatBox.scrollTop = chatBox.scrollHeight;
}

