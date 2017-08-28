<?php
namespace InFinancas\VisaElectron;

use Symfony\Component\DomCrawler\Crawler;

trait ErrorCheckerTrait
{
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

    public function thereIsSuccessMessage(Crawler $crawler)
    {
        return $crawler->filter('#MainContent_msgSucessoForm')->first()->count() > 0;
    }
}
