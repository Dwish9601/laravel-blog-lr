@if($errors->any())
    <div class="flash" style="border-color:#e07a7a">
        @foreach($errors->all() as $e)<div class="err">• {{ $e }}</div>@endforeach
    </div>
@endif

<p><input name="title" placeholder="Заголовок" value="{{ old('title', $post->title ?? '') }}"></p>
<p><textarea name="body" placeholder="Текст поста...">{{ old('body', $post->body ?? '') }}</textarea></p>

<p>
    <select name="visibility">
        @php $v = old('visibility', $post->visibility ?? 'public') @endphp
        <option value="public"      @selected($v=='public')>публичный</option>
        <option value="private"     @selected($v=='private')>только мне</option>
        <option value="by_request"  @selected($v=='by_request')>по запросу (нужен код)</option>
    </select>
</p>
<p><input name="access_code" placeholder="код доступа (необязательно, сгенерится сам)" value="{{ old('access_code', $post->access_code ?? '') }}"></p>
<p> <input name="tags" placeholder="теги через запятую: php, laravel, мысли" 
           value="{{ old('tags', $post && $post->tags ? $post->tags->pluck('name')->implode(', ') : '') }}"></p>