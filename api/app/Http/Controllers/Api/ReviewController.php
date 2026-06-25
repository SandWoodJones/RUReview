<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;

class ReviewController extends Controller
{
    use ApiResponse;

    private function storeImage($request, Review $review): void {
        if (!$request->hasFile('image'))
            return;

        $file = $request->file('image');
        $review->image()->create([
            'image_data' => file_get_contents($file->getRealPath()),
            'mime_type' => $file->getMimeType()
        ]);
    }

    public function index(): JsonResponse
    {
        $reviews = Review::with(['user', 'meal'])->latest()->get();

        return $this->success($reviews);
    }

    public function show(Review $review): JsonResponse
    {
        return $this->success($review->load(['user', 'meal']));
    }

    public function store(StoreReviewRequest $request): JsonResponse
    {
        $userId = $request->user()->id;
        $mealId = $request->validated('meal_id');

        if (Review::where('user_id', $userId)->where('meal_id', $mealId)->exists())
            return $this->error('Você ja avaliou esta refeição', 409);

        $review = Review::create(['user_id' => $userId, 'meal_id' => $mealId, 'rating' => $request->validated('rating'), 'comment' => $request->validated('comment')]);
        $this->storeImage($request, $review);

        return $this->success($review->load('image'), 'Avaliação registrada com sucesso', 201);
    }

    public function update(UpdateReviewRequest $request, Review $review): JsonResponse {
        if ($review->user_id !== $request->user()->id)
            return $this->error('Você só pode editar suas próprias avaliações', 403);

        $review->update([
            'rating' => $request->validated('rating'),
            'comment' => $request->validated('comment'),
        ]);

        if ($request->hasFile('image')) {
            $review->image()->delete();
            $this->storeImage($request, $review);
        }

        return $this->success($review->load('image'), 'Avaliação atualizada com sucesso');
    }

    public function destroy(Review $review): JsonResponse {
        if ($review->user_id !== request()->user()->id)
            return $this->error('Você só pode deletar suas próprias avaliações', 403);

        $review->delete();

        return $this->success(null, 'Avaliação deletada com sucesso');
    }
}
