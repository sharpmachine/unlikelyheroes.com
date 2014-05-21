<?php

class BWGControllerGalleryBox {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function execute() {
    $ajax_task = (isset($_POST['ajax_task']) ? esc_html($_POST['ajax_task']) : '');
    if (method_exists($this, $ajax_task)) {
      $this->$ajax_task();
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_BWG_DIR . "/frontend/models/BWGModelGalleryBox.php";
    $model = new BWGModelGalleryBox();

    require_once WD_BWG_DIR . "/frontend/views/BWGViewGalleryBox.php";
    $view = new BWGViewGalleryBox($model);

    $view->display();
  }

  public function save() {
    require_once WD_BWG_DIR . "/frontend/models/BWGModelGalleryBox.php";
    $model = new BWGModelGalleryBox();
    $option_row = $model->get_option_row_data();
    if ($option_row->popup_enable_email) {
      // Email validation.
      $email = (isset($_POST['bwg_email']) ? is_email(stripslashes($_POST['bwg_email'])) : FALSE);
    }
    else {
      $email = TRUE;
    }
    if ($option_row->popup_enable_captcha) {
      $bwg_captcha_input = (isset($_POST['bwg_captcha_input']) ? esc_html(stripslashes($_POST['bwg_captcha_input'])) : '');
      @session_start();
      $bwg_captcha_code = (isset($_SESSION['bwg_captcha_code']) ? esc_html(stripslashes($_SESSION['bwg_captcha_code'])) : '');
      if ($bwg_captcha_input === $bwg_captcha_code) {
        $captcha = TRUE;
      }
      else {
        $captcha = FALSE;
      }
    }
    else {
      $captcha = TRUE;
    }

    if ($email && $captcha) {
      global $wpdb;
      $image_id = (isset($_POST['image_id']) ? (int) $_POST['image_id'] : 0);
      $name = (isset($_POST['bwg_name']) ? esc_html(stripslashes($_POST['bwg_name'])) : '');
      $bwg_comment = (isset($_POST['bwg_comment']) ? esc_html(stripslashes($_POST['bwg_comment'])) : '');
      $bwg_email = (isset($_POST['bwg_email']) ? esc_html(stripslashes($_POST['bwg_email'])) : '');
      $save = $wpdb->insert($wpdb->prefix . 'bwg_image_comment', array(
        'image_id' => $image_id,
        'name' => $name,
        'date' => date('Y-m-d H:i'),
        'comment' => $bwg_comment,
        'url' => '',
        'mail' => $bwg_email,
        'published' => 1,
      ), array(
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d',
      ));
      $wpdb->query($wpdb->prepare('UPDATE ' . $wpdb->prefix . 'bwg_image SET comment_count=comment_count+1 WHERE id="%d"', $image_id));
    }
    $this->display();
  }

  public function delete() {
    global $wpdb;
    $comment_id = (isset($_POST['comment_id']) ? (int) $_POST['comment_id'] : 0);
    $image_id = (isset($_POST['image_id']) ? (int) $_POST['image_id'] : 0);
    $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwg_image_comment WHERE id="%d"', $comment_id));
    $wpdb->query($wpdb->prepare('UPDATE ' . $wpdb->prefix . 'bwg_image SET comment_count=comment_count-1 WHERE id="%d"', $image_id));
    $this->display();
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}