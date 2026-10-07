<?php wp_footer(); ?>

</body>
</html>
<footer class="ns-footer">
    
    <!-- تزئینات گل یاس (عکس‌های بدون پس‌زمینه خود را جایگزین کنید) -->
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/yas-flower-right.png" alt="" class="footer-floral footer-floral-top-right" loading="lazy">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/yas-flower-left.png" alt="" class="footer-floral footer-floral-bottom-left" loading="lazy">
    
    <div class="footer-container">
        
        <!-- ستون اول: درباره فروشگاه و شبکه‌های اجتماعی -->
        <div class="footer-col footer-about">
            <h3 class="footer-logo">نسیم استایل</h3>
            <p class="footer-desc">
                نسیم استایل، پنجره‌ای به دنیای مد و زیبایی. ما با انتخاب بهترین و باکیفیت‌ترین متریال‌ها، استایلی متمایز و شیک را برای شما به ارمغان می‌آوریم. زیبایی شما، الهام‌بخش ماست.
            </p>
            
            <div class="footer-socials">
                <a href="#" aria-label="اینستاگرام" class="social-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                </a>
                <a href="#" aria-label="تلگرام" class="social-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </a>
                <a href="#" aria-label="واتساپ" class="social-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                </a>
            </div>
        </div>

        <!-- ستون دوم: دسترسی سریع -->
        <div class="footer-col footer-links">
            <h4 class="footer-title">دسترسی سریع</h4>
            <ul>
                <li><a href="<?php echo esc_url(site_url('/shop/')); ?>">فروشگاه</a></li>
                <li><a href="<?php echo esc_url(site_url('/blog/')); ?>">مجله استایل</a></li>
                <li><a href="<?php echo esc_url(site_url('/my-account/')); ?>">حساب کاربری من</a></li>
                <li><a href="<?php echo esc_url(site_url('/faq/')); ?>">سوالات متداول</a></li>
                <li><a href="<?php echo esc_url(site_url('/terms/')); ?>">قوانین و مقررات</a></li>
            </ul>
        </div>

        <!-- ستون سوم: اطلاعات تماس -->
        <div class="footer-col footer-contact">
            <h4 class="footer-title">ارتباط با ما</h4>
            <ul class="contact-list">
                <li>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    <span>تلفن: ۰۲۱-۱۲۳۴۵۶۷۸</span>
                </li>
                <li>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span>ایمیل: info@nasimstyle.com</span>
                </li>
                <li>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <span>آدرس: تهران، خیابان مد و فشن، مجتمع تجاری نسیم، پلاک ۱</span>
                </li>
                <li>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    <span>ساعت کاری: شنبه تا پنجشنبه (۹ صبح الی ۹ شب)</span>
                </li>
            </ul>
        </div>

        <!-- ستون چهارم: نماد اعتماد (ای‌نماد) -->
        <div class="footer-col footer-trust">
            <h4 class="footer-title">نماد اعتماد</h4>
            <div class="trust-symbols">
                <!-- جایگاه ای‌نماد -->
                <a href="#" class="enamad-box" aria-label="ای نماد">
                    <!-- عکس ای‌نماد خود را اینجا بگذارید -->
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/enamad-placeholder.png" alt="اینماد" loading="lazy">
                </a>
                
                <!-- جایگاه ساماندهی یا درگاه پرداخت -->
                <a href="#" class="enamad-box" aria-label="درگاه امن">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/zarinpal-placeholder.png" alt="درگاه پرداخت" loading="lazy">
                </a>
            </div>
        </div>

    </div>

    <!-- کپی‌رایت -->
    <div class="footer-copyright">
        <div class="copyright-container">
            <p>کلیه حقوق مادی و معنوی این سایت متعلق به <strong>نسیم استایل</strong> می‌باشد.</p>
            <p class="developer">طراحی و توسعه با ❤️</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>



</body>
</html>