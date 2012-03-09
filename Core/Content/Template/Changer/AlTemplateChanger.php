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

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\Changer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\AlSlotManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Model\AlBlockQuery;
use AlphaLemon\ThemeEngineBundle\Core\TemplateSlots\AlSlot;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlPage;
use AlphaLemon\AlphaLemonCmsBundle\Model\AlLanguage;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Template\AlTemplateManager;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Base\AlContentManagerBase;

/**
 * Arranges the page's slot contents when a page changes its template
 *
 * @author AlphaLemon <info@alphalemon.com>
 */
class AlTemplateChanger extends AlContentManagerBase
{
    protected $container;
    protected $alLanguage; 
    protected $alPage;
    protected $previousTemplate;
    protected $newTemplate; 
    protected $operations = array();

    /**
     * Constructor
     * 
     * @param ContainerInterface    $container
     * @param string                $previousTemplate       The previous template name
     * @param string                $newTemplate            The new template name
     * @param AlPage                $alPage                 The AlPage object where the contents will live or null to use the curren page
     * @param AlLanguage            $alLanguage             The AlLanguage object where the contents will live or null to use the curren page
     */
    public function __construct(ContainerInterface $container, AlTemplateManager $previousTemplate, AlTemplateManager $newTemplate, AlPage $alPage = null, AlLanguage $alLanguage = null) 
    {
        parent::__construct($container);

        $this->container = $container;
        $this->alPage = (null === $alPage) ? $this->container->get('al_page_tree')->getAlPage() : $alPage;
        $this->alLanguage = (null === $alLanguage) ? $this->container->get('al_page_tree')->getAlLanguage() : $alLanguage; 
        $this->previousTemplate = $previousTemplate;
        $this->newTemplate = $newTemplate; 
        
        $this->analyse();
    }
    
    /**
     * Returns the required operations to do the changing
     * @return array 
     */
    public function getOperations()
    {
        return $this->operations;
    }
    
    /**
     * Arranges the page's contents accordig the new template's slots
     */
    public function change()
    {
        try
        {
            $rollBack = false;
            $this->connection->beginTransaction();
            
            $templateName = \ucfirst($this->newTemplate->getTemplateName()); 
            foreach($this->operations as $operation => $slots)
            {
                switch($operation)
                {
                    case 'add':
                        foreach($slots as $repeated => $slotNames)
                        {
                            foreach($slotNames as $slotName)
                            {
                                $slot = new AlSlot($slotName, array('repeated' => $repeated));                            
                                $slotManager = new AlSlotManager($this->container, $slot, $this->alPage, $this->alLanguage);
                                $result = $slotManager->addBlock();
                                if(null !== $result)
                                {   
                                    $rollBack = !$result;
                                    if($rollBack) break;
                                }
                            }
                            if($rollBack) break;
                        }
                        break;

                    case 'change':
                        foreach($slots as $intersections)
                        {
                            foreach($intersections as $oldRepeated => $intersection)
                            {
                                foreach($intersection as $repeated => $slotNames)
                                {
                                    foreach($slotNames as $slotName)
                                    {
                                        $slot = new AlSlot($slotName, array('repeated' => $repeated)); 
                                        $className = '\AlphaLemon\AlphaLemonCmsBundle\Core\Content\Slot\Repeated\Converter\AlSlotConverterTo' . ucfirst(strtolower($repeated));
                                        $converter = new $className($this->container, $slot, $this->alPage, $this->alLanguage);
                                        $rollBack = !$converter->convert();
                                        if($rollBack) break;
                                    }
                                    if($rollBack) break;
                                }
                                if($rollBack) break;
                            }
                            if($rollBack) break;
                        }
                        break;

                    case 'remove':
                        foreach($slots as $slotNames)
                        {
                            AlBlockQuery::create()->setContainer($this->container)->fromPageIdAndSlotName(array(1, $this->alPage->getId()), $slotNames)->delete();
                        }
                        break;
                }
                if($rollBack) break;
            }

            if (!$rollBack)
            {
                $this->connection->commit(); 
                return true;
            }
            else
            {
                $this->connection->rollback();
                return false;
            }
        }
        catch(\Exception $e)
        {
            if(isset($this->connection) && $this->connection !== null) $this->connection->rollback();
            throw $e;
        }
    }

    /**
     * Analyzes both the templates and retrieves the slot's differences. A slot can be added, removed or changed,
     * where changed means that the slot has changed how the contents are repeated. 
     * 
     * This method fills up the operations array where are saved the information required to change the template
     */
    private function analyse()
    {
        $previousSlots = $this->previousTemplate->getTemplateSlots()->toArray();
        $newSlots = $this->newTemplate->getTemplateSlots()->toArray();
        
        $diffsForNew = $this->calculateDifferences($newSlots, $previousSlots);
        $diffsForPrevious = $this->calculateDifferences($previousSlots, $newSlots);
        
        $add = $this->calculateIntersections($diffsForNew, $diffsForPrevious); 
        $remove = $this->calculateIntersections($diffsForPrevious, $diffsForNew);
        
        $this->operations['add'] = (array_key_exists('found', $add)) ? $add['found'] : array();
        $this->operations['change'] = (array_key_exists('intersected', $add)) ? $add['intersected'] : array();
        $this->operations['remove'] = (array_key_exists('found', $remove)) ? $remove['found'] : array();
    }
    
    /**
     * Calculates the differences between two arrays of slots
     * 
     * @param array $first
     * @param array $second
     * @return array 
     */
    private function calculateDifferences(array $first, array $second)
    {
        $result = array();
        foreach($first as $repeated => $slots)
        {
            $diff = array_diff($slots, $second[$repeated]);
            $result[$repeated] = $diff;
        }
        
        return $result;
    }
    
    /**
     * Calculates the intersections between the differences found on the arrays of slots
     * 
     * @param array $first
     * @param array $second
     * @return array
     */
    private function calculateIntersections(array $first, array $second)
    {
        $result = array();
        foreach($first as $aRepeated => $firstSlots)
        {
            $intersect = array();
            foreach($second as $bRepeated => $secondSlots)
            {
                $diff = array_intersect($firstSlots, $secondSlots); 
                if(!empty($diff))
                {
                    $intersect[$bRepeated][$aRepeated] = $diff;
                    $firstSlots = array_diff($firstSlots, $diff); 
                }
            }
            
            if(!empty($firstSlots))
            {
                $result['found'][$aRepeated] = $firstSlots;
            }
            
            if(!empty($intersect))
            {
                $result['intersected'][] = $intersect;
            }
        }
        
        return $result;
    }
}