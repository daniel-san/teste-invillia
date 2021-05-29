<?php

namespace Tests\Unit;

use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_belongs_to_a_person()
    {
        $person = Person::create([
            'name' => 'Test Person'
        ]);

        $phone = $person->phones()->create([
            'number' => '4444444'
        ]);

        $this->assertInstanceOf(Person::class, $phone->person);
    }
}
