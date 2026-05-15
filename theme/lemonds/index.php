<?php
/**
 * Generic fallback template
 *
 * 個別テンプレ（front-page.php / page-*.php / archive-*.php / single-*.php）が
 * マッチしなかった場合のフォールバック。
 *
 * @package lemonds
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="lm-main lm-main--fallback">
    <div class="lm-inner">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    </header>
                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>

            <?php
            the_posts_pagination([
                'mid_size'  => 1,
                'prev_text' => __('前へ', 'lemonds'),
                'next_text' => __('次へ', 'lemonds'),
            ]);
            ?>
        <?php else : ?>
            <section class="no-results">
                <h1><?php esc_html_e('コンテンツが見つかりませんでした', 'lemonds'); ?></h1>
                <p><?php esc_html_e('お探しのページは存在しないか、移動した可能性があります。', 'lemonds'); ?></p>
            </section>
        <?php endif; ?>
    </div>
</main>
<?php
get_template_part('template-parts/contact-cta');
get_footer();
