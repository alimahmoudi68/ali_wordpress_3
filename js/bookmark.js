
let btnRemoveBookmarks = document.querySelectorAll('.btn-remove-bookmark');
const body = document.querySelector("body");
let BackModalRemove = document.getElementById("back-modal-remove");
let modalRemove = document.getElementById("modal-remove");
let closeModalRemove = document.getElementById("close-modal-remove");
let btnSubmit = document.querySelector('#btn-submit');
let btnClose = document.querySelector('#btn-close');
let allowRemove = true;
let remove_child_number = 0;
let remove_bookmark_id = "";


btnRemoveBookmarks.forEach(function(btn){
  btn.addEventListener('click' , function(e){
    remove_child_number = btn.parentNode.parentNode.parentNode.getAttribute('remove-set') ;
    remove_bookmark_id = btn.getAttribute('data-set') ;
    body.style.overflow = "hidden";
    BackModalRemove.classList.remove('hidden');
    setTimeout(function(){
        modalRemove.classList.remove('hidden');
        modalRemove.classList.add('block');
        modalRemove.classList.add('animate-zoom-in');
    },100)
 
  });

});



btnSubmit.addEventListener('click' , function(){
  if(allowRemove){
    btnSubmit.classList.add('is-loading');
    allowRemove = false;

    axios.post('http://localhost/classnow/wp-json/myapi/bookmark/remove', {
        post_id: remove_bookmark_id ,
      }, {
      }).then(function (response) {


        if (response.data.status == 'success') {

          let parent_bookmark = document.querySelector(".parent-bookmark");
          let remove_bookmark = parent_bookmark.querySelector(`div[remove-set='${remove_child_number}']`);
          parent_bookmark.removeChild(remove_bookmark);


          if(parent_bookmark.children.length == 0){
            parent_bookmark.innerHTML='<p class="caption-no-result">هیچ موردی نشان گذاری نشده است</p>'
          }


          Swal.fire({
            text: response.data.msg ,
            heightAuto: false ,
            position: 'top-start',
            icon: 'success',
            showConfirmButton: false,
            timer: 2000
          });


        }else {

          
          Swal.fire({
            text: 'متاسفانه مشکلی رخ داد مجددا بعدا تلاش کنید',
            icon: 'error',
            heightAuto: false ,
            position: 'top-start',
            showConfirmButton: false,
            timer: 2000
          });
    
        }
    
    
    
      }).catch(function (error) {
        console.log(error);
        
        Swal.fire({
          text: 'متاسفانه مشکلی رخ داد مجددا بعدا تلاش کنید',
          icon: 'error',
          heightAuto: false ,
          position: 'top-start',
          showConfirmButton: false,
          timer: 2000

        });





      }).then(function () {
        // always
        allowRemove = true;
        btnSubmit.classList.remove('is-loading');
        closeMpdalRemove();

    });
  } 
 
});



btnClose.addEventListener('click' , function(e) {
  closeMpdalRemove();
});


window.addEventListener("click", function(event) {
  if (event.target == BackModalRemove) {
    closeMpdalRemove();
  }
});


const closeMpdalRemove = ()=>{
  BackModalRemove.classList.add('hidden');
  modalRemove.classList.add('hidden');
  modalRemove.classList.remove('block');
  modalRemove.classList.remove('animate-zoom-in');
  body.style.overflow = "auto";
}