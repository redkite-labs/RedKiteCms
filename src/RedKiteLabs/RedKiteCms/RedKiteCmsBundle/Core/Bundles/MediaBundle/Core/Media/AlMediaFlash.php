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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Bundles\MediaBundle\Core\Media;

/**
 * AlMediaFlash
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlMediaFlash extends AlMediaExt
{

  public function __construct($src, $options = array())
  {
    $this->skeleton = '<object width="%1$d" height="%2$d" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7">';
    $this->skeleton .= '  <param name="movie" value="%3$s">';
    $this->skeleton .= '    <embed src="%3$s" width="%1$d" height="%2$d" autoplay="true" wmode="transparent">';
    $this->skeleton .= '  </embed>';
    $this->skeleton .= '</object>';

    parent::__construct($src, $options);
  }

  public function render()
  {
    return sprintf($this->skeleton, $this->width, $this->height, $this->absoluteSrcPath);
  }
}