<?php

if (!function_exists('ksort_recursive')) {
  function ksort_recursive (&$array) {
    foreach ($array as &$value) {
      if (is_array($value)) ksort_recursive($value);
    }
    return ksort($array);
  }
}

switch ($modx->event->name) {
  case 'OnWebPagePrerender':
    $resource	= &$modx->resource;
    $html = &$resource->_output;
    $hash = sha1($html);
    $date = time();

    $protocol	= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domain = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    $url = $protocol.$domain.$uri;

    $keyArr = parse_url($url);
    $keyArr['query'] = (isset($keyArr['query'])) ? $keyArr['query'] : '';
    if ($keyArr['query']) {
      parse_str($keyArr['query'], $query);
      $keyArr['query'] = $query;
    }
    ksort_recursive($keyArr);
    $key = md5(serialize($keyArr));
//    $key = md5($resource->id.$url);

    /** @var lmims $lmims */
    $lmims = $modx->getObject('lmims', array('key' => $key));
    // lmimsRow not exists
    if ( !($lmims instanceof lmims) ) {
      // create new
      $lmims = $modx->newObject('lmims');
      $lmimsDate = $date;
      $lmimsHash = $hash;
      $lmims->fromArray(array(
        'key'	=> $key,
        'date' => $lmimsDate,
        'hash' => $lmimsHash,
        'url' => $url,
        'resource' => $resource->id,
      ), '', true);
      $lmims->save();
    }
    // get last modified data
    else {
      $lmimsDate = $lmims->get('date');
      $lmimsHash = $lmims->get('hash');
    }
    // if current document html not equal html from lmimsRow
    if ($hash !== $lmimsHash) {
      $lmims->fromArray(array(
        'key'	=> $key,
        'hash' => $hash,
        'date' => $date
      ), '', true);
      $lmims->save();
      $lmimsDate = $date;
      $lmimsHash = $hash;
    }

    // see: http://last-modified.com/ru/last-modified-if-modified-since-php.html
    $LastModified		= gmdate("D, d M Y H:i:s \G\M\T", $lmimsDate);
    $IfModifiedSince	= false;
    if (isset($_ENV['HTTP_IF_MODIFIED_SINCE'])) {
      $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
    }
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
      $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
    }
    if ($IfModifiedSince && $IfModifiedSince >= $lmimsDate) {
      header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
      exit;
    }
//    die(var_dump($LastModified));
    header('Last-Modified: '. $LastModified);

    break;
  case 'OnDocFormSave':
    if (!$modx->getOption('lmims.refresh_on_page_save')) return;

    $protocol	= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
    $url = $modx->makeUrl($resource->id, '', '', $protocol);
    if (function_exists('curl_init')) {
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_HEADER, true);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_AUTOREFERER, true);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_TIMEOUT, 10);
      curl_exec($curl);
      curl_close($curl);
    } else {
      file_get_contents($url);
    }

    break;
  case 'OnBeforeEmptyTrash':
    $ids = $modx->event->params['ids'];
    if ($modx->getOption('lmims.remove_related_lmims_on_empty_trash') && !empty($ids)) {
      $modx->removeCollection('lmims', array(
        'resource:IN' => $ids,
      ));
    }

    break;
  default:

    break;
}