<?php

namespace Tests\Feature;

use App\Models\Rota;
use App\Models\Shift;
use App\Models\Shop;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RotaControllerTest extends TestCase
{
    use RefreshDatabase;

    public $funHouse;
    public $lastMonday;
    public $rota;

    public function setUp(): void
    {
        parent::setUp();
        $date = new \DateTime();
        $this->lastMonday = $date->modify('Monday last week');

        $this->funHouse = Shop::factory()->create([
            'name' => 'FunHouse'
        ]);

        $this->rota = Rota::factory()->create([
            'shop_id' => $this->funHouse->id,
            'week_commence_date' => $this->lastMonday
        ]);
    }

    public function test_BlackWidow_works_singlemanning_at_FunHouse()
    {
        $startTime = new \DateTime();
        $startTime = $startTime->modify('Monday last week');
        $startTime = $startTime->setTime(9, 0);

        $endTime = new \DateTime();
        $endTime = $endTime->modify('Monday last week');
        $endTime = $endTime->setTime(17, 0);


        $blackWidow = Staff::factory()->create([
            'first_name' => 'Natasha',
            'surname' => 'Romanoff',
            'shop_id' => $this->funHouse->id
        ]);

        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $blackWidow->id,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);

        $response = $this->get('api/rota/' . $this->rota->id);

        $response->assertJson(
            [
                "Monday" => [
                    $blackWidow->first_name => 480
                ],
                "Tuesday" => [],
                "Wednesday" => [],
                "Thursday" => [],
                "Friday" => [],
                "Saturday" => [],
                "Sunday" => []
            ]
        );
    }

    public function test_BlackWidow_and_Thor_share_day_at_FunHouse()
    {
        $blackWidowStartTime = new \DateTime();
        $blackWidowStartTime = $blackWidowStartTime->modify('Tuesday last week');
        $blackWidowStartTime = $blackWidowStartTime->setTime(9, 0);

        $blackWidowEndTime = new \DateTime();
        $blackWidowEndTime = $blackWidowEndTime->modify('Tuesday last week');
        $blackWidowEndTime = $blackWidowEndTime->setTime(13, 0);

        $thorStartTime = new \DateTime();
        $thorStartTime = $thorStartTime->modify('Tuesday last week');
        $thorStartTime = $thorStartTime->setTime(13, 0);

        $thorEndTime = new \DateTime();
        $thorEndTime = $thorEndTime->modify('Tuesday last week');
        $thorEndTime = $thorEndTime->setTime(17, 0);

        $blackWidow = Staff::factory()->create([
            'first_name' => 'Natasha',
            'surname' => 'Romanoff',
            'shop_id' => $this->funHouse->id
        ]);
        $thor = Staff::factory()->create([
            'first_name' => 'Thor',
            'surname' => 'Odinson',
            'shop_id' => $this->funHouse->id
        ]);

        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $blackWidow->id,
            'start_time' => $blackWidowStartTime,
            'end_time' => $blackWidowEndTime
        ]);
        Shift::factory()->create([
            'rota_id' => $this->rota->id,
            'staff_id' => $thor->id,
            'start_time' => $thorStartTime,
            'end_time' => $thorEndTime
        ]);

        $response = $this->get('api/rota/' . $this->rota->id);

        $response->assertJson(
            [
                "Monday" => [],
                "Tuesday" => [
                    $blackWidow->first_name => 240,
                    $thor->first_name => 240,
                ],
                "Wednesday" => [],
                "Thursday" => [],
                "Friday" => [],
                "Saturday" => [],
                "Sunday" => []
            ]
        );
    }

    public function test_Wolverine_and_Gamora_share_day_at_FunHouse()
    {
        $wolverineStartTime = new \DateTime();
        $wolverineStartTime = $wolverineStartTime->modify('Wednesday last week');
        $wolverineStartTime = $wolverineStartTime->setTime(9, 0);

        $wolverineEndTime = new \DateTime();
        $wolverineEndTime = $wolverineEndTime->modify('Wednesday last week');
        $wolverineEndTime = $wolverineEndTime->setTime(15, 0);

        $gamoraStartTime = new \DateTime();
        $gamoraStartTime = $gamoraStartTime->modify('Wednesday last week');
        $gamoraStartTime = $gamoraStartTime->setTime(11, 0);

        $gamoraEndTime = new \DateTime();
        $gamoraEndTime = $gamoraEndTime->modify('Wednesday last week');
        $gamoraEndTime = $gamoraEndTime->setTime(17, 0);

        $wolverine = Staff::factory()->create([
               'first_name' => 'James',
               'surname' => 'Howlett',
               'shop_id' => $this->funHouse->id
           ]);
        $gamora = Staff::factory()->create([
             'first_name' => 'Gamora',
             'surname' => 'Zen',
             'shop_id' => $this->funHouse->id
         ]);

        Shift::factory()->create([
             'rota_id' => $this->rota->id,
             'staff_id' => $wolverine->id,
             'start_time' => $wolverineStartTime,
             'end_time' => $wolverineEndTime
         ]);
        Shift::factory()->create([
             'rota_id' => $this->rota->id,
             'staff_id' => $gamora->id,
             'start_time' => $gamoraStartTime,
             'end_time' => $gamoraEndTime
         ]);

        $response = $this->get('api/rota/' . $this->rota->id);

        $response->assertJson([
            "Monday" => [],
            "Tuesday" => [],
            "Wednesday" => [
                $wolverine->first_name => 120,
                $gamora->first_name => 120,
            ],
            "Thursday" => [],
            "Friday" => [],
            "Saturday" => [],
            "Sunday" => []
        ]);
    }

    public function test_Professor_and_lazyCyclops_share_day_at_FunHouse()
    {
        $professorStartTime = new \DateTime();
        $professorStartTime = $professorStartTime->modify('Thursday last week');
        $professorStartTime = $professorStartTime->setTime(9, 0);

        $professorEndTime = new \DateTime();
        $professorEndTime = $professorEndTime->modify('Thursday last week');
        $professorEndTime = $professorEndTime->setTime(17, 0);

        $cyclopsStartTime = new \DateTime();
        $cyclopsStartTime = $cyclopsStartTime->modify('Thursday last week');
        $cyclopsStartTime = $cyclopsStartTime->setTime(13, 0);

        $cyclopsEndTime = new \DateTime();
        $cyclopsEndTime = $cyclopsEndTime->modify('Thursday last week');
        $cyclopsEndTime = $cyclopsEndTime->setTime(14, 0);

        $professor = Staff::factory()->create([
                                                  'first_name' => 'James',
                                                  'surname' => 'Howlett',
                                                  'shop_id' => $this->funHouse->id
                                              ]);
        $cyclops = Staff::factory()->create([
                                               'first_name' => 'Gamora',
                                               'surname' => 'Zen',
                                               'shop_id' => $this->funHouse->id
                                           ]);

        Shift::factory()->create([
                                     'rota_id' => $this->rota->id,
                                     'staff_id' => $professor->id,
                                     'start_time' => $professorStartTime,
                                     'end_time' => $professorEndTime
                                 ]);
        Shift::factory()->create([
                                     'rota_id' => $this->rota->id,
                                     'staff_id' => $cyclops->id,
                                     'start_time' => $cyclopsStartTime,
                                     'end_time' => $cyclopsEndTime
                                 ]);

        $response = $this->get('api/rota/' . $this->rota->id);

        $response->assertJson([
                                  "Monday" => [],
                                  "Tuesday" => [],
                                  "Wednesday" => [],
                                  "Thursday" => [
                                      $professor->first_name => 420,
                                  ],
                                  "Friday" => [],
                                  "Saturday" => [],
                                  "Sunday" => []
                              ]);
    }

    public function test_url_validation()
    {
        $this->expectException(\Exception::class);

        $startTime = new \DateTime();
        $startTime = $startTime->modify('Monday last week');
        $startTime = $startTime->setTime(9, 0);

        $endTime = new \DateTime();
        $endTime = $endTime->modify('Monday last week');
        $endTime = $endTime->setTime(17, 0);


        $blackWidow = Staff::factory()->create([
                                                   'first_name' => 'Natasha',
                                                   'surname' => 'Romanoff',
                                                   'shop_id' => $this->funHouse->id
                                               ]);

        Shift::factory()->create([
                                     'rota_id' => $this->rota->id,
                                     'staff_id' => $blackWidow->id,
                                     'start_time' => $startTime,
                                     'end_time' => $endTime
                                 ]);

        $response = $this->get('api/rota/' . 'fake');

        $response->assertJson(
            [
                "Monday" => [
                    $blackWidow->first_name => 480
                ],
                "Tuesday" => [],
                "Wednesday" => [],
                "Thursday" => [],
                "Friday" => [],
                "Saturday" => [],
                "Sunday" => []
            ]
        );
    }

    public function test_wrong_time()
    {
        $this->expectException(\Exception::class);

        $startTime = new \DateTime();
        $startTime = $startTime->modify('Monday last week');
        $startTime = $startTime->setTime(17, 0);

        $endTime = new \DateTime();
        $endTime = $endTime->modify('Monday last week');
        $endTime = $endTime->setTime(9, 0);


        $blackWidow = Staff::factory()->create([
                                                   'first_name' => 'Natasha',
                                                   'surname' => 'Romanoff',
                                                   'shop_id' => $this->funHouse->id
                                               ]);

        Shift::factory()->create([
                                     'rota_id' => $this->rota->id,
                                     'staff_id' => $blackWidow->id,
                                     'start_time' => $startTime,
                                     'end_time' => $endTime
                                 ]);

        $response = $this->get('api/rota/' . $this->rota->id);

        $response->assertJson(
            [
                "Monday" => [
                    $blackWidow->first_name => 480
                ],
                "Tuesday" => [],
                "Wednesday" => [],
                "Thursday" => [],
                "Friday" => [],
                "Saturday" => [],
                "Sunday" => []
            ]
        );
    }
}
