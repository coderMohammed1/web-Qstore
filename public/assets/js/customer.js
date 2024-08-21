var main = document.getElementById("main");


if (screen.width >= 1366) {
    main.style.marginLeft = "16%";
    main.style.width = "70%";

}else if(screen >= 1066 ){
    main.style.marginLeft = "7%";
    main.style.width = "90%";

}else if(screen >= 961){
    main.style.marginLeft = "1.1%";
    main.style.width = "100%";

}else if(screen >= 922){
    main.style.marginLeft = "15%";
    main.style.width = "100%";

}else if(screen.width >= 812){
    main.style.marginLeft = "10%";
    main.style.width = "100%";

}else if(screen.width >= 583) {
    main.style.marginLeft = "20%";
    main.style.width = "100%";
    
}
else if (screen.width <= 550) {
    main.style.marginLeft = "3.3%";
}


// setInterval(scw,100);