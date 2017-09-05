<?php
namespace InFinancas\VisaElectron\Card;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Hallison Boaventura <hallisonboaventura@gmail.com>
 */
class Card
{
    private $number;
    private $balance;
    private $isActive;
    private $isBlue;

    /**
     * Constructor.
     *
     * @param string $number   Card number.
     * @param string $balance  Balance.
     * @param bool   $isActive Whether the card is active or not.
     * @param bool   $isBlue   Tells us if the card is blue. It can be blue or orange.
     */
    public function __construct($number, $balance, $isActive, $isBlue)
    {
        $this->number = $number;
        $this->balance = $balance;
        $this->isActive = $isActive;
        $this->isBlue = $isBlue;
    }

    /**
     * Creates a new Card instance.
     *
     * @param Crawler $crawler Crawler from the div that holds the cards.
     *
     * @return Card
     */
    public static function createFromCrawler(Crawler $crawler)
    {
        $anchor = $crawler->filter('a')->first();
        $image = $anchor->filter('img.cardInfoDataIMG')->first()->image();

        return new self(
            substr($anchor->link()->getUri(), -16),
            $crawler->filter('p.cardValueInfo > strong')->first()->text(),
            'ATIVO' === $crawler->filter('p.cardDateInfo > strong')->first()->text(),
            '/VISAInMaisazulCOMPRA.jpg' === substr($image->getUri(), -25)
        );
    }

    /**
     * Formats number to human readable format.
     *
     * @return string Output will be like 0000 0000 0000 0000.
     */
    public function getNumberFormatted()
    {
        return rtrim(chunk_split($this->number, 4, ' '));
    }

    /**
     * Returns balance.
     *
     * @return string
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Returns true if card is active.
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Returns true if card is blue.
     *
     * @return bool
     */
    public function getIsBlue()
    {
        return $this->isBlue;
    }
}
