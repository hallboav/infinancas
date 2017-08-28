<?php
namespace InFinancas\VisaElectron\Card;

use Symfony\Component\DomCrawler\Crawler;

class CardCollection implements \IteratorAggregate
{
    private $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public static function createFromCrawler(Crawler $crawler)
    {
        $collection = [];
        $crawler->filter('div.cardInfoDataBlock')->each(function ($crawler) use (&$collection) {
            $collection[] = Card::createFromCrawler($crawler);
        });

        return new self($collection);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }
}
