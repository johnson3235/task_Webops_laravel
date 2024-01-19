<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Models;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
                'model_id' => Models::all()->random()->id,

                'user_id' => User::all()->random()->id,

                'price' => $this->faker->numberBetween(100000,100000000),
               
                'Mileage' =>$this->faker->numberBetween(1000,100000000),
                
                'tank_fuel'=>$this->faker->randomElement($array = [40,50,60,80]),
               
                'engine' => $this->faker->randomElement($array2 = ['1200CC','1400CC','1600CC','2000CC']),
                
                'paint_color' => $this->faker->randomElement($array3 = ['Black','Red','blue','white']),
            
                'transmission' => $this->faker->randomElement($array4 = ['Manual','Automatic']),
            	
                'type' => $this->faker->randomElement($array6 = ['Sedan','Sports','Hatchback','Utility','Luxury','Hybrid']),

                'cylinder' => $this->faker->randomElement($array11 = ['4-Clinder','2-Clinder','3-Clinder','6-Clinder','8-Clinder','12-Clinder']),

                'fuel' => $this->faker->randomElement($array5 = ['Gas','Disel','Petrol']),

                'Doors' => $this->faker->randomElement($array7 = [2,4]),

                'seats' => $this->faker->randomElement($array8 = [2,5,7,11,14]),

                'sound' => $this->faker->randomElement($array9 = [0,1]),

                'Leather' => $this->faker->randomElement($array10 = [0,1]),

                
        ];
    }
}
