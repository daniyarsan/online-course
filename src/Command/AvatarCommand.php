<?php

namespace App\Command;

use App\Repository\UserRepository;
use App\Services\AvatarService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AvatarCommand extends Command
{
    protected static $defaultName = 'app:util';

    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private AvatarService $avatarService;

    public function __construct(UserRepository $userRepository, AvatarService $avatarService, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->avatarService = $avatarService;
        parent::__construct();

    }
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $allUsers = $this->userRepository->createQueryBuilder('u')->where('u.avatar is null')->getQuery()->getResult();

        foreach ($allUsers as $user) {
            $user->setAvatar($this->avatarService->generate(str_replace('_', '', $user->getUsername())));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            sleep(10);
            $io->success('Updated user ' . $user->getUsername());

        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
