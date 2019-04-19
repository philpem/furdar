<?php

namespace customfieldtypes\event;

use InterfaceEventCustomFieldType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TextMultiLineEventCustomFieldType implements InterfaceEventCustomFieldType
{
    public function getSymfonyFormType(\models\EventCustomFieldDefinitionModel $eventCustomFieldDefinitionModel)
    {
        return TextareaType::class;
    }

    public function getSymfonyFormOptions(\models\EventCustomFieldDefinitionModel $eventCustomFieldDefinitionModel)
    {
        return array(
            'label'=>$eventCustomFieldDefinitionModel->getLabel(),
            'mapped'=>false,
            'required'=>false,
        );
    }
}
