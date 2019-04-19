<?php

namespace site\forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteEditProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label'=>'Title',
            'required'=>false,
            'constraints' => new \Symfony\Component\Validator\Constraints\Length(array('min'=>1,'max'=>VARCHAR_COLUMN_LENGTH_USED)),
            'attr' => array('autofocus' => 'autofocus')
        ));
        
        $builder->add('description_text', TextareaType::class, array(
            'label'=>'Description',
            'required'=>false
        ));
        
        $builder->add('footer_text', TextareaType::class, array(
            'label'=>'Footer Text',
            'required'=>false
        ));
        
        if ($options['siteLogoAllowed']) {
            $builder->add('logo', FileType::class, array(
                "mapped" => false,
                'label'=>'Upload new Logo',
                'required'=>false
            ));
        }
    }
    
    public function getName()
    {
        return 'SiteEditProfileForm';
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'siteLogoAllowed' => null,
        ));
    }
}
