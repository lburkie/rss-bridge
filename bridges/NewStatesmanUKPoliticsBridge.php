<?php

class NewStatesmanUKPoliticsBridge extends BridgeAbstract
{
    const NAME = 'New Statesman UK Politics bridge';
    const URI = 'https://www.newstatesman.com/politics/uk-politics';
    const DESCRIPTION = 'Bridge to news outlet New Statesman. Specifically the UK Politics section';
    const MAINTAINER = 'lburkie';
    const PARAMETERS = []; // Can be omitted!
    const CACHE_TIMEOUT = 3600; // Can be omitted!
    
    public function collectData()
{
    $html = getSimpleHTMLDOM(self::URI) or returnServerError('Could not load New Statesman UK Politics page');

    foreach ($html->find('article') as $article) {
        $item = [];

        // Link + Title
        $linkElement = $article->find('a', 0);
        if (!$linkElement) {
            continue; // skip malformed articles
        }
        $item['uri'] = urljoin(self::URI, $linkElement->href);
        $item['title'] = trim($linkElement->plaintext);

        // Summary (optional)
        $summary = $article->find('p', 0);
        if ($summary) {
            $item['content'] = trim($summary->plaintext);
        }

        // Date (optional)
        $time = $article->find('time', 0);
        if ($time && isset($time->datetime)) {
            $item['timestamp'] = strtotime($time->datetime);
        }

        // Image (optional)
        $img = $article->find('img', 0);
        if ($img && isset($img->src)) {
            $imgSrc = urljoin(self::URI, $img->src);
            $item['enclosures'] = [$imgSrc]; // adds as media enclosure
            $item['content'] .= '<br><img src="' . $imgSrc . '" />';
        }

        $this->items[] = $item;
    }
}

}
