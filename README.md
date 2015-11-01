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

Add path to your User class in your config.yml file:
```
//config.yml
valantir_forum: 
    user_class: path/to/user/class
```
> ValantirForumBundle needs doctrine-extension-bundle, so if you have not this bundle in your application, please install it and configure it. For example:
```
// composer.json
"stof/doctrine-extensions-bundle": "1.2.*@dev",
```
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

```
// security.yml
access_control:
    - { path: ^/forum, role: ROLE_USER }
```
> If you want the user to be able to edit forum, give him the role **ROLE_FORUM_ADMIN**.

Finally you must install php_bbcode extension [from](https://pecl.php.net/package/bbcode)

