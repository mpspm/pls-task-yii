<?php

/**
 * @class      RssHelper
 *
 * Helper class to work with RSS feeds.
 *
 * @author     Mike Sabatino
 * @copyright  PLS 3rd Learning, Inc. All rights reserved.
 */

//use Feed;

class RssHelper
{
  public static function getItems(string $url, int $limit = 0, string $cacheDirectory, int $cacheExpiration, string $userAgent)
  {
    /* Feed::$userAgent = Yii::app()->params['curlUserAgent'];
		Feed::$cacheDir = Yii::app()->params['latestUpdatesFeedCacheDir'];
		Feed::$cacheExpire = Yii::app()->params['latestUpdatesFeedCacheExp'];
		$feed = Feed::loadRss(Yii::app()->params['latestUpdatesFeedUrl']);
		$items = [];
		if (!empty($feed)) {
			foreach ($feed->item as $item) {
				$more = ' <a href="' . $item->link . '" target="_blank">Read more</a>';
				$item->description = trim(str_replace(' [&#8230;]', '...' . $more, $item->description));
				$item->description = preg_replace('/The post.*appeared first on .*\./', '', $item->description);
			}
			$items = $feed->item;
		} */

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
