<?php
    $lang = get_current_lang();
?>

        <!-- <img class="w-auto h-[400px]" src='<?php echo get_template_directory_uri().'/images/border.png'?>'/> -->
        <span>0</span>
    </div>

    </div>
</main>


<?php wp_footer(); ?>  

<script>
function dataViewer() {
  return {
    showMenu: <?php echo ( is_front_page() || is_home() ) ? 'false' : 'true'; ?>,
    selectedId: null,
    selectedData: null,
    loading: false,
    showIndexMenu: true,
    pageContent: null, // محتوای فعلی

    async fetchLink(link) {
      try {
        await new Promise((resolve) => {
          gsap.to('.home-menu-item', {
            opacity: 0,
            x: -24,
            duration: 0.35,
            ease: 'power2.out',
            stagger: 0.05,
            onComplete: resolve
          });
        });

        // 2️⃣ حذف منو از DOM
        this.showIndexMenu = false;

        // 3️⃣ فعال کردن حالت لودینگ
        this.loading = true;

        // 3️⃣ درخواست به لینک (فچ HTML کامل از سرور)
        const response = await fetch(link, {
         credentials: 'same-origin'
        });
        const html = await response.text();

        // 4️⃣ استخراج محتوای جدید از HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.querySelector('.content');

        // اگر .content در پاسخ پیدا شد، ذخیره‌اش کن
        if (newContent) {
          this.pageContent = newContent.innerHTML;
        } else {
          this.pageContent = '<p>محتوا پیدا نشد.</p>';
        }

        // 5️⃣ پنهان کردن لودینگ و نمایش محتوای جدید با انیمیشن GSAP
        this.loading = false;

        await this.$nextTick(); // صبر کن تا DOM جدید رندر بشه
        gsap.fromTo(
          '.content',
          { opacity: 0, y: 30 },
          { opacity: 1, y: 0, duration: 0.4, ease: 'power2.out' }
        );

        this.showMenu = true


      } catch (err) {
        console.log(err);
      } finally {
        //
      }
    },

  }
}
</script>

</body>

</html>