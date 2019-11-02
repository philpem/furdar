<?php



namespace org\openacalendar\contact\index\forms;

use models\UserAccountModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        global $CONFIG;

        $builder->add('subject', TextType::class, array(
            'label'=>'Subject',
            'required'=>true,
            'constraints' => new \Symfony\Component\Validator\Constraints\Length(array('min'=>1,'max'=>VARCHAR_COLUMN_LENGTH_USED)),
            'attr' => array('autofocus' => 'autofocus')
        ));


        $builder->add('email', EmailType::class, array(
            'label'=>'Email',
            'required'=>true,
            'constraints' => new \Symfony\Component\Validator\Constraints\Length(array('min'=>1,'max'=>VARCHAR_COLUMN_LENGTH_USED)),
            'data' => $options['user'] ? $options['user']->getEmail() : '',
        ));
        
        $builder->add('message', TextareaType::class, array(
            'label'=>'Message',
            'required'=>true,
        ));

        
        if ($options['config']->contactFormAntiSpam && !$options['user']) {
            $builder->add('antispam', TextType::class, array('label'=>'What is 1 + 1?','required'=>true));
            
            $myExtraFieldValidatorSpam = function (FormEvent $event) {
                $form = $event->getForm();
                $myExtraField = $form->get('antispam')->getData();
                if ($myExtraField != '2' &&  $myExtraField != 'two') {
                    $form['antispam']->addError(new FormError("Please prove you are human"));
                }
            };
            $builder->addEventListener(FormEvents::POST_SUBMIT, $myExtraFieldValidatorSpam);
        }
    }
    
    public function getName()
    {
        return 'SignUpUserForm';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'config' => null,
            'user' => null,
        ));
    }
}
