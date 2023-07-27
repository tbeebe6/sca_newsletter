<?php

function get_latest_sca_file() {

        libxml_use_internal_errors(true);
        $html = file_get_contents(KINGDOM_NEWSLETTER_URL);
        $dom = new DOMDocument();
        $dom->loadHTML($html);

        $domxpath = new DOMXPath($dom);
        $elements = $domxpath->query("//img[@alt='". ACORN_ALT_TEXT ."']");
        foreach ($elements as $element) {
                $link = $element->parentNode;

                $length = $link->attributes->length;
                for ($i = 0; $i < $length; $i++) {    
                        if ($link->attributes->item($i)->nodeName == 'href') {
                                $doc_url =  $link->attributes->item($i)->nodeValue;
                        }
                }
        }
        return($doc_url);
}

