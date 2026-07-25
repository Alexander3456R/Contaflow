<?php

/**
 * Tests unitarios de ejemplo.
 *
 * Prueba básica para verificar la configuración de PHPUnit.
 */

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/** Pruebas unitarias de ejemplo */
class ExampleTest extends TestCase
{
    /** Prueba básica que siempre pasa */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
