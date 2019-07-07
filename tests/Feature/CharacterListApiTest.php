<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use App\Comment;

class CharacterListApiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_character_list_route_returns_character_list()
    {
        $this->get(route("list-characters", ["episode" => 1]));

        $content = $this->response->getContent();
        $data = json_decode($content);
        
        $this->assertTrue(count($data) > 0);        
    }

    public function test_character_list_can_be_sorted_by_name_in_ascending_order()
    {
        $this->get(route("list-characters", ["episode" => 1]) . "?sortBy=name&order=asc");

        $content = $this->response->getContent();
        $data = json_decode($content);

        $names = collect($data)->pluck("name");
        $merged1 = $names->implode("-");

        $merged2 = $names->sort()->implode("-");
        
        $this->assertEquals($merged1, $merged2);
    }

    public function test_character_list_can_be_sorted_by_name_in_descending_order()
    {
        $this->get(route("list-characters", ["episode" => 1]) . "?sortBy=name&order=desc");

        $content = $this->response->getContent();
        $data = json_decode($content);

        $names = collect($data)->pluck("name");
        $merged1 = $names->implode("-");

        $merged2 = $names->sort()->reverse()->implode("-");
        
        $this->assertEquals($merged1, $merged2);
    }



    public function test_character_list_can_be_sorted_by_gender_in_ascending_order()
    {
        $this->get(route("list-characters", ["episode" => 1]) . "?sortBy=gender&order=asc");

        $content = $this->response->getContent();
        $data = json_decode($content);

        $genders = collect($data)->pluck("gender");
        $merged1 = $genders->implode("-");

        $merged2 = $genders->sort()->implode("-");
        
        $this->assertEquals($merged1, $merged2);
    }

    public function test_character_list_can_be_sorted_by_gender_in_descending_order()
    {
        $this->get(route("list-characters", ["episode" => 1]) . "?sortBy=gender&order=desc");

        $content = $this->response->getContent();
        $data = json_decode($content);

        $genders = collect($data)->pluck("gender");
        $merged1 = $genders->implode("-");

        $merged2 = $genders->sort()->reverse()->implode("-");
        
        $this->assertEquals($merged1, $merged2);
    }
}
