<div class="accordion-content clear">
    <div class="accordion-item-title">
        <?php
        if (get_sub_field('item_title_description')) {
        ?>
        <div class="accordion-float-left">
            <p class="accordion-item-title-description">
                <?php the_sub_field('item_title_description'); ?>
            </p>
        <?php } ?>
        <span class="accordion-item-title-wrap">
            <?php the_sub_field('item_title'); ?>
        </span>
        <?php if (get_sub_field('item_title_description')) { ?>
            </div>
        <?php } ?>
        <span class="accordion-button-icon fa fa-plus"></span>
    </div>
    <div class="accordion-item-text">
        <div class="accordion-item-text-wrap"><span><?php the_sub_field('item_text'); ?></span></div>
    </div>
</div>
