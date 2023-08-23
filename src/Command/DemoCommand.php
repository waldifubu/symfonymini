<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Shapecode\Bundle\CronBundle\Attribute\AsCronJob;

#[AsCommand(
    name: 'demo',
    description: 'Add a short description for your command',
)]
#[AsCronJob('*/2 * * * *')]
class DemoCommand extends Command
{
    public function __construct(private readonly LoggerInterface $logger, private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }

    
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
            ->setHelp('This command allows you to create a user...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Getting a new quote');

        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        $this->insert();
        sleep(10);

        $this->logger->info(date('H:i:s'). ' New call #######################################');

        return Command::SUCCESS;
    }

    /**
     * @throws \Exception
     */
    private function insert(): void
    {
        $min = random_int(0,59);
        $task = new Task();
        $task->setSchedule("* * * * ${min}");
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}
