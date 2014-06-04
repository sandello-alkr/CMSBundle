<?php

namespace alkr\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MapItemType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address',null,array('label'=>'Адрес','attr'=>array('widget_col'=>8,'label_col'=>4)))
            ->add('lat',null,array('label'=>'Широта','attr'=>array('widget_col'=>8,'label_col'=>4)))
            ->add('lng',null,array('label'=>'Долгота','attr'=>array('widget_col'=>8,'label_col'=>4)))
            ->add('baloon',null,array('label'=>'Надпись на метке','attr'=>array('widget_col'=>8,'label_col'=>4)))
            ->add('text','ckeditor',array(
                'label'=>'Текст в облаке',
                'attr'=>array('widget_col'=>8,'label_col'=>4),
                'toolbar'=>array('basicstyles','insert'),
                'toolbar_groups'=>array(
                    'insert' => array('Image'),
                    'basicstyles' => array('Bold','Italic','Underline','Strike','RemoveFormat')
                    ),
                'height'=>'100',
                )
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'alkr\CMSBundle\Entity\MapItem'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'alkr_cmsbundle_map_item';
    }
}
