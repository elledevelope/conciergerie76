<?php

namespace App\Command;

use App\Service\OverpassService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:fetch-pois')]
class FetchPoisCommand extends Command
{
    private OverpassService $overpassService;

    public function __construct(OverpassService $overpassService)
    {
        parent::__construct();
        $this->overpassService = $overpassService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Fetching points of interest from Overpass API...');
        $this->overpassService->fetchAndStoreData();
        $output->writeln('Data successfully stored in the database.');

        return Command::SUCCESS;
    }
}
