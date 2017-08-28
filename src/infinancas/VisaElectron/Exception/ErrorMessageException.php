<?php
namespace InFinancas\VisaElectron\Exception;

use Symfony\Component\DomCrawler\Crawler;

class ErrorMessageException extends \RuntimeException
{
    private $crawler;

    public function __construct($message, Crawler $crawler = null)
    {
        $this->crawler = $crawler;

        parent::__construct($message);
    }

    public function getCrawler()
    {
        return $this->crawler;
    }
}
