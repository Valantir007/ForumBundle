# ValantirForumBundle

## Installation

### Step 1

To install bundle in your project, add below line to your composer.json file:

```
// composer.json
{
    // ...
    "require": {
        // ...
        "valantir/forumbundle": "dev-master",
    }
}
```
NOTE: Please replace dev-master with the latest stable version, for example 1.0.0.

Then install bundle by running update command:

```
// composer.json
$ php composer.phar update valantir/forumbundle
```

Now you need to add bundle to your AppKernel.php file:

```
<?php
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Valantir\ForumBundle\ValantirForumBundle(),
    // ...
);
```
### Step 2 - configuration
Now in file routing.yml add belowe code:
```
// routing.yml
valantir_forum:
    resource: "@ValantirForumBundle/Resources/config/routing/all.xml"
```

Add path to your User class in your config.yml file and comment assetic.bundles options or add ValantirForumBundle to this configuration:
```
//config.yml
valantir_forum: 
    user_class: path/to/user/class
```
```
//config.yml
assetic:
    #bundles: []
```
OR
```
//config.yml
assetic:
    bundles: [ValantirForumBundle]
```

> ValantirForumBundle needs doctrine-extension-bundle, so please configure it. For example:
```
// config.yml
stof_doctrine_extensions:
    default_locale: pl_PL
    orm:
        default:
            softdeleteable: true
            sluggable: true
            sortable: true
            tree: true
```
```
services:
    gedmo.listener.softdeleteable:
        class: Gedmo\SoftDeleteable\SoftDeleteableListener
        tags:
            - { name: doctrine.event_subscriber, connection: default }
        calls:
            - [ setAnnotationReader, [ @annotation_reader ] ]
```
```
doctrine:
    orm:
        auto_generate_proxy_classes: "%kernel.debug%"
        auto_mapping: true
        filters:
            softdeleteable:
                class: Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter
                enabled: true
```
> ValantirForumBundle needs tree, softdeleteable, sortable and sluggable

[More about doctrine-extension-bundle](https://github.com/Atlantic18/DoctrineExtensions)

> Your user class needs implement **Valantir\ForumBundle\Entity\UserInterface** or extend **Valantir\ForumBundle\Entity\User**

For example:

If you use FOSUserBundle and your class extends user class from this bundle, you can copy all properties and methods from Valantir\ForumBundle\Entity\User class.

> If you want the user to be able to edit forum, give him the role **ROLE_FORUM_ADMIN**.

### Step 3 - Additional configuration

By default forum uses golonka/bbcodeparser (bb_code_golonka_parser service) but if you want to use php_bbcode extension, you have to install it from [here](https://pecl.php.net/package/bbcode)

and add parser service:

```
// config.yml
valantir_forum: 
    parser: bb_code_extension_parser
```

If you want to write your own parser, you can do this by:

- creating your bbcode parser like a service
- extending class of AbstractParser (Valantir\ForumBundle\Service\AbstractParser)
- changing configuration:

```
// config.yml
valantir_forum: 
    parser: your_bb_code_parser_service
```