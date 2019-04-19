<?php

namespace site\forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class AdminVisibilityPublicForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('is_web_robots_allowed', CheckboxType::class, array(
            'label'=>'Allow search engines to list',
            'required'=>false
        ));
        
        if (!$options['config']->isSingleSiteMode) {
            $builder->add('is_listed_in_index', CheckboxType::class, array(
                'label'=>'List is directory for others to discover',
                'required'=>false
            ));
        }
    }
    
    public function getName()
    {
        return 'AdminVisibilityPublicForm';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'config' => null,
        ));
    }
}
