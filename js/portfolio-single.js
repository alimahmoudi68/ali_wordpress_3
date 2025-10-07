// ------- swiper service --------------
let sliderService = new Swiper(".slider-single-portfolio", {
  slidesPerView: 1.3,
  spaceBetween: 20,
  centeredSlides: true,
  rtl: true,
  direction: "horizontal",
  loop: true,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
});
