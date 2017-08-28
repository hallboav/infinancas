<?php
namespace InFinancas\VisaElectron\Card;

use Symfony\Component\DomCrawler\Crawler;

class TransactionCollection implements \IteratorAggregate
{
    private $collection;

    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public static function createFromCrawler(Crawler $crawler)
    {
        $collection = [];
        $crawler = $crawler->filter('table.cardDataTable1')->eq(2);
        $crawler->filter('tbody > tr')->each(function ($crawler) use (&$collection) {
            $collection[] = Transaction::createFromCrawler($crawler);
        });

        return new self($collection);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }

    public function sortByDate(Transaction $a, Transaction $b)
    {
        return $a->getDate() == $b->getDate() ? 0 : ($a->getDate() < $b->getDate() ? 1 : -1);
    }
}
