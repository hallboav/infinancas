<?php
namespace App\Command;

use Goutte\Client;
use InFinancas\VisaElectron\Exception\ErrorMessageException;
use InFinancas\VisaElectron\TransactionCollectionGetter;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Hallison Boaventura <hallisonboaventura@gmail.com>
 */
class TransactionsCommand extends Command
{
    private $getter;

    /**
     * Constructor.
     *
     * @param Client $client Fabien's client.
     */
    public function __construct(Client $client)
    {
        $this->getter = new TransactionCollectionGetter($client);
        parent::__construct();
    }

    /**
     * Set up command name, description and how inputs should be defined.
     *
     * @return null
     */
    protected function configure()
    {
        $this
            ->setName('transactions')
            ->setDescription('Shows up every transaction from last 90 days');

        $this
            ->setDefinition(
                new InputDefinition([
                    new InputArgument('session-id',  InputArgument::REQUIRED, 'Session ID'),
                    new InputArgument('card-number', InputArgument::REQUIRED, 'Card number')
                ])
            );
    }

    /**
     * Shows up transactions data to user.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sessionId = $input->getArgument('session-id');
        $cardNumber = preg_replace('#\s#', '', $input->getArgument('card-number'));

        try {
            $output->writeln(sprintf('Checking card <fg=yellow>%s</>.', rtrim(chunk_split($cardNumber, 4, ' '))), OutputInterface::VERBOSITY_VERBOSE);
            $transactions = $this->getter->get($sessionId, $cardNumber);
        } catch (ErrorMessageException $error) {
            $block = $this->getHelper('formatter')->formatBlock($error->getMessage(), 'error', true);
            $output->writeln($block);
            return 1;
        }

        $iterator = $transactions->getIterator();
        $iterator->uasort([$transactions, 'orderByDateDesc']);

        if (0 < count($iterator)) {
            $table = new Table($output);
            $table->setHeaders(['Date', 'Description', 'Value']);

            foreach ($iterator as $transaction) {
                $table->addRow([
                    $transaction->getDate()->format('d/m/Y'),
                    $transaction->getDescription(),
                    $transaction->getIsCredit() ? sprintf('<fg=green>%s</>', $transaction->getValue()) : $transaction->getValue()
                ]);
            }

            $table->render();
        } elseif (0 === count($iterator)) {
            $output->writeln('There have been no changes to your account in the last 90 days.');
        }
    }
}
