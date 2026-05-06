<div x-data="starRating({{ $book->id }}, {{ $userRating->score ?? 0 }})" class="star-rating flex items-center gap-1">
    <template x-for="(star, index) in [1,2,3,4,5]" :key="index">
        <button @click="setRating(star)" @mouseenter="hoverRating = star" @mouseleave="hoverRating = 0"
                :class="{
                    'filled': (hoverRating || currentRating) >= star,
                    'empty': (hoverRating || currentRating) < star
                }"
                class="star text-3xl transition-all duration-150">
            ★
        </button>
    </template>
    <span x-show="averageRating > 0" x-text="'(' + averageRating + ')'"
          class="ml-2 text-sm text-[var(--color-text-secondary)]" x-cloak></span>
</div>

<script>
    function starRating(bookId, initialRating) {
        return {
            currentRating: initialRating,
            hoverRating: 0,
            averageRating: {{ $book->averageRating() }},
            totalRatings: {{ $book->ratingsCount() }},

            async setRating(score) {
                this.currentRating = score;

                try {
                    const response = await fetch(`/books/${bookId}/rate`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        },
                        body: JSON.stringify({ score }),
                    });
                    const data = await response.json();
                    this.averageRating = data.average;
                    this.totalRatings = data.count;
                } catch (error) {
                    console.error('Error saving rating:', error);
                }
            }
        };
    }
</script>