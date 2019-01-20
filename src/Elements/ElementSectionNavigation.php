<?php

namespace Dynamic\Elements\Section\Elements;

use DNADesign\Elemental\Models\BaseElement;
use DNADesign\Elemental\Models\ElementalArea;
use DNADesign\ElementalList\Model\ElementList;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;

/**
 * Class ElementSectionNavigation.
 */
class ElementSectionNavigation extends BaseElement
{
    /**
     * @var string
     */
    private static $icon = 'sectionnav-icon';

    /**
     * @var string
     */
    private static $singular_name = 'Section Navigation Element';

    /**
     * @var string
     */
    private static $plural_name = 'Section Navigation Elements';

    /**
     * @var string
     */
    private static $table_name = 'ElementSectionNavigation';

    /**
     * @return null|\SilverStripe\ORM\DataObject
     * @throws \SilverStripe\ORM\ValidationException
     */
    public function getPage()
    {
        $area = $this->Parent();

        if ($area instanceof ElementalArea && $area->exists()) {
            if ($area->getOwnerPage() instanceof ElementList && $area->getOwnerPage()->exists()) {
                return $area->getOwnerPage()->getPage();
            } else {
                return $area->getOwnerPage();
            }
        }
        return parent::getPage();
    }

    /**
     * @return bool|\SilverStripe\ORM\SS_List
     */
    public function getSectionNavigation()
    {
        if ($page = $this->getPage()) {
            if ($page->Children()->Count() > 0) {
                return $page->Children();
            } elseif ($page->Parent()) {
                return $page->Parent()->Children();
            } else {
                return false;
            }
        }

        return false;
    }

    /**
     * @return DBHTMLText
     */
    public function getSummary()
    {
        if ($this->getPage()) {
            return DBField::create_field('HTMLText', 'Navigation for ' . $this->getPage()->Title)->Summary(20);
        }
        return DBField::create_field('HTMLText', '<p>Section Navigation</p>')->Summary(20);
    }

    /**
     * @return array
     */
    protected function provideBlockSchema()
    {
        $blockSchema = parent::provideBlockSchema();
        $blockSchema['content'] = $this->getSummary();
        return $blockSchema;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__.'.BlockType', 'Section Navigation');
    }
}
