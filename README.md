# MORM
PHP MYSQL ORM

composer: php composer.phar require melny/morm

packagist: https://packagist.org/packages/melny/morm

## Cookbook:

#### \MORM\MORM Construct

```
<?php

$config = [
    'unix_socket' => 'path/to/unix',
    'host'        => 'localhost(127.0.0.1)',
    'username'    => 'root',
    'password'    => 'root',
    'dbname'      => 'root',
    'charset'     => 'utf8',
];

$qb = new \QB\QB($config);
$morm = new \MORM\MORM($qb);

```

#### MORM ENTITY LOOK LIKE(CUSTOM CLASS)

```
<?php

namespace Entity;

class Foo // <-- custom model name
    extends \MORM\Entity // <-- *require* parent class
{
    const TABLE = 'data_table'; // <-- *require* database table name

    protected $id; // <-- *require* database table column name
    protected $title; // <-- *require* database table column name
    protected $datetime; // <-- *require* database table column name
}

```

#### \MORM\MORM Method find()

```
$qb = new \QB\QB($config);
$morm = new \MORM\MORM($qb);

$id = 1;

$entity = $morm->find('\Entity\Foo', $id);
```

#### \MORM\MORM Method findAll()

```
$qb = new \QB\QB($config);
$morm = new \MORM\MORM($qb);

$entity = $morm->findAll('\Entity\Foo');
```

#### \MORM\MORM Method findBy()

```
$qb = new \QB\QB($config);
$morm = new \MORM\MORM($qb);

$condition = ['id' => 1];
$order     = ['datetime' => 'ASC'];
$limit     = 55;
$limit     = 2;

$entity = $morm->findBy('\Entity\Foo', $condition, $order, $limit, $offset);
```

#### \MORM\MORM Method findOneBy()

```
$qb = new \QB\QB($config);
$morm = new \MORM\MORM($qb);

$condition = ['id' => 1];
$order     = ['datetime' => 'ASC'];
$limit     = 2;

$entity = $morm->findBy('\Entity\Foo', $condition, $order, $offset);
```

#### \MORM\MORM Method save()

```
$qb = new \QB\QB($config);
$morm = new \MORM\MORM($qb);

$id = 1;

$entity = $morm->find('\Entity\Foo', $id);

$entity->set('title', 'Bar');

$morm->save($entity);
```

#### \MORM\MORM Method remove()

```
$qb = new \QB\QB($config);
$morm = new \MORM\MORM($qb);

$id = 1;

$entity = $morm->find('\Entity\Foo', $id);

$morm->remove($entity);
```

#### \MORM\Entity Method set() && setData()

```
$qb = new \QB\QB($config);
$morm = new \MORM\MORM($qb);

$id = 1;

$entity = $morm->find('\Entity\Foo', $id);

$entity->set('title', 'FooBar');

$entity->setData([
    'title' => 'FooBar',
    'datetime' => '2015-12-10 14:20:11',
]);
```

#### \MORM\Entity Method get() && getData()

```
$qb = new \QB\QB($config);
$morm = new \MORM\MORM($qb);

$id = 1;

$entity = $morm->find('\Entity\Foo', $id);

$columnData = $entity->get('title');

$arrayData = $entity->getData();
```
