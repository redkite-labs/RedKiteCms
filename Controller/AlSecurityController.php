<?php
/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 * 
 * @license    GPL LICENSE Version 2.0
 * 
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlUser;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlRole;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlUserQuery;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlRoleQuery;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlUserRoleQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Security\AlUserType;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Security\AlRoleType;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlUserRole;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Security\AlUserRoleType;

/**
 * Implements the authentication action to grant the use of the CMS. 
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlSecurityController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
        
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
        return $this->render('AlphaLemonCmsBundle:Security:login.html.twig', array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token'    => $csrfToken,      
        ));
    }
    
    public function listUsersAction()
    {
        return $this->loadUsers();
    }
    
    public function listRolesAction()
    {
        return $this->loadRoles();
    }
    
    public function showUserAction()
    {
        $request = $this->getRequest();
        $user = (null !== $request->get('id') && 0 != $request->get('id')) ? AlUserQuery::create()->findPk($request->get('id')) : new AlUser();
        
        $roles = AlRoleQuery::create()->find();
        
        $assignedRoles = array();
        $userRoles = AlUserRoleQuery::create()->filterByAlUser($user)->find();
        foreach($userRoles as $userRole)
        {
            $assignedRoles[] = $userRole->getRoleId();
        }
        $form = $this->createForm(new AlUserType(), $user);
        
        return $this->render('AlphaLemonCmsBundle:Security:user.html.twig', array(
            'form' => $form->createView(),
            'roles' => $roles,
            'assigned_roles' => $assignedRoles,
        ));
    }
    
    public function showRoleAction()
    {
        $request = $this->getRequest();
        $role = (null !== $request->get('id') && 0 != $request->get('id')) ? AlRoleQuery::create()->findPk($request->get('id')) : new AlRole();
        
        $form = $this->createForm(new AlRoleType(), $role);
        
        return $this->render('AlphaLemonCmsBundle:Security:role.html.twig', array(
            'form' => $form->createView(),
        ));
    }
        
    public function saveUserAction()
    {        
        try
        {
            $request = $this->getRequest();
            $params = array();
            $data = explode('&', $request->get('roles'));
            foreach($data as $value) {
                $tmp = preg_split('/=/', $value);
                if($tmp[0] == 'al_role') {
                    $params[$tmp[0]][] = $tmp[1];
                }
                else {
                    $params[$tmp[0]] = $tmp[1];
                }
            }

            if(empty($params['al_role'])) {
                $response = new Response();
                $response->setStatusCode('404');
                return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => 'Any role has been choosen'), $response);
            }


            $user = (null !== $request->get('id') && 0 != $request->get('id')) ? AlUserQuery::create()->findPk($request->get('id')) : new AlUser();
            $user->setUsername($request->get('al_username'));
            $user->setPassword($request->get('al_password'));
            $user->setEmail($request->get('al_email'));
            $user->save();

            $userRoles = AlUserRoleQuery::create()->filterByAlUser($user)->delete();
            foreach($params['al_role'] as $roleId) {
                $userRole = new AlUserRole();
                $userRole->setUserId($user->getId());
                $userRole->setRoleId($roleId);
                $userRole->save();
            }
            
            return $this->loadUsers();
        }
        catch(\PropelException $e)
        {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
        }
    }
    
    public function deleteUserAction()
    {        
        try
        {
            $request = $this->getRequest();
            if (null !== $request->get('id'))
            {
                $user = AlUserQuery::create()->findPk($request->get('id'));
                $user->setToDelete(1);
                $user->save();
            }
            
            return $this->loadUsers();
        }
        catch(\PropelException $e)
        {
            $response = new Response();
            $response->setStatusCode('404');
            return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
        }
    }
    
    private function loadUsers()
    {
        $users = AlUserQuery::create()->filterByToDelete(0)->find();
        
        return $this->render('AlphaLemonCmsBundle:Security:users_list.html.twig', array(
            'users' => $users,
        ));
    }
    
    private function loadRoles()
    {
        $roles = AlRoleQuery::create()->filterByToDelete(0)->find();
        
        return $this->render('AlphaLemonCmsBundle:Security:roles_list.html.twig', array(
            'roles' => $roles,
        ));
    }
}

