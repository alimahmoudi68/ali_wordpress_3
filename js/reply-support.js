
//---------- ajax send ticket  -------------
let inputBody = document.querySelector('textarea[name="body"]');
let inputFile = document.querySelector('input[name="upload"]');




// input body
inputBody.addEventListener("focus" , function(e){
  this.style.border='1px solid #1e90ff';
  this.style.borderRight='1px solid #1e90ff';
});

inputBody.addEventListener("blur-sm" , function(e){
  this.style.border='1px solid #bbbaba';
  this.style.borderRight='1px solid #bbbaba';
});



// ---------------------- validation ------------------------------------------
let btnSubmit = document.querySelector('#btn-submit');
let body;
let allowReply = true;


btnSubmit.addEventListener('click' , function(e){
  e.preventDefault();

    let allErrorMsg=document.querySelectorAll('small');
    allErrorMsg.forEach(small =>{
        small.style.visibility='hidden';
    });
    checkInput(e);
});


function checkInput(e){
  body = inputBody.value.trim();
  
  let valid = true;


  if(body === ''){
      setError( inputBody , 'لطفا توضیحات سوال را وارد کنید');
      valid = false;
  }

  if(inputFile.files[0] !== undefined ){
    if(inputFile.files[0].size > 50000000){
      setError( inputFile , 'لطفا فایلی با حداکثر 50 مگابایت انتخاب کنید');
      valid = false;
    }

  }
 

  if(valid){
    //document.querySelector('#license-form').submit(); 
   

    if(allowReply){
      allowReply = false;

      btnSubmit.classList.add('is-loading');

      let formEl = document.querySelector('#form');
      var form = formEl; // You need to use standard javascript object here
      var formData = new FormData(form);


        axios.post('http://localhost/classnow/wp-json/myapi/tickets/reply',formData , {
        }).then(function (response) {


          if (response.data = 'success') {

  
            btnSubmit.classList.remove('is-loading');
    
            Swal.fire({
              text: 'جواب شما با موفقیت ارسال شد',
              icon: 'success',
              showCancelButton: false,
              confirmButtonText: 'متوجه شدم',
              confirmButtonColor: '#1b98e0',
            });



            // refresh page
            window.location.reload();


  
          } else if (response.data !== 'success'){
  
            btnSubmit.classList.remove('is-loading');


            Swal.fire({
              text: 'متاسفانه مشکلی رخ داد مجددا بعدا تلاش کنید',
              icon: 'error',
              showCancelButton: false,
              confirmButtonText: 'متوجه شدم',
              confirmButtonColor: '#1b98e0',
            });

           

            
          }else {
            isShowSearch = true;
  
            btnSubmit.classList.remove('is-loading');
 
            Swal.fire({
              text: 'متاسفانه مشکلی رخ داد مجددا بعدا تلاش کنید',
              icon: 'error',
              showCancelButton: false,
              confirmButtonText: 'متوجه شدم',
              confirmButtonColor: '#1b98e0',
            });  

          }
  
        }).catch(function (error) {
          console.log(error);
        }).then(function () {
          allowReply = true;
          btnSubmit.style.width = `unset`;
          btnSubmit.style.height =  `unset`;
        });

    }

  }

}


function setError( input , msg){

  input.style.border='1px solid #ee5253';
  input.style.borderRight='1px solid #ee5253';
  let txtError =  input.parentElement.querySelector('small');
  txtError.innerHTML = msg ;
  txtError.style.visibility = 'visible';
}



