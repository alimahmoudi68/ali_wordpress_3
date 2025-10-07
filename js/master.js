document.addEventListener('DOMContentLoaded', function () {
    initCheckHeaderMenu();
});


// Resolve basePath from PHP (supports subfolder e.g. /portfolio)
var basePath = (window.themeGlobals && window.themeGlobals.basePath) ? window.themeGlobals.basePath.replace(/\/$/, '') : '';


// Helper: determine if a given path is the "home" (fa or en) under basePath
function isHomePath(pathname) {
    var p = pathname || window.location.pathname;
    if (!/\/$/.test(p)) p += '/';
    var baseFa = (basePath || '') + '/';
    var baseEn = (basePath || '') + '/en/';
    return p === baseFa || p === baseEn;
}


// Update header menu based on current page (for SPA navigation)
function updateHeaderMenuForCurrentPage() {
    var currentPath = window.location.pathname;
    var isHomePage = isHomePath(currentPath);
    var headerMenu = document.querySelector('.header-menu');

    
    if (headerMenu) {
        if (isHomePage) {
            // Hide header menu on home page
            headerMenu.style.display = 'none';
            headerMenu.setAttribute('data-hidden', 'true');
        } else {
            // Show header menu on other pages with animation
            headerMenu.style.display = 'block';
            headerMenu.removeAttribute('data-hidden');
            gsap.killTweensOf(headerMenu);
            gsap.fromTo(headerMenu, 
                { opacity: 0, y: -20 }, 
                { opacity: 1, y: 0, duration: 0.5, ease: 'power2.out' }
            );
        }
    }
}


function initCheckHeaderMenu() {
    var isHomePage = isHomePath(window.location.pathname);
    console.log('isHomePage>>' , isHomePage);

    var headerMenu = document.querySelector('.header-menu');
    if (headerMenu) {
        if (isHomePage) {
            headerMenu.style.display = 'none';
            headerMenu.setAttribute('data-hidden', 'true');
        } else {
            headerMenu.style.display = 'block';
            headerMenu.removeAttribute('data-hidden');
        }
    }
}