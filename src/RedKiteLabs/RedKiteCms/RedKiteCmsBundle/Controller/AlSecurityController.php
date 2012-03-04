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
use Symfony\Component\HttpFoundation\Response;

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
        
        $response = null;
        if ($request->isXmlHttpRequest()) { 
            $response = new Response();
            $response->setStatusCode('403');
        }
        
        $alPage = \AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlPageQuery::create()->homePage()->findOne();
        $alLanguage = \AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlLanguageQuery::create()->mainLanguage()->findOne();
        
        return $this->render('AlphaLemonCmsBundle:Security:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'language_name'     => $alLanguage->getLanguage(),
            'page_name'     => $alPage->getPageName(),
        ), $response);
    }
    
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    public function logoutAction()
    {
        // The security layer will intercept this request
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
        $isNewUser = (null !== $request->get('id') && 0 != $request->get('id')) ? false : true;
        $user = (!$isNewUser) ? AlUserQuery::create()->findPk($request->get('id')) : new AlUser();
        $form = $this->createForm(new AlUserType(), $user);
        
        $errors = array();
        if ('POST' === $request->getMethod()) {
            try {
                $userProxy = $this->get('security.context')->getToken()->getUser();
                
                if($isNewUser || $request->get('al_password') != $user->getPassword()) { 
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($userProxy);
                
                    $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
                    $password = $encoder->encodePassword($request->get('al_password'), $salt);
                    
                    $user->setSalt($salt);
                    $user->setPassword($password);
                }
                
                $user->setRoleId($request->get('al_role_id'));
                $user->setUsername($request->get('al_username'));                
                $user->setEmail($request->get('al_email'));

                $validator = $this->get('validator');
                $errors = $validator->validate($user);
                if(count($errors) == 0) { 
                    $user->save();
                    return $this->loadUsers();
                }
            }
            catch(\PropelException $e)
            {
                $response = new Response();
                $response->setStatusCode('404');
                return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
            }
        }
        
        return $this->render('AlphaLemonCmsBundle:Security:user.html.twig', array(
            'form' => $form->createView(),
            'errors' => $errors,
        ));
    }
    
    public function showRoleAction()
    {
        $request = $this->getRequest();
        $role = (null !== $request->get('id') && 0 != $request->get('id')) ? AlRoleQuery::create()->findPk($request->get('id')) : new AlRole();
        
        $form = $this->createForm(new AlRoleType(), $role);
        
        $errors = array();
        if ('POST' === $request->getMethod()) {
            try {
                $request = $this->getRequest();
                
                $role = (null !== $request->get('id') && 0 != $request->get('id')) ? AlRoleQuery::create()->findPk($request->get('id')) : new AlRole();
                $role->setRole($request->get('al_rolename'));
                $validator = $this->get('validator');
                $errors = $validator->validate($role);
                if(count($errors) == 0) { 
                    $role->save();
                    return $this->loadRoles();
                }
            }
            catch(\PropelException $e)
            {
                $response = new Response();
                $response->setStatusCode('404');
                return $this->render('AlphaLemonPageTreeBundle:Error:ajax_error.html.twig', array('message' => $e->getMessage()), $response);
            }
        }
        
        return $this->render('AlphaLemonCmsBundle:Security:role.html.twig', array(
            'form' => $form->createView(),
            'errors' => $errors,
        ));
    }
            
    public function deleteUserAction()
    {        
        try
        {
            $request = $this->getRequest();
            if (null !== $request->get('id'))
            {
                $user = AlUserQuery::create()->findPk($request->get('id'));
                $user->delete();
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
    
    public function deleteRoleAction()
    {        
        try
        {
            $request = $this->getRequest();
            if (null !== $request->get('id'))
            {
                $user = AlRoleQuery::create()->findPk($request->get('id'));
                $user->delete();
            }
            
            return $this->loadRoles();
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
        $users = AlUserQuery::create()->find(); 
        
        return $this->render('AlphaLemonCmsBundle:Security:users_list.html.twig', array(
            'users' => $users,
        ));
    }
    
    private function loadRoles()
    {
        $roles = AlRoleQuery::create()->find();
        
        return $this->render('AlphaLemonCmsBundle:Security:roles_list.html.twig', array(
            'roles' => $roles,
        ));
    }
}

