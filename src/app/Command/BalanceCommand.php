<?php
namespace App\Command;

use Goutte\Client;
use InFinancas\VisaElectron\Authenticator;
use InFinancas\VisaElectron\Exception\ErrorMessageException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Hallison Boaventura <hallisonboaventura@gmail.com>
 */
class BalanceCommand extends Command
{
    private $authenticator;

    /**
     * Constructor.
     *
     * @param Client $client Fabien's client.
     */
    public function __construct(Client $client)
    {
        $this->authenticator = new Authenticator($client);
        parent::__construct();
    }

    /**
     * Set up command name, description, help and how inputs should be defined.
     *
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('balance')
            ->setDescription('Shows up balance for each card')
            ->setHelp('You can set up environment variables; see https://github.com/hallboav/infinancas for more details');

        $this
            ->setDefinition(
                new InputDefinition([
                    new InputArgument('username', InputArgument::OPTIONAL, 'CPF'),
                    new InputArgument('password', InputArgument::OPTIONAL, 'Password')
                ])
            );
    }

    /**
     * Shows up balance to user.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ((null === $username = $input->getArgument('username')) && (false === $username = getenv('IN_FINANCAS_USERNAME'))) {
            throw new \RuntimeException('Missing username argument and IN_FINANCAS_USERNAME environment variable.');
        }

        if ((null === $password = $input->getArgument('password')) && (false === $password = getenv('IN_FINANCAS_PASSWORD'))) {
            throw new \RuntimeException('Missing password argument and IN_FINANCAS_PASSWORD environment variable.');
        }

        try {
            $output->writeln(sprintf('Authenticating with CPF <fg=yellow>%s</>.', $username), OutputInterface::VERBOSITY_VERBOSE);
            list($owner, $cards, $sessionId) = $this->authenticator->authenticate($username, $password);
        } catch (ErrorMessageException $error) {
            $block = $this->getHelper('formatter')->formatBlock($error->getMessage(), 'error', true);
            $output->writeln($block);
            return 1;
        }

        $output->writeln(sprintf('Your session ID is <fg=yellow>%s</>.', $sessionId), OutputInterface::VERBOSITY_VERBOSE);
        $output->writeln(sprintf('<fg=yellow>%s</>\'s cards:', $owner), OutputInterface::VERBOSITY_VERBOSE);

        if ($output->isVerbose()) {
            $table = new Table($output);
            $table->setHeaders(['Active', 'Balance', 'Card number', 'Color']);

            foreach ($cards as $card) {
                $table->addRow([
                    $card->getIsActive() ? 'YES' : 'NO',
                    $card->getBalance(),
                    $card->getNumberFormatted(),
                    $card->getIsBlue() ? 'BLUE' : 'ORANGE'
                ]);
            }

            $table->render();
        } else {
            foreach ($cards as $card) {
                $color = $card->getIsActive() ? 'green' : 'yellow';
                $output->writeln(sprintf('<fg=%s>%s</>', $color, $card->getBalance()));
            }
        }
    }
}
