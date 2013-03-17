<?php
/**
 * This file is part of the BusinessDropCapBundle and it is distributed
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

namespace AlphaLemon\Block\BootbusinessProductBlockBundle\Core\Listener;

use AlphaLemon\AlphaLemonCmsBundle\Core\Listener\ImagesBlock\BaseImagesBlockEditedListener;

/**
 * Renders the editor to manipulate a Json item
 *
 * @author alphalemon <webmaster@alphalemon.com>
 * 
 * @api
 */
class ProductBlockEditedListener extends BaseImagesBlockEditedListener
{
    public function configure()
    {     
        return array('images_editor_template' => 'BootstrapThumbnailBlockBundle:Images:images_list.html.twig');
    }
    
    public function getManagedBlockType()
    {     
        return 'BootbusinessProductBlock';
    }
}
