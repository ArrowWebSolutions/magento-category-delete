<?php

require_once 'abstract.php';

/**
 * Script for automatic category tree creation.
 *
 * @category    Mage
 * @package     Mage_Shell
 * @author      Arron King (Arrow Web Solutions Ltd.) - info@arrowdesign.co.uk
 */
class Mage_Shell_DeleteCategories extends Mage_Shell_Abstract
{
    /**
     * Main script method that deletes (or counts) categories between certain
     * limits
     */
    public function run()
    {
        $from = $this->getArg('from');
        $to = $this->getArg('to');
        
        $dryRun = $this->getArg('dry-run');
        
        $categories = Mage::getModel('catalog/category')->getCollection();
        $categories
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('id');
        
        if ($from && intval($from) == $from)
        {
            $categories
                ->addAttributeToFilter('entity_id', array(
                    'gteq' => $from
                ));
        }
        
        if ($to && intval($to) == $to)
        {
            $categories
                ->addAttributeToFilter('entity_id', array(
                   'lteq' => $to 
                ));
        }
        
        $categories->load();
        
        $count = $categories->count();
        
        if ($dryRun)
        {
            echo "Categories that would be deleted: {$count}" . PHP_EOL;
        }
        else
        {
            $categories->delete();
            echo "Deleted {$count} categories." . PHP_EOL;
        }
    }

    /**
     * List of available script options.
     *
     * @return string
     */
    public function usageHelp()
    {
        return <<< USAGE
Usage:  php -f delete_categories.php -- [options]

  --from [from]     The category id to delete from (inclusive)
  --to [to]         The category id to delete to (inclusive)
  --dry-run         Don't delete, just output how many categories would be delete
  -h            Short alias for help
  help          This help

USAGE;
    }
}

if (php_sapi_name() != 'cli') {
    exit('Run it from cli.');
}

$shell = new Mage_Shell_DeleteCategories();
$shell->run();
