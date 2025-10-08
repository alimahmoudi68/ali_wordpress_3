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


      animateContantWidth(target, options = {}) {
        const el = document.querySelector('.content');
        if (!el) {
          console.warn('Element with class "content" not found');
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

        async goTo(page) {
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

              // فچ داده‌ها
              let res;
              if (page === 'page2') {
                res = await fetch('https://jsonplaceholder.typicode.com/posts?_limit=5');
              } else {
                res = await fetch('https://jsonplaceholder.typicode.com/users?_limit=5');
              }
              this.data = await res.json();

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
                    this.bordersClosed = false;
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

      backToHome() {
        const page = this.currentPage;
        const left = document.querySelector('.border-left');
        const right = document.querySelector('.border-right');
        const flash = this.$refs.flash;

        const tl = gsap.timeline({
          onComplete: () => {
            this.currentPage = 'home';
            this.data = [];
          }
        });

        tl.to(this.$refs[page], { x: 150, opacity: 0, scale: 0.9, duration: 0.6, ease: "power2.in" })
          .to([left, right], 
              { x: (i) => (i === 0 ? "+=60" : "-=60"), duration: 0.8, ease: "elastic.out(1, 0.6)" }, 
              "-=0.3")
          .fromTo(this.$refs.home, 
              { x: -120, opacity: 0 }, 
              { x: 0, opacity: 1, duration: 0.8, ease: "back.out(1.7)",
                onComplete: () => {
                  this.bordersClosed = false;
                }
              },
              "-=0.2")
          .to(flash, { opacity: 0.4, duration: 0.15, yoyo: true, repeat: 1 }, "-=0.5");
      }
    }
  }
</script>

</body>

</html>