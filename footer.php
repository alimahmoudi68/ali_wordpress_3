<?php
    $lang = get_current_lang();
?>


    </div>
</main>


<?php wp_footer(); ?>  


<script>
  function pageController() {
    return {
      showTopMenu: <?php echo ( is_front_page() || is_home() ) ? 'false' : 'true'; ?>,
      currentPage: 'home',
      loading: false,
      data: [],
      pageContent: '',


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
        const ease = options.ease || 'power3.inOut';

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
          el.style.transition = `width ${duration}s ease`;
          requestAnimationFrame(() => { 
            el.style.width = width;
            console.log('CSS transition applied');
          });
        }

        return true;
      },


        async goTo(page , isFromHome = true) {
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
          // شروع انیمیشن خروج صفحه فعلی - محو شدن به سمت بالا
          gsap.fromTo(this.$refs[this.currentPage], 
            { y: 0, opacity: 1, scale: 1 },
            { y: -200, opacity: 0, scale: 1, duration: 1.2, ease: "power3.in",
              onComplete: async () => {
                this.loading = true;
                await this.$nextTick();
              }
            }
          );

          // صبر می‌کنیم تا انیمیشن خروج تمام شود
          await new Promise(resolve => setTimeout(resolve, 1200));

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
            console.error('Failed to fetch page content:', err);
            this.pageContent = '';
          }

          // تغییر صفحه و غیرفعال کردن لودینگ
          this.currentPage = page;
          this.loading = false;
          await this.$nextTick();

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

        loadHomePage(){
          this.currentPage = 'home';
          this.showTopMenu = false;
          this.pageContent ='';
          this.animateContantWidth(500);        
        }
    }
  }
</script>

</body>

</html>