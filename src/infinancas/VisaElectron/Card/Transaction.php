<?php
namespace InFinancas\VisaElectron\Card;

use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Hallison Boaventura <hallisonboaventura@gmail.com>
 */
class Transaction
{
    private $date;
    private $description;
    private $value;
    private $isCredit;

    /**
     * Constructor.
     *
     * @param \DateTime $date        Day the transaction happened.
     * @param string    $description Usually the name of the entity that provided the service.
     * @param string    $value       Transaction value.
     * @param bool      $isCredit    Whether it was credit or not.
     */
    public function __construct(\DateTime $date, $description, $value, $isCredit)
    {
        $this->date = $date;
        $this->description = $description;
        $this->value = $value;
        $this->isCredit = $isCredit;
    }

    /**
     * Returns year based on month and day.
     *
     * @param string $month
     * @param string $day
     *
     * @return string
     */
    private static function guessYear($month, $day)
    {
        list($currentYear, $currentMonth) = explode('|', (new \DateTime())->format('Y|m'));
        return $month > $currentMonth ? $currentYear - 1 : $currentYear;
    }

    /**
     * Creates a new Transaction instance.
     *
     * @param Crawler $crawler Crawler from the table row that holds the transaction.
     *
     * @return Transaction
     */
    public static function createFromCrawler(Crawler $crawler)
    {
        // Sample:
        // <tr>
        //   <td class="td110"><b>16/12</b></td>
        //   <td class="td390">SUBWAY                   </td>
        //   <td class="td120"><b> 24,00</b> -</td>
        // </tr>

        $date = $crawler->filter('td.td110 > b')->first()->text();
        list($day, $month) = explode('/', $date);
        $year = self::guessYear($month, $day);

        $thirdColumn = $crawler->filter('td.td120');
        return new self(
            new \DateTime(sprintf('%04d-%02d-%02d', $year, $month, $day)),
            rtrim($crawler->filter('td.td390')->first()->text()),
            sprintf('R$ %s', ltrim($thirdColumn->filter('b')->first()->text())),
            substr($thirdColumn->first()->text(), -1) === '+'
        );
    }

    /**
     * Returns date the transaction happened.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Returns value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns true if value was credit.
     *
     * @return bool
     */
    public function getIsCredit()
    {
        return $this->isCredit;
    }
}
