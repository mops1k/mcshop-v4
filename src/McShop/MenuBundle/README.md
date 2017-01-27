# AsuMenuBundle
Библиотека для полуавтоматической генерации меню в любом проекте

## Установка
Скачать:
```bash
composer require asu/menu-bundle
```

Добавить в проект:
```php
// app/AppKernel.php
$bundles = array(
            ...
            new Asu\MenuBundle\AsuMenuBundle(),
            ...
        );
```

## Использование
Сперва Вам необходимо создать builder для будущего меню. *Пример*:
```php
<?php
namespace McShop\MenuBundle\Builder;

use McShop\MenuBundle\Model\BuilderInterface;
use McShop\MenuBundle\Model\Common\AbstractBuilder;

class MainBuilder extends AbstractBuilder
{
    /**
     * @var null
     */
    protected $lastRootName = null;

    /**
     * Add root item
     *
     * @param $name
     * @param $title
     *
     * @return $this
     */
    public function addRootItem($name, $title, $url = null, array $options = [])
    {
        $this->menu[ $name ] = $options;
        $this->menu[ $name ]['title'] = $this->trans($title);
        if (null != $url) {
            $this->menu[ $name ]['url'] = $url;
        }
        $this->lastRootName = $name;

        return $this;
    }

    public function addDivider()
    {
        $this->addItem('divider', '', 'empty', ['class' => 'divider']);
        return $this;
    }

    /**
     * Add item to menu
     *
     * @param       $title
     * @param       $url
     * @param array $options
     *
     * @return $this
     */
    public function addItem($name, $title, $url, array $options = [])
    {
        if (!isset($options['rootName'])) {
            $root = &$this->menu[ $this->lastRootName ]['items'];
        } else {
            $root = &$this->menu[ $options['rootName'] ]['items'];
            unset($options['rootName']);
        }

        $root[ $name ] = $options;
        if ($url !== 'empty') {
            $root[ $name ]['url'] = $url;
        }
        $root[ $name ]['title'] = $this->trans($title);

        return $this;
    }
}
```

Затем создать сам файл меню. *Пример*:
```php
<?php
namespace McShop\MenuBundle\Menu;

use McShop\MenuBundle\Builder\MainBuilder;
use McShop\MenuBundle\Model\Common\AbstractMenu;
use Symfony\Component\Security\Core\User\UserInterface;

class MainMenu extends AbstractMenu
{
    public function applicationMenu($_locale = 'ru')
    {
        /** @var MainBuilder $builder */
        $builder = $this->getBuilder();

        if ($this->isGranted('ROLE_LEGAL_ENTITY_ADMIN')) {
            $builder->addRootItem('company', 'Company');

            if ($this->isGranted('ROLE_SUPER_ADMIN')) {
                $builder->addItem('manage_organizations', 'Manage organizations', '#');
            }
            elseif ($this->isGranted('ROLE_ORGANIZATION_ADMIN')) {
                $builder->addItem('edit_organization', 'Edit organization', '#');
            }
        }

        return $builder->getMenu();
    }
}
```

Затем добавить их в services.yml своего проекта или бандла. *Пример*:
```yaml
services:
    asu.menu.global:
        class: McShop\MenuBundle\Menu\MainMenu
        parent: asu.menu.base_menu
        tags:
            - { name: asu_menu, alias: global }

    asu.builder.global:
        class: McShop\MenuBundle\Builder\MainBuilder
        arguments:
            - '@translator'
        tags:
            - { name: asu_menu.builder, alias: global }

```
** Алиас необходим для дальнейшего использования меню и обязательно должен совпадать как у класса меню так и у класса builder для этого меню

После чего необходимо создать шаблон для меню. *Пример*:
```twig
<ul class="nav navbar-nav">
    {% for key,item in menu %}
        {% if item.items is defined %}
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    {% if item.icon is defined %}<i class="fa {{ item.icon }}"></i> {% endif %}
                    {{ item.title }}
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    {% for subkey, subitem in item.items %}
                        {%- set class = path(app.request.get('_route'), app.request.get('_route_params')) == subitem.url|default(null) ? 'active '~subitem.class|default(null) : subitem.class|default(null) %}
                        <li{{ ' class='~class }}>
                            {% if subitem.url is defined %}
                                <a href="{{ subitem.url }}">
                                    {% if subitem.icon is defined %}<i
                                        class="fa {{ subitem.icon }}"></i> {% endif %}{{ subitem.title }}
                                </a>
                            {% else %}
                                {{ subitem.title }}
                            {% endif %}
                        </li>
                    {% endfor %}
                </ul>
            </li>
        {% else %}
            {%- set class = path(app.request.get('_route'), app.request.get('_route_params')) == item.url|default(null) ? 'active '~item.class|default(null) : item.class|default(null) %}
            {# Нет выпадающего меню #}
            <li{{ ' class='~class }}>
                <a href="{{ item.url }}">{% if item.icon is defined %}<i
                        class="fa {{ item.icon }}"></i> {% endif %}{{ item.title }}</a>
            </li>
        {% endif %}
    {% endfor %}
</ul>
```

После чего можно отрисовать меню в необходимом месте при помощи функции twig. *Пример*:
```twig
{{ renderMenu('global.application', 'AsuEditorumBundle:Layout/Menu:application.html.twig', { _locale: app.request.locale }) }}
```
** Где 'global.application':
`global` - алиас указанный в сервисе
`application` - первая часть имени метода для меню (applicationMenu)