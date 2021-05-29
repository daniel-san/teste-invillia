<?php

namespace Tests\Unit;

use App\Models\Person;
use App\Models\Phone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_many_phones()
    {
        $person = Person::create([
            'name' => 'Test Person'
        ]);

        $person->phones()->createMany([
            ['number' => '4444444'],
            ['number' => '5555555'],
            ['number' => '6666666'],
        ]);

        $this->assertCount(3, $person->phones);
        $this->assertContainsOnlyInstancesOf(Phone::class, $person->phones);
    }
}