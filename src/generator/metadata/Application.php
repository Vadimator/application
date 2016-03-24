<?php
//[PHPCOMPRESSOR(remove,start)]
namespace samsoncms\application\generator\metadata;

/**
 * Created by Vitaly Iegorov <egorov@samsonos.com>.
 * on 23.03.16 at 16:21
 */
class Application extends \samsoncms\api\generator\metadata\Generic
{
    /** @var string Generate application from current entity */
    public $generateApplication;

    /** @var string Show application from current entity */
    public $showApplication;

    /** @var string Icon for application from current entity */
    public $iconApplication;

    /** @var string Icon for application from current entity */
    public $renderMainApplication;

    /** @var array Collection of entity nested entities identifiers */
    public $childNavigationIDs = array();

    /** @var array Collection of entity additional field id to its value column name */
    public $showFieldsInList = array();

    /** @var array Collection of application custom additional field renderer */
    public $customTypeFields = array();
}
//[PHPCOMPRESSOR(remove,end)]
