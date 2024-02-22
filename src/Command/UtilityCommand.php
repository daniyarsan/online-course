<?php

namespace App\Command;

use App\Entity\Answer;
use App\Entity\Choice;
use App\Entity\Question;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UtilityCommand extends Command
{
    protected static $defaultName = 'app:utility';
    protected static $defaultDescription = 'Add a short description for your command';

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

        $answers = $this->entityManager->getRepository(Answer::class)->findBy([]);

        /**@var Answer $answer */
        foreach ($answers as $answer) {
            $output->writeln('<info>Processing answer '.$answer->getId().' </info>');

            /**@var ArrayCollection $questions */
            $questions = $this->entityManager->getRepository(Question::class)->findBy(['title' => $answer->getQuestionText()]);

            if (count($questions) > 1) {
                foreach ($questions as $question) {
                    $choice = $question->getChoiceByDescription($answer->getContent());
                    if ($choice) {
                        $answer->setQuestion($choice->getQuestion());
                        $this->entityManager->persist($answer);
                        $this->entityManager->flush();
                    }
                }
            } else {
                if (isset($questions[0])){
                    $answer->setQuestion($questions[0]);
                    $this->entityManager->persist($answer);
                    $this->entityManager->flush();
                }

            }
        }


        $io->success('Job is done');
        return Command::SUCCESS;
    }
}
