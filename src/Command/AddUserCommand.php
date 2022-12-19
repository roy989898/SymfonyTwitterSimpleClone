<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:add-user',
    description: 'Add user',
)]
class AddUserCommand extends Command
{

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher, private UserRepository $userRepository, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'email')
            ->addArgument('username', InputArgument::REQUIRED, 'username')
            ->addArgument('password', InputArgument::REQUIRED, 'password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $username = $input->getArgument('username');
        $password = $input->getArgument('password');


        $io->note(sprintf('You passed an email: %s', $email));
        $io->note(sprintf('You passed an username: %s', $username));
        $io->note(sprintf('You passed an password: %s', $password));

        $user = new User();
        $user->setIsVerified(true);
        $user->setEmail($email);
        $user->setName($username);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        $this->userRepository->save($user, true);

        $io->success('User created');

        return Command::SUCCESS;
    }
}
