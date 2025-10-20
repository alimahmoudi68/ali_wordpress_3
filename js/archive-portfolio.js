console.log('wpPortfolio' , wpPortfolio)
document.addEventListener("alpine:init" , ()=>{
    Alpine.data('dropdown' , ()=>({
    open:false ,
    close(){
        if(this.open){
            let filtersOld = this.filters;
            this.filtersTemp = filtersOld;
            this.filtersTemp = JSON.parse(JSON.stringify(this.filters)); 
            this.open = false;

        }
    },
    toggle(){
        let filtersOld = {...this.filters};
        this.filtersTemp = JSON.parse(JSON.stringify(this.filters)); 
        this.open = !this.open;
    },
    }));

    Alpine.data('allPost', () => ({
        url : wpPortfolio.url, 
        filterElementKey : wpPortfolio.filterElementKey,
        filtersTemp : wpPortfolio.filtersTemp ,
        filters : wpPortfolio.filters,
        allAttributes : wpPortfolio.allAttributes ,
        loading: false,
        page: 1,
        totalPage :  Math.ceil(wpPortfolio.totalPosts / wpPortfolio.limit),
        posts :   wpPortfolio.posts ,
        showDetail : false ,
        detailData : {} ,
        loaded: false,
        init() {
            this.loaded = true;
        },
        fetchPosts(query , p) {
            if (this.loading) return;
            this.loading = true;

            console.log('qq' , query)

            if(p == 1){
                this.posts = [];
            }else{
                query = `page=${p}${query?.length > 0 ?  '&' : ''}${query}`;
            }

            // Fetch data from the API
            fetch(`${wpPortfolio.url.replace('?', '')}?page=${p}${query !== "" ? '&'+query : ''}`)
                .then(response => response.json())
                .then(data => {
                    //console.log(data)
                    if(data?.portfolios?.length > 0){
                        if(p==1){
                            this.posts = data.portfolios;
                        }else{
                            this.posts.push(...data.portfolios);
                        }
                        this.page = p; 
                        
                        this.totalPage =  Math.ceil( data.total / wpPortfolio.limit ) ;
                        
                    }else{

                        this.totalPage = 0;
                    }

        
                    this.loading = false;

                    window.history.pushState({}, '', this.url+query);
                })
                .catch(error => {
                    console.error('Error fetching posts:', error);
                    this.loading = false;
                });
        },
        makeFilterUrl(nextPage){

            let filtersTempOld = {...this.filtersTemp};
            
            this.filters = filtersTempOld; 


            let filterArr = Object.keys(this.filters).map((key) => [key, this.filters[key]]);
            const result = [];

            filterArr.forEach(item => {
                const key = item[0];
                const values = item[1];

                if (values?.length > 0) { 
                    result.push(`${key}=${values.join(',')}`);
                }
            });

            let query =  result.join('&');
            // console.log(query)

            this.fetchPosts(query , nextPage);
            this.open = false;

        },
        filterToList(obj){

            const result = [];

            for (const key in obj) {
                if (obj.hasOwnProperty(key)) {
                    const values = obj[key];

                    values.forEach(value => {
                        result.push({ key: key, value: value });
                    });
                }
            }

            console.log('re' , result)

            return result;

        },
        isShowFilterElement(filterName , attributeName){

            if(attributeName.includes( this.filterElementKey[filterName] )){
                return true;
            }else{
                return false;
            }

        },
        updateFilters(id , type , isMakeFilterUrl = false) {

            console.log('>>>>>' ,type, id);

            if(this.filtersTemp[type].includes(id)){
                let oldFilters = this.filtersTemp ;
                oldFilters[type] = oldFilters[type].filter(item => item !== id);
                this.filtersTemp = oldFilters;

            }else{

                let oldFilters = this.filtersTemp ;
                oldFilters[type].push(id);
                this.filtersTemp = oldFilters;
            }

            if(isMakeFilterUrl){
                if(type=='priceMin' || type=='priceMax'){
                    $slider.value1 = this.filtersTemp.priceMin[0] ?? 0;
                    $slider.value2 = this.filtersTemp.priceMax[0] ?? 10000000;
                }
                this.makeFilterUrl(1);
            }

            console.log( 'this.filters', this.filters);
            console.log('filtersTemp' , this.filtersTemp);
            console.log('totalPage' , this.totalPage);

        },
        clearFilter(type) {
            console.log('t' , type) 
            let oldFiltersTemp = this.filtersTemp ;
            oldFiltersTemp[type] = [];
            console.log('oldFiltersTemp' , oldFiltersTemp)
            this.filtersTemp = oldFiltersTemp;
        },
        getAttribiteName(attribute , term){

            console.log('this.allAttributes' , this.allAttributes)
            if(attribute == 'priceMin'){
                return ('از قیمت' + ' ' + term);
            }else if(attribute == 'priceMax'){
                return ('تا قیمت' + ' ' + term);
            }else{
                let termObj = this.allAttributes[attribute].filter(item=>item.key == term);
                return termObj[0].value;
            }
        },
        checkScroll() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100) {
                if( !this.loading && (this.page < this.totalPage) ){
                    //this.makeFilterUrl((this.page)+1);
                }
            }
        },
        showDetailHandler(data){
            console.log(data)
            this.showDetail = true;
            this.detailData = data;
            document.querySelector('video').load();
        },
        closeDetailHandler(){
            this.showDetail = false;
            this.detailData = {};
        }
    }));
})

// filterElementKey = {
//     color : '' ,
//     size : ''
// }

// filter = {
//     color : [] ,
//     size : []
// }

// filter = {
//     color : [{key : 'core-i5' , value : 'پنج هسته'}] ,
//     size : []
// }


function allPost() {
    return {
        url : wpPortfolio.url, 
        filterElementKey : wpPortfolio.filterElementKey,
        filtersTemp : wpPortfolio.filtersTemp ,
        filters : wpPortfolio.filters,
        allAttributes : wpPortfolio.allAttributes ,
        loading: false,
        page: 1,
        totalPage :  Math.ceil(wpPortfolio.totalPosts / wpPortfolio.limit),
        posts :   wpPortfolio.posts ,
        totalPage : null,
        showDetail : false ,
        detailData : {} ,
        loaded: false,
        init() {

            //console.log('filterElementKey' , this.filterElementKey)

            this.loaded = true;
        },
        fetchPosts(query , p) {
            if (this.loading) return;
            this.loading = true;

            console.log('qq' , query)

            if(p == 1){
                this.posts = [];
            }else{
                query = `page=${p}${query?.length > 0 ?  '&' : ''}${query}`;
            }

            // Fetch data from the API
            fetch(`${wpPortfolio.url.replace('?', '')}?page=${p}${query !== "" ? '&'+query : ''}`)
                .then(response => response.json())
                .then(data => {
                    //console.log(data)
                    if(data?.portfolios?.length > 0){
                        if(p==1){
                            this.posts = data.portfolios;
                        }else{
                            this.posts.push(...data.portfolios);
                        }
                        this.page = p; 
                        
                        this.totalPage =  Math.ceil( data.total / wpPortfolio.limit ) ;
                        
                    }else{

                        this.totalPage = 0;
                    }

        
                    this.loading = false;

                    window.history.pushState({}, '', this.url+query);
                })
                .catch(error => {
                    console.error('Error fetching posts:', error);
                    this.loading = false;
                });
        },
        makeFilterUrl(nextPage){

            let filtersTempOld = {...this.filtersTemp};
            
            this.filters = filtersTempOld; 


            let filterArr = Object.keys(this.filters).map((key) => [key, this.filters[key]]);
            const result = [];

            filterArr.forEach(item => {
                const key = item[0];
                const values = item[1];

                if (values?.length > 0) { 
                    result.push(`${key}=${values.join(',')}`);
                }
            });

            let query =  result.join('&');
            // console.log(query)

            this.fetchPosts(query , nextPage);
            this.open = false;

        },
        filterToList(obj){

            const result = [];

            for (const key in obj) {
                if (obj.hasOwnProperty(key)) {
                    const values = obj[key];

                    values.forEach(value => {
                        result.push({ key: key, value: value });
                    });
                }
            }

            console.log('re' , result)

            return result;

        },
        isShowFilterElement(filterName , attributeName){

            if(attributeName.includes( this.filterElementKey[filterName] )){
                return true;
            }else{
                return false;
            }

        },
        updateFilters(id , type , isMakeFilterUrl = false) {

            console.log('>>>>>' ,type, id);

            if(this.filtersTemp[type].includes(id)){
                let oldFilters = this.filtersTemp ;
                oldFilters[type] = oldFilters[type].filter(item => item !== id);
                this.filtersTemp = oldFilters;

            }else{

                let oldFilters = this.filtersTemp ;
                oldFilters[type].push(id);
                this.filtersTemp = oldFilters;
            }

            if(isMakeFilterUrl){
                if(type=='priceMin' || type=='priceMax'){
                    $slider.value1 = this.filtersTemp.priceMin[0] ?? 0;
                    $slider.value2 = this.filtersTemp.priceMax[0] ?? 10000000;
                }
                this.makeFilterUrl(1);
            }

            console.log( 'this.filters', this.filters);
            console.log('filtersTemp' , this.filtersTemp);
            console.log('totalPage' , this.totalPage);

        },
        clearFilter(type) {
            console.log('t' , type) 
            let oldFiltersTemp = this.filtersTemp ;
            oldFiltersTemp[type] = [];
            console.log('oldFiltersTemp' , oldFiltersTemp)
            this.filtersTemp = oldFiltersTemp;
        },
        getAttribiteName(attribute , term){

            console.log('this.allAttributes' , this.allAttributes)
            if(attribute == 'priceMin'){
                return ('از قیمت' + ' ' + term);
            }else if(attribute == 'priceMax'){
                return ('تا قیمت' + ' ' + term);
            }else{
                let termObj = this.allAttributes[attribute].filter(item=>item.key == term);
                return termObj[0].value;
            }
        },
        checkScroll() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100) {
                if( !this.loading && (this.page < this.totalPage) ){
                    //this.makeFilterUrl((this.page)+1);
                }
            }
        },
        showDetailHandler(data){
            console.log(data)
            this.showDetail = true;
            this.detailData = data;
            document.querySelector('video').load();
        },
        closeDetailHandler(){
            this.showDetail = false;
            this.detailData = {};
        }
    };
}
