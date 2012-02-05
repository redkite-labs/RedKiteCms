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
use AlphaLemon\AlphaLemonCmsBundle\Model\AlUserQuery;
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
        $users = AlUserQuery::create()->find();
        
        return $this->render('AlphaLemonCmsBundle:Security:list.html.twig', array(
            'users' => $users,
        ));
    }
    
    public function saveAction($id)
    {        
        $user = ($id > 0) ? AlUserQuery::create()->findPk($id) : new AlUser();
        if(null === $user)
        {
            $this->container->get('session')->setFlash('error', 'The user you trying to edit does not exists');
            
            return $this->redirect($this->generateUrl('_user_list'));
        }
        
        $form = $this->createForm(new AlUserType(), $user);

        $request = $this->getRequest();

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $user->save();

                return $this->redirect($this->generateUrl('_user_list'));
            }
        }
        
        return $this->render('AlphaLemonCmsBundle:Security:user.html.twig', array(
            'form' => $form->createView(),
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
    }
}

