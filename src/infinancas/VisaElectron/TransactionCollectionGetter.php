<?php
namespace InFinancas\VisaElectron;

use Goutte\Client;
use InFinancas\VisaElectron\Card\TransactionCollection;
use InFinancas\VisaElectron\Exception\ErrorMessageException;

/**
 * @author Hallison Boaventura <hallisonboaventura@gmail.com>
 */
class TransactionCollectionGetter
{
    use ErrorCheckerTrait;
    use CookieTrait;

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
     * Returns a new instance of TransactionCollection based on downloaded In Finanças transactions page.
     *
     * @param string $sessionId  24 characters length.
     * @param string $cardNumber 16 characters length.
     *
     * @return TransactionCollection
     */
    public function get($sessionId, $cardNumber)
    {
        $this->client->getCookieJar()->set($this->createCookieFromSessionId($sessionId));
        $uri = 'https://portal.infinancasservicos.com.br/infinancas/Redirect.aspx?ttCD_OPERACAO=1&ttNR_1=' . $cardNumber;
        $crawler = $this->client->request('GET', $uri);

        if ($message = $this->getErrorMessageIfThereIs($crawler)) {
            throw new ErrorMessageException($message, $crawler);
        }

        return TransactionCollection::createFromCrawler($crawler);
    }
}
