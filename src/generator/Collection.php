<?php
//[PHPCOMPRESSOR(remove,start)]
/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 24.03.16 at 13:34
 */
namespace samsoncms\application\generator;

/**
 * SamsonCMS application collection class generator.
 *
 * @package samsoncms\application\generator
 */
class Collection extends \samsoncms\api\generator\Generic
{
    /** Default control class name */
    const DEFAULT_GENERIC_CONTROL_TYPE = 'Control';

    /** Custom css selector in generic constructor */
    const DEFAULT_CUSTOM_TYPE_CSS = '';

    /** User can edit field in list of application */
    const DEFAULT_CUSTOM_TYPE_EDITABLE = 'false';

    /** Field can be sortable in list of application */
    const DEFAULT_CUSTOM_TYPE_SORTABLE = 'false';

    /** Default namespace of custom types */
    const DEFAULT_CUSTOM_TYPE_NAMESPACE = '\\samsonphp\\cms\\types\\';

    /**
     * Class uses generation part.
     *
     * @param \samsoncms\api\generator\metadata\Generic $metadata Entity metadata
     */
    protected function createUses($metadata)
    {
        $this->generator
            ->text('use samsoncms\field\Control;')
            ->text('use samsoncms\field\Generic;')
            ->newLine();
    }

    /**
     * Class definition generation part.
     *
     * @param \samsoncms\application\generator\metadata\Application $metadata Entity metadata
     */
    protected function createDefinition($metadata)
    {
        $this->generator
            ->multiComment(array('Collection for SamsonCMS application "'.$metadata->name.'"'))
            ->defClass($metadata->entity, '\\'.\samsoncms\app\material\Collection::class);
    }

    /**
     * Create generic constructor for collection.
     *
     * @param        $customType
     * @param        $name
     * @param        $description
     * @param int    $type
     * @param string $css
     * @param string $editable
     * @param string $sortable
     *
     * @return string
     */
    public function createCollectionField(
        $customType,
        $name,
        $description,
        $type,
        $css = self::DEFAULT_CUSTOM_TYPE_CSS,
        $editable = self::DEFAULT_CUSTOM_TYPE_CSS,
        $sortable = self::DEFAULT_CUSTOM_TYPE_CSS
    )
    {

        // If custom type is exists then use it or use default generic type
        if ($customType) {

            // If field has namespace then use it or use default namespace
            $class = preg_match('/\\\/', $customType) ? $customType : self::DEFAULT_CUSTOM_TYPE_NAMESPACE . $customType;
        } else {

            $class = self::DEFAULT_GENERIC_TYPE;
        }


        return "\n\t\t\tnew {$class}('$name', t('$description', true), $type, '$css', $editable, $sortable)";
    }

    /**
     * Class constructor generation part.
     *
     * @param \samsoncms\application\generator\metadata\Application $metadata Entity metadata
     */
    protected function createConstructor($metadata)
    {
        $constructorCode = <<<'EOD'
    /**
     * Generic SamsonCMS application collection constructor
     *
     * @param RenderInterface $renderer View render object
     * @param QueryInterface $query Query object
     */
    public function __construct($renderer, $query = null, $pager = null)
    {
        parent::__construct($renderer, $query, $pager);

        // Generic of fields
        $this->fields = array({{fields}});
    }
EOD;
        // Iterate all application fields and create generic constructor for them
        foreach ($metadata->showFieldsInList as $fieldID) {
            // Create constructor for custom type or if it not exists then use cms defined type
            $genericFields[] = $this->createCollectionField(
                $metadata->customTypeFields[$fieldID],
                $metadata->allFieldIDs[$fieldID],
                $metadata->fieldDescriptions[$fieldID],
                $metadata->allFieldCmsTypes[$fieldID],
                self::DEFAULT_CUSTOM_TYPE_CSS,
                self::DEFAULT_CUSTOM_TYPE_EDITABLE,
                self::DEFAULT_CUSTOM_TYPE_SORTABLE
            );
        }

        $constructorCode = str_replace(
            '{{fields}}',
            implode(',', array_merge(
                    $genericFields,
                    array("\n\t\t\t" . 'new ' . self::DEFAULT_GENERIC_CONTROL_TYPE . '()' . "\n\t\t"))
            ),
            $constructorCode);

        $this->generator->text($constructorCode);
    }
}
//[PHPCOMPRESSOR(remove,end)]
