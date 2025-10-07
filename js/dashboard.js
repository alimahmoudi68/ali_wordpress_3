
let menus = document.querySelectorAll('.woocommerce-MyAccount-navigation ul li');

currenUrl = window.location.href;
menus.forEach(function(menu){
    let link = menu.querySelector('a').getAttribute('href');
    if(currenUrl == link){
        menu.classList.add('active')

    }
});


let navigationMenu = document.querySelector('.woocommerce-MyAccount-navigation');
let showNavigationHandler = document.querySelector('.navigationHandler');
window.onresize = function(event) {
    let width = window.innerWidth;
    if(width < 768){
        navigationMenu.classList.add = 'max-h-0'
    }
    if(width > 768){
        navigationMenu.classList.remove = "max-h-0";
    }
};
showNavigationHandler.addEventListener('click' , function(){
    if (navigationMenu.style.maxHeight) {
        navigationMenu.style.maxHeight = null;
      } else {
        navigationMenu.style.maxHeight = navigationMenu.scrollHeight + "px";
      }
});