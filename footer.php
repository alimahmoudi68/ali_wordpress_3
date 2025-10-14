<?php
    $lang = get_current_lang();
?>
            </div>
          <img class="w-auto h-[350px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/>
        </div>
      </div>
    </div>
</main>


<?php wp_footer(); ?>  


<script>
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
      },
  
      
      // init() {
      //   if (typeof window.gsap === 'undefined') {
      //     console.warn('GSAP not loaded yet');
      //     return;
      //   }
      //   if (this.currentPage !== '/' && this.currentPage !== '/home' && this.currentPage !== '') {
      //     this.loadPage(this.currentPage);
      //   }
      // },



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


        async goTo(page , isFromHome = true) {
          this.initUrl = null;
          const homeEl = this.$refs.home;
          const flash = this.$refs.flash;

          // تایم‌لاین برای انیمیشن خروج منو
          const tl = gsap.timeline({
              onComplete: async () => {
                this.loading = true;
                await this.$nextTick();
                
                // // انیمیشن عرض content به اندازه عکس صفحه اصلی
                // this.animateContantWidth(500);

                // افکت نور
                gsap.to(flash, { opacity: 0.7, duration: 0.2, yoyo: true, repeat: 1 });

               
              try {
                const homeBase = "<?php echo esc_url( home_url( '/' ) ); ?>";
                const url = homeBase.replace(/\/+$/, '/') + String(page).replace(/^\/+/, '');
                const response = await fetch(url, { credentials: 'same-origin' });
                const html = await response.text();
                console.log('html' , html)
                const parser = new DOMParser();
                console.log('parser' , parser)
                const doc = parser.parseFromString(html, 'text/html');
                console.log('doc' , doc)
                const contentEl = doc.querySelector('.content');
                this.pageContent = contentEl ? contentEl.innerHTML : '';
                console.log('contentEl' , contentEl)
              } catch (err) {
                console.error('Failed to fetch /about content:', err);
                this.pageContent = '';
              }
              

              // نمایش لودینگ حداقل 5 ثانیه
              await new Promise(resolve => setTimeout(resolve, 2000));

              this.loading = false;

              // ابتدا عرض content را به full تغییر می‌دهیم
              this.animateContantWidth('full');
              
              // ۲ ثانیه صبر می‌کنیم تا انیمیشن عرض تمام شود
              await new Promise(resolve => setTimeout(resolve, 2000));

              this.currentPage = page;
              await this.$nextTick();

              const fullUrl = this.basePath.replace(/\/$/, '') + page;
              window.history.pushState({}, '', fullUrl);

              this.removeContentServerSide();

              gsap.fromTo(this.$refs[page], 
                { y: -200, opacity: 0, scale: 1 },
                { y: 0, opacity: 1, scale: 1, duration: 1.2, ease: "power3.out",
                  onComplete: async () => {
                    await this.$nextTick();
                  }
                }
              );

              this.showTopMenu = true
            }
          });

          // ابتدا دکمه‌ها را به سمت راست می‌بریم
          const buttons = homeEl.querySelectorAll('button');
          const spans = homeEl.querySelectorAll('span');
          
          tl.to([...buttons, ...spans], { 
            x: -50, 
            opacity: 0, 
            duration: 0.4, 
            ease: "power2.in",
            stagger: 0.1
          })
          // سپس کل دیو home را ناپدید می‌کنیم
          .to(homeEl, { 
            x: 0, 
            opacity: 0, 
            duration: 0.6, 
            ease: "power2.in" 
          }, "-=0.2");
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
            this.pageContent = contentEl ? contentEl.innerHTML : '';
            console.log('contentEl' , contentEl)
          } catch (err) {
            console.error('Failed to fetch page content:', err);
            this.pageContent = '';
          }

          // تغییر صفحه و غیرفعال کردن لودینگ
          this.currentPage = page;
          this.loading = false;
          await this.$nextTick();

          if(page == '/'){
            this.showTopMenu = false;
            this.animateContantWidth(500 , {duration : 0.1}); 
          }  

          const fullUrl = this.basePath.replace(/\/$/, '') + page;
          window.history.pushState({}, '', fullUrl);

          // انیمیشن ورود صفحه جدید
          gsap.fromTo(this.$refs[page], 
            { y: -200, opacity: 0, scale: 1 },
            { y: 0, opacity: 1, scale: 1, duration: 1.2, ease: "power3.out",
              onComplete: async () => {
                this.bordersClosed = false;
                await this.$nextTick();
              }
            }
          );
       
        },
    }
  }
</script>

</body>

</html>