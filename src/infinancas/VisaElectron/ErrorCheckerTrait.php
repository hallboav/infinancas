<?php
namespace InFinancas\VisaElectron;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Hallison Boaventura <hallisonboaventura@gmail.com>
 */
trait ErrorCheckerTrait
{
    /**
     * Returns the error message if it can be found on crawler.
     *
     * @param Crawler $crawler
     *
     * @return string
     */
    public function getErrorMessageIfThereIs(Crawler $crawler)
    {
        $errorNode = $crawler->filter('#msgErro')->first();
        if ($errorNode->count() > 0) {
            return $errorNode->text();
        }

        $errorNode = $crawler->filter('#MainContent_msgErroForm')->first();
        if ($errorNode->count() > 0) {
            return $errorNode->text();
        }
    }

    /**
     * Tells us if there is success message.
     *
     * @param Crawler $crawler
     *
     * @return bool
     */
    public function thereIsSuccessMessage(Crawler $crawler)
    {
        return $crawler->filter('#MainContent_msgSucessoForm')->first()->count() > 0;
    }
}
