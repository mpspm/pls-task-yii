<?php

/**
 * @class      RssHelper
 *
 * Helper class to work with RSS feeds.
 *
 * @author     Mike Sabatino
 * @copyright  PLS 3rd Learning, Inc. All rights reserved.
 */

class RssHelper
{
  public static function getItemsByLimit(int $limit = 0, string $url, string $cacheDirectory, int $cacheExpiration, string $userAgent): array
  {
    Feed::$userAgent = $userAgent;
    Feed::$cacheDir = $cacheDirectory;
    Feed::$cacheExpire = $cacheExpiration;

    $feed = Feed::loadRss($url);

    $itemArray = [];

    foreach ($feed->item as $item) {
      $itemArray[] = $item;
    }

    //Sort by timestamp of each feed item
    usort($itemArray, function ($a, $b) {
      if ($a->timestamp == $b->timestamp) {
        return 0;
      }
      return ($a->timestamp > $b->timestamp) ? 1 : -1;
    });

    $items = array_slice($itemArray, 0, $limit);

    //convert back to object before returning
    $items = json_decode(json_encode($items));

    return $items;
  }
}
