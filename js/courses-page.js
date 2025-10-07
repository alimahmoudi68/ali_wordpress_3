

let btnLoadMore = document.querySelector('.btn-load-more');

let pages = document.querySelector('.posts-list').dataset.max;
let current_page = document.querySelector('.posts-list').dataset.page;


if( pages <= current_page ){
    btnLoadMore.classList.add('hidden');
}



if(typeof(btnLoadMore) !== 'undefined' && btnLoadMore !== null){
    btnLoadMore.addEventListener('click' , ()=>{

        document.querySelector('.loading').classList.remove('hidden');
        let textLoadMore = document.querySelector('.txt-more');


        textLoadMore.classList.add('hidden');


        let queryParams = new URLSearchParams(window.location.search);
        const q_query = queryParams.get('q');
        const sort_query = queryParams.get('sort');
        const cat_query = queryParams.get('cat');


        axios.post(`${home_url.base_url}/wp-json/myapi/load-more` , {
            current_page :  current_page ,
            q : q_query  ?? '',
            sort : sort_query ?? '',
            cat : cat_query ?? '',
        })
        .then(res=>{


            textLoadMore.classList.remove('hidden');

            if(res.data.success){

                let postsList = document.querySelector('.posts-list');
                postsList.innerHTML += res.data.data ;
    

                let queryParams = new URLSearchParams(window.location.search);
                queryParams.set('p', parseInt(document.querySelector('.posts-list').dataset.page)+1);
                history.replaceState(null, null, "?"+queryParams.toString());


                document.querySelector('.posts-list').dataset.page++;
    
                if(document.querySelector('.posts-list').dataset.page == document.querySelector('.posts-list').dataset.max ){
    
                    let btnLoadMore2 = document.querySelector('.btn-load-more');
                    btnLoadMore2.classList.add('hidden');
                    
                    document.querySelector('.posts-list').style.padding = '0 0 80px 0';
                }else{
                    document.querySelector('.txt-more').classList.remove('hidden');
                    document.querySelector('.loading').classList.add('hidden');
    
                }

            }else{

                if(res.data.data == 'No more post'){

                    let btnLoadMore2 = document.querySelector('.btn-load-more');
                    btnLoadMore2.parentNode.removeChild(btnLoadMore2);

                }

            }


        
        });
    })
}


function getData (){

    let loading = document.querySelector('#loading');

    loading.classList.remove('hidden');


    const urlParams = new URLSearchParams(window.location.search);
    const q_query = urlParams.get('q');
    const sort_query = urlParams.get('sort');
    const cat_query = urlParams.get('cat');


    axios.post(`${home_url.base_url}/wp-json/myapi/get-posts` , {
        q : q_query  ?? '',
        sort :sort_query ?? '',
        cat : cat_query ?? '',
    })
    .then(res=>{

        let postsList = document.querySelector('.posts-list');
        loading.classList.add('hidden');
        postsList.innerHTML = '' ;

        postsList.innerHTML += "<div id='loading' class='w-full h-full hidden flex items-center p-5 justify-center rounded-lg bg-background-100 dark:bg-darkBack-100 absolute top-0 left-0 z-999'><div class='lds-ellipsis'><div></div><div></div><div></div><div></div></div></div>" ;
        if(res.data.success){

            postsList.innerHTML += res.data.data ;

            
            document.querySelector('.posts-list').dataset.page = 1;
            document.querySelector('.posts-list').dataset.max = res.data.max ;


            if(1 == res.data.max || res.data.max == 0 ){

                let btnLoadMore2 = document.querySelector('.btn-load-more');
                btnLoadMore2.classList.add('hidden');
                document.querySelector('.posts-list').style.padding = '0 0 80px 0';

            }else{
                document.querySelector('.btn-load-more').classList.remove('hidden');
                document.querySelector('.loading').classList.add('hidden');

            }

        }else{

            if(res.data.data == 'No more post'){

                let btnLoadMore2 = document.querySelector('.btn-load-more');
                btnLoadMore2.parentNode.removeChild(btnLoadMore2);

                postsList.innerHTML += "<span class='w-full text-center my-5'>موردی پیدا نشد</span>";


            }

        }


    });

}

//------------ search ------------------
let btnSearch = document.querySelector('.btn-search-courses');
let inputSearch = document.querySelector('.input-search-courses'); 

btnSearch.addEventListener('click' , ()=>{
    let q = inputSearch.value ;
    let queryParams = new URLSearchParams(window.location.search);
    queryParams.set('q', q);

    history.replaceState(null, null, "?"+queryParams.toString());

    getData();


});



//----------------- sort --------------
let btnSortNewest = document.querySelector('#btn-sort-newest');
let btnSortCheapest = document.querySelector('#btn-sort-cheapest');
let btnSortExpensive = document.querySelector('#btn-sort-expensive');


btnSortNewest.addEventListener('click' , ()=>{
    let queryParams = new URLSearchParams(window.location.search);
    queryParams.delete('sort');
    queryParams.delete('p');
    history.replaceState(null, null, "?"+queryParams.toString());

    btnSortNewest.classList.add('bg-background-100' ,'dark:bg-darkBack-100');
    btnSortCheapest.classList.remove('bg-background-100', 'dark:bg-darkBack-100');
    btnSortExpensive.classList.remove('bg-background-100' ,'dark:bg-darkBack-100');

    getData();

});


btnSortCheapest.addEventListener('click' , ()=>{
    let queryParams = new URLSearchParams(window.location.search);
    queryParams.set('sort', 'cheapest');
    queryParams.delete('p');
    history.replaceState(null, null, "?"+queryParams.toString());

    btnSortNewest.classList.remove('bg-background-100', 'dark:bg-darkBack-100');
    btnSortCheapest.classList.add('bg-background-100' ,'dark:bg-darkBack-100');
    btnSortExpensive.classList.remove('bg-background-100', 'dark:bg-darkBack-100');

    getData();
});


btnSortExpensive.addEventListener('click' , ()=>{
    let queryParams = new URLSearchParams(window.location.search);
    queryParams.set('sort', 'expensive');
    queryParams.delete('p');
    history.replaceState(null, null, "?"+queryParams.toString());

    btnSortNewest.classList.remove('bg-background-100' ,'dark:bg-darkBack-100');
    btnSortCheapest.classList.remove('bg-background-100' ,'dark:bg-darkBack-100');
    btnSortExpensive.classList.add('bg-background-100', 'dark:bg-darkBack-100');

    getData();

});

//------------ cat ------------------
let allBttCat = document.querySelectorAll('.btn-cat');

allBttCat.forEach( function(btn){
    btn.addEventListener('click', function(event){
      let id = event.currentTarget.dataset.id;
      let cat_query_arr = [];

      let queryParams = new URLSearchParams(window.location.search);
      const cat_query = queryParams.get('cat');

      chanegStateCat(event);


      if(cat_query){

        cat_query_arr = cat_query.split(',');


        if(cat_query_arr.includes(id)){
            cat_query_arr = [...cat_query_arr].filter(item=>item !== id);
            chanegStateCat(event , false);
        }else{
            cat_query_arr.push(id);
            chanegStateCat(event , true);
        }

      }else{
        cat_query_arr.push(id);
        chanegStateCat(event , true);

      }


      cat_query_arr.length > 0  ? queryParams.set('cat', arrToText(cat_query_arr)) : queryParams.delete('cat');
      queryParams.delete('p');
      history.replaceState(null, null, "?"+queryParams.toString());


      getData();

  
    })
});


function chanegStateCat(event , state){

    let tagName = event.target.tagName;

    if(tagName == 'LI'){
        console.log(event.target)
        state ? event.target.firstElementChild.classList.add('bg-primary-100') : event.target.firstElementChild.classList.remove('bg-primary-100');
    }else{
        state ? event.target.parentNode.firstElementChild.classList.add('bg-primary-100') : event.target.parentNode.firstElementChild.classList.remove('bg-primary-100');
    }


}


function arrToText(arr){
    let text=''
    arr.forEach((element , index) => {
        text += element;
        if(index !== arr.length - 1){
            text += ',';
        }
    });
    return text;
} 


// ------- mobile filter ------
let btnMobileFilter = document.querySelector('#btn-mobile-filter');
btnMobileFilter.addEventListener('click' , function(){
    console.log(11)
    let mobileFilter = document.querySelector('#mobile-filter');
    mobileFilter.classList.remove('hidden');
    mobileFilter.classList.add('fixed' , 'w-screen' , 'h-screen');

});

let clodeMobileFilterAll = document.querySelectorAll('.close-mobile-filter');
clodeMobileFilterAll.forEach( function(btn){
    btn.addEventListener('click', function(){
        let mobileFilter = document.querySelector('#mobile-filter');
        mobileFilter.classList.add('hidden');
    });
});



//---------- mobile sort ------------
let btnMobileSort = document.querySelector('#btn-mobile-sort');
let mstSidebarSort = document.querySelector(".mst-sidebar-sort");
let mstSidebarBgSort = document.querySelector(".mst-sidebar-bg-sort");

btnMobileSort.addEventListener('click' , function(){
    mstSidebarBgSort.style.display = "block";
  setTimeout(()=>{
    mstSidebarSort.classList.remove('translate-y-full');
  } , 10);
})

let closeMobileSort = document.querySelector('.close-mobile-sort');
closeMobileSort.addEventListener('click', function(){
    closeSortMobaile();
});


function closeSortMobaile(){
    mstSidebarSort.classList.add('translate-y-full');
    setTimeout(()=>{
    mstSidebarBgSort.style.display = "none";
    } , 200);
}

let btnSortNewestMobile = document.querySelector('#btn-sort-newest-mobile');
let btnSortCheapestMobile = document.querySelector('#btn-sort-cheapest-mobile');
let btnSortExpensiveMobile = document.querySelector('#btn-sort-expensive-mobile');


btnSortNewestMobile.addEventListener('click' , ()=>{
    let queryParams = new URLSearchParams(window.location.search);
    queryParams.delete('sort');
    queryParams.delete('p');
    history.replaceState(null, null, "?"+queryParams.toString());

    btnSortNewestMobile.firstElementChild.classList.remove('hidden');
    btnSortCheapestMobile.firstElementChild.classList.add('hidden');
    btnSortExpensiveMobile.firstElementChild.classList.add('hidden');

    getData();
    closeSortMobaile();
    document.querySelector('#btn-mobile-sort-text').textContent = 'جدید ترین';

});


btnSortCheapestMobile.addEventListener('click' , ()=>{
    let queryParams = new URLSearchParams(window.location.search);
    queryParams.set('sort', 'cheapest');
    queryParams.delete('p');
    history.replaceState(null, null, "?"+queryParams.toString());

    btnSortNewestMobile.firstElementChild.classList.add('hidden');
    btnSortCheapestMobile.firstElementChild.classList.remove('hidden');
    btnSortExpensiveMobile.firstElementChild.classList.add('hidden');

    getData();
    closeSortMobaile();
    document.querySelector('#btn-mobile-sort-text').textContent = 'ارزان ترین';

});


btnSortExpensiveMobile.addEventListener('click' , ()=>{
    let queryParams = new URLSearchParams(window.location.search);
    queryParams.set('sort', 'expensive');
    queryParams.delete('p');
    history.replaceState(null, null, "?"+queryParams.toString());

    btnSortNewestMobile.firstElementChild.classList.add('hidden');
    btnSortCheapestMobile.firstElementChild.classList.add('hidden');
    btnSortExpensiveMobile.firstElementChild.classList.remove('hidden');

    getData();
    closeSortMobaile();
    document.querySelector('#btn-mobile-sort-text').textContent = 'گران ترین';


});
