<?php
namespace InFinancas\VisaElectron;

use Goutte\Client;
use InFinancas\VisaElectron\Exception\ErrorMessageException;
use InFinancas\VisaElectron\Card\CardCollection;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Hallison Boaventura <hallisonboaventura@gmail.com>
 */
class Authenticator
{
    use ErrorCheckerTrait;

    private $client;

    /**
     * Constructor.
     *
     * @param Client $client Fabien's client.
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Authenticates and returns user information.
     *
     * @param string $username
     * @param string $password
     *
     * @return array List with user information (owner, cards and session identifier).
     */
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

        $session = $this->client->getCookieJar()->get('ASP.NET_SessionId')->getValue();
        list($owner, $cards) = self::getUserData($crawler);

        return [
            $owner,
            $cards,
            $session
        ];
    }

    /**
     * Obtains and returns sensitive user information from crawler.
     *
     * @param Crawler $crawler Crawler from logged in page.
     *
     * @return array List with user information (owner and cards).
     */
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
