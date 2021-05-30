<?php

namespace Tests\Feature;

use App\Jobs\ProcessXmlFilesJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class XmlUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_is_accessible()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_it_can_process_xml_files_for_people_and_shiporders()
    {
        $this->withoutExceptionHandling();
        $people = UploadedFile::fake()->createWithContent('people.xml',
            file_get_contents(__DIR__ . '/../stubs/people.xml')
        );
        $shipOrders = UploadedFile::fake()->createWithContent('shiporders.xml',
            file_get_contents(__DIR__ . '/../stubs/shiporders.xml')
        );

        $response = $this->post(route('web.xml-upload'), [
            'people' => $people,
            'shiporders' => $shipOrders
        ]);

        $response->assertSessionHasNoErrors()
                 ->assertStatus(302)
                 ->assertRedirect(route('web.index'));
    }

    public function test_it_can_process_xml_files_in_the_background()
    {
        Queue::fake();

        $people = UploadedFile::fake()->createWithContent('people.xml',
            file_get_contents(__DIR__ . '/../stubs/people.xml')
        );
        $shipOrders = UploadedFile::fake()->createWithContent('shiporders.xml',
            file_get_contents(__DIR__ . '/../stubs/shiporders.xml')
        );

        $response = $this->post(route('web.xml-upload'), [
            'people' => $people,
            'shiporders' => $shipOrders,
            'async' => true,
        ]);

        Queue::assertPushed(ProcessXmlFilesJob::class);
        $response->assertSessionHasNoErrors()
                 ->assertStatus(302)
                 ->assertRedirect(route('web.index'));

    }

    public function test_it_returns_errors_when_files_are_invalid()
    {
        $people = UploadedFile::fake()->createWithContent('people.xml',
            file_get_contents(__DIR__ . '/../stubs/people_invalid.xml')
        );
        $shipOrders = UploadedFile::fake()->createWithContent('shiporders.xml',
            file_get_contents(__DIR__ . '/../stubs/shiporders_invalid.xml')
        );

        $response = $this->post(route('web.xml-upload'), [
            'people' => $people,
            'shiporders' => $shipOrders
        ]);

        $response->assertSessionHasErrors()
                 ->assertStatus(302)
                 ->assertRedirect(route('web.index'));
    }

    public function test_it_returns_errors_when_files_are_malformed()
    {
        $people = UploadedFile::fake()->createWithContent('people.xml',
            file_get_contents(__DIR__ . '/../stubs/people_malformed.xml')
        );
        $shipOrders = UploadedFile::fake()->createWithContent('shiporders.xml',
            file_get_contents(__DIR__ . '/../stubs/shiporders_malformed.xml')
        );

        $response = $this->post(route('web.xml-upload'), [
            'people' => $people,
            'shiporders' => $shipOrders
        ]);

        $response->assertSessionHasErrors()
                 ->assertStatus(302)
                 ->assertRedirect(route('web.index'));
    }

}
