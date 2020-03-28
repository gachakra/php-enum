# php-enum

inspired from this article https://qiita.com/Hiraku/items/71e385b56dcaa37629fe (japanese)

## Quick start to run examples
```bash
docker-compose up -d --build
```

## Usage
### Class Definition
```php

/**
 * @method static self ELEMENT1
 * @method static self ELEMENT2
 */
class EnumChild extends Enum {

  const ELEMENT1 = 'element1';
  const ELEMENT2 = 'element2';
}

EnumChild::ELEMENT1();
new EnumChild(EnumChild::ELEMENT2)
```
### Class Definition
