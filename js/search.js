function search() {
    if (document.querySelector(".search_bar input").value != "") {
        location.href = "https://www.baidu.com/s?from=1011440l&word=" + document.querySelector(".search_bar input").value;
        document.querySelector(".search_bar input").value = "";
    } return false;
}