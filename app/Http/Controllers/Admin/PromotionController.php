<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()
                               ->paginate(10);

        return view(
            'admin.promotions.index',
            compact('promotions')
        );
    }

    public function create()
    {
        return view(
            'admin.promotions.create'
        );
    }

    public function store(Request $request)
    {
        $validated =
            $request->validate([

                'name' =>
                    'required',

                'description' =>
                    'nullable',

                'start_date' =>
                    'nullable|date',

                'end_date' =>
                    'nullable|date'
            ]);

        Promotion::create(
            $validated
        );

        return redirect()

            ->route(
                'admin.promotions.index'
            )

            ->with(
                'success',
                'Promotion créée.'
            );
    }

    public function edit(
        Promotion $promotion
    )
    {
        return view(
            'admin.promotions.edit',
            compact('promotion')
        );
    }

    public function update(
        Request $request,
        Promotion $promotion
    )
    {
        $promotion->update(

            $request->validate([

                'name' =>
                    'required',

                'description' =>
                    'nullable',

                'start_date' =>
                    'nullable|date',

                'end_date' =>
                    'nullable|date'
            ])
        );

        return redirect()

            ->route(
                'admin.promotions.index'
            )

            ->with(
                'success',
                'Promotion modifiée.'
            );
    }

    public function destroy(
        Promotion $promotion
    )
    {
        $promotion->delete();

        return back()->with(
            'success',
            'Promotion supprimée.'
        );
    }
}