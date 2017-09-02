<?php
  function render_template($path, $data) {
    if (!file_exists($path)) {
      return '';
    }

    extract($data);

    ob_start();
    require $path;

    return ob_get_clean();
  }
