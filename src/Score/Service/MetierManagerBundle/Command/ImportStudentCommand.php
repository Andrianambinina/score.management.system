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

    /**
     * ImportStudentCommand constructor.
     * @param Container $_container
     */
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

    /**
     * @param InputInterface $_input
     * @param OutputInterface $_output
     * @return int|null|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    protected function execute(InputInterface $_input, OutputInterface $_output)
    {
        // Get manager
        $_student_manager = $this->_container->get(ServiceName::SRV_METIER_ETUDIANT);

        $_now = new \DateTime();
        $_output->writeln('--- <comment>Début : ' . $_now->format('d-m-Y G:i:s') . ' ---</comment>');

        $_message = "Une erreur interne s'est produite. Veuillez rééssayer";
        $_check_import = $_student_manager->importStudent($_output);
        if ($_check_import) {
            $_message = " Importation éffectué avec succés ! ";
        }
        $_output->writeln(PHP_EOL . $_message);
        $_output->writeln('--- <comment>Fin : ' . $_now->format('d-m-Y G:i:s') . ' ---</comment>');
    }
}