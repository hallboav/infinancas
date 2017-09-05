<?php
namespace InFinancas\VisaElectron\Card;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Hallison Boaventura <hallisonboaventura@gmail.com>
 */
class CardCollection implements \IteratorAggregate
{
    private $collection;

    /**
     * Constructor.
     *
     * @param array $collection Array of Card instances.
     */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Creates a new CardCollection instance.
     *
     * @param Crawler $crawler Crawler from logged in page.
     *
     * @return CardCollection
     */
    public static function createFromCrawler(Crawler $crawler)
    {
        $collection = [];
        $crawler->filter('div.cardInfoDataBlock')->each(function ($crawler) use (&$collection) {
            $collection[] = Card::createFromCrawler($crawler);
        });

        return new self($collection);
    }

    /**
     * Returns an iterator.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }
}
