<?
namespace alkr\CMSBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use alkr\CMSBundle\Entity\Page;

class LoadData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $file = yaml_parse_file(__DIR__.'/../../../../../../../app/config/globals.yml');

        if($file['twig']['globals']['sidebar']['menu'])
        {
            $leftMenuRoot = new Page();
            $leftMenuRoot->setTitle('Корень')
                ->setEnabled(true)
                ->setContent('')
                ;
            $manager->persist($leftMenuRoot);

            $child = new Page();
            $child->setTitle('1-1')
                ->setEnabled(true)
                ->setContent('')
                ->setParent($leftMenuRoot)
                ;
            $manager->persist($child);

            $child = new Page();
            $child->setTitle('1-2')
                ->setEnabled(true)
                ->setContent('')
                ->setParent($leftMenuRoot)
                ;
            $manager->persist($child);

            $grandchild = new Page();
            $grandchild->setTitle('2-1')
                ->setEnabled(true)
                ->setContent('')
                ->setParent($child)
                ;
            $manager->persist($grandchild);
        }

        $leftMenuRoot = new Page();
        $leftMenuRoot->setTitle('Страницы для шапки')
            ->setEnabled(true)
            ->setContent('')
            ;
        $manager->persist($leftMenuRoot);

        $manager->flush();
    }
}