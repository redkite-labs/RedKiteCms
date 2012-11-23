<?php

namespace AlphaLemon\Block\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ElFinderFileController extends Controller
{
    public function connectFileAction()
    {
        $connector = $this->container->get('el_finder.file_connector');
        $connector->connect();
    }
    
    public function showMediaLibraryAction()
    {
        return $this->container->get('templating')->renderResponse('FileBundle:Block:file_media_library.html.twig', array('enable_yui_compressor' => $this->container->getParameter('alpha_lemon_cms.enable_yui_compressor')));
    }
}
