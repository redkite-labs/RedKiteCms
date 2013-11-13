<?php
/**
 * This file is part of the RedKiteCmsBunde Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.redkite-labs.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace RedKiteLabs\RedKiteCmsBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use RedKiteLabs\RedKiteCmsBundle\Model\AlUser;
use RedKiteLabs\RedKiteCmsBundle\Model\AlRole;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\Security\AlUserType;
use RedKiteLabs\RedKiteCmsBundle\Core\Form\Security\AlRoleType;
use RedKiteLabs\RedKiteCmsBundle\Core\Exception\General\RuntimeException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Implements the authentication action to grant the use of the CMS.
 *
 * @author RedKite Labs <webmaster@redkite-labs.com>
 */
class SecurityController extends Base\BaseController
{
    private $factoryRepository = null;
    private $userRepository = null;
    private $roleRepository;

    public function loginAction()
    {
        $bootstrapVersion = $this->container->get('red_kite_cms.active_theme')->getThemeBootstrapVersion(); 
        $this->container->get('twig')->addGlobal('bootstrap_version', $bootstrapVersion);
        
        $request = $this->container->get('request');
        $params = $this->checkRequestError();
        
        $response = null;
        $template = 'RedKiteCmsBundle:Security:Login/login-form.html.twig';
        if ($request->isXmlHttpRequest()) {
            $response = new Response();
            $response->setStatusCode('403');
            $template = sprintf('RedKiteCmsBundle:Bootstrap:%s/Security/Login/login-form-ajax.html.twig', $bootstrapVersion);
        }

        $factoryRepository = $this->container->get('red_kite_cms.factory_repository');
        $pageReporitory = $factoryRepository->createRepository('Page');
        $languageReporitory = $factoryRepository->createRepository('Language');

        $alPage = $pageReporitory->homePage();
        $alLanguage = $languageReporitory->mainLanguage();
        $params['target'] = '/backend/' . $alLanguage->getLanguageName() . '/' . $alPage->getPageName();
        
        return $this->container->get('templating')->renderResponse($template, $params, $response);
    }

    /**
     * @codeCoverageIgnore
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @codeCoverageIgnore
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }

    public function listUsersAction(Request $request)
    {
        return $this->loadUsers();
    }

    public function listRolesAction(Request $request)
    {
        return $this->loadRoles($request);
    }

    public function loadUserAction(Request $request)
    {
        $values = array();
        $userId = $request->get('entityId');
        if (null !== $userId) {
            $alUser = $this->userRepository()->fromPK($userId);
            $values[] = array("name" => "#al_user_id", "value" => $alUser->getId());
            $values[] = array("name" => "#al_user_username", "value" => $alUser->getUserName());
            $values[] = array("name" => "#al_user_email", "value" => $alUser->getEmail());
            $values[] = array("name" => "#al_user_AlRole", "value" => $alUser->getRoleId());
        }

        return $this->buildJsonResponse($values);
    }

    public function loadRoleAction(Request $request)
    {
        $values = array();
        $roleId = $request->get('entityId');
        if (null !== $roleId) {
            $alRole = $this->roleRepository()->fromPK($roleId);
            $values[] = array("name" => "#al_role_id", "value" => $alRole->getId());
            $values[] = array("name" => "#al_role_role", "value" => $alRole->getRole());
        }

        return $this->buildJsonResponse($values);
    }
    
    public function saveUserAction(Request $request)
    {
        $message = '';
        $errors = array();
        if ('POST' === $request->getMethod()) { 
            $userId = $request->get('userId');
            $isNewUser = (null !== $userId && $userId != 0) ? false : true;
            $user = ( ! $isNewUser) ? $this->userRepository()->fromPk($userId) : new AlUser();
            
            $userName = $request->get('username');
            if (null !== $this->userRepository()->fromUsername($userName) && $user->getUserName() != $userName ) {
                throw new RuntimeException('exception_username_exists');
            }
            
            $user->setRoleId($request->get('roleId'));
            $user->setUsername($userName);
            $user->setPassword($request->get('password'));
            $user->setEmail($request->get('email'));

            $validator = $this->container->get('validator');
            $errors = $validator->validate($user);
            if (count($errors) > 0) {
                $message = $this->container->get('templating')->render('RedKiteCmsBundle:Security:Entities/_errors.html.twig', array(
                    'errors' => $errors,
                ));
                
                throw new RuntimeException($message);
            }
            
            $factory = $this->container->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
            $password = $encoder->encodePassword($request->get('password'), $salt);

            $user->setSalt($salt);
            $user->setPassword($password);

            $messageKey = "security_controller_user_not_saved";
            if ($user->save() > 0) {
                $messageKey = "security_controller_user_saved";
            }
            $message = $this->translate($messageKey);
        }
        
        $values = array(
            array(
                'key' => 'message',
                'value' => $message,
            ),
            array(
                'key' => 'refresh_list',
                'value' => $this->loadUsersList(),
            ),
        );
        
        return $this->buildJsonResponse($values);
    }

    public function saveRoleAction(Request $request)
    {
        $message = '';
        $errors = array();
        if ('POST' === $request->getMethod()) {
            $roleId = $request->get('roleId');
            $roleName = strtoupper($request->get('role'));
            $isNewRole = (null !== $roleId && 0 != $roleId) ? false : true;
            $role = ( ! $isNewRole) ? $this->roleRepository()->fromPK($roleId) : new AlRole();
            if (null !== $this->roleRepository()->fromRoleName($roleName) && $role->getRoleName() != $roleName ) {
                throw new RuntimeException('exception_role_exists');
            }
            
            $role->setRole($roleName);
            $validator = $this->container->get('validator');
            $errors = $validator->validate($role);
            if (count($errors) > 0) {
                $message = $this->container->get('templating')->render('RedKiteCmsBundle:Security:Entities/_errors.html.twig', array(
                    'errors' => $errors,
                ));
                
                throw new RuntimeException($message);                
            }
            
            $messageKey = "security_controller_role_not_saved";
            if ($role->save() > 0) {
                $messageKey = "security_controller_role_saved";
            }
            $message = $this->translate($messageKey);
        }
        
        $values = array(
            array(
                'key' => 'message',
                'value' => $message,
            ),
            array(
                'key' => 'refresh_list',
                'value' => $this->loadRolesList(),
            ),
        );
        
        return $this->buildJsonResponse($values);
    }

    public function deleteUserAction(Request $request)
    {
        if (null !== $request->get('id')) {
            $user = $this->userRepository()->fromPk($request->get('id'));
            $user->delete();

            $values = array(
                array(
                    'key' => 'message',
                    'value' => $this->translate('security_controller_user_removed'),
                ),
                array(
                    'key' => 'refresh_list',
                    'value' => $this->loadUsersList(),
                ),
            );

            return $this->buildJsonResponse($values);
        }
        
        throw new RuntimeException('security_controller_nothing_made');
    }

    public function deleteRoleAction(Request $request)
    {
        $roleId = $request->get('id');
        if (null !== $roleId) {
            $users = $this->userRepository()->usersByRole($roleId);
            if (count($users) > 0) {
                throw new RuntimeException('security_controller_role_in_use');
            }
            
            $user = $this->roleRepository()->fromPK($roleId);
            $user->delete();
            
            $values = array(
                array(
                    'key' => 'message',
                    'value' => $this->translate('security_controller_role_removed'),
                ),
                array(
                    'key' => 'refresh_list',
                    'value' => $this->loadRolesList(),
                ),
            );
            
            return $this->buildJsonResponse($values);
        }
        
        throw new RuntimeException('security_controller_nothing_made');
    }
    
    protected function checkRequestError()
    {
        $request = $this->container->get('request');
        $session = $request->getSession();
        
        // get the error if any (works with forward and redirect -- see below)
        $error = '';
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
        
        return array(
            "error" => $error,
            "last_username" => $lastUsername,
        );
    }

    private function loadUsers()
    {  
        $form = $this->container->get('form.factory')->create(new AlUserType(), new AlUser());
        
        return $this->container->get('templating')->renderResponse('RedKiteCmsBundle:Security:Entities/users_panel.html.twig', array(
            'users' => $this->userRepository()->activeUsers(),
            'form' => $form->createView(),
        ));
    }

    private function loadRoles()
    {
        $form = $this->container->get('form.factory')->create(new AlRoleType(), new AlRole());
        
        return $this->container->get('templating')->renderResponse('RedKiteCmsBundle:Security:Entities/roles_panel.html.twig', array(
            'roles' => $this->roleRepository()->activeRoles(),
            'form' => $form->createView(),
        ));
    }
    
    private function loadUsersList()
    { 
        return $this->container->get('templating')->render('RedKiteCmsBundle:Security:Entities/_users_list.html.twig', array(
            'users' => $this->userRepository()->activeUsers(),
        ));
    }
    
    private function loadRolesList()
    { 
        return $this->container->get('templating')->render('RedKiteCmsBundle:Security:Entities/_roles_list.html.twig', array(
            'roles' => $this->roleRepository()->activeRoles(),
        ));
    }
    
    private function factoryRepository()
    {
        if (null === $this->factoryRepository) {
            $this->factoryRepository = $this->container->get('red_kite_cms.factory_repository');
        }

        return $this->factoryRepository;
    }

    private function userRepository()
    {
        if (null === $this->userRepository) {
            $this->userRepository = $this->factoryRepository()->createRepository('User');
        }

        return $this->userRepository;
    }

    private function roleRepository()
    {
        if (null === $this->roleRepository) {
            $this->roleRepository = $this->factoryRepository()->createRepository('Role');
        }

        return $this->roleRepository;
    }
    
    private function buildJsonResponse(array $values)
    {
        $response = new Response(json_encode($values));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
