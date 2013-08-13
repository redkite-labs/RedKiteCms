<?php
/**
 * This file is part of the RedKite CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) RedKite Labs <webmaster@redkite-labs.com>
 *
 * For the full copyright and license infpageRepositoryation, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

class AlphaLemonPropelQuickBuilder extends \PropelQuickBuilder
{
    public function buildClasses(array $classTargets = null)
    {
        $classes = $this->getClasses($classTargets);
        
        $newClass = 'class AlUser extends BaseAlUser implements \Symfony\Component\Security\Core\User\UserInterface' . PHP_EOL;
        $newClass .= '{' . PHP_EOL;
        $newClass .= '    public function getRoles()' . PHP_EOL;
        $newClass .= '    {' . PHP_EOL;
        $newClass .= '        return array($this->getAlRole()->getRole());' . PHP_EOL;
        $newClass .= '    }' . PHP_EOL . PHP_EOL;
        $newClass .= '    public function eraseCredentials(){}' . PHP_EOL;
        $newClass .= '}';
        
        $classes = preg_replace('/class AlUser extends BaseAlUser\n{\n}/is', $newClass, $classes); 
        
        eval($classes);
    }
}
