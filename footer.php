<?php
    $lang = get_current_lang();
?>
            </div>
          <img class="w-auto h-[550px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
        </div>
      </div>
    </div>
</main>


<?php wp_footer(); ?>  


<script>
  // تابع برای ری‌استارت کردن Alpine.js بعد از AJAX
  async function reinitializeAlpineAfterAjax(doc) {
    if (window.Alpine) {
      console.log('🔁 Reinitializing Alpine after AJAX load...');
      
      // اجرای مجدد اسکریپت‌های Alpine که ممکن است در محتوای جدید باشند
      const alpineScripts = doc.querySelectorAll('script');
      alpineScripts.forEach(script => {
        const scriptContent = script.textContent || '';
        if (scriptContent.includes('Alpine.data') || scriptContent.includes('alpine:init')) {
          try {
            eval(scriptContent);
            console.log('✅ Alpine script executed:', scriptContent.substring(0, 100) + '...');
          } catch (err) {
            console.warn('⚠️ Error executing Alpine script:', err);
          }
        }
      });
      
      // توقف مشاهده تغییرات DOM فعلی
      if (Alpine.stopObservingMutations) {
        Alpine.stopObservingMutations();
      }
      
      // شروع مجدد Alpine برای شناسایی کامپوننت‌های جدید
      Alpine.start();
      
      // اطمینان از اینکه کامپوننت‌های جدید شناسایی شده‌اند
      await new Promise(resolve => setTimeout(resolve, 100));
    }
  }

  // تابع برای اجرای wpPortfolio script از AJAX
  function executeWpPortfolioScript(doc) {
    try {
      let localizedScript = doc.querySelector('#wpPortfolio-data');

      if (!localizedScript) {
        const scripts = Array.from(doc.querySelectorAll('script'));
        localizedScript = scripts.find(s => {
          const txt = (s.textContent || '').trim();
          return (
            txt.length > 0 &&
            (txt.includes('window.wpPortfolio') ||
              txt.includes('var wpPortfolio') ||
              /wpPortfolio\s*=/.test(txt))
          );
        });
      }

      if (localizedScript) {
        eval(localizedScript.textContent);
        console.log('✅ wpPortfolio loaded from AJAX:', window.wpPortfolio);
      } else {
        console.warn('⚠️ No wpPortfolio script found in fetched HTML.');
      }
    } catch (err) {
      console.error('Error evaluating wpPortfolio script:', err);
    }
  }

  // تعریف global Alpine.js کامپوننت‌ها برای استفاده در AJAX
  document.addEventListener("alpine:init", () => {
    Alpine.data('dropdown', () => ({
      open: false,
      close() {
        if (this.open) {
          let filtersOld = this.filters;
          this.filtersTemp = filtersOld;
          this.filtersTemp = JSON.parse(JSON.stringify(this.filters)); 
          this.open = false;
        }
      },
      toggle() {
        let filtersOld = {...this.filters};
        this.filtersTemp = JSON.parse(JSON.stringify(this.filters)); 
        this.open = !this.open;
      },
    }));

    Alpine.data('allPost', () => ({
      url: window.wpPortfolio?.url || '', 
      filterElementKey: window.wpPortfolio?.filterElementKey || {},
      filtersTemp: window.wpPortfolio?.filtersTemp || {},
      filters: window.wpPortfolio?.filters || {},
      allAttributes: window.wpPortfolio?.allAttributes || {},
      loading: false,
      page: 1,
      totalPage: window.wpPortfolio ? Math.ceil(window.wpPortfolio.totalPosts / window.wpPortfolio.limit) : 0,
      posts: window.wpPortfolio?.posts || [],
      showDetail: false,
      detailData: {},
      loaded: false,
      init() {
        this.loaded = true;
      },
      fetchPosts(query, p) {
        if (this.loading) return;
        this.loading = true;

        console.log('qq', query);

        if (p == 1) {
          this.posts = [];
        } else {
          query = `page=${p}${query?.length > 0 ? '&' : ''}${query}`;
        }

        // Fetch data from the API
        fetch(`${this.url.replace('?', '')}?page=${p}${query !== "" ? '&' + query : ''}`)
          .then(response => response.json())
          .then(data => {
            if (data?.portfolios?.length > 0) {
              if (p == 1) {
                this.posts = data.portfolios;
              } else {
                this.posts.push(...data.portfolios);
              }
              this.page = p; 
              this.totalPage = Math.ceil(data.total / window.wpPortfolio.limit);
            } else {
              this.totalPage = 0;
            }
            this.loading = false;
            window.history.pushState({}, '', this.url + query);
          })
          .catch(error => {
            console.error('Error fetching posts:', error);
            this.loading = false;
          });
      },
      makeFilterUrl(nextPage) {
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

        let query = result.join('&');
        this.fetchPosts(query, nextPage);
        this.open = false;
      },
      filterToList(obj) {
        const result = [];
        for (const key in obj) {
          if (obj.hasOwnProperty(key)) {
            const values = obj[key];
            values.forEach(value => {
              result.push({ key: key, value: value });
            });
          }
        }
        return result;
      },
      isShowFilterElement(filterName, attributeName) {
        if (attributeName.includes(this.filterElementKey[filterName])) {
          return true;
        } else {
          return false;
        }
      },
      updateFilters(id, type, isMakeFilterUrl = false) {
        console.log('>>>>>', type, id);

        if (this.filtersTemp[type].includes(id)) {
          let oldFilters = this.filtersTemp;
          oldFilters[type] = oldFilters[type].filter(item => item !== id);
          this.filtersTemp = oldFilters;
        } else {
          let oldFilters = this.filtersTemp;
          oldFilters[type].push(id);
          this.filtersTemp = oldFilters;
        }

        if (isMakeFilterUrl) {
          this.makeFilterUrl(1);
        }
      },
      clearFilter(type) {
        console.log('t', type);
        let oldFiltersTemp = this.filtersTemp;
        oldFiltersTemp[type] = [];
        this.filtersTemp = oldFiltersTemp;
      },
      getAttribiteName(attribute, term) {
        if (attribute == 'priceMin') {
          return ('از قیمت' + ' ' + term);
        } else if (attribute == 'priceMax') {
          return ('تا قیمت' + ' ' + term);
        } else {
          let termObj = this.allAttributes[attribute].filter(item => item.key == term);
          return termObj[0].value;
        }
      },
      showDetailHandler(data) {
        console.log(data);
        this.showDetail = true;
        this.detailData = data;
        document.querySelector('video').load();
      },
      closeDetailHandler() {
        this.showDetail = false;
        this.detailData = {};
      }
    }));
  });

  function pageController() {
    return {
      basePath : "<?php echo esc_js( parse_url( home_url(), PHP_URL_PATH ) ); ?>",
      showTopMenu: <?php echo ( is_front_page() || is_home() ) ? 'false' : 'true'; ?>,
      initUrl: "<?php echo esc_js( str_replace( parse_url( home_url(), PHP_URL_PATH ), '', $_SERVER['REQUEST_URI'] ) ); ?>",
      currentPage: null,
      loading: false,
      data: [],
      pageContent: '',

      init() {
        console.log('currentPage' , this.currentPage)
        console.log('initUrl' , this.initUrl)
        
        // اگر صفحه اصلی نیست، عرض را کامل می‌کنیم
        if (this.initUrl !== '/' && this.initUrl !== null && this.initUrl !== '') {
          this.showTopMenu = true;
          this.animateContantWidth('full', {duration: 0.1});
        }
      },



      animateContantWidth(target, options = {}) {
        const el = document.querySelector('.contentContainer');
        if (!el) {
          console.warn('Element with class "contentContainer" not found');
          return false;
        }

        const resolveWidth = (val) => {
          if (typeof val === 'number' && isFinite(val)) return val + 'px';
          if (typeof val === 'string') {
            const v = val.trim().toLowerCase();
            if (v === 'full' || v === '100%' || v === 'fullwidth') return '100%';
            const n = parseFloat(v);
            if (!isNaN(n)) return n + 'px';
          }
          return null;
        };

        const width = resolveWidth(target);
        if (!width) {
          console.warn('Invalid width value:', target);
          return false;
        }

        const duration = typeof options.duration === 'number' ? options.duration : 0.9;
        const ease = 'linear';

        console.log('Animating content width to:', width);

        if (window.gsap && typeof window.gsap.to === 'function') {
          window.gsap.to(el, { 
            width, 
            duration, 
            ease, 
            overwrite: 'auto',
            onComplete: () => console.log('Animation completed')
          });
        } else {
          el.style.transition = `width ${duration}s linear`;
          requestAnimationFrame(() => { 
            el.style.width = width;
            console.log('CSS transition applied');
          });
        }

        return true;
      },
      contentWidthFull(){
        const el = document.querySelector('.contentContainer');
        if (el) {
          el.style.width ='100%';
        }
      },
      removeContentServerSide(){
        let contentServerRender = document.querySelector('.content');
        if(contentServerRender){
          contentServerRender.remove();
        }
      },

      // تابع کمکی برای پیدا کردن element با مکانیزم fallback
      findElement(refName, selectors = []) {
        let element = this.$refs[refName];
        
        if (!element) {
          // لیست پیش‌فرض selectors برای جستجو
          const defaultSelectors = [
            `[x-ref="${refName}"]`,
            `#${refName}`,
            `.${refName}`,
            `[data-${refName}]`
          ];
          
          const allSelectors = [...defaultSelectors, ...selectors];
          
          for (const selector of allSelectors) {
            element = document.querySelector(selector);
            if (element) break;
          }
        }
        
        return element;
      },

      async loadPageContent(page) {
        try {
          // === مرحله ۱: گرفتن محتوای صفحه با AJAX ===
          const homeBase = "<?php echo esc_url( home_url( '/' ) ); ?>";
          const url = homeBase.replace(/\/+$/, '/') + String(page).replace(/^\/+/, '');
          const response = await fetch(url, { credentials: 'same-origin' });
          const html = await response.text();
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');

          // === مرحله ۲: گرفتن محتوای اصلی ===
          const contentEl = doc.querySelector('.content');
          const fetchedContent = contentEl ? contentEl.innerHTML : '';
          console.log('✅ Fetched content:', contentEl);

          // === مرحله ۳: اجرای wpPortfolio از اسکریپت صفحه‌ی جدید (در صورت وجود) ===
          executeWpPortfolioScript(doc);

          // === مرحله ۴: ری‌استارت کردن Alpine (اگر لازم باشد) ===
          await this.$nextTick();
          await reinitializeAlpineAfterAjax(doc);

          // === مرحله ۵: نمایش لودینگ و انیمیشن ===
          this.loading = false;

          // شروع انیمیشن عرض صفحه
          if(page == '/'){
            this.showTopMenu = false;
            this.animateContantWidth(500 , {duration : 0.1}); 
          } else {
            this.animateContantWidth('full');
            // صبر کردن تا انیمیشن عرض کامل شود
            await new Promise(resolve => setTimeout(resolve, 1500));
          }

          // === مرحله ۶: آپدیت URL و حذف محتوای سرور ===
          const fullUrl = this.basePath.replace(/\/$/, '') + page;
          window.history.pushState({}, '', fullUrl);
          this.removeContentServerSide();

          // === مرحله ۷: انیمیشن ورود صفحه جدید ===
          // ابتدا صفحه را تنظیم می‌کنیم اما محتوا را نمایش نمی‌دهیم
          this.pageContent = '';
          this.currentPage = page;
          await this.$nextTick();

          // حالا انیمیشن را شروع می‌کنیم
          gsap.fromTo(
            this.$refs[page],
            { y: -200, opacity: 0, scale: 1 },
            {
              y: 0,
              opacity: 1,
              scale: 1,
              duration: 1.2,
              ease: 'power3.out',
              onStart: () => {
                // فقط زمانی که انیمیشن شروع می‌شود، محتوا را نمایش می‌دهیم
                this.pageContent = fetchedContent;
              },
              onComplete: async () => {
                await this.$nextTick();
              }
            }
          );

          this.showTopMenu = true;

        } catch (err) {
          console.error('❌ Failed to fetch content:', err);
          this.pageContent = '';
          this.loading = false;
        }
      },


      async goTo(page, isFromHome = true) {
  this.initUrl = null;
  
  // پیدا کردن homeEl با استفاده از تابع کمکی
  const homeEl = this.findElement('home', ['.home-content', '.main-content']);
  
  if (!homeEl) {
    console.warn('⚠️ homeEl not found, skipping home animation');
    // اگر homeEl پیدا نشد، مستقیماً به مرحله بعد برویم
    this.loading = true;
    await this.$nextTick();
    await this.loadPageContent(page);
    return;
  }
  
  // پیدا کردن flash element با استفاده از تابع کمکی
  const flash = this.findElement('flash');




  // تایم‌لاین برای انیمیشن خروج منو
  const tl = gsap.timeline({
    onComplete: async () => {
      this.loading = true;
      await this.$nextTick();

      // افکت فلش نور
      if (flash) {
        gsap.to(flash, { opacity: 0.7, duration: 0.2, yoyo: true, repeat: 1 });
      }

      // استفاده از تابع loadPageContent برای کاهش تکرار کد
      await this.loadPageContent(page);
    }
  });

  // === مرحله ۰: انیمیشن خروج صفحه اصلی ===
  // اطمینان از وجود homeEl قبل از استفاده
  if (!homeEl) {
    console.error('❌ homeEl is still null, cannot proceed with animation');
    return;
  }
  
  const buttons = homeEl.querySelectorAll('button');
  const spans = homeEl.querySelectorAll('span');

  tl.to([...buttons, ...spans], {
    x: -50,
    opacity: 0,
    duration: 0.4,
    ease: 'power2.in',
    stagger: 0.1
  })
    .to(
      homeEl,
      {
        x: 0,
        opacity: 0,
        duration: 0.6,
        ease: 'power2.in'
      },
      '-=0.2'
    );
},



        async loadPage(page) {
          this.initUrl = null;
          // شروع انیمیشن خروج صفحه فعلی - محو شدن به سمت بالا
          if(this.currentPage){

            gsap.fromTo(this.$refs[this.currentPage], 
              { y: 0, opacity: 1, scale: 1 },
              { y: -200, opacity: 0, scale: 1, duration: 1.2, ease: "power3.in",
                onComplete: async () => {
                  this.loading = true;
                  await this.$nextTick();
                }
              }
            );
            
          }else{

            const contentEl = document.querySelector('.content');
            gsap.fromTo(
              contentEl,
              { y: 0, opacity: 1, scale: 1 },
              {
                y: -200,
                opacity: 0,
                scale: 1,
                duration: 1.2,
                ease: "power3.in",
                onComplete: async () => {
                  this.loading = true;
                  await this.$nextTick();
                }
              }
            );

          }

          // صبر می‌کنیم تا انیمیشن خروج تمام شود
          await new Promise(resolve => setTimeout(resolve, 1200));

          try {
            const homeBase = "<?php echo esc_url( home_url( '/' ) ); ?>";
            const url = homeBase.replace(/\/+$/, '/') + String(page).replace(/^\/+/, '');
            
            let contentServerRender = document.querySelector('.content');
            if(contentServerRender){
              this.contentWidthFull();
              contentServerRender.remove();
            }
            const response = await fetch(url, { credentials: 'same-origin' });
            const html = await response.text();
            console.log('html' , html)
            const parser = new DOMParser();
            console.log('parser' , parser)
            const doc = parser.parseFromString(html, 'text/html');
            console.log('doc' , doc)
            const contentEl = doc.querySelector('.content');
            const fetchedContent = contentEl ? contentEl.innerHTML : '';
            console.log('contentEl' , contentEl)

            // === اجرای wpPortfolio از اسکریپت صفحه‌ی جدید (در صورت وجود) ===
            executeWpPortfolioScript(doc);

            // === ری‌استارت کردن Alpine (اگر لازم باشد) ===
            await this.$nextTick();
            await reinitializeAlpineAfterAjax(doc);

            // غیرفعال کردن لودینگ
            this.loading = false;

            if(page == '/'){
              this.showTopMenu = false;
              this.animateContantWidth(480 , {duration : 0.1}); 
            } else {
              // برای صفحات دیگر، ابتدا عرض را کامل می‌کنیم
              this.animateContantWidth('full');
              await new Promise(resolve => setTimeout(resolve, 1500));
            }

            const fullUrl = this.basePath.replace(/\/$/, '') + page;
            window.history.pushState({}, '', fullUrl);

            // انیمیشن ورود صفحه جدید
            // ابتدا صفحه را تنظیم می‌کنیم اما محتوا را نمایش نمی‌دهیم
            this.pageContent = '';
            this.currentPage = page;
            await this.$nextTick();

            // حالا انیمیشن را شروع می‌کنیم
            gsap.fromTo(this.$refs[page], 
              { y: -200, opacity: 0, scale: 1 },
              { y: 0, opacity: 1, scale: 1, duration: 1.2, ease: "power3.out",
                onStart: () => {
                  // فقط زمانی که انیمیشن شروع می‌شود، محتوا را نمایش می‌دهیم
                  this.pageContent = fetchedContent;
                },
                onComplete: async () => {
                  this.bordersClosed = false;
                  await this.$nextTick();
                }
              }
            );

          } catch (err) {
            console.error('Failed to fetch page content:', err);
            this.pageContent = '';
            this.loading = false;
          }
       
        },
    }
  }
</script>

</body>

</html>