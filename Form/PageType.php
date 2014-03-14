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
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => 'alkr\ExtendCMSBundle\Entity\Page',
                'required' => false,                    // [2]
                'fields' => array(                      // [3]
                    'title' => array('label' => 'Заголовок'),
                    'annotation' => array('label'=>'Аннотация'),
                    'content' => array('label'=>'Содержание'),
                    'metaTitle' => array('label'=>'Заголовок браузера'),
                    'keywords' => array('label'=>'Ключевые слова'),
                    'description' => array('label'=>'Описание')
                    )
                )
            )
            ->add('parent',null,array('choices'=>$this->parents,'group_by'=>'categoryName','label'=>'Родитель'))
            ->add('url',null,array('label'=>'Адрес'))
            ->add('enabled',null,array('required'=>false,'label'=>'Включена'))
            ->add('category',null,array('label'=>'Категория','required'=>true))
            ->add('feedback',null,array('label'=>'Форма обратной связи'))
            ->add('map',null,array('label'=>'Карта'))
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
