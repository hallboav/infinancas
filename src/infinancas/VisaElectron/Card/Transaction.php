<?php
namespace InFinancas\VisaElectron\Card;

use Symfony\Component\DomCrawler\Crawler;

class Transaction implements \JsonSerializable
{
    private $date;
    private $description;
    private $value;
    private $isCredit;

    public function __construct(\DateTime $date, $description, $value, $isCredit)
    {
        $this->date = $date;
        $this->description = $description;
        $this->value = $value;
        $this->isCredit = $isCredit;
    }

    private static function guessYear($month, $day)
    {
        list($currentYear, $currentMonth) = explode('|', (new \DateTime())->format('Y|m'));
        return $month > $currentMonth ? $currentYear - 1 : $currentYear;
    }

    /*
     * <tr>
     *   <td class="td110"><b>16/12</b></td>
     *   <td class="td390">SUBWAY                   </td>
     *   <td class="td120"><b> 24,00</b> -</td>
     * </tr>
     */
    public static function createFromCrawler(Crawler $crawler)
    {
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

    public function getDate()
    {
        return $this->date;
    }

    public function jsonSerialize()
    {
        return [
            'date' => $this->date->format(\DateTime::ISO8601),
            'description' => $this->description,
            'value' => $this->value,
            'is_credit' => $this->isCredit
        ];
    }
}
