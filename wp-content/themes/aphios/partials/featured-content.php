<?php
add_image_size('feature', 800, 600, true);
$image1 = get_sub_field('item1_image');
$image2 = get_sub_field('item2_image');
$size = 'feature';
?>
<!--  begin item loop  -->
<div class="featured-content">
    <div class="one-half first">';
        <a href="<?php the_sub_field('item1_link'); ?>">
            <div class="featured-content-item">';
                <?php echo wp_get_attachment_image($image1, $size); ?>
                <div class="item-text">
                    <div class="text-wrap">
                        <h3><?php the_sub_field('item1_title'); ?></h3>
                        <button>Learn More</button>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="one-half">';
        <a href="<?php the_sub_field('item2_link') ?>">
            <div class="featured-content-item">';
                <?php echo wp_get_attachment_image($image2, $size); ?>
                <div class="item-text">
                    <div class="text-wrap">
                        <h3><?php the_sub_field('item2_title'); ?></h3>
                        <button>Learn More</button>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>