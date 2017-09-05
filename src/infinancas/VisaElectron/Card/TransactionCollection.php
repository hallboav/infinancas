<?php
namespace InFinancas\VisaElectron\Card;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Hallison Boaventura <hallisonboaventura@gmail.com>
 */
class TransactionCollection implements \IteratorAggregate
{
    private $collection;

    /**
     * Constructor.
     *
     * @param array $collection Array of Transaction instances.
     */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Creates a new TransactionCollection instance.
     *
     * @param Crawler $crawler Crawler from a transactions page.
     *
     * @return TransactionCollection
     */
    public static function createFromCrawler(Crawler $crawler)
    {
        $collection = [];
        $crawler = $crawler->filter('table.cardDataTable1')->eq(2);
        $crawler->filter('tbody > tr')->each(function ($crawler) use (&$collection) {
            $collection[] = Transaction::createFromCrawler($crawler);
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

    /**
     * Sorts transactions by date desc.
     *
     * @param Transaction $a
     * @param Transaction $b
     *
     * @return int
     */
    public function orderByDateDesc(Transaction $a, Transaction $b)
    {
        return $a->getDate() == $b->getDate() ? 0 : ($a->getDate() < $b->getDate() ? 1 : -1);
    }
}
