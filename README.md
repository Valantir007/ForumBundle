#ValantirForumBundle

routing.yml

valantir_forum:
    resource: "@ValantirForumBundle/Resources/config/routing/all.xml"

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

services:
    gedmo.listener.softdeleteable:
        class: Gedmo\SoftDeleteable\SoftDeleteableListener
        tags:
            - { name: doctrine.event_subscriber, connection: default }
        calls:
            - [ setAnnotationReader, [ @annotation_reader ] ]

doctrine:
    orm:
        auto_generate_proxy_classes: "%kernel.debug%"
        auto_mapping: true
        filters:
            softdeleteable:
                class: Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter
                enabled: true

ValantirForumBundle wymaga tree, softdeleteable, sortable oraz sluggable
tree - fora i podfora
softdeleteable - miekkie usuwanie postow, topicow i forow
sortable - sortowanie watkow, postow i forow po polu position
sluggable - tworzenie slugow po tytulach forow i topicow

-więcej na stronie rozszerzenia https://github.com/Atlantic18/DoctrineExtensions

Klasa usera musi implementowac interfejs Valantir\ForumBundle\Entity\UserInterface oraz rozszerzac klase Valantir\ForumBundle\Entity\User
Jeśli uzywasz bundle'a FOSUserBundle i przez to nie mozesz rozszerzyc juz klasy Usera z ForumBundle,
 wystarczy, ze skopiujesz klase FOS\UserBundle\Model\User do swojej ze wszystkimi zaleznosciami 
lub skopiujesz wlasciwosci i metody klasy Valantir\ForumBundle\Entity\User

należy zainstalować rozszerzenie php_bbcode stąd https://pecl.php.net/package/bbcode

dodaj do security.yml
access_control:
    - { path: ^/forum, role: ROLE_USER }


Aby edytować forum, nadaj użytkownikowi rolę ROLE_FORUM_ADMIN