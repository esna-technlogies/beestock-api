<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use CommonServices\UserServiceBundle\lib\UserManagerService;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class UserSetupContext implements Context, SnippetAcceptingContext
{
    /**
     * @var UserManagerService
     */
    private $userService;

    /**
     * UserSetupContext constructor.
     *
     * @param UserManagerService $userManagerService
     */
    public function __construct(UserManagerService $userManagerService)
    {
        $this->userService = $userManagerService;
    }

    /**
     *
     * @param TableNode $users
     *
     * @Given there are Users with the following details:
     */
    public function thereAreUsersWithTheFollowingDetails(TableNode $users)
    {
        foreach ($users->getColumnsHash() as $key => $val) {

            /*            $confirmationToken =
                            isset($val['confirmation_token']) && ($val['confirmation_token'] != '')
                                ? $val['confirmation_token']: null;*/

            $user = $this->userService->createNewUser();

            try{
                $this->userService->addNewUser($user, $val);
            }

            catch (Exception $e){
                print_r(\CommonServices\UserServiceBundle\Exception\InvalidFormException::$formErrors);

            }


        }
    }
}