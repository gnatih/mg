<?php

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

final class Product_Button_Widget extends Widget_Base
{
  public function get_name()
  {
    return 'product-button-widget';
  }

  public function get_title()
  {
    return 'Product List with Button';
  }

  public function get_icon()
  {
    return 'fa fa-pencil';
  }

  public function get_categories()
  {
    return ['general'];
  }

  public function _register_controls()
  {
    $this->start_controls_section('content_section', [
      'label' => 'Products list',
      'tab' => Controls_Manager::TAB_CONTENT
    ]);

    $repeater = new Repeater();

    $repeater->add_control('title', [
      'label' => 'Title',
      'label_block' => true,
      'type' => Controls_Manager::TEXT,
    ]);

    $repeater->add_control('btn_text', [
      'label' => 'Button text',
      'type' => Controls_Manager::TEXT,
      'default' => 'Add to Cart',
      'placeholder' => 'Add to Cart'
    ]);

    $repeater->add_control('product_id', [
      'label' => 'Product ID',
      'type' => Controls_Manager::NUMBER,
    ]);

    $repeater->add_control('is_ajax', [
      'label' => 'Is AJAX',
      'type' => Controls_Manager::SWITCHER,
      'default' => 'yes',
      'description' => 'Specify if user remains on the same page after adding the product to cart.'
    ]);

    $this->add_control('products_list', [
      'label' => 'List of products',
      'type' => Controls_Manager::REPEATER,
      'fields' => $repeater->get_controls(),
      'title_field' => '{{{ title }}}'
    ]);

    $this->end_controls_section();

    $this->start_controls_section('styles_section', [
      'label' => 'Odd rows',
      'tab' => Controls_Manager::TAB_STYLE
    ]);

    $this->add_control('text_color_odd', [
      'label' => 'Text color',
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} {{CURRENT_ITEM}} .product-button-text' => 'color: {{VALUE}}'
      ]
    ]);

    $this->add_control('text_background_odd', [
      'label' => 'Text background',
      'type' => Controls_Manager::COLOR,
      'selectors' => [
        '{{WRAPPER}} {{CURRENT_ITEM}} .product-button-text' => 'background-color: {{VALUE}}'
      ]
    ]);


    $this->end_controls_section();

    // $this->add_control('options_list', [
    //   'label' => 'List of products',
    //   'type' => Controls_Manager::REPEATER,
    //   'fields' => $repeater->get_controls(),
    //   'default' => ['', '', ''],
    //   'title_field' => '{{{ title }}}'
    // ]);
  }

  protected function render()
  {
    $list = $this->get_settings_for_display('products_list');

    if (count($list)) {
      $i = 0;

      echo '<div class="pb-wrapper">';

      foreach ($list as $item) {
        $style = $i % 2 == 0 ? 'even' : 'odd';
        $product_cart_id = WC()->cart->generate_cart_id($item['product_id']);
        $in_cart = WC()->cart->find_product_in_cart($product_cart_id);
        $btn_text = $in_cart ? 'Added' : $item['btn_text'];
        $classes = ['pb-btn'];
        $is_ajax = isset($item['is_ajax']) && $item['is_ajax'] == 'yes' ? true : false;

        if ($in_cart) {
          $classes[] = 'added';
        }

        echo '<div class="pb-row pb-row-'.$style.'">';
        echo '<div class="pb-title">'.$item['title'].'</div>';

        if ($is_ajax) {
          $classes[] = 'mg-ajax-add-to-cart-button';

          echo '<a class="'.implode(' ', $classes).'" data-product-id="'.$item['product_id'].'" href="javascript:;">'.$btn_text.'</a>';
        } else {
          echo '<a class="'.implode(' ', $classes).'" href="'.site_url().'/cart?add-to-cart='.$item['product_id'].'">'.$btn_text.'</a>';
        }
        echo '</div>';

        $i++;
      }

      echo '</div>';
    }

    // if (count($list)) {
    //   $i = 0;
    //   echo '<div class="product-button-wrapper">';

    //   foreach ($list as $item) {
    //     $style = $i % 2 == 0 ? 'even' : 'odd';
    //     echo '<div class="product-button-row elementor-repeater-item-'.$item['_id'].'"><span class="product-button-text">'.$item['title'].'</span>';

    //     if ($item['product_id']) {
    //       echo '<a href="?add-to-cart='.$item['product_id'].'" class="product-button-btn">'.$item['btn_text'].'</a>';
    //     }

    //     echo '</div>';
    //   }

    //   echo '</div>';
    // } else {
    //   echo 'Please add a few products to this list';
    // }
  }
}
