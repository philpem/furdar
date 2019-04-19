<?php



namespace index\forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class LogInUserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, array(
            'label'=>'Email',
            'required'=>true,
            'attr' => array('autofocus' => 'autofocus')
        ));
        
        $builder->add('password', PasswordType::class, array(
            'label'=>'Password',
            'required'=>true
        ));

        $builder->add(
            "rememberme",
            CheckboxType::class,
            array(
                'required' => false,
                'label' => 'Remember Me'
            )
        );
        $builder->get('rememberme')->setData(true);
    }
    
    public function getName()
    {
        return 'LogInUserForm';
    }
}
