<?php

namespace alkr\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PageType extends AbstractType
{
    protected $parents;

    public function __construct ($parents)
    {
        $this->parents = $parents;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $file = yaml_parse_file(__DIR__.'/../../../../../../app/config/globals.yml');
        $parents = $this->parents;
        $builder
            ->add('title',null,array('label' => 'Заголовок'))
            ->add('parent','extended_entity',array('choices'=>$parents,'label'=>'Родитель','property'=>'indent','class'=>'alkr\CMSBundle\Entity\Page','option_attributes'=>array('data-next'=>'nextPrior')))
            ->add('prior',null,array('label'=>'Порядок'))
            ->add('enabled',null,array('required'=>false,'label'=>'Включена'))
            ->add('annotation',null,array('label'=>'Аннотация'))
            ->add('content','ckeditor',array('label'=>'Содержание'))
            ->add('metaTitle',null,array('label'=>'Заголовок браузера'))
            ->add('menuTitle',null,array('label'=>'Заголовок меню'))
            ->add('keywords',null,array('label'=>'Ключевые слова'))
            ->add('description',null,array('label'=>'Описание'))
            ->add('url',null,array('label'=>'','required'=>false))
            ;


        /*$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($parents) {
            $product = $event->getData();
            $form = $event->getForm();
            if (!$product || null === $product->getId() || is_object($product->getParent()))
                $form->add('parent',null,array('choices'=>$parents,'label'=>'Родитель','property'=>'indent'));
        });*/

        if($file['twig']['globals']['modules']['views'])
            $builder->add('view','choice',array('label'=>'Шаблон','choices'=>array('two_sidebars.html.twig'=>'two_sidebars')));
        if($file['twig']['globals']['modules']['feedback'])
            $builder->add('feedback',null,array('label'=>'Форма обратной связи'));
        if($file['twig']['globals']['modules']['map'])
            $builder->add('map',null,array('label'=>'Карта'));
        if($file['twig']['globals']['modules']['gallery'])
            $builder->add(
                'photos',
                'bootstrap_collection',
                array(
                    'label'              => 'Фотографии',
                    'type'               => new \alkr\CMSBundle\Form\PhotoDescType(),
                    'allow_add'          => true,
                    'allow_delete'       => true,
                    'add_button_text'    => 'Добавить фото',
                    'delete_button_text' => 'Удалить фото',
                    'sub_widget_col'     => 9,
                    'button_col'         => 3
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
            'data_class' => 'alkr\CMSBundle\Entity\Page'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'alkr_cmsbundle_page';
    }
}
