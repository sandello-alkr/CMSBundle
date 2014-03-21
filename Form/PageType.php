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
        $builder
            ->add('translations', 'a2lix_translations_gedmo', array(
                'translatable_class' => 'alkr\CMSBundle\Entity\Page',
                'required' => false,                    // [2]
                'fields' => array(                      // [3]
                    'title' => array('label' => 'Заголовок'),
                    'annotation' => array('label'=>'Аннотация'),
                    'content' => array('field_type'=>'ckeditor','label'=>'Содержание'),
                    'metaTitle' => array('label'=>'Заголовок браузера'),
                    'menuTitle' => array('label'=>'Заголовок меню'),
                    'keywords' => array('label'=>'Ключевые слова'),
                    'description' => array('label'=>'Описание')
                    // 'url' => array('label'=>'Адрес')
                    )
                )
            );

        $parents = $this->parents;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($parents) {
            $product = $event->getData();
            $form = $event->getForm();
            if (!$product || null === $product->getId() || is_object($product->getParent()))
                $form->add('parent',null,array('choices'=>$parents,'label'=>'Родитель','property'=>'indent','required'=>true));
        });

        $builder->add('enabled',null,array('required'=>false,'label'=>'Включена'));
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
