<?php
/*
 * An AlphaLemonCms Block
 */

namespace {{ namespace }}\Core\Block;

use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManager;

/**
 * Description of AlBlockManager{{ bundle_basename }}
 */
class AlBlockManager{{ bundle_basename }} extends AlBlockManager
{
    public function getDefaultValue()
    {
        return array('HtmlContent' => '<p>Default content</p>');
    }
}