<?php

namespace App\Command;

use App\Service\ImportUsersService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:import-users')]

class ImportUsersCommand extends \Symfony\Component\Console\Command\Command {


    public function __construct(private ImportUsersService $importUsersService)

    {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);
        $this->importUsersService->importUsers($io);

        return \Symfony\Component\Console\Command\Command::SUCCESS;
    }

}

