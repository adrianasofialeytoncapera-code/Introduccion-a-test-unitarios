<?php

namespace MyTests;

class TestRunner
{
    private int $passed = 0;
    private int $failed = 0;
    private array $failures = [];

    // ─── Assertions ───────────────────────────────────────────────

    public function assertEquals(mixed $expected, mixed $actual, string $message = ''): void
    {
        if ($expected != $actual) {
            throw new AssertionError(
                $message ?: "Expected " . var_export($expected, true) . " but got " . var_export($actual, true)
            );
        }
    }

    public function assertSame(mixed $expected, mixed $actual, string $message = ''): void
    {
        if ($expected !== $actual) {
            throw new AssertionError(
                $message ?: "Expected (strict) " . var_export($expected, true) . " but got " . var_export($actual, true)
            );
        }
    }

    public function assertTrue(mixed $value, string $message = ''): void
    {
        $this->assertEquals(true, (bool)$value, $message ?: "Expected true but got false");
    }

    public function assertFalse(mixed $value, string $message = ''): void
    {
        $this->assertEquals(false, (bool)$value, $message ?: "Expected false but got true");
    }

    public function assertNull(mixed $value, string $message = ''): void
    {
        if ($value !== null) {
            throw new AssertionError($message ?: "Expected null but got " . var_export($value, true));
        }
    }

    public function assertNotNull(mixed $value, string $message = ''): void
    {
        if ($value === null) {
            throw new AssertionError($message ?: "Expected non-null value");
        }
    }

    public function assertCount(int $expected, array $array, string $message = ''): void
    {
        $this->assertEquals($expected, count($array), $message ?: "Expected count {$expected} but got " . count($array));
    }

    public function assertContains(string $needle, string $haystack, string $message = ''): void
    {
        if (strpos($haystack, $needle) === false) {
            throw new AssertionError($message ?: "Expected '{$haystack}' to contain '{$needle}'");
        }
    }

    public function assertEqualsFloat(float $expected, float $actual, int $decimals = 2, string $message = ''): void
    {
        $factor = 10 ** $decimals;
        if (round($expected * $factor) !== round($actual * $factor)) {
            throw new AssertionError(
                $message ?: "Expected ~{$expected} but got {$actual} (precision: {$decimals} decimals)"
            );
        }
    }

    /**
     * Assert that a callable throws the given exception class.
     * Returns the caught exception so you can inspect its message.
     */
    public function assertThrows(string $exceptionClass, callable $fn): \Throwable
    {
        try {
            $fn();
        } catch (\Throwable $e) {
            if (!($e instanceof $exceptionClass)) {
                throw new AssertionError(
                    "Expected exception {$exceptionClass} but got " . get_class($e) . ": " . $e->getMessage()
                );
            }
            return $e;
        }
        throw new AssertionError("Expected exception {$exceptionClass} but no exception was thrown.");
    }

    public function assertAll(array $items, callable $assertion, string $message = ''): void
    {
        foreach ($items as $i => $item) {
            try {
                $assertion($item);
            } catch (AssertionError $e) {
                throw new AssertionError("assertAll failed at index {$i}: " . $e->getMessage());
            }
        }
    }

    // ─── Test runner ──────────────────────────────────────────────

    /**
     * Run a single test case.
     * @param string   $name  Human-readable test name
     * @param callable $fn    The test body (receives $this as runner)
     */
    public function test(string $name, callable $fn): void
    {
        try {
            $fn($this);
            $this->passed++;
            echo "  \033[32m✓\033[0m {$name}\n";
        } catch (\Throwable $e) {
            $this->failed++;
            $this->failures[] = ['name' => $name, 'error' => $e->getMessage()];
            echo "  \033[31m✗\033[0m {$name}\n";
            echo "    \033[33m↳ " . $e->getMessage() . "\033[0m\n";
        }
    }

    /**
     * Run a data-driven test (equivalent to xUnit [Theory] + [InlineData]).
     * @param string   $name     Base test name
     * @param array    $dataset  Array of argument arrays
     * @param callable $fn       Test body; receives ($runner, ...$args)
     */
    public function theory(string $name, array $dataset, callable $fn): void
    {
        foreach ($dataset as $index => $args) {
            $label = $name . ' [' . implode(', ', array_map(fn($v) => var_export($v, true), $args)) . ']';
            $this->test($label, fn($t) => $fn($t, ...$args));
        }
    }

    /**
     * Group tests under a suite name (like a test class).
     */
    public function suite(string $name, callable $fn): void
    {
        echo "\n\033[1;34m▶ {$name}\033[0m\n";
        $fn($this);
    }

    public function printSummary(): void
    {
        $total = $this->passed + $this->failed;
        echo "\n" . str_repeat('─', 50) . "\n";
        echo "\033[1mResultados: {$total} tests";
        if ($this->failed === 0) {
            echo "  \033[32m✓ Todos pasaron\033[0m\n";
        } else {
            echo "  \033[32m{$this->passed} pasaron\033[0m  \033[31m{$this->failed} fallaron\033[0m\n";
            echo "\nFallas:\n";
            foreach ($this->failures as $f) {
                echo "  \033[31m✗\033[0m {$f['name']}\n";
                echo "    {$f['error']}\n";
            }
        }
        echo str_repeat('─', 50) . "\n";
    }

    public function hasFailed(): bool
    {
        return $this->failed > 0;
    }
}

class AssertionError extends \RuntimeException {}
