<?php



namespace index\forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;

use \ExtensionManager;
use repositories\UserNotificationPreferenceRepository;
use models\UserAccountModel;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserEmailsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = array(
            'None'=>'n',
            'Events you are attending'=>'a',
            'Events you are or might be attending'=>'m',
            'Events you are or might be attending, or you watch'=>'w',
        );
        $builder->add('email_upcoming_events', ChoiceType::class, array('label'=>'Which events shall we tell you about?','required'=>true,'choices'=>$choices,'expanded'=>true, 'choices_as_values'=>true));

        $builder->add(
            "email_upcoming_events_days_notice",
            IntegerType::class,
            array(
                'required'=>true,
                'label'=>'How many days notice do you want?'
            )
        );

        $repo = new \repositories\UserNotificationPreferenceRepository($options['app']);

        foreach ($options['preferences'] as $key=>$preference) {
            $userPref = $repo->load(
                $options['user'],
                $preference->getUserNotificationPreferenceExtensionID(),
                $preference->getUserNotificationPreferenceType()
            );

            $builder->add(
                $key,
                CheckboxType::class,
                array(
                    'required' => false,
                    'label' => $preference->getLabel(),
                    'mapped' => false,
                    'data' => $userPref->getIsEmail(),
                )
            );
        }
    }

    public function getName()
    {
        return 'UserEmailsForm';
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'app' => null,
            'user' => null,
            'preferences' => null,
        ));
    }
}
