<?php

declare(strict_types=1);

namespace Ksfraser\Tests\Unit\KnowledgeBase\Service;

use Ksfraser\KnowledgeBase\Service\KnowledgeBaseService;
use PHPUnit\Framework\TestCase;

class KnowledgeBaseServiceTest extends TestCase
{
    public function testServiceExists(): void
    {
        $this->assertTrue(
            class_exists('Ksfraser\KnowledgeBase\Service\KnowledgeBaseService') ||
            file_exists(__DIR__ . '/../../../../src/Ksfraser/KnowledgeBase/Service/KnowledgeBaseService.php')
        );
    }
}
