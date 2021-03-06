<?php



namespace index\forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserChangePasswordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldpassword', PasswordType::class, array(
            'label'=>'Old Password',
            'required'=>true,
            'attr' => array('autofocus' => 'autofocus')
        ));
        $builder->add('password1', PasswordType::class, array(
            'label'=>'New Password',
            'required'=>true
        ));
        $builder->add('password2', PasswordType::class, array(
            'label'=>'Repeat new password',
            'required'=>true
        ));

        $myExtraFieldValidator = function (FormEvent $event) {
            $form = $event->getForm();
            $myExtraField1 = $form->get('password1')->getData();
            $myExtraField2 = $form->get('password2')->getData();
            if (strlen($myExtraField1) < 2) {
                $form['password1']->addError(new FormError("Password: at least 2 characters."));
            }
            if ($myExtraField1 != $myExtraField2) {
                $form['password2']->addError(new FormError("Passwords do not match"));
            }
        };
        $builder->addEventListener(FormEvents::POST_SUBMIT, $myExtraFieldValidator);
    }
    
    public function getName()
    {
        return 'UserChangePasswordForm';
    }
}
