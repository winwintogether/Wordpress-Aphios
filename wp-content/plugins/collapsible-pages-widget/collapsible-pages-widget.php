<?php
/**
 * Plugin Name: Collapsible Pages List Widget
 * Description: Collapsible pages list widget
 * Version: 1.1.2
 * Author: XinHongLee
 */
namespace xinhonglee;

defined("ABSPATH") or die("No script kiddies please!");
require dirname(__FILE__) . "/Node.php";

// Some utilities
function pluck_keys(array $arr, array $keys)
{
    $result = array();
    foreach ($arr as $key => $value) {
        if (in_array($key, $keys)) {
            $result[$key] = $value;
        }
    }
    return $result;
}

/**
 * Actual Widget class
 * @author XinHongLee
 */
class CollapsiblePagesWidget extends \WP_Widget
{
    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        parent::__construct(
            "collapsible_pages_widget",
            "Collapsible Pages",
            array("description" => "For creating a list of pages that is collapsible")
        );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        echo $args["before_widget"];
        if (!empty($instance["title"])) {
            echo $args["before_title"] . apply_filters("widget_title", $instance["title"]) . $args["after_title"];
        }
        $pages = $this->get_pages_recursive(0, array(
            "exclude" => $instance["exclude"],
            "sort_column" => $instance["sort_column"],
            "sort_order" => $instance["sort_order"]
        ));

        echo $this->print_pages_recursive($pages, array(
                "show_threshold" => 0,
                "color" => $instance["color"]
            )
        )->toHtml(true);
        $expand_to_id = get_the_ID();
        echo <<<HERE
            <script>
                jQuery(document).on("collapsible_pages_ready",function(){
                    expand_to_page($expand_to_id)
                });
            </script>
HERE;
        echo $args["after_widget"];
    }

    /**
     * Helper function for creating form options
     * @param array $instance The Widget instance options array
     * @param string $name
     * @param array $opts Should containt 'default' key for default value
     * @return array with [id, name, value] keys
     */
    private function input_opts($instance, $name, $opts)
    {
        return array(
            "id" => $this->get_field_id($name),
            "name" => $this->get_field_name($name),
            "value" => esc_attr(isset($instance[$name]) ? $instance[$name] : $opts['default'])
        );
    }

    /**
     * Create a input form from a class, label, and a "model"
     * @param  string $class [description]
     * @param  string $label [description]
     * @param  array $model Model of the form with keys [id, name, value]
     * @return Node          The Form as a Node (div{label, input})
     */
    private function form_input($class, $labelText, array $model)
    {
        $div = new Node("div");
        $div->addClass($class);

        $label = new Node("label");
        $label->addAttribute("for", $model["id"]);
        $label->addText($labelText);

        $div->addChild($label);

        $input = new Node("input");
        $input->addAttributes($model);
        $input->addClass("widefat");

        $div->addChild($input);

        return $div;
    }

    /**
     * Create a select form from a class, label, and a "model"
     * @param  string $class [description]
     * @param  string $label [description]
     * @param  array $model Model of the form with keys [id, name, value]
     * @return Node          The Form as a Node (select is wrapped in a div)
     */
    private function form_select($class, $labelText, array $model)
    {
        $div = new Node("div");
        $div->addClass($class);

        $label = new Node("label");
        $label->addAttribute("for", $model["id"]);
        $label->addText($labelText);
        $div->addChild($label);

        $select = new Node("select");
        $whitelist = array("name", "id", "value");
        $select->setAttributes(pluck_keys($model, $whitelist));
        $select->addClass("widefat");

        foreach ($model["options"] as $option) {
            $optNode = new Node("option");
            $optNode->setAttribute("value", $option[0]);
            $optNode->addText($option[1]);
            if ($option[0] === $model["value"]) {
                $optNode->setAttribute("selected", "");
            }
            $select->addChild($optNode);
        }

        $div->addChild($select);
        return $div;
    }

    public function form($instance)
    {
        $title = $this->input_opts($instance, "title", array("default" => "Pages"));
        $color = $this->input_opts($instance, "color", array("default" => "#000"));
        $exclude = $this->input_opts($instance, "exclude", array("default" => ""));

        // sort column "model"
        $sort_options = array(
            array("post_title", "Title"),
            array("menu_order", "Page order")
        );
        $sort_column = $this->input_opts($instance, "sort_column", array(
            "default" => $sort_options[0][0]
        ));
        $sort_column["options"] = $sort_options;

        // sort order "model"
        $order_options = array(
            array("asc", "Ascending"),
            array("desc", "Descending")
        );
        $sort_order = $this->input_opts($instance, "sort_order", array("default" => $order_options[0][0]));
        $sort_order["options"] = $order_options;

        $title_form = $this->form_input("cpw-title", "Title:", $title);
        $color_form = $this->form_input("cpw-color", "Color:", $color);

        // make color_form label display:block
        $color_form->getChild(0)->setAttribute("style", "display:block;");

        $exclude_form = $this->form_input("cpw-exclude", "Exclude:",
            $exclude + array("placeholder" => "Comma separated list of ids"));

        $sort_column_form = $this->form_select("cpw-sort_column", "Sort by:", $sort_column);
        $sort_order_form = $this->form_select("cpw-sort_order", "Order by:", $sort_order);
        ?>
        <!-- Script for color picker  -->
        <script>
            (function ($) {
                $(function () {
                    $("#<?php echo $color['id'] ?>").wpColorPicker();
                });
            })(jQuery);
        </script>
        <!-- Actual options markup -->
        <p>
            <?php echo $title_form->toHtml(true) ?>
            <?php echo $color_form->toHtml(true) ?>
            <?php echo $exclude_form->toHtml(true) ?>
            <?php echo $sort_column_form->toHtml(true) ?>
            <?php echo $sort_order_form->toHtml(true) ?>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance["title"] = (!empty($new_instance["title"])) ? strip_tags($new_instance["title"]) : "";
        $instance["color"] = (!empty($new_instance["color"])) ? strip_tags($new_instance["color"]) : "";
        $instance["exclude"] = (!empty($new_instance["exclude"])) ? strip_tags($new_instance["exclude"]) : "";
        $instance["sort_column"] = $new_instance["sort_column"];
        $instance["sort_order"] = $new_instance["sort_order"];
        return $instance;
    }

    private function get_pages_with_parent($id, $options = array())
    {
        global $wpdb;
        $pages = get_pages(array_merge(array(
            "parent" => $id
        ), $options));
        return $pages;
    }

    private function get_pages_recursive($id = 0, $options = array())
    {
        $pages = $this->get_pages_with_parent($id, $options);

        foreach ($pages as $page) {
            $children = $this->get_pages_recursive($page->ID, $options);
            if (count($children) > 0)
                $page->children = $children;
        }
        return $pages;
    }

    private function print_pages_recursive($pages, $options = array(), $level = 0)
    {
        $ul = new Node("ul");
        $options = array_merge(array(
            "show_threshold" => 0,
            "color" => "#000"
        ), $options);
        if ($level > $options["show_threshold"])
            $ul->addClass("hidden");

        foreach ($pages as $page) {
            $li = new Node("li", array(
                "class" => array("page_item", "page-item-" . $page->ID),
                "data-page-id" => $page->ID
            ));
            $ul->addChild($li);
            $toggle_item = new Node("div", array("class" => "toggle-item"));
            $li->addChild($toggle_item);
            $a = new Node("a", array(
                    "href" => get_page_link($page->ID)
                )
            );
            $a->addText($page->post_title);
            $li->addChild($a);
            if (isset($page->children)) {
                $li->addClass("page_item_has_children");

                $plus_toggle = new Node("span", array("class" => "toggle icon-plus"));
                $plus_svg = new Node("svg");
                $plus_svg->addText('
                    <rect rx="1" id="svg_2" height="25%" width="100%" y="37.5%" x="0%" fill="' . $options["color"] . '"/>
                    <rect rx="1" id="svg_3" height="100%" width="25%" y="0%" x="37.5%"  fill="' . $options["color"] . '"/>
                ');
                $plus_toggle->addChild($plus_svg);

                $minus_toggle = new Node("span", array("class" => "toggle icon-minus hidden"));
                $minus_svg = new Node("svg");
                $minus_svg->addText('
                    <rect rx="1" id="svg_1" height="25%" width="100%" y="37.5%" x="0%" fill="' . $options["color"] . '" />
                ');
                $minus_toggle->addChild($minus_svg);

                $toggle_item->addChild($plus_toggle);
                $toggle_item->addChild($minus_toggle);

                $childUl = $this->print_pages_recursive($page->children, $options, $level + 1);
                $childUl->addClass("children");
                $li->addChild($childUl);
            }
        }
        return $ul;
    }
}

add_action("wp_enqueue_scripts", function () {
    wp_enqueue_style("cpw-style", plugins_url("style.css", __FILE__), '', '1.2');
    wp_enqueue_script("cpw-js", plugins_url("script.js", __FILE__), array("jquery"));
});

add_action("widgets_init", function () {
    register_widget('\xinhonglee\CollapsiblePagesWidget');
});

// add color picker
add_action('admin_enqueue_scripts', function ($hook) {
    if ("widgets.php" != $hook) {
        return;
    }
    wp_enqueue_style("wp-color-picker");
    wp_enqueue_script("wp-color-picker");
});
