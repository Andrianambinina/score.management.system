<?php

namespace App\Score\Service\MetierManagerBundle\Command;

use App\Score\Service\MetierManagerBundle\Utils\ServiceName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class ImportStudentCommand
 * @package App\Score\Service\MetierManagerBundle\Command
 */
class ImportStudentCommand extends Command
{
    private $_container;

    public function __construct(Container $_container)
    {
        $this->_container = $_container;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('import:student')
            ->setDescription('Students import');
    }

    protected function execute(InputInterface $_input, OutputInterface $_output)
    {
        // Get manager
        $_student_manager = $this->_container->get(ServiceName::SRV_METIER_ETUDIANT);

        $_student_manager->importStudent();

        $_output->writeln('Commande executer');
    }
}