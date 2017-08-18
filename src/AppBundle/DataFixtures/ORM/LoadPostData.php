<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 29/07/2017
 * Time: 00:45
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Post;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{

    private $text = "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad adipisci alias, animi asperiores autem dicta dignissimos dolor dolorum ducimus eius illo iste,
     iusto perferendis perspiciatis quae quas quo reiciendis similique, sint tenetur? Accusamus amet architecto autem doloribus eaque eius fugiat impedit in, laborum natus nisi
      numquam odio optio quod ratione voluptas voluptatum. Amet asperiores consequuntur expedita facere minima molestias, quis recusandae sed. Asperiores assumenda dolores iusto
       officiis similique sunt ullam. Amet aperiam architecto assumenda at autem beatae consequuntur cum cumque debitis distinctio eius, eos error et facilis fugit ipsum itaque
        iusto laboriosam laborum laudantium magnam modi necessitatibus nobis nostrum omnis pariatur perspiciatis placeat porro provident quas quis quos rerum sunt tempora unde
         vel vero. Accusamus assumenda eius enim error, expedita inventore laboriosam nam non, nulla officiis optio porro reprehenderit temporibus voluptatem voluptates. Assumenda,
          deserunt dolorem enim eos, esse harum id in incidunt maxime porro quas qui quibusdam ratione reiciendis, repellendus! Labore minima nulla officiis. Amet at autem corporis
           cupiditate est fuga incidunt libero maxime molestias nam nemo nisi obcaecati officiis provident rerum similique tempore, vel velit veritatis voluptatem. A, cupiditate
            dignissimos dolores doloribus enim et eum ex facere hic labore laborum laudantium mollitia nam officia perferendis quae qui quo ratione repellat repellendus reprehenderit 
            saepe sint temporibus vitae voluptates. Assumenda, deserunt dolorem dolores esse explicabo perspiciatis praesentium? Ab consequuntur culpa dicta dignissimos doloribus
             dolorum eum harum, id magni nam natus nulla odit possimus quae quasi quisquam reiciendis repellat sed similique suscipit? Dignissimos dolorum eum explicabo minima
              omnis quo vero voluptate? Magni, nam, optio? Aliquam, animi autem commodi dolor, eaque earum eos et fugit harum id ipsam laudantium libero necessitatibus neque 
              reiciendis, tenetur voluptas voluptate voluptates. Amet consequuntur molestias qui sequi vero? Aliquid aut autem blanditiis corporis debitis dicta doloribus ea
               eligendi eos, excepturi in laudantium maiores natus necessitatibus nemo nisi optio quidem quod repellendus reprehenderit repudiandae, sit unde voluptatum! Culpa
                deleniti illo ipsum maxime necessitatibus, soluta. Accusantium assumenda aut consectetur cumque debitis deleniti dolorem, dolores eos molestias non nostrum 
                possimus praesentium quaerat quos sed. Culpa eaque enim eos incidunt, ipsam repellat vitae. Aliquam amet architecto aut autem distinctio dolores ducimus et
                impedit iure maiores minima modi nesciunt nobis nostrum odit pariatur perferendis quas qui sed sequi, similique soluta sunt tempore unde ut voluptate voluptates
                 voluptatum. Aut blanditiis distinctio ipsa officia ratione rem rerum tempora veritatis. Alias asperiores aspernatur cupiditate eligendi quasi! Accusamus adipisci 
                 aperiam aspernatur blanditiis earum error quasi veniam. Corporis debitis iusto natus nostrum tempora. Atque doloribus et exercitationem impedit placeat praesentium 
                 tempore veniam voluptatum! Accusamus architecto asperiores aspernatur assumenda at beatae blanditiis corporis culpa debitis, distinctio dolore dolores doloribus 
                 error esse est et ex facilis harum impedit incidunt ipsa itaque labore libero nemo quaerat quas quidem quos rem repellat sed similique soluta voluptas voluptates?
                  Ab aliquid asperiores at consequuntur eos ex fuga itaque iure molestias neque praesentium quaerat ratione reiciendis reprehenderit sit ullam, unde voluptatum.
                   Ducimus esse et molestiae officia quo sint sit sunt! Consequatur dolorem eligendi fugit, id maiores neque quasi quo veniam! Accusamus aliquid animi culpa earum 
                   eos eveniet ex, explicabo id itaque natus numquam odio optio perspiciatis, quaerat quia quidem repellat soluta. Alias beatae consectetur corporis cum doloremque,
                    eius eos ex fugit ipsa laudantium molestiae nesciunt nostrum nulla officiis praesentium quae quos sed tempore totam, voluptatibus! Ab accusamus aliquid animi 
                    aspernatur assumenda consequatur corporis distinctio dolore eaque eius expedita iusto mollitia necessitatibus nesciunt nisi nobis, odit quis reiciendis rem 
                    repellat, reprehenderit sed similique velit voluptatibus voluptatum? Accusantium asperiores blanditiis consectetur cupiditate dolorem doloribus explicabo illum 
                    itaque iure labore magnam maxime molestias neque officiis pariatur porro, ratione reprehenderit repudiandae sit voluptatibus. Accusamus aspernatur autem earum 
                    explicabo magnam molestias non nostrum porro quis, sapiente. Adipisci consequuntur corporis delectus dignissimos eaque explicabo id inventore minima odio 
                    officiis praesentium quas reiciendis, unde? Alias aliquam aliquid assumenda at aut autem consectetur cumque cupiditate distinctio dolor doloremque dolorum 
                    enim explicabo illo, ipsam itaque iure iusto magni molestiae mollitia nesciunt nisi odio perspiciatis porro quam quasi quia quidem quos reiciendis reprehenderit
                     sed sequi tempore vero voluptate voluptatem voluptatibus voluptatum! Ab accusantium asperiores at autem, consequatur cupiditate deserunt distinctio ducimus,
                      ea eaque enim eum fuga fugit, illo itaque laudantium magnam odit porro quo temporibus totam vel veritatis voluptatibus. Aut consequatur culpa dolore ducimus 
                      eligendi facilis inventore ipsa ipsum iste nam necessitatibus nihil, obcaecati odio pariatur quia reiciendis reprehenderit saepe soluta, velit vitae? Ab
                       accusamus aliquid amet aperiam architecto at autem corporis delectus doloremque dolorum esse eum, eveniet expedita facilis fugit harum hic ipsum iste magnam
                        minus molestias non numquam officia quaerat quasi quos reprehenderit sapiente sit soluta tempora, totam vitae voluptas voluptates. Accusantium architecto
                         aut deserunt doloremque ducimus excepturi harum hic in ipsum, iusto labore laborum minima molestias neque nisi non placeat quas quibusdam reprehenderit 
                         suscipit velit veniam, vitae voluptate? Alias aliquam aliquid at, atque autem beatae culpa deleniti dolores enim est fugit id iste labore laboriosam minus
                          mollitia nam necessitatibus nostrum odio perferendis quaerat quibusdam sapiente suscipit tempore temporibus velit, vitae voluptatibus. Aliquam architecto
                           beatae consectetur consequuntur cupiditate delectus dicta dolore doloribus est fuga ipsum laborum magni molestiae provident qui quibusdam quisquam, sed 
                           similique tenetur voluptate. Ad aliquam aspernatur consequuntur dolores ex maxime, mollitia natus sit totam vel. Accusantium assumenda at autem dolor
                            doloribus eligendi eos error facilis iusto libero minima mollitia nam nulla officia, perspiciatis quae quam qui quidem, quisquam ratione repellat similique 
                            sint voluptatem? Corporis dolorum, earum eius, eligendi laboriosam libero maiores modi nesciunt nulla officiis quae qui repudiandae sequi sunt voluptas. 
                            Alias, at doloremque dolores eaque, eius inventore iusto maxime nesciunt nihil optio qui quia repudiandae similique temporibus unde velit veniam 
                            veritatis vero. Accusantium architecto aut cumque, deleniti eveniet ex expedita facilis illo laudantium libero magni mollitia natus qui recusandae sunt
                             tempore vero. Ab alias beatae commodi corporis, cumque dolor eligendi impedit modi nam nesciunt nostrum officiis optio perferendis perspiciatis quae 
                             quaerat quas quisquam soluta tempore veritatis? Consectetur delectus, deleniti dignissimos dolorem dolores eaque eos facilis fuga in incidunt iure 
                             laborum molestiae molestias nemo nobis odio quas quibusdam sequi veniam voluptates. Atque eos esse est exercitationem maiores nemo, neque quod?";
    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $post = new Post();
        $post->setName("L'esport c'est vraiment trop cool");
        $post->setAuthor();
        $post->setCategory($this->getReference('category.esport'));
        $post->setAuthor($this->getReference('user.robin'));
        $post->setContent($this->text);
        $date = new \DateTime();
        $post->setCreationDate($date);
        $post->setLastUpdateDate($date);
        $this->setReference("post", $post);
        $manager->persist($post);

        $post = new Post();
        $post->setName("Teemo c'est le meilleur champion");
        $post->setAuthor();
        $post->setCategory($this->getReference('category.esport'));
        $post->setAuthor($this->getReference('user.hugo'));
        $post->setContent($this->text);
        $date = new \DateTime();
        $post->setCreationDate($date);
        $post->setLastUpdateDate($date);
        $manager->persist($post);

        $post = new Post();
        $post->setName("S'amuser avec Yorick");
        $post->setAuthor();
        $post->setCategory($this->getReference('category.esport'));
        $post->setAuthor($this->getReference('user.lemy'));
        $post->setContent($this->text);
        $date = new \DateTime();
        $post->setCreationDate($date);
        $post->setLastUpdateDate($date);
        $manager->persist($post);

        $post = new Post();
        $post->setName("Prank de Sardoche");
        $post->setAuthor();
        $post->setCategory($this->getReference('category.esport'));
        $post->setAuthor($this->getReference('user.robin'));
        $post->setContent($this->text);
        $date = new \DateTime();
        $post->setCreationDate($date);
        $post->setLastUpdateDate($date);
        $manager->persist($post);

        $post = new Post();
        $post->setName("Garen, c'est le meilleur deuxième champion");
        $post->setAuthor();
        $post->setCategory($this->getReference('category.esport'));
        $post->setAuthor($this->getReference('user.hugo'));
        $post->setContent($this->text);
        $date = new \DateTime();
        $post->setCreationDate($date);
        $post->setLastUpdateDate($date);
        $manager->persist($post);

        $post = new Post();
        $post->setName("La létalité, ça pue");
        $post->setAuthor();
        $post->setCategory($this->getReference('category.esport'));
        $post->setAuthor($this->getReference('user.hugo'));
        $post->setContent($this->text);
        $date = new \DateTime();
        $post->setCreationDate($date);
        $post->setLastUpdateDate($date);
        $manager->persist($post);

        $post = new Post();
        $post->setName("Rendez-nous l'ancien Graves");
        $post->setAuthor();
        $post->setCategory($this->getReference('category.esport'));
        $post->setAuthor($this->getReference('user.robin'));
        $post->setContent($this->text);
        $date = new \DateTime();
        $post->setCreationDate($date);
        $post->setLastUpdateDate($date);
        $manager->persist($post);

        $manager->flush();
    }

    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        return 3;
    }
}