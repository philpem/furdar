<?php



namespace index\forms;

use Silex\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SignUpUserForm extends AbstractType
{


    /** @var Application */
    protected $app;



    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->app = $options['app'];

        $builder->add('email', EmailType::class, array(
            'label'=>'Email',
            'required'=>true,
            'constraints' => new \Symfony\Component\Validator\Constraints\Length(array('min'=>1,'max'=>VARCHAR_COLUMN_LENGTH_USED)),
            'attr' => array('autofocus' => 'autofocus'),
        ));
        $builder->add('displayname', TextType::class, array(
            'label'=>'Your Name',
            'required'=>true,
            'constraints' => new \Symfony\Component\Validator\Constraints\Length(array('min'=>1,'max'=>VARCHAR_COLUMN_LENGTH_USED)),
            'data' => 'Person',
        ));
        $builder->add('password1', PasswordType::class, array(
            'label'=>'Password',
            'required'=>true
        ));
        $builder->add('password2', PasswordType::class, array(
            'label'=>'Repeat password',
            'required'=>true
        ));

        $builder->add(
            "agree",
            CheckboxType::class,
            array(
                'required' => true,
                'label' => 'I agree to the terms and conditions'
            )
        );

        $builder->add(
            "email_about_events_interested_in",
            CheckboxType::class,
            array(
                'required' => false,
                'label' => 'Can we email you about events you express an interest in?'
            )
        );

        $builder->add(
            "email_about_edits",
            CheckboxType::class,
            array(
                'required' => false,
                'label' => 'Can we email you about any additions or edits you make to the site?'
            )
        );

        if ($this->app['config']->newUserRegisterAntiSpam) {
            $builder->add('antispam', TextType::class, array('label'=>'What is 2 + 2?','required'=>true));
            
            $myExtraFieldValidatorSpam = function (FormEvent $event) {
                $form = $event->getForm();
                $myExtraField = $form->get('antispam')->getData();
                if ($myExtraField != '4' &&  $myExtraField != 'four') {
                    $form['antispam']->addError(new FormError("Please prove you are human"));
                }
            };
            $builder->addEventListener(FormEvents::POST_SUBMIT, $myExtraFieldValidatorSpam);
        }
        
        /** agree to terms **/
        $myExtraFieldValidator1 = function (FormEvent $event) {
            $form = $event->getForm();
            $myExtraField = $form->get('agree')->getData();
            if (empty($myExtraField)) {
                $form['agree']->addError(new FormError("Please agree to the terms and conditions"));
            }
        };
        $builder->addEventListener(FormEvents::POST_SUBMIT, $myExtraFieldValidator1);
        
        
        /** email looks real **/
        $myExtraFieldValidator2 = function (FormEvent $event) {
            $form = $event->getForm();
            $myExtraField = $form->get('email')->getData();
            if (!filter_var($myExtraField, FILTER_VALIDATE_EMAIL)) {
                $form['email']->addError(new FormError("Please enter a email address"));
            }
        };
        $builder->addEventListener(FormEvents::POST_SUBMIT, $myExtraFieldValidator2);

        
        /** passwords **/
        $myExtraFieldValidator4 = function (FormEvent $event) {
            $form = $event->getForm();
            $myExtraField1 = $form->get('password1')->getData();
            $myExtraField2 = $form->get('password2')->getData();
            if (strlen($myExtraField1) < 2) {
                $form['password1']->addError(new FormError("Please choose a password with at least 2 characters."));
            }
            if ($myExtraField1 != $myExtraField2) {
                $form['password2']->addError(new FormError("Please enter your password again; they did not match."));
            }
        };
        $builder->addEventListener(FormEvents::POST_SUBMIT, $myExtraFieldValidator4);
    }
    
    public function getName()
    {
        return 'SignUpUserForm';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'app' => null,
        ));
    }
}
