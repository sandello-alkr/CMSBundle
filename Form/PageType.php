<?php

namespace alkr\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
        $builder
            ->add('parent',null,array('choices'=>$this->parents,'group_by'=>'categoryName','label'=>'Родитель'))
            ->add('title',null,array('label'=>'Заголовок'))
            ->add('url',null,array('label'=>'Адрес'))
            ->add('enabled',null,array('required'=>false,'label'=>'Включена'))
            ->add('category',null,array('label'=>'Категория','required'=>true))
            ->add('annotation',null,array('label'=>'Аннотация'))
            ->add('content','ckeditor',array('label'=>'Содержание'))
            ->add('feedback',null,array('label'=>'Форма обратной связи'))
            ->add('map',null,array('label'=>'Карта'))
            ->add('metaTitle',null,array('label'=>'Заголовок браузера'))
            ->add('keywords',null,array('label'=>'Ключевые слова'))
            ->add('description',null,array('label'=>'Описание'))
            ->add(
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
