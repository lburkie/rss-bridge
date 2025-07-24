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
        $item = []; // Create an empty item
    
        $item['title'] = 'Hello World!';
    
        $this->items[] = $item; // Add item to the list
    }
}
