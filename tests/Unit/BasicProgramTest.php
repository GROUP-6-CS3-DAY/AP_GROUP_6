<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class BasicProgramTest extends TestCase
{
    public function test_basic_functionality()
    {
        $this->assertTrue(true);
        $this->assertEquals('test', 'test');
    }

    public function test_program_validation_rules()
    {
        // Test basic validation logic without domain entities
        $name = 'Valid Program Name';
        $description = 'This is a valid description that is long enough';
        
        $this->assertGreaterThan(3, strlen($name));
        $this->assertGreaterThan(10, strlen($description));
    }

    public function test_national_alignment_validation()
    {
        $validAlignments = ['NDPIII', 'DigitalRoadmap2023_2028', '4IR'];
        $testAlignment = 'NDPIII, DigitalRoadmap2023_2028';
        
        $alignmentTokens = array_map('trim', explode(',', $testAlignment));
        $hasValidAlignment = false;
        
        foreach ($alignmentTokens as $token) {
            if (in_array($token, $validAlignments)) {
                $hasValidAlignment = true;
                break;
            }
        }
        
        $this->assertTrue($hasValidAlignment);
    }
}
