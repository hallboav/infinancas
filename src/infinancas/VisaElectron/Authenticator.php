<?php
namespace InFinancas\VisaElectron;

use Goutte\Client;
use InFinancas\VisaElectron\Exception\ErrorMessageException;
use InFinancas\VisaElectron\Card\CardCollection;
use Symfony\Component\DomCrawler\Crawler;

class Authenticator
{
    use ErrorCheckerTrait;

    private $client;
    private $errorChecker;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function authenticate($username, $password)
    {
        $crawler = $this->client->request('GET', 'https://portal.infinancasservicos.com.br/infinancas/');

        $form = $crawler->selectButton('ENTRAR')->form([
            'ttNOME_USUARIO' => $username,
            'ttSENHA' => $password
        ]);

        $crawler = $this->client->submit($form);

        if ($message = $this->getErrorMessageIfThereIs($crawler)) {
            throw new ErrorMessageException($message, $crawler);
        }

        list($owner, $cards) = self::getUserData($crawler);

        return [
            $owner,
            $cards
        ];
    }

    public static function getUserData(Crawler $crawler)
    {
        $ucowner = $crawler->filter('div.headerInfoTextBlock > p > strong')->first()->text();
        $owner = ucwords(strtolower($ucowner));
        $cards = CardCollection::createFromCrawler($crawler);

        return [
            $owner,
            $cards->getIterator()->getArrayCopy()
        ];
    }
}
