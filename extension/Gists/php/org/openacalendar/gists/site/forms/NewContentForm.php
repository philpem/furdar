<?php

namespace org\openacalendar\gists\site\forms;

use Silex\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class NewContentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('content_title', TextType::class, array(
            'label'=>'Title',
            'required'=>false,
            'attr' => array('autofocus' => 'autofocus')
        ));
        $builder->add('content_text', TextareaType::class, array(
            'label'=>'Text',
            'required'=>false
        ));

        $builder->add('event_slug', NumberType::class, array(
            'label'=>'Event Slug',
            'required'=>false,
            'mapped'=>false,
        ));
    }

    public function getName()
    {
        return 'GistContentNewForm';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
        );
    }
}
