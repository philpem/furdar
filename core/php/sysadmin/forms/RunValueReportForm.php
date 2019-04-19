<?php

namespace sysadmin\forms;

use BaseSeriesReport;
use Silex\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class RunValueReportForm extends AbstractType
{

    /** @var  BaseSeriesReport */
    protected $report;

    protected $timeZoneName = "Europe/London";

    /** @var Application */
    protected $app;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->app = $options['app'];
        $this->report = $options['report'];

        $builder
            ->add('output', ChoiceType::class, array(
                'expanded' => true,
                'choices' => array('htmlTable' => 'Table in Web Browser'),
                'data' => 'htmlTable',
            ));

        if ($this->report->getHasFilterTime()) {
            $builder->add('start_at', DateTimeType::class, array(
                'label'=>'Start Date & Time',
                'model_timezone' => 'UTC',
                'view_timezone' => $this->timeZoneName,
                'required'=>false
            ));

            $builder->add('end_at', DateTimeType::class, array(
                'label'=>'End Date & Time',
                'model_timezone' => 'UTC',
                'view_timezone' => $this->timeZoneName,
                'required'=>false
            ));
        }

        if ($this->report->getHasFilterSite()) {
            $builder->add('site_id', IntegerType::class, array(
                'label'=>'Site ID',
                'required'=>false,
                'data'=> ($this->app['config']->isSingleSiteMode ? $this->app['config']->singleSiteID : null),
            ));
        }
    }
    
    public function getName()
    {
        return 'RunReportForm';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'app' => null,
            'report' => null,
        ));
    }
}
