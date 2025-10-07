
//---------- ajax send ticket  -------------
let inputTitle = document.querySelector('input[name="title"]');
let inputBody = document.querySelector('textarea[name="body"]');
let selectCourse = document.querySelector('select[name="courseId"]');
let inputFile = document.querySelector('input[name="upload"]');



// input title
inputTitle.addEventListener("focus" , function(e){
  this.style.border='1px solid #1e90ff';
  this.style.borderRight='1px solid #1e90ff';
});

inputTitle.addEventListener("blur-sm" , function(e){
  this.style.border='1px solid #bbbaba';
  this.style.borderRight='1px solid #bbbaba';
});



// input body
inputBody.addEventListener("focus" , function(e){
  this.style.border='1px solid #1e90ff';
  this.style.borderRight='1px solid #1e90ff';
});

inputBody.addEventListener("blur-sm" , function(e){
  this.style.border='1px solid #bbbaba';
  this.style.borderRight='1px solid #bbbaba';
});


// select cat
selectCourse.addEventListener("focus" , function(e){
  this.style.border='1px solid #1e90ff';
  this.style.borderRight='1px solid #1e90ff';
});

selectCourse.addEventListener("blur-sm" , function(e){
  this.style.border='1px solid #bbbaba';
  this.style.borderRight='1px solid #bbbaba';
});


// ---------------------- validation ------------------------------------------
let btnSubmit = document.querySelector('#btn-submit');
let courseId;
let title;
let body;
let allowSend = true;


btnSubmit.addEventListener('click' , function(e){
  e.preventDefault();

   
    checkInput(e);
});


function checkInput(e){
  courseId = selectCourse.value.trim();
  title = inputTitle.value.trim();
  body = inputBody.value.trim();
  
  let valid = true;

  if(courseId === '0'){
      setError( selectCourse , 'لطفا عنوان دوره آموزشی را مشخص کنید');
      valid = false;
  }

  if(title === ''){
      setError( inputTitle , 'لطفا عنوان سوال را وارد کنید');
      valid = false;
  }

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

    if(allowSend){
      allowSend = false;

      btnSubmit.classList.add('is-loading');

      let formEl = document.querySelector('#form');
      var form = formEl; // You need to use standard javascript object here
      var formData = new FormData(form);


        axios.post('http://localhost/classnow/wp-json/myapi/tickets/new',formData , {
        }).then(function (response) {


          if (response.data = 'success') {
            
  
            btnSubmit.classList.remove('is-loading');
    
            Swal.fire({
              text: 'این دوره با موفقیت به سبد خرید شما اضافه شد',
              icon: 'success',
              showCancelButton: false,
              confirmButtonText: 'متوجه شدم',
              confirmButtonColor: '#1b98e0',
            });
  
  
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
          allowSend = true;
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



