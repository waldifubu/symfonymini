<?php

namespace App\Service;

class PodcastRssGenerator
{
 private string $header = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
    xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
    xmlns:podcast="https://podcastindex.org/namespace/1.0"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:rawvoice="http://www.rawvoice.com/rawvoiceRssModule/"
    xmlns:googleplay="http://www.google.com/schemas/play-podcasts/1.0">
<channel>
XML;

private $footer = <<<'XML'
</channel>
</rss>
XML;

    public function createPodcast(string $title, string $description, string $author, bool $locked, bool $explicit, string $copyright, string $language, \DateTimeInterface $created, string $cover, string $type, string $owner, string $keywords): string
    {
        $podcast = $this->header;


    }
}