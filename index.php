<?php
// این تابع هدر شما را به صفحه می‌آورد
get_header();
?>

<main style="padding: 40px 5%; max-width: 1200px; margin: 0 auto; min-height: 60vh;">
    <?php
    // این حلقه محتوای صفحه (مانند سبد خرید، تسویه حساب یا حساب کاربری) را رندر می‌کند
    if (have_posts()):
        while (have_posts()):
            the_post();
            the_content();
        endwhile;
    endif;
    ?>
</main>

<?php
// این تابع فوتر را به همراه تمام استایل‌ها، اسکریپت‌ها و ساختار استاندارد می‌آورد
get_footer();
?>