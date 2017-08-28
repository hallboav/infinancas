<?php
namespace InFinancas\VisaElectron\Card;

use Symfony\Component\DomCrawler\Crawler;

class Card
{
    private $number;
    private $balance;
    private $isActive;
    private $isBlue;

    public function __construct($number, $balance, $isActive, $isBlue)
    {
        $this->number = $number;
        $this->balance = $balance;
        $this->isActive = $isActive;
        $this->isBlue = $isBlue;
    }

    public static function createFromCrawler(Crawler $crawler)
    {
        $a = $crawler->filter('a')->first();
        $image = $a->filter('img.cardInfoDataIMG')->first()->image();

        return new self(
            substr($a->link()->getUri(), -16),
            $crawler->filter('p.cardValueInfo > strong')->first()->text(),
            'ATIVO' === $crawler->filter('p.cardDateInfo > strong')->first()->text(),
            '/VISAInMaisazulCOMPRA.jpg' === substr($image->getUri(), -25)
        );
    }

    public function getNumberFormatted()
    {
        return rtrim(chunk_split($this->number, 4, ' '));
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getIsActive()
    {
        return $this->isActive;
    }

    public function getIsBlue()
    {
        return $this->isBlue;
    }
}
