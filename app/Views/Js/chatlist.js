// [Real-time] The sidebar used to refresh itself with XMLHttpRequest every
// 800ms. It now uses a single Server-Sent Events connection for the chat list,
// and plain fetch() calls for the one-off search request.
const searchBar = document.querySelector(".users .search input"),
usersList = document.querySelector(".users .users-list"),
searchBtn = document.querySelector(".users .search button");

searchBtn.onclick = ()=>{
    searchBar.classList.toggle("active");
    searchBar.focus();
    searchBtn.classList.toggle("active");
    searchBar.value = "";
    searchBar.classList.remove("Active");
}

searchBar.onkeyup = ()=>{
    let searchTerm = searchBar.value;
    if(searchTerm != "")
        searchBar.classList.add("Active");
    else
        searchBar.classList.remove("Active");

    fetch("search", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "searchTerm=" + encodeURIComponent(searchTerm)
    }).then(response => response.text())
      .then(data => {
          // [Real-time] Only show search results while the user is searching,
          // the live list keeps updating underneath.
          if(searchBar.classList.contains("Active"))
              usersList.innerHTML = data;
      })
      .catch(error => console.error(error));
}

// [Real-time] The chat list is pushed by the server when a user registers,
// changes status or sends a message.
const stream = new EventSource("stream");

stream.addEventListener("chats", (event)=>{
    if(searchBar.classList.contains("Active")) return; // keep search results
    if(usersList.innerHTML === event.data) return;
    usersList.innerHTML = event.data;
});

stream.addEventListener("unauthorized", ()=>{
    window.location.href = "login";
});
