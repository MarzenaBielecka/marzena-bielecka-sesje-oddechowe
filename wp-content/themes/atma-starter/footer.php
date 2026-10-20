</main>

<?php
$privacy_page_id = absint(get_option('wp_page_for_privacy_policy'));
$privacy_page = $privacy_page_id ? get_post($privacy_page_id) : null;
$privacy_url = $privacy_page
    && 'page' === $privacy_page->post_type
    && 'publish' === $privacy_page->post_status
        ? get_permalink($privacy_page)
        : '';
?>

<footer class="atma-footer">
    <div class="atma-container">
        <div class="atma-footer-bottom">
            <span>© <?php echo esc_html(wp_date('Y')); ?> Marzena Bielecka</span>
            <?php if ($privacy_url) : ?>
                <a href="<?php echo esc_url($privacy_url); ?>">Polityka prywatności</a>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>