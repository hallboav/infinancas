<?php
namespace InFinancas\VisaElectron\Exception;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Hallison Boaventura <hallisonboaventura@gmail.com>
 */
class ErrorMessageException extends \RuntimeException
{
    private $crawler;

    /**
     * Constructor.
     *
     * @param string       $message
     * @param Crawler|null $crawler
     */
    public function __construct($message, Crawler $crawler = null)
    {
        $this->crawler = $crawler;

        parent::__construct($message);
    }

    /**
     * Returns crawler.
     *
     * @return Crawler
     */
    public function getCrawler()
    {
        return $this->crawler;
    }
}
