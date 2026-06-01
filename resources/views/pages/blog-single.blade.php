@extends('layouts.public')
@section('title', $post->title . ' — KATUISCIA')
@section('description', $post->excerpt ?? Str::limit(strip_tags($post->content), 150))

@section('head')
<meta name="keywords" content="{{ $post->tags }}">
<style>
/* ===== ARTICLE HERO ===== */
.article-hero {
  position:relative;padding:10rem 0 3rem;
  background:linear-gradient(180deg, #faf7f2 0%, #fff 100%);
  text-align:center;
}
.article-hero__category {
  display:inline-block;padding:6px 18px;border-radius:99px;
  font-size:11px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;
  background:#ede4db;color:#8b6f5a;margin-bottom:1.25rem;
}
.article-hero__title {
  font-family:var(--font-heading);font-size:clamp(2rem, 5vw, 3.5rem);
  font-weight:300;color:#2d2117;line-height:1.25;
  max-width:750px;margin:0 auto 1rem;
}
.article-hero__meta {
  display:flex;justify-content:center;align-items:center;gap:1.5rem;
  font-size:14px;color:#8b7b6e;
}
.article-hero__meta span { display:flex;align-items:center;gap:0.35rem; }
.article-hero__excerpt {
  max-width:650px;margin:1.5rem auto 0;
  font-size:1.15rem;line-height:1.65;color:#6b5d53;
}

/* ===== ARTICLE LAYOUT ===== */
.article-body {
  max-width:720px;margin:0 auto;padding:2rem 1.5rem 4rem;
}
.article-cover {
  width:100%;max-height:500px;object-fit:cover;border-radius:16px;
  margin-bottom:2.5rem;box-shadow:0 4px 24px rgba(0,0,0,0.06);
}

/* ===== CONTENT TYPOGRAPHY ===== */
.article-text {
  font-family:'Georgia', 'Times New Roman', var(--font-body), serif;
  font-size:18px;line-height:1.8;color:#2d2117;
}
.article-text > *:first-child::first-letter {
  font-size:3.5em;float:left;line-height:0.8;margin:0.1em 0.5em 0.2em 0;
  font-weight:700;color:#c4967a;font-family:var(--font-heading);
}
.article-text h1, .article-text h2, .article-text h3, .article-text h4 {
  font-family:var(--font-heading);color:#2d2117;margin:2.5rem 0 1rem;
  font-weight:400;clear:both;
}
.article-text h2 { font-size:1.75rem;line-height:1.3; }
.article-text h3 { font-size:1.35rem;line-height:1.4; }
.article-text h4 { font-size:1.15rem;text-transform:uppercase;letter-spacing:0.08em;color:#c4967a; }
.article-text p { margin-bottom:1.5rem; }
.article-text a {
  color:#c4967a;text-decoration:none;
  border-bottom:1px solid rgba(196,150,122,0.3);
  transition:border-color 0.2s;
}
.article-text a:hover { border-bottom-color:#c4967a; }
.article-text ul, .article-text ol { margin:1.25rem 0 1.25rem 1.75rem; }
.article-text li { margin-bottom:0.5rem;padding-left:0.25rem; }
.article-text li::marker { color:#c4967a; }
.article-text img {
  max-width:100%;border-radius:12px;margin:2rem 0;
  box-shadow:0 2px 12px rgba(0,0,0,0.05);
}
.article-text blockquote {
  border-left:3px solid #c4967a;padding:1.25rem 1.75rem;
  margin:2rem 0;background:linear-gradient(90deg, rgba(196,150,122,0.04) 0%, transparent 100%);
  font-style:italic;color:#6b5d53;font-size:1.05em;
}
.article-text table {
  width:100%;border-collapse:collapse;margin:2rem 0;font-size:0.95em;
}
.article-text th { background:#faf7f2;color:#2d2117;font-weight:600;text-align:left;padding:12px 16px;border-bottom:2px solid #e0d5c8; }
.article-text td { padding:12px 16px;border-bottom:1px solid #eee7df; }
.article-text code { background:#faf7f2;padding:3px 8px;border-radius:5px;font-size:0.9em;color:#8b6f5a; }
.article-text pre { background:#1e1e1e;color:#d4d4d4;padding:1.5rem;border-radius:10px;overflow-x:auto;margin:2rem 0;font-size:0.9em;line-height:1.6; }
.article-text pre code { background:none;padding:0;color:inherit; }

/* ===== AUTHOR CARD ===== */
.author-card {
  display:flex;gap:1.5rem;align-items:center;
  padding:2rem;background:#faf7f2;border-radius:16px;
  margin:3rem 0;border:1px solid #ede4db;
}
.author-card__avatar {
  width:64px;height:64px;border-radius:50%;
  background:linear-gradient(135deg, #c4967a, #8b6f5a);
  display:flex;align-items:center;justify-content:center;
  color:#fff;font-size:1.5rem;font-weight:600;flex-shrink:0;
}
.author-card__name { font-weight:600;font-size:1.05rem;color:#2d2117;margin-bottom:0.25rem; }
.author-card__bio { font-size:14px;color:#8b7b6e;line-height:1.5; }

/* ===== TAGS ===== */
.article-tags { margin:2.5rem 0;display:flex;flex-wrap:wrap;align-items:center;gap:0.5rem; }
.article-tags__label { font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#a39688;margin-right:0.25rem; }
.article-tag {
  display:inline-block;padding:6px 16px;border-radius:99px;
  font-size:12px;color:#8b7b6e;background:#faf7f2;
  border:1px solid #ede4db;transition:all 0.2s;text-decoration:none;
}
.article-tag:hover { background:#ede4db;color:#6b5d53; }

/* ===== BACK LINK ===== */
.article-back {
  display:inline-flex;align-items:center;gap:0.5rem;
  padding:12px 0;color:#8b7b6e;font-size:14px;text-decoration:none;
  border-bottom:1px solid transparent;transition:all 0.2s;
}
.article-back:hover { color:#2d2117;border-bottom-color:#c4967a; }

/* ===== DIVIDER ===== */
.article-divider {
  border:none;height:1px;background:linear-gradient(90deg, #ede4db, transparent);
  margin:3rem 0;
}

/* ===== RECENT SECTION ===== */
.related-section {
  max-width:1100px;margin:0 auto;padding:3rem 1.5rem 5rem;
}
.related-section__title {
  font-family:var(--font-heading);font-size:1.75rem;font-weight:300;
  color:#2d2117;text-align:center;margin-bottom:2.5rem;
}
.related-grid { display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem; }
.related-card {
  display:block;text-decoration:none;border-radius:14px;overflow:hidden;
  background:#fff;border:1px solid #ede4db;transition:all 0.25s;
}
.related-card:hover { transform:translateY(-3px);box-shadow:0 12px 32px rgba(0,0,0,0.06);border-color:#d4c5b8; }
.related-card__img {
  width:100%;height:200px;object-fit:cover;display:block;
}
.related-card__placeholder {
  width:100%;height:200px;display:flex;align-items:center;justify-content:center;
  background:linear-gradient(135deg, #faf7f2, #ede4db);font-size:2.5rem;
}
.related-card__body { padding:1.25rem 1.5rem; }
.related-card__tag {
  font-size:10px;font-weight:600;text-transform:uppercase;
  letter-spacing:0.08em;color:#c4967a;margin-bottom:0.5rem;display:block;
}
.related-card__title {
  font-family:var(--font-heading);font-size:1.05rem;font-weight:400;
  color:#2d2117;line-height:1.4;margin-bottom:0.5rem;
}
.related-card__date { font-size:12px;color:#a39688; }

@media (max-width:768px) {
  .article-hero { padding:8rem 1rem 2rem; }
  .article-body { padding:1.5rem 1rem 3rem; }
  .article-text { font-size:17px; }
  .related-grid { grid-template-columns:1fr; }
}
</style>
@endsection

@section('content')
<main id="main-content">

  {{-- HERO --}}
  <header class="article-hero">
    <div class="reveal-k-k">
      <span class="article-hero__category">{{ $post->category }}</span>
      <h1 class="article-hero__title">{{ $post->title }}</h1>
      <div class="article-hero__meta">
        <span>📅 {{ $post->created_at->locale('fr')->isoFormat('DD MMMM YYYY') }}</span>
        <span>👁 {{ $post->views }} vues</span>
        <span>⏱ {{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} min de lecture</span>
      </div>
      @if($post->excerpt)
      <p class="article-hero__excerpt">{{ $post->excerpt }}</p>
      @endif
    </div>
  </header>

  {{-- ARTICLE BODY --}}
  <article class="article-body reveal-k-k delay-1">
    @if($post->image_url)
    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="article-cover">
    @endif

    <div class="article-text">
      {!! $post->content !!}
    </div>

    {{-- TAGS --}}
    @if($post->tags)
    <div class="article-tags">
      <span class="article-tags__label">Tags</span>
      @foreach(explode(',', $post->tags) as $tag)
      <span class="article-tag">{{ trim($tag) }}</span>
      @endforeach
    </div>
    @endif

    {{-- AUTHOR --}}
    <div class="author-card">
      <div class="author-card__avatar">K</div>
      <div>
        <div class="author-card__name">KATUISCIA</div>
        <div class="author-card__bio">L'art du soin botanique, des rituels et des conseils d'experts pour révéler votre beauté authentique.</div>
      </div>
    </div>

    <hr class="article-divider">

    <a href="{{ route('blog') }}" class="article-back">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
      Retour au Journal Beauté
    </a>
  </article>

  {{-- RELATED POSTS --}}
  @if($recentPosts->isNotEmpty())
  <section class="related-section reveal-k-k delay-3">
    <h2 class="related-section__title">À lire aussi</h2>
    <div class="related-grid">
      @foreach($recentPosts as $r)
      <a href="{{ route('blog.show', $r->slug) }}" class="related-card">
        @if($r->image_url)
        <img src="{{ $r->image_url }}" alt="{{ $r->title }}" class="related-card__img">
        @else
        <div class="related-card__placeholder">📝</div>
        @endif
        <div class="related-card__body">
          <span class="related-card__tag">{{ $r->category }}</span>
          <h3 class="related-card__title">{{ $r->title }}</h3>
          <span class="related-card__date">{{ $r->created_at->locale('fr')->isoFormat('DD MMM YYYY') }}</span>
        </div>
      </a>
      @endforeach
    </div>
  </section>
  @endif

</main>
@endsection

@section('scripts')
<script type="module" src="{{ asset('js/main.js') }}"></script>
@endsection
