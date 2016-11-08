<!-- resources/views/parts/errors.blade.php -->

<!-- Form Error List -->
<div id="error-block" class="alert alert-danger" @if (count($errors) == 0) style="display: none;" @endif >
    <strong>以下のエラーが発生しました。</strong>
    <br>
    <ul id="error-list">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
