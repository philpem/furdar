<?php

namespace site\forms;

use Silex\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use repositories\builders\CountryRepositoryBuilder;
use models\SiteModel;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label'=>'Title',
            'required'=>false,
            'constraints' => new \Symfony\Component\Validator\Constraints\Length(array('min'=>1,'max'=>VARCHAR_COLUMN_LENGTH_USED)),
        ));

        /**
        $builder->add("is_manual_events_creation",
            CheckboxType::class,
            array(
                'required'=>false,
                'label'=>'Do you want to create events manually from this import?'
            )
        );
         * **/

        $crb = new CountryRepositoryBuilder($options['app']);
        $crb->setSiteIn($options['site']);
        $countries = array();
        foreach ($crb->fetchAll() as $country) {
            $countries[$country->getTitle()] = $country->getId();
        }
        // TODO if current country not in list add it now
        $builder->add('country_id', ChoiceType::class, array(
            'label'=>'Country',
            'choices' => $countries,
            'required' => true,
            'choices_as_values' => true,
        ));
    }
    
    public function getName()
    {
        return 'ImportEditForm';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'site' => null,
            'app' => null,
        ));
    }
}
