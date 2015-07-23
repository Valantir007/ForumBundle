#ValantirForumBundle

routing.yml

valantir_forum:
    resource: "@ValantirForumBundle/Resources/config/routing/all.xml"


Klasa usera
Musi implementowac klase Valantir\ForumBundle\Entity\ForumUserInterface

Należy ustawić klasę usera w pliku config.yml

valantir_forum: 
    user_class: path/to/user/class

Bundle wymaga pakietu
"stof/doctrine-extensions-bundle": "1.2.*@dev",

Należy skonfigurować ten pakiet dodajac np to:

stof_doctrine_extensions:
    default_locale: pl_PL
    orm:
        default:
            softdeleteable: true
            sluggable: true
            sortable: true
            tree: true

ValantirForumBundle wymaga tree, softdeleteable, sortable oraz sluggable
tree - fora i podfora
softdeleteable - miekkie usuwanie postow, topicow i forow
sortable - sortowanie watkow, postow i forow po polu position
sluggable - tworzenie slugow po tytulach forow, topicow i postow

Klasa usera musi implementowac interfejs Valantir\ForumBundle\Entity\UserInterface oraz rozszerzac klase Valantir\ForumBundle\Entity\User
Jeśli uzywasz bundle'a FOSUserBundle i przez to nie mozesz rozszerzyc juz klasy Usera z ForumBundle,
 wystarczy, ze skopiujesz klase FOS\UserBundle\Model\User do swojej ze wszystkimi zaleznosciami 
lub skopiujesz wlasciwosci i metody klasy Valantir\ForumBundle\Entity\User

w pliku mapowania należy wkleić poniższy kod:

<one-to-many field="forums" target-entity="Valantir\ForumBundle\Entity\Forum" mapped-by="author" />
<one-to-many field="topics" target-entity="Valantir\ForumBundle\Entity\Topic" mapped-by="author" />
<one-to-many field="posts" target-entity="Valantir\ForumBundle\Entity\Post" mapped-by="author" />

należy zainstalować rozszrzenie php_bbcode stąd https://pecl.php.net/package/bbcode