<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Meal;
use App\Http\Requests\StoreMealRequest;
use App\Models\DailyMenu;
use App\Http\Requests\UpdateMealRequest;

class MealController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Meal::query()->with('dailyMenu');

        if ($date = $request->query('date'))
            $query->whereHas('dailyMenu', fn($q) => $q->where('date', $date));

        return $this->success($query->get());
    }

    public function show(Meal $meal): JsonResponse
    {
        return $this->success($meal->load('dailyMenu'));
    }

    public function store(StoreMealRequest $request): JsonResponse
    {
        $data = $request->validated();

        $menu = DailyMenu::firstOrCreate(['date' => $data['date']]);

        if ($menu->meals()->where('type', $data['type'])->exists())
            return $this->error('Já existe uma refeição deste tipo nesta data', 409);

        $meal = $menu->meals()->create($data);
        return $this->success($meal, 'Refeição cadastrada com sucesso', 201);
    }

    public function update(UpdateMealRequest $request, Meal $meal): JsonResponse
    {
        $meal->update($request->validated());

        return $this->success($meal, 'Refeição atualizada com sucesso');
    }

    public function destroy(Meal $meal): JsonResponse
    {
        $meal->delete();

        return $this->success(null, 'Refeição deletada com sucesso');
    }
}
