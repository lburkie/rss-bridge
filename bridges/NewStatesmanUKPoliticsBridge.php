<?php

class NewStatesmanUKPoliticsBridge extends BridgeAbstract {
    const NAME = 'NewStatesmanUKPoliticsBridge';
    const URI = 'https://www.newstatesman.com/politics/uk-politics';
    const DESCRIPTION = 'Latest articles from the UK Politics section of New Statesman';
    const MAINTAINER = 'lburkie';

    public function collectData() {
        $html = getSimpleHTMLDOM(self::URI);
        if (!$html) {
            returnServerError('Failed to retrieve HTML from source');
        }

        foreach ($html->find('.c-story__header__headline--catalogue') as $element) {
            $link = $element->find('a', 0);
            if (!$link) {
                continue;
            }

            $item = [];

            // Get title and URI
            $item['title'] = trim($link->plaintext);
            $item['uri'] = $link->href;

            // Make URI absolute if needed
            if (strpos($item['uri'], 'http') !== 0) {
                $item['uri'] = 'https://www.newstatesman.com' . $item['uri'];
            }

            // Try to get article summary text (optional — depends on site structure)
            $storyContainer = $element->parent(); // move up one level to find siblings
            $summary = $storyContainer->find('.c-story__standfirst', 0);
            if ($summary) {
                $item['content'] = trim($summary->plaintext);
            } else {
                $item['content'] = ''; // fallback empty content
            }

            $this->items[] = $item;
        }
    }
}
