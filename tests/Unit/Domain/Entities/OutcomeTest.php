<?php

namespace Tests\Unit\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\Outcome;
use App\Domain\ValueObjects\OutcomeType;
use App\Domain\ValueObjects\CommercializationStatus;

class OutcomeTest extends TestCase
{
    public function test_can_create_outcome_with_valid_data()
    {
        $mockOutcomeType = $this->createMock(OutcomeType::class);
        $mockOutcomeType->method('getValue')->willReturn('publication');
        $mockOutcomeType->method('getDisplayName')->willReturn('Publication');

        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);
        $mockCommercializationStatus->method('getValue')->willReturn('ready');
        $mockCommercializationStatus->method('getDisplayName')->willReturn('Ready');

        $outcome = new Outcome(
            id: 'outcome-123',
            projectId: 'proj-1',
            title: 'Research Publication on AI Innovation',
            description: 'Published research paper on artificial intelligence innovations in healthcare',
            outcomeType: $mockOutcomeType,
            qualityCertification: 'ISO 9001',
            dateAchieved: '2024-01-15',
            commercializationStatus: $mockCommercializationStatus,
            impact: 'Significant contribution to healthcare AI research',
            artifactLink: 'https://example.com/publication.pdf'
        );

        $this->assertEquals('outcome-123', $outcome->getId());
        $this->assertEquals('proj-1', $outcome->getProjectId());
        $this->assertEquals('Research Publication on AI Innovation', $outcome->getTitle());
        $this->assertEquals('2024-01-15', $outcome->getDateAchieved());
        $this->assertEquals('publication', $outcome->getOutcomeType()->getValue());
        $this->assertEquals('ready', $outcome->getCommercializationStatus()->getValue());
    }

    public function test_required_fields_validation_project_id()
    {
        $mockOutcomeType = $this->createMock(OutcomeType::class);
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Outcome.ProjectId is required');

        new Outcome(
            id: 'outcome-123',
            projectId: '', // Empty project ID should fail
            title: 'Valid Title',
            description: 'Valid description here',
            outcomeType: $mockOutcomeType,
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: $mockCommercializationStatus
        );
    }

    public function test_required_fields_validation_title()
    {
        $mockOutcomeType = $this->createMock(OutcomeType::class);
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Outcome.Title is required');

        new Outcome(
            id: 'outcome-123',
            projectId: 'proj-1',
            title: '', // Empty title should fail
            description: 'Valid description here',
            outcomeType: $mockOutcomeType,
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: $mockCommercializationStatus
        );
    }

    public function test_required_fields_validation_description()
    {
        $mockOutcomeType = $this->createMock(OutcomeType::class);
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Outcome.Description is required');

        new Outcome(
            id: 'outcome-123',
            projectId: 'proj-1',
            title: 'Valid Title',
            description: '', // Empty description should fail
            outcomeType: $mockOutcomeType,
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: $mockCommercializationStatus
        );
    }

    public function test_title_length_validation()
    {
        $mockOutcomeType = $this->createMock(OutcomeType::class);
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Outcome title must be at least 5 characters long');

        new Outcome(
            id: 'outcome-123',
            projectId: 'proj-1',
            title: 'ABC', // Too short
            description: 'Valid description here',
            outcomeType: $mockOutcomeType,
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: $mockCommercializationStatus
        );
    }

    public function test_description_length_validation()
    {
        $mockOutcomeType = $this->createMock(OutcomeType::class);
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Outcome description must be at least 15 characters long');

        new Outcome(
            id: 'outcome-123',
            projectId: 'proj-1',
            title: 'Valid Title',
            description: 'Short desc', // Too short
            outcomeType: $mockOutcomeType,
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: $mockCommercializationStatus
        );
    }

    public function test_future_date_validation()
    {
        $mockOutcomeType = $this->createMock(OutcomeType::class);
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);

        $futureDate = date('Y-m-d', strtotime('+1 year'));

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Outcome date achieved cannot be in the future');

        new Outcome(
            id: 'outcome-123',
            projectId: 'proj-1',
            title: 'Valid Title',
            description: 'Valid description here',
            outcomeType: $mockOutcomeType,
            qualityCertification: '',
            dateAchieved: $futureDate, // Future date should fail
            commercializationStatus: $mockCommercializationStatus
        );
    }

    public function test_update_validates_business_rules()
    {
        $mockOutcomeType = $this->createMock(OutcomeType::class);
        $mockOutcomeType->method('getValue')->willReturn('publication');
        
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);
        $mockCommercializationStatus->method('getValue')->willReturn('ready');

        $outcome = new Outcome(
            id: 'outcome-123',
            projectId: 'proj-1',
            title: 'Original Title',
            description: 'Original description here',
            outcomeType: $mockOutcomeType,
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: $mockCommercializationStatus
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Outcome title must be at least 5 characters long');

        $outcome->update(['title' => 'AB']); // Too short
    }

    public function test_successful_update_changes_properties()
    {
        $mockOutcomeType = $this->createMock(OutcomeType::class);
        $mockOutcomeType->method('getValue')->willReturn('publication');
        
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);
        $mockCommercializationStatus->method('getValue')->willReturn('ready');

        $outcome = new Outcome(
            id: 'outcome-123',
            projectId: 'proj-1',
            title: 'Original Title',
            description: 'Original description here',
            outcomeType: $mockOutcomeType,
            qualityCertification: '',
            dateAchieved: '2024-01-15',
            commercializationStatus: $mockCommercializationStatus
        );

        $outcome->update([
            'title' => 'Updated Outcome Title',
            'description' => 'Updated description with more comprehensive details',
            'impact' => 'Significant industry impact achieved',
            'artifact_link' => 'https://example.com/updated-link.pdf'
        ]);

        $this->assertEquals('Updated Outcome Title', $outcome->getTitle());
        $this->assertEquals('Updated description with more comprehensive details', $outcome->getDescription());
        $this->assertEquals('Significant industry impact achieved', $outcome->getImpact());
        $this->assertEquals('https://example.com/updated-link.pdf', $outcome->getArtifactLink());
    }

    public function test_commercialization_readiness_validation()
    {
        $mockOutcomeType = $this->createMock(OutcomeType::class);
        $mockOutcomeType->method('getValue')->willReturn('product');
        
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);
        $mockCommercializationStatus->method('getValue')->willReturn('ready');

        $outcome = new Outcome(
            id: 'outcome-123',
            projectId: 'proj-1',
            title: 'Product Development Outcome',
            description: 'Developed innovative healthcare product',
            outcomeType: $mockOutcomeType,
            qualityCertification: 'FDA Approved',
            dateAchieved: '2024-01-15',
            commercializationStatus: $mockCommercializationStatus,
            impact: 'Revolutionary healthcare solution',
            artifactLink: 'https://example.com/product.html'
        );

        $this->assertTrue($outcome->isReadyForCommercialization());
        $this->assertTrue($outcome->hasQualityCertification());
        $this->assertTrue($outcome->hasSignificantImpact());
    }

    public function test_outcome_type_specific_validation()
    {
        // Test patent-specific validation
        $mockPatentType = $this->createMock(OutcomeType::class);
        $mockPatentType->method('getValue')->willReturn('patent');
        
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);
        $mockCommercializationStatus->method('getValue')->willReturn('in_progress');

        $patentOutcome = new Outcome(
            id: 'outcome-patent',
            projectId: 'proj-1',
            title: 'AI Innovation Patent',
            description: 'Patent for artificial intelligence healthcare innovation',
            outcomeType: $mockPatentType,
            qualityCertification: 'Patent Pending',
            dateAchieved: '2024-01-15',
            commercializationStatus: $mockCommercializationStatus
        );

        $this->assertEquals('patent', $patentOutcome->getOutcomeType()->getValue());
        $this->assertTrue($patentOutcome->requiresIntellectualPropertyProtection());
    }

    public function test_artifact_link_validation()
    {
        $validUrls = [
            'https://example.com/document.pdf',
            'http://research.org/paper.html',
            'https://patents.uspto.gov/patent/12345'
        ];

        $invalidUrls = [
            'not-a-url',
            'ftp://invalid-protocol.com',
            'javascript:alert("xss")',
            ''
        ];

        foreach ($validUrls as $url) {
            $this->assertTrue(
                $this->isValidUrl($url),
                "Valid URL should pass validation: {$url}"
            );
        }

        foreach ($invalidUrls as $url) {
            $this->assertFalse(
                $this->isValidUrl($url),
                "Invalid URL should fail validation: {$url}"
            );
        }
    }

    public function test_date_achieved_format_validation()
    {
        $validDates = [
            '2024-01-15',
            '2023-12-31',
            date('Y-m-d') // Today
        ];

        $invalidDates = [
            '15/01/2024', // Wrong format
            '2024-13-01', // Invalid month
            '2024-01-32', // Invalid day
            'invalid-date'
        ];

        foreach ($validDates as $date) {
            $this->assertTrue(
                $this->isValidDate($date),
                "Valid date should pass validation: {$date}"
            );
        }

        foreach ($invalidDates as $date) {
            $this->assertFalse(
                $this->isValidDate($date),
                "Invalid date should fail validation: {$date}"
            );
        }
    }

    public function test_outcome_business_logic_methods()
    {
        $mockOutcomeType = $this->createMock(OutcomeType::class);
        $mockOutcomeType->method('getValue')->willReturn('product');
        
        $mockCommercializationStatus = $this->createMock(CommercializationStatus::class);
        $mockCommercializationStatus->method('getValue')->willReturn('commercialized');

        $outcome = new Outcome(
            id: 'outcome-123',
            projectId: 'proj-1',
            title: 'Commercial Product Launch',
            description: 'Successfully launched commercial healthcare product with significant market impact',
            outcomeType: $mockOutcomeType,
            qualityCertification: 'ISO 13485 Medical Device Certification',
            dateAchieved: '2024-01-15',
            commercializationStatus: $mockCommercializationStatus,
            impact: 'Generated $1M in revenue within first quarter of launch',
            artifactLink: 'https://example.com/product-page.html'
        );

        $this->assertTrue($outcome->isReadyForCommercialization());
        $this->assertTrue($outcome->hasQualityCertification());
        $this->assertTrue($outcome->hasSignificantImpact());
        $this->assertTrue($outcome->hasArtifactLink());
        $this->assertFalse($outcome->requiresIntellectualPropertyProtection()); // Product type doesn't require IP
    }

    /**
     * Helper methods for validation testing
     */
    private function isValidUrl(string $url): bool
    {
        if (empty($url)) {
            return false;
        }
        return filter_var($url, FILTER_VALIDATE_URL) !== false && 
               (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0);
    }

    private function isValidDate(string $date): bool
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
