<?php
/**
 * Created by IntelliJ IDEA.
 * User: gachakra
 * Date: 2019-08-04
 * Time: 16:59
 */

namespace Tests\Exceptions;


use Gachakra\PhpEnum\Enum;
use Gachakra\PhpEnum\Exceptions\RootEnumMethodCallException;
use Generator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class RootEnumMethodCallExceptionTest extends TestCase {

    private const EXCEPTION_MESSAGE = "Cannot call static method directly via root abstract Enum";

    private const NON_EXISTING_ENUM_NAME  = 'NON_EXISTING_NAME';
    private const NON_EXISTING_ENUM_VALUE = 'NON_EXISTING_VALUE';

    /**
     * @throws ReflectionException
     * @see Enum::$constants
     */
    protected function setUp(): void {
        parent::setUp();

        $this->expectException(RootEnumMethodCallException::class);
        $this->expectExceptionMessage(self::EXCEPTION_MESSAGE);

        // force to set Root Enum name-value set to inner static cache of constants
        {
            $reflectedRootEnum = (new ReflectionClass(Enum::class));
            $reflectedConstantsCache = $reflectedRootEnum->getProperty('constants');
            $reflectedConstantsCache->setAccessible(true);
            $reflectedConstantsCache->setValue([
                    Enum::class => [self::NON_EXISTING_ENUM_NAME => self::NON_EXISTING_ENUM_VALUE]
            ]);
        }
    }

    /**
     * @test
     * @dataProvider provideRootEnumPublicStaticMethodsAndNonExistingName
     *
     * @param string $publicStaticMethodName
     * @param array  $args
     */
    public function thrown_if_public_static_method_is_called_via_root_enum_directly(string $publicStaticMethodName, array $args) {

        empty($args)
                ? Enum::$publicStaticMethodName()
                : Enum::$publicStaticMethodName(...$args);
    }

    /**
     * @test
     * @dataProvider provideRootEnumPublicStaticMethods
     *
     * @param string $publicStaticMethodName
     * @param array  $args
     * @throws ReflectionException
     */
    public function thrown_even_if_root_enum_public_static_method_is_called_via_reflection(string $publicStaticMethodName, array $args) {

        ($reflectedRootEnum = new ReflectionClass(Enum::class))
                ->getMethod($publicStaticMethodName)
                ->invokeArgs($reflectedRootEnum, $args);
    }

    public function provideRootEnumPublicStaticMethodsAndNonExistingName(): Generator {

        foreach ($this->provideRootEnumPublicStaticMethods() as $methodAndArgs) {
            yield $methodAndArgs;
        }
        yield [self::NON_EXISTING_ENUM_NAME, []];
    }

    /**
     * generate array of [public-static-method name, [its arguments]] like below
     * - ['of', ['NON_EXISTING_NAME']]
     * - ['fromValue', ['NON_EXISTING_VALUE']]
     * - ['__callStatic', ['NON_EXISTING_NAME', ['whatever']]]
     *
     * @return Generator
     * @throws ReflectionException
     *
     * @see Enum::of
     * @see Enum::fromValue
     * @see Enum::__callStatic
     */
    public function provideRootEnumPublicStaticMethods(): Generator {

        $methods = (new ReflectionClass(Enum::class))
                ->getMethods(ReflectionMethod::IS_STATIC);

        foreach ($methods as $method) {
            if ($method->isPublic()) {
                yield [$method->getName(), self::createTestArguments($method)];
            }
        }
    }

    private static function createTestArguments(ReflectionMethod $method): array {

        $args = [];
        foreach ($method->getParameters() as $parameter) {

            $typeHint = $parameter->getType()
                    ? $parameter->getType()->getName()
                    : '';

            if ($typeHint === '') {
                // when an arg does not have a type hint, always required Enum const value as an arg.
                $args[] = self::NON_EXISTING_ENUM_VALUE;

            } else if ($typeHint === 'string') {
                // when the type hint of an arg is string, always required Enum const name as an arg.
                $args[] = self::NON_EXISTING_ENUM_NAME;

            } else if ($typeHint === 'array') {
                $args[] = ['whatever'];
            }
        }
        return $args;
    }
}