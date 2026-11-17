// [Real-time] This file used to poll the "getMsgs" endpoint with
// XMLHttpRequest every 500ms and to send messages with XMLHttpRequest too.
// The conversation now arrives over one Server-Sent Events connection and
// sending a message is a one-off fetch() call.
const form = document.querySelector('.typing-area'),
inputFaild = form.querySelector('.input-faild'),
sendBtn = form.querySelector('button'),
chatBox = document.querySelector('.chat-box'),
peerId = form.querySelector('input[name="msg_resciver_id"]').value;

form.onsubmit = (e) =>{
    e.preventDefault();
}

sendBtn.onclick = ()=>{

    // [Real-time] Nothing to send when the field is empty.
    if(inputFaild.value.trim() === "") return;

    let formData = new FormData(form);

    fetch('send_msg', {
        method: 'POST',
        body: formData
    }).then(response => response.text())
      .then(() => {
          inputFaild.value = "";
          scrollToEnd();
      })
      .catch(error => console.error(error));
}

chatBox.onmouseenter = ()=>{
    chatBox.classList.add('active');
}
chatBox.onmouseleave = ()=>{
    chatBox.classList.remove('active');
}

// [Real-time] One persistent connection for the whole conversation. The server
// pushes a "messages" event only when a new message is stored, so the browser
// no longer asks for data on a timer.
const stream = new EventSource('stream?user_id=' + encodeURIComponent(peerId));

stream.addEventListener('messages', (event)=>{
    if(chatBox.innerHTML === event.data) return;
    chatBox.innerHTML = event.data;
    if(!chatBox.classList.contains('active'))
        scrollToEnd();
});

// [Real-time] The stream reports an expired session instead of failing silently.
stream.addEventListener('unauthorized', ()=>{
    window.location.href = 'login';
});

function scrollToEnd(){
    chatBox.scrollTop = chatBox.scrollHeight;
}
