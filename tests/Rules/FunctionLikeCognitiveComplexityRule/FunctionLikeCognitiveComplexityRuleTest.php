<?php

declare(strict_types=1);

namespace Artemeon\CognitiveComplexity\Tests\Rules\FunctionLikeCognitiveComplexityRule;

use Iterator;
use Override;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Artemeon\CognitiveComplexity\Rules\FunctionLikeCognitiveComplexityRule;
use Artemeon\CognitiveComplexity\Tests\Rules\FunctionLikeCognitiveComplexityRule\Fixture\ClassMethodOverComplicated;
use Artemeon\CognitiveComplexity\Tests\Rules\FunctionLikeCognitiveComplexityRule\Fixture\VideoRepository;

final class FunctionLikeCognitiveComplexityRuleTest extends RuleTestCase
{
    /**
     * @param list<array{0: string, 1: int, 2?: string|null}> $expectedErrorMessagesWithLines
     */
    #[DataProvider('provideDataForTest')]
    public function test(string $filePath, array $expectedErrorMessagesWithLines): void
    {
        $this->analyse([$filePath], $expectedErrorMessagesWithLines);
    }

    public static function provideDataForTest(): Iterator
    {
        $errorMessage = sprintf(FunctionLikeCognitiveComplexityRule::ERROR_MESSAGE, 'someFunction()', 8);
        yield [__DIR__ . '/Fixture/function.php.inc', [[$errorMessage, 3]]];

        $errorMessage = sprintf(
            FunctionLikeCognitiveComplexityRule::ERROR_MESSAGE,
            ClassMethodOverComplicated::class . '::someMethod()',
            8
        );
        yield [__DIR__ . '/Fixture/ClassMethodOverComplicated.php', [[$errorMessage, 9]]];

        $errorMessage = sprintf(
            FunctionLikeCognitiveComplexityRule::ERROR_MESSAGE,
            VideoRepository::class . '::findBySlug()',
            8
        );
        yield [__DIR__ . '/Fixture/VideoRepository.php', [[$errorMessage, 12]]];
    }

    /**
     * @return string[]
     */
    #[Override]
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__ . '/config/configured_rule.neon'];
    }

    protected function getRule(): Rule
    {
        return self::getContainer()->getByType(FunctionLikeCognitiveComplexityRule::class);
    }
}
