<?php

namespace org\openacalendar\curatedlists\site\forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CuratedListEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label'=>'Title',
            'required'=>true,
            'constraints' => new \Symfony\Component\Validator\Constraints\Length(array('min'=>1,'max'=>VARCHAR_COLUMN_LENGTH_USED)),
            'attr' => array('autofocus' => 'autofocus')
        ));
        $builder->add('description', TextareaType::class, array(
            'label'=>'Description',
            'required'=>false
        ));
    }
    
    public function getName()
    {
        return 'CuratedListEditForm';
    }
}
