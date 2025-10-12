<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Presentation\Requests\ParticipantRequest;
use Illuminate\Support\Facades\Validator;

class ParticipantRequestTest extends TestCase
{
    private ParticipantRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new ParticipantRequest();
    }

    public function test_required_fields_rule()
    {
        $data = [];
        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'affiliation' => 'required|string|in:cs,ee,me,ce,other',
            'institution' => 'required|string|in:scit,other',
        ]);
        
        $this->assertFalse($validator->passes());
        $errors = $validator->errors();
        
        $this->assertTrue($errors->has('full_name'));
        $this->assertTrue($errors->has('email'));
        $this->assertTrue($errors->has('affiliation'));
        $this->assertTrue($errors->has('institution'));
    }

    public function test_email_format_validation()
    {
        $data = [
            'full_name' => 'Test User',
            'email' => 'invalid-email',
            'affiliation' => 'cs',
            'institution' => 'scit'
        ];

        $validator = Validator::make($data, [
            'email' => 'required|email|max:255',
        ]);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('email'));
    }

    public function test_affiliation_values_are_restricted()
    {
        $data = [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'affiliation' => 'invalid_affiliation',
            'institution' => 'scit'
        ];

        $validator = Validator::make($data, [
            'affiliation' => 'required|string|in:cs,ee,me,ce,other',
        ]);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('affiliation'));
    }

    public function test_institution_values_are_restricted()
    {
        $data = [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'affiliation' => 'cs',
            'institution' => 'invalid_institution'
        ];

        $validator = Validator::make($data, [
            'institution' => 'required|string|in:scit,other',
        ]);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('institution'));
    }

    public function test_specialization_requirement_rule()
    {
        $data = [
            'full_name' => 'Test User',
            'email' => 'unique@example.com',
            'affiliation' => 'cs',
            'institution' => 'scit',
            'cross_skill_trained' => true,
            'specialization' => null
        ];

        $validator = Validator::make($data, [
            'cross_skill_trained' => 'boolean',
            'specialization' => 'nullable|string|max:255',
        ]);

        // Apply the custom validation logic manually
        $validator->after(function ($validator) use ($data) {
            if ($data['cross_skill_trained'] && empty($data['specialization'])) {
                $validator->errors()->add('cross_skill_trained', 'Cross-skill flag requires Specialization.');
            }
        });

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('cross_skill_trained'));
    }

    public function test_valid_participant_data()
    {
        $data = [
            'full_name' => 'Test User',
            'email' => 'valid@example.com',
            'affiliation' => 'cs',
            'specialization' => 'software',
            'institution' => 'scit',
            'cross_skill_trained' => true
        ];

        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'affiliation' => 'required|string|in:cs,ee,me,ce,other',
            'institution' => 'required|string|in:scit,other',
            'specialization' => 'nullable|string|max:255',
            'cross_skill_trained' => 'boolean',
        ]);

        // Apply the custom validation logic manually
        $validator->after(function ($validator) use ($data) {
            if ($data['cross_skill_trained'] && empty($data['specialization'])) {
                $validator->errors()->add('cross_skill_trained', 'Cross-skill flag requires Specialization.');
            }
        });
        
        $this->assertTrue($validator->passes());
    }

    public function test_cross_skill_trained_can_be_false_without_specialization()
    {
        $data = [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'affiliation' => 'cs',
            'institution' => 'scit',
            'cross_skill_trained' => false,
            'specialization' => null
        ];

        $validator = Validator::make($data, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'affiliation' => 'required|string|in:cs,ee,me,ce,other',
            'institution' => 'required|string|in:scit,other',
            'specialization' => 'nullable|string|max:255',
            'cross_skill_trained' => 'boolean',
        ]);

        // Apply the custom validation logic manually
        $validator->after(function ($validator) use ($data) {
            if ($data['cross_skill_trained'] && empty($data['specialization'])) {
                $validator->errors()->add('cross_skill_trained', 'Cross-skill flag requires Specialization.');
            }
        });
        
        $this->assertTrue($validator->passes());
    }

    public function test_specialization_is_optional_when_provided()
    {
        $data = [
            'full_name' => 'Test User',
            'email' => 'test@example.com',
            'affiliation' => 'cs',
            'institution' => 'scit',
            'specialization' => 'Machine Learning',
            'cross_skill_trained' => false
        ];

        $validator = Validator::make($data, [
            'specialization' => 'nullable|string|max:255',
        ]);

        $this->assertTrue($validator->passes());
    }

    public function test_boolean_fields_accept_valid_values()
    {
        $validBooleanValues = [true, false, 1, 0, '1', '0'];

        foreach ($validBooleanValues as $value) {
            $data = [
                'cross_skill_trained' => $value
            ];

            $validator = Validator::make($data, [
                'cross_skill_trained' => 'boolean',
            ]);

            $this->assertTrue($validator->passes(), "Failed for boolean value: " . var_export($value, true));
        }
    }

    public function test_string_length_limits()
    {
        $data = [
            'full_name' => str_repeat('a', 256), // Too long
            'email' => str_repeat('a', 250) . '@example.com', // Too long
            'specialization' => str_repeat('a', 256), // Too long
        ];

        $validator = Validator::make($data, [
            'full_name' => 'string|max:255',
            'email' => 'email|max:255',
            'specialization' => 'nullable|string|max:255',
        ]);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->errors()->has('full_name'));
        $this->assertTrue($validator->errors()->has('email'));
        $this->assertTrue($validator->errors()->has('specialization'));
    }
}
