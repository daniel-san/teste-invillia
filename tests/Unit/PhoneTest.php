<?php

namespace Tests\Unit;

use App\Models\Person;
use App\Models\Phone;
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

        $person->phones()->create([
            'number' => '4444444'
        ]);

        $phone = $person->phones()->first();

        $this->assertInstanceOf(Person::class, $phone->person);
    }
}
