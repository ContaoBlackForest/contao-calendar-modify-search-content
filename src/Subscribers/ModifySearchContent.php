<?php
/**
 * FRAMEWORK
 *
 * Copyright (C) FRAMEWORK
 *
 * @package   brugg-regio-ch
 * @file      ModifySearchContent.php
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2015 owner
 */


namespace ContaoBlackForest\Modify\Search\Content\Calendar\Subscribers;


use ContaoBlackForest\Modify\Search\Content\Event\AddDataForContentEvent;
use ContaoBlackForest\Modify\Search\Content\ModifySearchContentEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ModifySearchContent
 *
 * @package ContaoBlackForest\Modify\Search\Content\Calendar\Subscribers
 */
class ModifySearchContent implements EventSubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array
        (
            ModifySearchContentEvents::ADD_DATA_FOR_CONTENT => array
            (
                array('addDataForContent'),
            ),
        );
    }

    /**
     * @param AddDataForContentEvent $event
     */
    public function addDataForContent(AddDataForContentEvent $event)
    {
        if ((!$event->getModule() instanceof \ModuleEventReader)
            && (\Input::get('auto_item'))
        ) {
            return;
        }

        $alias = \Input::get('auto_item');

        $model = \CalendarEventsModel::findByIdOrAlias($alias);
        if (!$model) {
            return;
        }

        global $objPage;

        $strUrl = \Environment::get('base') . TL_PATH;
        $strUrl .= \Controller::generateFrontendUrl($objPage->row(), '/' . $model->alias, $objPage->language, true);


        $event->setUrl($strUrl);
        $event->setTemplate('search_content_calendar');
        $event->setData($model);
    }
}
