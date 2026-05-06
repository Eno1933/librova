@props(['book'])

<a href="{{ route('books.show', $book->slug) }}" class="card group block overflow-hidden">
    <div class="aspect-[3/4] bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
        @if($book->cover_image)
            <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
        @endif
        @if($book->is_featured)
            <span class="absolute top-2 right-2 bg-secondary-light dark:bg-secondary-dark text-gray-900 text-xs font-semibold px-2 py-1 rounded-full">
                Featured
            </span>
        @endif
    </div>
    <div class="p-3">
        <h3 class="font-heading font-semibold text-sm line-clamp-2 text-text-primary-light dark:text-text-primary-dark group-hover:text-primary-light dark:group-hover:text-primary-dark transition">
            {{ $book->title }}
        </h3>
        <p class="text-xs text-text-secondary-light dark:text-text-secondary-dark mt-1">{{ $book->author }}</p>
        <div class="flex items-center mt-2">
            <div class="flex items-center text-yellow-500">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="w-3 h-3 {{ $i <= round($book->averageRating()) ? 'fill-current' : 'text-gray-300 dark:text-gray-600' }}" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endfor
            </div>
            <span class="text-xs text-text-muted-light dark:text-text-muted-dark ml-1">
                {{ $book->ratingsCount() }}
            </span>
        </div>
    </div>
</a>