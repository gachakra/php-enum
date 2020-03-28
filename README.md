# php-enum

inspired from this article https://qiita.com/Hiraku/items/71e385b56dcaa37629fe (japanese)

## Quick start to run examples
```bash
docker-compose up -d --build // build docker images and up containers
docker exec -it php-enum_php bash // login the php container

php examples/BasicMethods/Continent.php // or another example php file
```

## Usage
### Class Definition
#### Private or protected way
```php
/**
 * @method static self ELEMENT1
 * @method static self ELEMENT2
 */
class PrivateEnumChild extends Enum
{
    const ELEMENT1 = 'element1';
    const ELEMENT2 = 'element2';
}

PrivateEnumChild::ELEMENT1(); // Instantiate an enum element using internal cache
```
#### Public way
```php
/**
 * @method static self ELEMENT1
 * @method static self ELEMENT2
 */
class PublicEnumChild extends Enum
{
    const ELEMENT1 = 'element1';
    const ELEMENT2 = 'element2';
    
    public function __construct(string $value)
    {
        parent::__construct($value);
    }
}

PublicEnumChild::ELEMENT1(); // Instantiate an enum element using internal cache
new PublicEnumChild(PublicEnumChild::ELEMENT2); // Create new enum element instance
```

### Basic Methods
```php
EnumChild::ELEMENT1()->equals(EnumChild::of('ELEMENT1')); // true
EnumChild::ELEMENT1()->equals(EnumChild::fromValue('element1')); // true

EnumChild::ELEMENT1()->equals(EnumChild::elements()['ELEMENT1']); // true
EnumChild::ELEMENT1()->equals(EnumChild::valueToElement()['element1']); // true
```
