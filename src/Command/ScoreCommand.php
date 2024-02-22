<?php

namespace App\Command;

use App\Entity\Answer;
use App\Entity\Choice;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ScoreCommand extends Command
{
    protected static $defaultName = 'app:score';
    protected static $defaultDescription = 'Recalculate scores of users';

    private $entityManager;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_OPTIONAL, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

//        $answers = $this->entityManager->getRepository(Answer::class)->findAll();
        $answers = $this->entityManager->getRepository(Answer::class)->findBy([]);

        /**@var Answer $answer */
        foreach ($answers as $answer) {
            $output->writeln('<info>Processing answer '.$answer->getId().' </info>');
            $question = $answer->getQuestion();

            if ($question->getId() > 0) {
                $choice = $question->getChoiceByDescription($answer->getContent());
                if ($choice) {
                    $answer->setScore($choice->getScore());
                }
                $this->entityManager->persist($answer);
                $this->entityManager->flush();
            }
        }


        $io->success('Job is done');
        return Command::SUCCESS;
    }
}
