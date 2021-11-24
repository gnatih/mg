<?php

if (! function_exists('mix')) {
  function mix($path)
  {
    $pathWithOutSlash = ltrim($path, '/');
    $pathWithSlash = '/'.ltrim($path, '/');
    $manifestFile = get_theme_file_path('mix-manifest.json');

    if (! $manifestFile) {
      return get_template_directory_uri().'/'.$pathWithOutSlash;
    }

    $manifestArray = json_decode(file_get_contents($manifestFile), true);

    if (file_exists(get_template_directory().'/hot')) {
      return 'http://localhost:8080/'.$pathWithOutSlash;
    }

    if (array_key_exists($pathWithSlash, $manifestArray)) {
      return get_template_directory_uri().'/'.ltrim($manifestArray[$pathWithSlash], '/');
    }

    return get_template_directory_uri().'/'.$pathWithOutSlash;
  }
}
