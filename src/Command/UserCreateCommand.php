<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreateCommand extends Command
{
    protected const NAME = 'user:create';

    /** @var EntityManagerInterface  */
    private $em;

    /** @var UserPasswordEncoderInterface  */
    private $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct();

        $this->em = $em;
        $this->encoder = $encoder;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME)->setDescription('Creates a new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $question = new Question('Please enter email for new user: ', 'user@example.com');
        $email = $helper->ask($input, $output, $question);

        $question = new Question(\sprintf('Enter password for user %s: ', $email));
        $password = $helper->ask($input, $output, $question);

        $question = new ChoiceQuestion('Select role for new user?', array_values(User::ROLES), 0);
        $question->setErrorMessage('Role \'%s\' is invalid.');
        $role = $helper->ask($input, $output, $question);

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setRoles([$role]);
        $user->setActive(true);

        $this->em->persist($user);
        $this->em->flush();

        $io->success(\sprintf('Successfully create user %s', $email));
    }
}
