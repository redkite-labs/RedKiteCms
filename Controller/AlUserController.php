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
use AlphaLemon\AlphaLemonCmsBundle\Model\AlUserQuery;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlRoleQuery;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlUserRoleQuery;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Security\AlUserType;

use AlphaLemon\AlphaLemonCmsBundle\Model\AlUserRole;
use AlphaLemon\AlphaLemonCmsBundle\Core\Form\Security\AlUserRoleType;

/**
 * Manages the CMS users
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlUserController extends Controller
{
    public function listAction()
    {
        return $this->loadUsers();
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
        
        return $this->render('AlphaLemonCmsBundle:Security:list.html.twig', array(
            'users' => $users,
        ));
    }


    /*
    public function saveAction($id)
    {        
        $user = ($id > 0) ? AlUserQuery::create()->findPk($id) : new AlUser();
        $roles = \AlphaLemon\AlphaLemonCmsBundle\Model\AlRoleQuery::create()->find();
        if(null === $user)
        {
            $this->container->get('session')->setFlash('error', 'The user you trying to edit does not exists');
            
            return $this->redirect($this->generateUrl('_user_list'));
        }
        
        $form = $this->createForm(new AlUserType(), $user);

        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request->get('user'));

            if ($form->isValid()) {
                $user->save();
                
               

                return $this->redirect($this->generateUrl('_user_list'));
            }
        }
        
        return $this->render('AlphaLemonCmsBundle:Security:user.html.twig', array(
            'form' => $form->createView(), 
            'roles' => $roles,
        ));
    }
    
    public function deleteAction($id)
    {
        $user = AlUserQuery::create()->findPk($id);
        if(null === $user)
        {
            $this->container->get('session')->setFlash('error', 'The user you trying to delete does not exists');
        }
        $user->delete();
        
        return $this->redirect($this->generateUrl('_user_list'));
    }*/
}

