//---ajax add to cart---
jQuery(document).ready(function($) {
  $('.addToCard').on('click', function(e){ 
  

    e.preventDefault();
    $thisbutton = $(this) ; 
    let width = $thisbutton[0].offsetWidth ;
    let height = $thisbutton[0].offsetHeight ;
    $thisbutton[0].style.width  = `${width}px`;
    $thisbutton[0].style.height  = `${height}px`;

    $thisbutton[0].childNodes[1].classList.add('hidden');
    $thisbutton[0].classList.add('is-loading');

    product_qty = 1 ;

    product_id = $thisbutton[0].getAttribute('data-set') ;
    //variation_id = $form.find('input[name=variation_id]').val() || 0;
  var data = {
          action: 'ql_woocommerce_ajax_add_to_cart',
          product_id ,
          product_sku: '',
          quantity: 1,
      };
  $.ajax({
          type: 'post',
          url: wc_add_to_cart_params.ajax_url,
          data: data,
          beforeSend: function (response) {
              $thisbutton.removeClass('added').addClass('loading');
          },
          complete: function (response) {
              $thisbutton.addClass('added').removeClass('loading');
          }, 
          success: function (response) { 

              $thisbutton[0].childNodes[1].classList.remove('hidden');
              $thisbutton[0].classList.remove('is-loading');
              $thisbutton[0].style.width  = 'unset';
              $thisbutton[0].style.height  = 'unset';

           

              if (response.error & response.product_url) {
                  window.location = response.product_url;
                  Swal.fire({
                    text: 'متاسفانه خطایی رخ داد',
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonText: 'متوجه شدم',
                  });
                  return;
              } else { 
                  $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);

                  Swal.fire({
                    icon: "success",
                    html: `
                    .این دوره با موفقیت به سبد خرید شما اضافه شد
                    `,
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonColor: "#fc427b",
                    cancelButtonColor: "#2563eb",
                    confirmButtonText: `
                      <a href='/cart'>تکمیل خرید</a>
                    `,
                    confirmButtonAriaLabel: "Thumbs up, great!",
                    cancelButtonText: `
                     متوجه شدم
                    `,
                    cancelButtonAriaLabel: "Thumbs down"
                  });
              } 
          }, 
      }); 
   }); 
});
//------ FAQ -------
let coll = document.getElementsByClassName("btn-collapse");
let i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    let content = this.nextElementSibling;
    this.querySelector('svg').remove();
    if (content.style.maxHeight){
        content.style.maxHeight = null;
        this.innerHTML  += "<svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4 sm:h-6 sm:w-6 stroke-gray-600 md:group-hover:stroke-primary-100 dark:stroke-white-100' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='2'><path stroke-linecap='round' stroke-linejoin='round' d='M12 4v16m8-8H4' /></svg>" ;
    } else {
        content.style.maxHeight = content.scrollHeight + "px";
        this.innerHTML  += "<svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4 sm:h-6 sm:w-6 stroke-gray-600 md:group-hover:stroke-primary-100 dark:stroke-white-100' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='2'><path stroke-linecap='round' stroke-linejoin='round' d='M20 12H4' /></svg>";
    } 
  });
}
//------ more -------
let btnMore = document.querySelector('.btn-more');
let moreContent = document.querySelector('.more-content');
let gradient = document.querySelector('.gradientMore');
btnMore.addEventListener('click' , ()=>{
  //moreContent.classList.remove('max-h-[200px]');
  moreContent.style.maxHeight = moreContent.scrollHeight + "px";
  gradient.classList.add('hidden');
  btnMore.classList.add('hidden');
});
//------ episodes -------
let btnSeasons = document.getElementsByClassName("btn-season");
let j;

for (i = 0; i < btnSeasons.length; i++) {
  btnSeasons[i].addEventListener("click", function() {
    let episodes = this.nextElementSibling;
    let svgIcon = this.querySelector('svg');
    if (episodes.style.maxHeight){
        episodes.style.maxHeight = null;
        svgIcon.classList.add('rotate-90');
    } else {
        episodes.style.maxHeight = episodes.scrollHeight + "px";
        svgIcon.classList.remove('rotate-90');
    } 
  });
}
