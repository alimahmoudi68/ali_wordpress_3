let
persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g],
arabicNumbers  = [/٠/g, /١/g, /٢/g, /٣/g, /٤/g, /٥/g, /٦/g, /٧/g, /٨/g, /٩/g],
fixNumbers = function (str)
{
  if(typeof str === 'string')
  {
    for(var i=0; i<10; i++)
    {
      str = str.replace(persianNumbers[i], i).replace(arabicNumbers[i], i);
    }
  }
  return str;
};
let resend = document.querySelector('.verify-resend');
let timer = document.querySelector('.verify-timer');
let inputToken = document.querySelector('input[name="token"]');
let inputPhone = document.querySelector('input[name="phone"]');
let phone ;

inputPhone.addEventListener("focus" , function(e){
  this.style.border='1px solid #1e90ff';
  this.style.borderRight='1px solid #1e90ff';
});

inputPhone.addEventListener("blur-sm" , function(e){
  this.style.border='1px solid #bbbaba';
  this.style.borderRight='1px solid #bbbaba';
});



let btnSendOtp = document.querySelector('#btn-send-otp');
let allowSendOtp = true;
let cardSendOtp = document.querySelector('#card-send-otp');
let cardVerify = document.querySelector('#card-verify');
let cardRegister = document.querySelector('#card-register');

btnSendOtp.addEventListener('click' , (e)=>{

  let allErrorMsg=document.querySelectorAll('small');
  allErrorMsg.forEach(small =>{
    small.classList.add('invisible');
  });

  checkInput(e);

})


function checkInput(e){
  phone = inputPhone.value.trim();
  
  let valid = true;

  if(!validatePhone(phone)){
      setError( inputPhone , 'لطفا شماره همراه معتبر وارد نمایید');
      valid = false;
  }


  if(valid){
    //document.querySelector('#license-form').submit(); 
  

    btnSendOtp.classList.add('is-loading');


    if(allowSendOtp){
      allowSendOtp = false;

      
      axios.post(`${url.base_url}/wp-json/myapi/send-otp`, {
          phone: phone ,
          wp_nonce : nonces.nonce_send_phone
        }, {
        }).then(function (response) {

          if (response.data.status == 'success') {

            inputToken.value = response.data.token;

            cardVerify.classList.remove('hidden');
            cardSendOtp.classList.add('hidden');
            cardVerify.classList.add('flex');
            setTimeout(()=>{
              cardVerify.classList.remove('translate-y-[300px]');
              showTimer();

            } , 100)

          
      
          }else {

            
            Swal.fire({
              text: 'متاسفانه مشکلی رخ داد مجددا بعدا تلاش کنید',
              icon: 'error',
              showCancelButton: false,
              confirmButtonText: 'متوجه شدم',
              confirmButtonColor: '#1b98e0',
              heightAuto: false
            });
      
          }
      
      
        }).catch(function (error) {
          console.log(error);
          
          Swal.fire({
            text: 'متاسفانه مشکلی رخ داد مجددا بعدا تلاش کنید',
            icon: 'error',
            showCancelButton: false,
            confirmButtonText: 'متوجه شدم',
            confirmButtonColor: '#1b98e0',
            heightAuto: false

          });


        }).then(function () {
          // always
          allowSendOtp = true;
          btnSendOtp.classList.remove('is-loading');

      });

    }
  }

}


//----------- resend sms -----------------
resend.addEventListener('click' , function(){
  phone = inputPhone.value.trim();
  //axios
  axios.post(`${url.base_url}/wp-json/myapi/send-otp`,{
      phone : phone ,
      wp_nonce : nonces.nonce_send_phone
  }).then(function (response) {
                  if(response.data.status == 'success'){
                    // timer

                    // set new token
                    inputToken.value = response.data.token;

                    showTimer();
                    timer.style.display = 'block';
                    resend.style.display = 'none';
                  }
  }).catch(function (error) {
                  console.log(error);
  }).then(function () {
      // always
  });
});
//// --------------- resend sms -----------------




///// ------------------- verify -----------------
let inputPhoneOtp = document.querySelector('input[name="otp"]');
let allowVerify = true;



inputPhoneOtp.addEventListener("focus" , function(e){
  this.style.border='1px solid #1e90ff';
  this.style.borderRight='1px solid #1e90ff';
});

inputPhoneOtp.addEventListener("blur-sm" , function(e){
  this.style.border='1px solid #bbbaba';
  this.style.borderRight='1px solid #bbbaba';
});


let btnVerify = document.querySelector('#btn-verify');

inputPhoneOtp.addEventListener('keypress' , (e)=>{
  if(!isNumber(e)){
    e.preventDefault()
  }
});


inputPhoneOtp.addEventListener('input' , (e)=>{
  if((e.target.value).length == 6){
    verifyHandler();
  }

});


function isNumber(evt) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
      return false;
  }
  return true;
}

btnVerify.addEventListener('click' , function(){
  verifyHandler();
});


function verifyHandler(){
  let token = inputToken.value ;
  let otp = inputPhoneOtp.value;
  if(otp == ''){
    Swal.fire({
      text: 'لطفا کد ارسال شده را وارد کنید',
      icon: 'error',
      showCancelButton: false,
      confirmButtonText: 'متوجه شدم',
      confirmButtonColor: '#1b98e0',
      heightAuto: false
    });

  }else{

    btnVerify.classList.add('is-loading');


    if(allowVerify){
      allowVerify = false;

      
      axios.post(`${url.base_url}/wp-json/myapi/verify`, {
          wp_nonce : nonces.nonce_send_code ,
          token: token ,
          otp : otp
        }, {
        }).then(function (response) {

          if (response.data.status == 'successLogin') {
            window.location.href = url.base_url;
      
          }else if(response.data.status == 'success'){

            cardVerify.classList.add('hidden');
            cardRegister.classList.remove('hidden');
            cardRegister.classList.add('flex');
            setTimeout(()=>{

              cardRegister.classList.remove('translate-y-[300px]');

            } , 100);

          }else{

            
            Swal.fire({
              text: 'کد اشتباه است یا منقضی شده است',
              icon: 'error',
              showCancelButton: false,
              confirmButtonText: 'متوجه شدم',
              confirmButtonColor: '#1b98e0',
              heightAuto: false

            });
      
          }
        
      
        }).catch(function (error) {
          console.log(error);
          
          Swal.fire({
            text: 'متاسفانه مشکلی رخ داد مجددا بعدا تلاش کنید',
            icon: 'error',
            showCancelButton: false,
            confirmButtonText: 'متوجه شدم',
            confirmButtonColor: '#1b98e0',
            heightAuto: false
          });


        }).then(function () {
          // always
          allowVerify = true;
          btnVerify.classList.remove('is-loading');

      });

    }


  }

}

//-----------  /verify -----------------



//----------- register -----------------
let inputNonce3 = document.querySelector('input[name="wp_nonce3"]');
let inputName = document.querySelector('input[name="name"]');
// let inputPassword = document.querySelector('input[name="password"]');
let allowRegister = true;


inputName.addEventListener("focus" , function(e){
  this.style.border='1px solid #1e90ff';
  this.style.borderRight='1px solid #1e90ff';
});

inputName.addEventListener("blur-sm" , function(e){
  this.style.border='1px solid #bbbaba';
  this.style.borderRight='1px solid #bbbaba';
});



// inputPassword.addEventListener("focus" , function(e){
//   this.style.border='1px solid #1e90ff';
//   this.style.borderRight='1px solid #1e90ff';
// });

// inputPassword.addEventListener("blur-sm" , function(e){
//   this.style.border='1px solid #bbbaba';
//   this.style.borderRight='1px solid #bbbaba';
// });


let btnRegister = document.querySelector('#btn-register');


btnRegister.addEventListener('click' , function(){

  let allErrorMsg=document.querySelectorAll('small');
  allErrorMsg.forEach(small =>{
    small.classList.add('invisible');
  });

  registerHandler();
});


function registerHandler(){
  let name = inputName.value ;
  //let password = inputPassword.value;

  let valid = true;

  if(!validateName(name)){
      setError( inputName , 'لطفا نام خود را وارد نمایید');
      valid = false;
  }

  
  // if(!validatePassword(password)){
  //   setError( inputPassword , 'لطفا یک رمز عبور حداقل 6 رقمی وارد کنید');
  //   valid = false;
  // }


  if(valid){
  
    btnRegister.classList.add('is-loading');


    if(allowRegister){
      allowRegister = false;

      
      axios.post(`${url.base_url}/wp-json/myapi/register`, {
          wp_nonce : inputNonce3.value ,
          token: document.querySelector('input[name="token"]').value ,
          otp : document.querySelector('input[name="otp"]').value ,
          name : name,
          //password : password
        }, {
        }).then(function (response) {

                
          if (response.data.status == 'success') {

            window.location.href = url.base_url;
            
          }else {

            
            Swal.fire({
              text: 'متاسفانه مشکلی رخ داد مجددا بعدا تلاش کنید',
              icon: 'error',
              showCancelButton: false,
              confirmButtonText: 'متوجه شدم',
              confirmButtonColor: '#1b98e0',
              heightAuto: false
            });
      
          }
      
      
      
        }).catch(function (error) {
          console.log(error);
          
          Swal.fire({
            text: 'متاسفانه مشکلی رخ داد مجددا بعدا تلاش کنید',
            icon: 'error',
            showCancelButton: false,
            confirmButtonText: 'متوجه شدم',
            confirmButtonColor: '#1b98e0',
            heightAuto: false

          });


        }).then(function () {
          // always
          allowRegister = true;
          btnRegister.classList.remove('is-loading');

      });

    }
  }


}
//-----------  /register -----------------



// ----- validation and error -----------
function setError( input , msg){

  input.style.border='1px solid #ee5253';
  input.style.borderRight='1px solid #ee5253';
  let txtError =  input.parentElement.querySelector('small');
  txtError.innerHTML = msg ;
  txtError.classList.remove('invisible');
}

function validatePhone(phone) {
  var re = /^09[0-9]{9}$/ig;
  return re.test(fixNumbers(phone).toLowerCase());
}


function validateName(name) {
  console.log(name.length)
  if(name.length < 1){
    return false;
  }else{
    return true;
  }
}


function validatePassword(password) {
  console.log(password.length)

  if(password.length < 6){
    return false;
  }else{
    return true;
  }
}



// ---------- Timer -------------
function showTimer() {
  let minute = 1
  let second = 59
  let x = setInterval(function() { 
  if(second == 0){
      second = 59;
      minute = minute -1
  }
  
  document.getElementById("seconds").innerText = second;
  document.getElementById("minutes").innerText = minute;

  second = second - 1 ;

  if (minute < 0) {
      clearInterval(x);
      timer.style.display = 'none';
      resend.style.display = 'block';
      document.getElementById("seconds").innerText = 59;
      document.getElementById("minutes").innerText = 1;
  }
  }, 1000);
}