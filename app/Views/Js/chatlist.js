const searchBar = document.querySelector(".users .search input"),
usersList = document.querySelector(".users .users-list"),
searchBtn = document.querySelector(".users .search button"),
logout = document.querySelector('.users .logout');

searchBtn.onclick = ()=>{
    searchBar.classList.toggle("active");
    searchBar.focus();
    searchBtn.classList.toggle("active");
    searchBar.value = "";
    searchBar.classList.remove("Active");
}

searchBar.onkeyup = ()=>{
    let searchTerm = searchBar.value;
    if(searchTerm !="")
        searchBar.classList.add("Active");
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "search");
    xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                usersList.innerHTML = data;
                console.log(data);
            }
        }
    }
    // to send the data as key => value
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("searchTerm="+searchTerm);
    
}


setInterval(()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "chatlist");
    xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                if(!searchBar.classList.contains("Active")){
                    usersList.innerHTML = data;
                }
            }
        }
    }
    xhr.send();
    
}, 800);

logout.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "logout");
    xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
                let data = xhr.response;
                console.log(data);
            }
        }
    }
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("logout="+true);
}