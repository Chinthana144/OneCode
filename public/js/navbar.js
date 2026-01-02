const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});

$(document).ready(function () {

    $(".btn_menu_toggle").click(function(){
        $("#mobile_menu_items").toggleClass('show');
    });
});//jquery
