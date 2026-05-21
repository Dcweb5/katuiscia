@extends('layouts.public')
@section('title', 'Blog — KATUISCIA | Le Journal Beauté')
@section('content')
<main id="main-content" class="bg-light pb-24">

    <section class="pt-[calc(160px+3rem)] pb-12 px-4 text-center">
      <div class="reveal-k-k">
        <h1 class="font-heading text-4xl lg:text-5xl font-light text-primary mb-4">Le Journal Beauté</h1>
        <p class="text-lg text-text-light max-w-2xl mx-auto">Explorez l'art du soin botanique, des rituels et des conseils d'experts pour révéler votre beauté authentique.</p>
      </div>
    </section>

    <section class="max-w-[1200px] mx-auto px-4 lg:px-8">
      <div class="flex justify-center gap-2 flex-wrap mb-12 reveal-k-k delay-1">
        <a href="{{ route('blog') }}" class="px-6 py-2 rounded-full border {{ !request('category') ? 'border-primary bg-primary text-white' : 'border-gray-300 hover:border-primary hover:bg-primary hover:text-white' }} text-sm uppercase tracking-wide transition-colors" style="text-decoration:none;">Tous</a>
        @foreach($categories as $cat)
        <a href="{{ route('blog', ['category' => $cat]) }}" class="px-6 py-2 rounded-full border {{ request('category') === $cat ? 'border-primary bg-primary text-white' : 'border-gray-300 hover:border-primary hover:bg-primary hover:text-white' }} text-sm uppercase tracking-wide transition-colors" style="text-decoration:none;">{{ $cat }}</a>
        @endforeach
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($posts as $post)
        <a href="{{ route('blog.show', $post->slug) }}" class="article-card reveal-k-k">
          <div class="article-card__img-wrapper">
            <span class="article-card__tag">{{ $post->category }}</span>
            @if($post->image_url)
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="article-card__img">
            @else
            <div style="height:240px;background:var(--color-peach);display:flex;align-items:center;justify-content:center;font-size:3rem;">📝</div>
            @endif
          </div>
          <div class="article-card__content">
            <span class="article-card__date">{{ $post->created_at->locale('fr')->isoFormat('DD MMMM YYYY') }}</span>
            <h3 class="article-card__title">{{ $post->title }}</h3>
            <p class="article-card__excerpt">{{ Str::limit($post->excerpt, 120) }}</p>
            <span class="article-card__read-more">Lire l'article <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg></span>
          </div>
        </a>
        @empty
        <div class="col-span-3 text-center py-16">
          <p style="font-size:3rem;">📝</p>
          <p class="text-text-muted">Aucun article pour le moment. Revenez bientôt !</p>
        </div>
        @endforelse
      </div>

      @if($posts->hasPages())
      <div class="mt-12 flex justify-center">
        {{ $posts->appends(request()->query())->links() }}
      </div>
      @endif
    </section>

  </main>
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>
@endsection
