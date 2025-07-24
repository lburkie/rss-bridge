<?php

class NewStatesmanUKPoliticsBridge extends BridgeAbstract {
    const NAME = 'New Statesman UK Politics Bridge';
    const URI = 'https://www.newstatesman.com/politics/uk-politics';
    const DESCRIPTION = 'Latest articles from the UK Politics section of New Statesman';
    const MAINTAINER = 'lburkie';

 public function collectData()
{
    $html = getSimpleHTMLDOM(self::URI) or returnServerError('Could not load New Statesman UK Politics page');

    // Iterate through headline blocks (they contain the correct article links and titles)
    foreach ($html->find('.c-story__header__headline--catalogue') as $headlineBlock) {
        $link = $headlineBlock->find('a', 0);
        if (!$link) {
            continue; // skip if link is missing
        }

        $item = [];

        // Title and link
        $item['title'] = trim($link->plaintext);
        $item['uri'] = urljoin(self::URI, $link->href);

        // Move up to the parent article element to search for other data (like summary, date, image)
        $article = $headlineBlock->closest('article'); // closest <article> ancestor

        if (!$article) {
            $item['content'] = ''; // fallback
            $this->items[] = $item;
            continue;
        }

        // Summary
        $summary = $article->find('p.card__standfirst, .c-story__standfirst', 0); // try multiple possible classes
        if ($summary) {
            $item['content'] = trim($summary->plaintext);
        } else {
            $item['content'] = '';
        }

        // Timestamp
        $time = $article->find('time', 0);
        if ($time && isset($time->datetime)) {
            $item['timestamp'] = strtotime($time->datetime);
        }

        // Image
        $img = $article->find('img', 0);
        if ($img && isset($img->src)) {
            $imgSrc = urljoin(self::URI, $img->src);
            $item['enclosures'] = [$imgSrc];
            $item['content'] .= '<br><img src="' . $imgSrc . '" />';
        }

        $this->items[] = $item;
    }
}

}
