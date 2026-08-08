<form method="POST" action="{{ $action ?? route('instructor.l1-management.update', $target) }}">

    @csrf
    @method('PUT')

    @php $formErrors = $errors->getBag('default'); @endphp

    @if($formErrors->has('subject_ids'))
        <div class="alert alert-danger py-2 mb-2">
            Au moins une matière doit être sélectionnée.
        </div>
    @endif

    <div class="mb-3">

        @foreach($subjects as $subject)

            <div class="form-check form-check-inline">

                <input
                    type="checkbox"
                    class="form-check-input subject-checkbox-{{ $target->id }}"
                    name="subject_ids[]"
                    id="subject-{{ $target->id }}-{{ $subject->id }}"
                    value="{{ $subject->id }}"
                    @checked($target->subjects->contains($subject->id))
                >

                <label class="form-check-label" for="subject-{{ $target->id }}-{{ $subject->id }}">
                    {{ $subject->name }}
                </label>

            </div>

        @endforeach

    </div>

    <button type="submit" class="btn btn-success btn-sm"
            onclick="
                const checked = document.querySelectorAll('.subject-checkbox-{{ $target->id }}:checked').length;
                if (checked === 0) {
                    event.preventDefault();
                    alert('Veuillez sélectionner au moins une matière pour cet instructeur.');
                }
            ">
        Enregistrer
    </button>

</form>
