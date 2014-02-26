<?php

namespace alkr\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeedbackType extends AbstractType
{
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name','text',array('label'=>'Имя'))
            ->add('email','email',array('label'=>'E-mail'))
            ->add('contacts','text',array('label'=>'Контакты'))
            ->add('message','textarea',array('label'=>'Сообщение'))
            ->add('submit', 'submit', array('label' => 'Отправить'))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        // return 'alkr_cmsbundle_feedback';
    }
}
