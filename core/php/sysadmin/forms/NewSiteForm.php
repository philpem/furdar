<?php



namespace sysadmin\forms;

use Silex\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class NewSiteForm extends AbstractType
{


    /** @var Application */
    protected $app;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->app = $options['app'];

        $builder->add('email', EmailType::class, array('label'=>'Email Of Owner','required'=>true));

        // The rest of this is duplicated from index\forms\CreateForm

        $builder->add('title', TextType::class, array(
            'label'=>'Title',
            'required'=>true,
            'constraints' => new \Symfony\Component\Validator\Constraints\Length(array('min'=>1,'max'=>VARCHAR_COLUMN_LENGTH_USED)),
        ));

        $builder->add('slug', TextType::class, array(
            'label'=>'Slug For Web Address',
            'required'=>true,
            'constraints' => new \Symfony\Component\Validator\Constraints\Length(array('min'=>1,'max'=>VARCHAR_COLUMN_LENGTH_USED)),
        ));

        $myExtraFieldValidator = function (FormEvent $event) {
            $form = $event->getForm();
            $myExtraField = $form->get('slug')->getData();
            if (!ctype_alnum($myExtraField) || strlen($myExtraField) < 2) {
                $form['slug']->addError(new FormError("Numbers and letters only, at least 2."));
            } elseif (in_array($myExtraField, $this->app['config']->siteSlugReserved)) {
                $form['slug']->addError(new FormError("That is already taken."));
            }
        };
        $builder->addEventListener(FormEvents::POST_SUBMIT, $myExtraFieldValidator);

        $readChoices = array(
            'Public, and listed on search engines and our directory'=>'public',
            'Public, but not listed so only people who know about it can find it'=>'protected',
        );
        $builder->add('read', ChoiceType::class, array('label'=>'Who can read?','required'=>true,'choices'=>$readChoices,'expanded'=>true, 'choices_as_values'=>true));
        $builder->get('read')->setData('public');

        $writeChoices = array(
            'Anyone can add data'=>'public',
            'Only people I say can add data'=>'protected',
        );
        $builder->add('write', ChoiceType::class, array('label'=>'Who can write?','required'=>true,'choices'=>$writeChoices,'expanded'=>true, 'choices_as_values'=>true));
        $builder->get('write')->setData('public');
    }
    
    public function getName()
    {
        return 'NewSiteForm';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'app' => null,
        ));
    }
}
