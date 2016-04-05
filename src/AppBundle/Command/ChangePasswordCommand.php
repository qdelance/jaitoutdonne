<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

/**
 * A command console that updates a user's password
 *
 *     $ php app/console app:change-password
 *
 * @author Quentin Delance <quentin.delance@gmail.com>
 */
class ChangePasswordCommand extends ContainerAwareCommand
{
    const MAX_ATTEMPTS = 5;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            // a good practice is to use the 'app:' prefix to group all your custom application commands
            ->setName('jaitoutdonne:change-password')
            ->setDescription('Change a user\'s password in the database')
            ->setHelp($this->getCommandHelp())
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the new user')
            ->addArgument('new-password', InputArgument::OPTIONAL, 'The plain password of the new user')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('username') && null !== $input->getArgument('password') && null !== $input->getArgument('email')) {
            return;
        }

        $output->writeln('');
        $output->writeln('Change User Password Command Interactive Wizard');
        $output->writeln('-----------------------------------------------');

        $output->writeln(array(
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
            '',
        ));

        $console = $this->getHelper('question');

        // Ask for the username if it's not defined
        $username = $input->getArgument('username');
        if (null === $username) {
            $question = new Question(' > <info>Username</info>: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException('The username cannot be empty');
                }

                return $answer;
            });
            $question->setMaxAttempts(self::MAX_ATTEMPTS);

            $username = $console->ask($input, $output, $question);
            $input->setArgument('username', $username);
        } else {
            $output->writeln(' > <info>Username</info>: '.$username);
        }

        $newPassword = $input->getArgument('new-password');
        if (null === $newPassword) {
            $question = new Question(' > <info>New Password</info> (your type will be hidden): ');
            $question->setValidator(array($this, 'passwordValidator'));
            $question->setHidden(true);
            $question->setMaxAttempts(self::MAX_ATTEMPTS);

            $newPassword = $console->ask($input, $output, $question);
            $input->setArgument('new-password', $newPassword);
        } else {
            $output->writeln(' > <info>New Password</info>: '.str_repeat('*', strlen($newPassword)));
        }
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $username = $input->getArgument('username');
        $plainPassword = $input->getArgument('new-password');

        // first check if a user with the same username already exists
        $existingUser = $this->entityManager->getRepository('AppBundle:User')->findOneBy(array('username' => $username));

        if (null === $existingUser) {
            throw new \RuntimeException(sprintf('Cannot find a user with the "%s" username.', $username));
        }

        $encoder = $this->getContainer()->get('security.password_encoder');
        $encodedPassword = $encoder->encodePassword($existingUser, $plainPassword);
        $existingUser->setPassword($encodedPassword);

        $this->entityManager->persist($existingUser);
        $this->entityManager->flush();

        $output->writeln('');
        $output->writeln(sprintf('[OK] password for user %s was successfully updated', $existingUser->getUsername()));

        if ($output->isVerbose()) {
            $finishTime = microtime(true);
            $elapsedTime = $finishTime - $startTime;

            $output->writeln(sprintf('[INFO] Elapsed time: %.2f ms', $elapsedTime*1000));
        }
    }

    /**
     * This internal method should be private, but it's declared as public to
     * maintain PHP 5.3 compatibility when using it in a callback.
     *
     * @internal
     */
    public function passwordValidator($plainPassword)
    {
        if (empty($plainPassword)) {
            throw new \Exception('The password can not be empty');
        }

        if (strlen(trim($plainPassword)) < 6) {
            throw new \Exception('The password must be at least 6 characters long');
        }

        return $plainPassword;
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp()
    {
        return <<<HELP
The <info>%command.name%</info> command updates a user's password:

  <info>php %command.full_name%</info> <comment>username new-password</comment>

If you omit any of the three required arguments, the command will ask you to
provide the missing values:

  # command will ask you for the new password
  <info>php %command.full_name%</info> <comment>username</comment>

  # command will ask you username and new password
  <info>php %command.full_name%</info>

HELP;
    }
}
