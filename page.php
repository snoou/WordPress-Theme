<?php
get_header();
?>

<main style="padding: 40px 5%; max-width: 1200px; margin: 0 auto; min-height: 60vh;">
    <?php
    if (have_posts()):
        while (have_posts()):
            the_post();
            the_content();
        endwhile;
    endif;
    ?>
</main>

<?php
get_footer();
?>